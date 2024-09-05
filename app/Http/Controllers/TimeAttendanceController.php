<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeAttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Get filter inputs
        $filterMonth = $request->input('bulan', Carbon::now()->month);
        $filterYear = $request->input('tahun', Carbon::now()->year);
        $filterNamaLengkap = $request->input('nama_lengkap');
        $filterKodeDept = $request->input('kode_dept');

        // Get the departments excluding "Security"
        $departments = DB::table('department')
            ->where('nama_dept', '!=', 'Security')
            ->get();

        // Get the number of days in the selected month
        $daysInMonth = Carbon::create($filterYear, $filterMonth)->daysInMonth;

        // Get karyawan data with filters, excluding "Security" department
        $karyawanQuery = DB::table('karyawan')
            ->where('status_kar', 'Aktif') // Add this condition to filter by status_kar
            ->whereNotIn('kode_dept', function ($query) {
                $query->select('kode_dept')
                    ->from('department')
                    ->where('grade', '=', 'NS');
            });

        if ($filterNamaLengkap) {
            $karyawanQuery->where('nama_lengkap', 'like', '%' . $filterNamaLengkap . '%');
        }
        if ($filterKodeDept) {
            $karyawanQuery->where('kode_dept', $filterKodeDept);
        }
        $karyawan = $karyawanQuery->get()->groupBy('kode_dept');

        // Get the earliest and latest years from the presensi table
        $years = DB::table('presensi')
            ->selectRaw('MIN(YEAR(tgl_presensi)) as earliest_year, MAX(YEAR(tgl_presensi)) as latest_year')
            ->first();

        $earliestYear = $years->earliest_year;
        $latestYear = $years->latest_year;

        // Get presensi data for the selected month
        $presensi = DB::table('presensi')
            ->select('nip', 'tgl_presensi', 'jam_in')
            ->whereMonth('tgl_presensi', $filterMonth)
            ->whereYear('tgl_presensi', $filterYear)
            ->get();

        // Get approved leave data for the selected month
        $cuti = DB::table('pengajuan_cuti')
            ->select('nik', 'tgl_cuti', 'tgl_cuti_sampai')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->where(function ($query) use ($filterMonth, $filterYear) {
                $query->whereMonth('tgl_cuti', $filterMonth)
                    ->whereYear('tgl_cuti', $filterYear)
                    ->orWhereMonth('tgl_cuti_sampai', $filterMonth)
                    ->whereYear('tgl_cuti_sampai', $filterYear);
            })
            ->get();

        // Get approved izin data for the selected month
        $izin = DB::table('pengajuan_izin')
            ->select('nik', 'tgl_izin', 'tgl_izin_akhir', 'status', 'keputusan', 'pukul')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->where(function ($query) use ($filterMonth, $filterYear) {
                $query->whereMonth('tgl_izin', $filterMonth)
                    ->whereYear('tgl_izin', $filterYear)
                    ->orWhereMonth('tgl_izin_akhir', $filterMonth)
                    ->whereYear('tgl_izin_akhir', $filterYear);
            })
            ->get();

        // Get national holidays
        $liburNasional = DB::table('libur_nasional')
            ->select('tgl_libur')
            ->whereMonth('tgl_libur', $filterMonth)
            ->whereYear('tgl_libur', $filterYear)
            ->pluck('tgl_libur')
            ->toArray();

        // Calculate working hours
        $attendanceData = [];
        $departmentTotals = [];

        foreach ($departments as $department) {
            $departmentKaryawan = $karyawan->get($department->kode_dept) ?: collect();
            $departmentAttendance = [];
            $departmentTotalHours = 0;

            foreach ($departmentKaryawan as $k) {
                $totalJamKaryawan = 0;
                $row = [
                    'nama_lengkap' => $k->nama_lengkap,
                    'attendance' => []
                ];

                // Get the employee's shift pattern ID and start shift date
                $shiftPatternId = DB::table('karyawan')
                    ->where('nip', $k->nip)
                    ->value('shift_pattern_id');

                $startShift = Carbon::parse(DB::table('karyawan')
                    ->where('nip', $k->nip)
                    ->value('start_shift'));

                // Calculate cycle length from shift_pattern_cycle table
                $cycleLength = DB::table('shift_pattern_cycle')
                    ->where('pattern_id', $shiftPatternId)
                    ->count();

                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $date = Carbon::create($filterYear, $filterMonth, $i);
                    $dateString = $date->toDateString();
                    $daysFromStart = $date->diffInDays($startShift);
                    $dayOfWeek = Carbon::parse($dateString)->dayOfWeekIso;

                    if ($shiftPatternId) {
                        if ($cycleLength == 7) {
                            $shiftId = DB::table('shift_pattern_cycle')
                                ->where('pattern_id', $shiftPatternId)
                                ->where('cycle_day', $dayOfWeek)
                                ->value('shift_id');
                        } else {
                            $cyclePosition = $daysFromStart % $cycleLength;
                            $shiftId = DB::table('shift_pattern_cycle')
                                ->where('pattern_id', $shiftPatternId)
                                ->where('cycle_day', $cyclePosition + 1)
                                ->value('shift_id');
                        }

                        if ($shiftId) {
                            // Fetch the early_time, start_time, latest_time, and status from the shifts table
                            $shiftTimes = DB::table('shift')
                                ->where('id', $shiftId)
                                ->select('early_time', 'latest_time', 'start_time', 'status')
                                ->first();

                            if ($shiftTimes) {
                                // Check if 'early_time', 'start_time', and 'latest_time' are not null
                                $morning_start = $shiftTimes->early_time ? strtotime($shiftTimes->early_time) : null;
                                $work_start = $shiftTimes->start_time ? strtotime($shiftTimes->start_time) : null;
                                $afternoon_start = $shiftTimes->latest_time ? strtotime($shiftTimes->latest_time) : null;
                                $status_work = $shiftTimes->status;
                            } else {
                                // Handle missing shift times by setting to null
                                $morning_start = null;
                                $work_start = null;
                                $afternoon_start = null;
                                $status_work = null;
                            }
                        } else {
                            // Handle missing shift pattern for the day
                            $morning_start = null;
                            $work_start = null;
                            $afternoon_start = null;
                            $status_work = null;
                        }
                    } else {
                        // Handle missing shift pattern ID
                        $morning_start = null;
                        $work_start = null;
                        $afternoon_start = null;
                        $status_work = null;
                    }

                    // Set default values if no shift times are available
                    if ($morning_start === null) {
                        $morning_start = strtotime('06:00:00');
                    }
                    if ($work_start === null) {
                        $work_start = strtotime('08:00:00');
                    }
                    if ($afternoon_start === null) {
                        $afternoon_start = strtotime('13:00:00');
                    }
                    if ($status_work === null) {
                        $status_work = 'P';
                    }

                    // Check if the date is a weekend or national holiday
                    $isWeekend = $date->isWeekend();
                    $isLibur = in_array($dateString, $liburNasional);

                    // Get all attendance records for the day
                    $dailyAttendance = $presensi->where('nip', $k->nip)->where('tgl_presensi', $dateString);

                    // Determine the earliest and latest jam_in for the day
                    $jamMasuk = $dailyAttendance->min('jam_in');
                    $jamPulang = $dailyAttendance->max('jam_in');

                    $isCuti = $this->checkCuti($cuti, $k->nik, $date);
                    $isIzin = $this->checkIzin($izin, $k->nik, $date);

                    // Default values
                    $totalHours = 0;

                    if ($isCuti) {
                        $totalHours = 8; // Normal working hours
                    }

                    if ($isIzin) {
                        $status = $isIzin->status;
                        $keputusan = $isIzin->keputusan;
                        $pukul = $isIzin->pukul;

                        if ($status == 'Tmk') {
                            $totalHours = 8;
                        } elseif ($status == 'Dt' && $keputusan == 'Terlambat') {
                            $jamMasuk = $pukul;
                        } elseif ($status == 'Pa' && $keputusan == 'Pulang Awal') {
                            $jamPulang = $pukul;
                        } elseif ($status == 'Tam') {
                            $jamMasuk = $pukul;
                        } elseif ($status == 'Tap') {
                            $jamPulang = $pukul;
                        }
                    }

                    // Calculate working hours if jamMasuk and jamPulang are available
                    if ($jamMasuk && $jamPulang) {
                        $jamMasukCarbon = Carbon::parse($jamMasuk);
                        $jamPulangCarbon = Carbon::parse($jamPulang);

                        if ($jamPulangCarbon->gt($jamMasukCarbon)) {
                            $minutesWorked = $jamMasukCarbon->diffInMinutes($jamPulangCarbon);
                            $totalHours = round($minutesWorked / 60, 1); // Subtract 1 hour for rest and round to nearest decimal
                        }
                    }

                    // Include attendance data if it's a weekend, national holiday, or if presensi exists
                    if ($isWeekend || $isLibur || $dailyAttendance->isNotEmpty() || $isIzin) {
                        if ($totalHours == 0) {
                            $row['attendance'][] = [
                                'hours' => '',
                            ];
                        } else {
                            $row['attendance'][] = [
                                'hours' => $totalHours,
                            ];
                        }
                    } else {
                        $row['attendance'][] = [
                            'hours' => '',
                        ];
                    }
                    // Accumulate total hours for the karyawan
                    $totalJamKaryawan += $totalHours;
                }
                $row['total_jam_kerja'] = $totalJamKaryawan;
                $departmentAttendance[] = $row;
                $departmentTotalHours += $totalJamKaryawan;
            }

            $attendanceData[] = [
                'department' => $department->nama_dept,
                'karyawan' => $departmentAttendance,
                'total_hours' => $departmentTotalHours
            ];

            // Accumulate total hours for the department
            $departmentTotals[$department->kode_dept] = $departmentTotalHours;
        }

        // Prepare data for the view
        $data = [
            'attendanceData' => $attendanceData,
            'daysInMonth' => $daysInMonth,
            'currentMonth' => $filterMonth,
            'currentYear' => $filterYear,
            'departments' => $departments,
            'earliestYear' => $earliestYear,
            'latestYear' => $latestYear,
            'departmentTotals' => $departmentTotals
        ];

        return view('time.timeattendance', $data);
    }

    private function checkCuti($cuti, $nik, $date)
    {
        foreach ($cuti as $c) {
            if ($c->nik == $nik && $date->between(Carbon::parse($c->tgl_cuti), Carbon::parse($c->tgl_cuti_sampai))) {
                return true;
            }
        }
        return false;
    }

    private function checkIzin($izin, $nik, $date)
    {
        foreach ($izin as $i) {
            $start = Carbon::parse($i->tgl_izin);
            $end = Carbon::parse($i->tgl_izin_akhir);

            if ($i->nik == $nik && $date->between($start, $end)) {
                return $i; // Return the full object, not just true
            }
        }
        return null; // Return null if no match is found
    }

    public function att_monitoring()
    {
        return view('time.timeatt');
    }

    public function get_att(Request $request)
    {
        $nama_lengkap = $request->nama_lengkap;
        $nip = $request->nip;

        $query = DB::table('presensi')
            ->selectRaw('DATE(tgl_presensi) as tanggal, presensi.nip, presensi.nik, nama_lengkap, nama_dept, jam_in')
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');

        if ($nama_lengkap) {
            $query->where('nama_lengkap', 'like', '%' . $nama_lengkap . '%');
        }

        if ($nip) {
            $query->where('presensi.nip', $nip);
        }

        $query->orderBy('tgl_presensi', 'desc');
        $presensi = $query->get();

        // Fetch approved izin data for the relevant dates and NIPs
        $izin = DB::table('pengajuan_izin')
            ->select('nik', 'tgl_izin', 'tgl_izin_akhir', 'status', 'keputusan', 'pukul')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->get();

        // Process records to find min and max jam_in
        $processedPresensi = [];

        // Process presensi records first
        foreach ($presensi as $record) {
            $tanggal = $record->tanggal;
            $nip = $record->nip;
            $nama_dept = $record->nama_dept;
            $nik = $record->nik; // Get the nik from the record
            $time = strtotime($record->jam_in);

            // Get the employee's shift pattern ID and start shift date
            $shiftPatternId = DB::table('karyawan')
                ->where('nip', $nip)
                ->value('shift_pattern_id');

            $startShift = Carbon::parse(DB::table('karyawan')
                ->where('nip', $nip)
                ->value('start_shift'));

            // Calculate cycle length from shift_pattern_cycle table
            $cycleLength = DB::table('shift_pattern_cycle')
                ->where('pattern_id', $shiftPatternId)
                ->count();

            $date = Carbon::parse($tanggal);
            $daysFromStart = $date->diffInDays($startShift);
            $dayOfWeek = $date->dayOfWeekIso;

            // Determine the shiftId based on shift pattern
            if ($shiftPatternId) {
                if ($cycleLength == 7) {
                    $shiftId = DB::table('shift_pattern_cycle')
                        ->where('pattern_id', $shiftPatternId)
                        ->where('cycle_day', $dayOfWeek)
                        ->value('shift_id');
                } else {
                    $cyclePosition = $daysFromStart % $cycleLength;
                    $shiftId = DB::table('shift_pattern_cycle')
                        ->where('pattern_id', $shiftPatternId)
                        ->where('cycle_day', $cyclePosition + 1)
                        ->value('shift_id');
                }

                if ($shiftId) {
                    $shiftTimes = DB::table('shift')
                        ->where('id', $shiftId)
                        ->select('early_time', 'latest_time', 'start_time', 'status')
                        ->first();

                    if ($shiftTimes) {
                        $morning_start = $shiftTimes->early_time ? strtotime($shiftTimes->early_time) : strtotime('06:00:00');
                        $work_start = $shiftTimes->start_time ? strtotime($shiftTimes->start_time) : strtotime('08:00:00');
                        $afternoon_start = $shiftTimes->latest_time ? strtotime($shiftTimes->latest_time) : strtotime('13:00:00');
                        $status_work = $shiftTimes->status ?? 'P';
                    } else {
                        // Handle missing shift pattern ID
                        $morning_start = null;
                        $work_start = null;
                        $afternoon_start = null;
                        $status_work = null;
                    }
                } else {
                    // Handle missing shift pattern ID
                    $morning_start = null;
                    $work_start = null;
                    $afternoon_start = null;
                    $status_work = null;
                }
            } else {
                // Handle missing shift pattern ID
                $morning_start = null;
                $work_start = null;
                $afternoon_start = null;
                $status_work = null;
            }

            // Set default values if no shift times are available
            if ($morning_start === null) {
                $morning_start = strtotime('06:00:00');
            }
            if ($work_start === null) {
                $work_start = strtotime('08:00:00');
            }
            if ($afternoon_start === null) {
                $afternoon_start = strtotime('13:00:00');
            }
            if ($status_work === null) {
                $status_work = 'P';
            }

            $key = $tanggal . '_' . $nip;

            if (!isset($processedPresensi[$key])) {
                $processedPresensi[$key] = [
                    'tanggal' => $tanggal,
                    'nip' => $nip,
                    'nama_lengkap' => $record->nama_lengkap,
                    'nama_dept' => $record->nama_dept,
                    'jam_masuk' => '',
                    'jam_pulang' => '',
                    'total_jam_kerja' => 0,
                ];
            }

            if ($time >= $morning_start && $time < $afternoon_start) {
                if (empty($processedPresensi[$key]['jam_masuk']) || $time < strtotime($processedPresensi[$key]['jam_masuk'])) {
                    $processedPresensi[$key]['jam_masuk'] = $record->jam_in;
                }
            } elseif ($time >= $afternoon_start) {
                if (empty($processedPresensi[$key]['jam_pulang']) || $time > strtotime($processedPresensi[$key]['jam_pulang'])) {
                    $processedPresensi[$key]['jam_pulang'] = $record->jam_in;
                }
            }

            // Apply the izin logic using nik
            $isIzin = $this->checkIzin($izin, $nik, Carbon::parse($tanggal));
            if ($isIzin) {
                $status = $isIzin->status;
                $keputusan = $isIzin->keputusan;
                $pukul = $isIzin->pukul;

                if ($status == 'Tmk') {
                    $processedPresensi[$key]['jam_masuk'] = $morning_start;
                    $processedPresensi[$key]['jam_pulang'] = $afternoon_start;
                }

                if ($status == 'Dt' && $keputusan == 'Terlambat') {
                    $processedPresensi[$key]['jam_masuk'] = $pukul;
                }

                if ($status == 'Pa' && $keputusan == 'Pulang Awal') {
                    $processedPresensi[$key]['jam_pulang'] = $pukul;
                }

                if ($status == 'Tam' && !$processedPresensi[$key]['jam_masuk']) {
                    $processedPresensi[$key]['jam_masuk'] = $pukul;
                }

                if ($status == 'Tap' && !$processedPresensi[$key]['jam_pulang']) {
                    $processedPresensi[$key]['jam_pulang'] = $pukul;
                }
            }

            // Calculate total working hours for the day
            $jamMasuk = $processedPresensi[$key]['jam_masuk'];
            $jamPulang = $processedPresensi[$key]['jam_pulang'];

            if ($jamMasuk && $jamPulang) {
                $jamMasukCarbon = Carbon::parse($jamMasuk);
                $jamPulangCarbon = Carbon::parse($jamPulang);

                if ($jamPulangCarbon->gt($jamMasukCarbon)) {
                    $minutesWorked = $jamMasukCarbon->diffInMinutes($jamPulangCarbon);
                    $totalHours = round($minutesWorked / 60, 1); // Subtract 1 hour for rest and round to nearest decimal
                    $processedPresensi[$key]['total_jam_kerja'] = $totalHours;
                }
            }
        }

        // Process izin records to add missing presensi data
        foreach ($izin as $record) {
            $nik = $record->nik;
            $status = $record->status;
            $tanggal = Carbon::parse($record->tgl_izin);

            while ($tanggal <= Carbon::parse($record->tgl_izin_akhir)) {
                $key = $tanggal->toDateString() . '_' . $nik;

                if ($status == 'Tmk') {
                    $processedPresensi[$key]['tanggal'] = $tanggal->toDateString();
                    $processedPresensi[$key]['nama_lengkap'] = $nama_lengkap;
                    $processedPresensi[$key]['nip'] = $nip;
                    $processedPresensi[$key]['nama_dept'] = $nama_dept;
                    $processedPresensi[$key]['jam_masuk'] = $morning_start;
                    $processedPresensi[$key]['jam_pulang'] = $afternoon_start;
                    $processedPresensi[$key]['total_jam_kerja'] = 8; // Add 8 hours for Tmk status
                }

                $tanggal->addDay();
            }
        }

        // Convert the processed data to a collection and sort by date
        $presensi = collect($processedPresensi)->sortByDesc('tanggal');

        return view("time.gettimeatt", compact('presensi'));
    }

    public function daymonitor()
    {
        return view('time.daytimeatt');
    }

    public function showdaymonitor(Request $request)
    {
        $tanggal = $request->tanggal;

        // Fetch presensi data for the specified date
        $query = DB::table('presensi')
            ->selectRaw('DATE(tgl_presensi) as tanggal, presensi.nip, presensi.nik, nama_lengkap, nama_dept, jam_in')
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');

        if ($tanggal) {
            $query->whereDate('tgl_presensi', $tanggal);
        }

        $query->orderBy('nama_dept', 'asc');
        $presensi = $query->get();

        // Fetch approved izin data for the relevant date
        $izin = DB::table('pengajuan_izin')
            ->select('pengajuan_izin.nik', 'pengajuan_izin.pukul', 'pengajuan_izin.tgl_izin', 'pengajuan_izin.tgl_izin_akhir', 'pengajuan_izin.status', 'pengajuan_izin.keputusan', 'pengajuan_izin.pukul', 'karyawan.nip', 'karyawan.nama_lengkap', 'department.nama_dept')
            ->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->where(function ($query) use ($tanggal) {
                $query->whereDate('tgl_izin', '<=', $tanggal)
                    ->whereDate('tgl_izin_akhir', '>=', $tanggal);
            })
            ->get();

        // Initialize processed presensi array
        $processedPresensi = [];

        // Process presensi records first
        foreach ($presensi as $record) {
            $tanggal = $record->tanggal;
            $nip = $record->nip;
            $nik = $record->nik;
            $time = strtotime($record->jam_in);
            // Get the employee's shift pattern ID and start shift date
            $shiftPatternId = DB::table('karyawan')
                ->where('nip', $nip)
                ->value('shift_pattern_id');

            $startShift = Carbon::parse(DB::table('karyawan')
                ->where('nip', $nip)
                ->value('start_shift'));

            // Calculate cycle length from shift_pattern_cycle table
            $cycleLength = DB::table('shift_pattern_cycle')
                ->where('pattern_id', $shiftPatternId)
                ->count();

            $date = Carbon::parse($tanggal);
            $daysFromStart = $date->diffInDays($startShift);
            $dayOfWeek = $date->dayOfWeekIso;

            // Determine the shiftId based on shift pattern
            if ($shiftPatternId) {
                if ($cycleLength == 7) {
                    $shiftId = DB::table('shift_pattern_cycle')
                        ->where('pattern_id', $shiftPatternId)
                        ->where('cycle_day', $dayOfWeek)
                        ->value('shift_id');
                } else {
                    $cyclePosition = $daysFromStart % $cycleLength;
                    $shiftId = DB::table('shift_pattern_cycle')
                        ->where('pattern_id', $shiftPatternId)
                        ->where('cycle_day', $cyclePosition + 1)
                        ->value('shift_id');
                }

                if ($shiftId) {
                    $shiftTimes = DB::table('shift')
                        ->where('id', $shiftId)
                        ->select('early_time', 'latest_time', 'start_time', 'status')
                        ->first();

                    if ($shiftTimes) {
                        $morning_start = $shiftTimes->early_time ? strtotime($shiftTimes->early_time) : strtotime('06:00:00');
                        $work_start = $shiftTimes->start_time ? strtotime($shiftTimes->start_time) : strtotime('08:00:00');
                        $afternoon_start = $shiftTimes->latest_time ? strtotime($shiftTimes->latest_time) : strtotime('13:00:00');
                        $status_work = $shiftTimes->status ?? 'P';
                    } else {
                        // Handle missing shift pattern ID
                        $morning_start = null;
                        $work_start = null;
                        $afternoon_start = null;
                        $status_work = null;
                    }
                } else {
                    // Handle missing shift pattern ID
                    $morning_start = null;
                    $work_start = null;
                    $afternoon_start = null;
                    $status_work = null;
                }
            } else {
                // Handle missing shift pattern ID
                $morning_start = null;
                $work_start = null;
                $afternoon_start = null;
                $status_work = null;
            }

            // Set default values if no shift times are available
            if ($morning_start === null) {
                $morning_start = strtotime('06:00:00');
            }
            if ($work_start === null) {
                $work_start = strtotime('08:00:00');
            }
            if ($afternoon_start === null) {
                $afternoon_start = strtotime('13:00:00');
            }
            if ($status_work === null) {
                $status_work = 'P';
            }

            $key = $tanggal . '_' . $nip;

            if (!isset($processedPresensi[$key])) {
                $processedPresensi[$key] = [
                    'tanggal' => $tanggal,
                    'nip' => $nip,
                    'nik' => $nik,
                    'nama_lengkap' => $record->nama_lengkap,
                    'nama_dept' => $record->nama_dept,
                    'jam_masuk' => '',
                    'jam_pulang' => '',
                    'total_jam_kerja' => 0,
                ];
            }

            if ($time >= $morning_start && $time < $afternoon_start) {
                if (empty($processedPresensi[$key]['jam_masuk']) || $time < strtotime($processedPresensi[$key]['jam_masuk'])) {
                    $processedPresensi[$key]['jam_masuk'] = $record->jam_in;
                }
            } elseif ($time >= $afternoon_start) {
                if (empty($processedPresensi[$key]['jam_pulang']) || $time > strtotime($processedPresensi[$key]['jam_pulang'])) {
                    $processedPresensi[$key]['jam_pulang'] = $record->jam_in;
                }
            }

            // Calculate total working hours for the day
            $jamMasuk = $processedPresensi[$key]['jam_masuk'];
            $jamPulang = $processedPresensi[$key]['jam_pulang'];

            if ($jamMasuk && $jamPulang) {
                $jamMasukCarbon = Carbon::parse($jamMasuk);
                $jamPulangCarbon = Carbon::parse($jamPulang);

                if ($jamPulangCarbon->gt($jamMasukCarbon)) {
                    $minutesWorked = $jamMasukCarbon->diffInMinutes($jamPulangCarbon);
                    $totalHours = round($minutesWorked / 60, 1); // Subtract 1 hour for rest and round to nearest decimal
                    $processedPresensi[$key]['total_jam_kerja'] = $totalHours;
                }
            }
        }

        // Process izin records to add missing presensi data
        foreach ($izin as $record) {
            $nik = $record->nik;
            $nip = $record->nip;
            $pukul = $record->pukul;
            $nama_lengkap = $record->nama_lengkap;
            $nama_dept = $record->nama_dept;
            $tanggalMulai = Carbon::parse($record->tgl_izin);
            $tanggalAkhir = Carbon::parse($record->tgl_izin_akhir);

            while ($tanggalMulai <= $tanggalAkhir) {
                if ($tanggalMulai->toDateString() == $tanggal) {
                    $key = $tanggalMulai->toDateString() . '_' . $nip;

                    if (!isset($processedPresensi[$key])) {
                        $processedPresensi[$key] = [
                            'tanggal' => $tanggalMulai->toDateString(),
                            'nip' => $nip,
                            'nik' => $nik,
                            'nama_lengkap' => $nama_lengkap,
                            'nama_dept' => $nama_dept,
                            'jam_masuk' => null,
                            'jam_pulang' => null,
                            'total_jam_kerja' => 0,
                        ];
                    }

                    $status = $record->status;
                    $keputusan = $record->keputusan;

                    if ($status == 'Tmk') {
                        $processedPresensi[$key]['jam_masuk'] = $morning_start;
                        $processedPresensi[$key]['jam_pulang'] = $afternoon_start;
                        $processedPresensi[$key]['total_jam_kerja'] = $afternoon_start - $morning_start; // Add 8 hours for Tmk status
                    }

                    if ($status == 'Dt' && $keputusan == 'Terlambat') {
                        $processedPresensi[$key]['jam_masuk'] = $pukul;
                    }

                    if ($status == 'Pa' && $keputusan == 'Pulang Awal') {
                        $processedPresensi[$key]['jam_pulang'] = $pukul;
                    }

                    if ($status == 'Tam' && !$processedPresensi[$key]['jam_masuk']) {
                        $processedPresensi[$key]['jam_masuk'] = $pukul;
                    }

                    if ($status == 'Tap' && !$processedPresensi[$key]['jam_pulang']) {
                        $processedPresensi[$key]['jam_pulang'] = $pukul;
                    }
                }

                $tanggalMulai->addDay();
            }
        }

        // Convert the processed data to a collection and sort by date
        $presensi = collect($processedPresensi)->sortByDesc('tanggal');

        return view("time.timeshowday", compact('presensi'));
    }
}
