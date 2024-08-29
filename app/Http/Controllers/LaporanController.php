<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\AttendanceExport;
use App\Exports\TimeExport;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{

    public function index()
    {
        $years = DB::table('presensi')
        ->selectRaw('MIN(YEAR(tgl_presensi)) as earliest_year, MAX(YEAR(tgl_presensi)) as latest_year')
        ->first();

        $earliestYear = $years->earliest_year;
        $latestYear = $years->latest_year;
        return view('laporan.attendance', compact('earliestYear', 'latestYear'));
    }
    public function exportData(Request $request)
    {
        // Get filter inputs
        $filterMonth = $request->input('bulan', Carbon::now()->month);
        $filterYear = $request->input('tahun', Carbon::now()->year);
        $filterNamaLengkap = $request->input('nama_lengkap');
        $filterKodeDept = $request->input('kode_dept');

        // Get the departments excluding "Security"
        $departments = DB::table('department')
            ->get();

        // Get the number of days in the selected month
        $daysInMonth = Carbon::create($filterYear, $filterMonth)->daysInMonth;

        $totalWorkdays = $this->getTotalWorkdays($filterYear, $filterMonth);

        // Get karyawan data with filters, excluding "Security" department
        $karyawanQuery = DB::table('karyawan')
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
            ->select('nip', 'tgl_presensi', DB::raw('MIN(jam_in) as earliest_jam_in'))
            ->whereMonth('tgl_presensi', $filterMonth)
            ->whereYear('tgl_presensi', $filterYear)
            ->groupBy('nip', 'tgl_presensi')
            ->get();

        // Get national holidays for the selected month
        $liburNasional = DB::table('libur_nasional')
            ->whereMonth('tgl_libur', $filterMonth)
            ->whereYear('tgl_libur', $filterYear)
            ->pluck('tgl_libur')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            });

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

        // Debugging: Get Izin Data
        $izin = DB::table('pengajuan_izin')
            ->select('nik', 'tgl_izin', 'tgl_izin_akhir', 'status', 'keputusan', 'pukul', 'tgl_jadwal_off')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->where(function ($query) use ($filterMonth, $filterYear) {
                $query->whereMonth('tgl_izin', $filterMonth)
                    ->whereYear('tgl_izin', $filterYear)
                    ->orWhereMonth('tgl_izin_akhir', $filterMonth)
                    ->whereYear('tgl_izin_akhir', $filterYear);
            })
            ->get();

        // Process presensi and cuti data to format for display
        $attendanceData = [];
        foreach ($departments as $department) {
            $departmentKaryawan = $karyawan->get($department->kode_dept) ?: collect();
            $departmentAttendance = [];
            $totalJumlahTelat = 0;
            $totalP = 0;
            $totalT = 0;
            $totalOff = 0;
            $totalSakit = 0;
            $totalIzin = 0;
            $totalCuti = 0;
            $totalH1 = 0;
            $totalH2 = 0;
            $totalDinas = 0;
            $totalBlank = 0;
            $totalMangkir = 0;
            $totalKaryawan = count($departmentKaryawan);

            foreach ($departmentKaryawan as $k) {
                $jumlahTelat = 0;
                $menitTelat = 0;
                $totalHadir = 0;
                $totalTidakHadir = 0;
                $totalJumlahOff = 0;
                $totalJumlahSakit = 0;
                $totalJumlahIzin = 0;
                $totalJumlahCuti = 0;
                $totalJumlahDinas = 0;
                $totalJumlahMangkir = 0;
                $totalJumlahH1 = 0;
                $totalJumlahH2 = 0;
                $totalJumlahBlank = 0;

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

                    $attendance = $presensi->where('nip', $k->nip)->where('tgl_presensi', $dateString)->first();
                    $isCuti = $this->checkCuti($cuti, $k->nik, $date);
                    $isIzin = $this->checkIzin($izin, $k->nik, $date);
                    $status = $this->getAttendanceStatus($date, $attendance, $isCuti, $isIzin, $work_start, $morning_start, $afternoon_start, $status_work);


                    // Check if the date is a national holiday
                    if ($liburNasional->contains($dateString)) {
                        $status = 'LN'; // Mark as national holiday
                    }

                    // Calculate late minutes if attendance exists and status is 'T'
                    if ($attendance) {
                        $jam_in = Carbon::parse($attendance->earliest_jam_in);
                        if ($status === 'T') {
                            $menitTelat += $jam_in->diffInMinutes(Carbon::parse($work_start));
                            $jumlahTelat++;
                        }
                    }

                    if ($status === 'P') {
                        $totalHadir++;
                    } elseif ($status === 'T') {
                        $totalTidakHadir++;
                    } elseif ($status === 'OFF') {
                        $totalJumlahOff++;
                    } elseif ($status === 'S') {
                        $totalJumlahSakit++;
                    } elseif ($status === 'I') {
                        $totalJumlahIzin++;
                    } elseif ($status === 'C') {
                        $totalJumlahCuti++;
                    } elseif ($status === 'D') {
                        $totalJumlahDinas++;
                    } elseif ($status === 'MK') {
                        $totalJumlahMangkir++;
                    } else {
                        if (!$date->isWeekend() && !$liburNasional->contains($dateString)) {
                            $totalJumlahBlank++;
                        }
                    }

                    $row['attendance'][] = [
                        'status' => $status,
                        'class' => $this->determineAttendanceClass($date, $status)
                    ];
                }

                $row['jumlah_telat'] = $jumlahTelat;
                $row['menit_telat'] = $menitTelat;
                $row['presentase'] = round(($totalTidakHadir / $totalWorkdays) * 100);
                $row['totalP'] = $totalHadir;
                $row['totalT'] = $totalTidakHadir;
                $row['totalOff'] = $totalJumlahOff;
                $row['totalSakit'] = $totalJumlahSakit;
                $row['totalIzin'] = $totalJumlahIzin;
                $row['totalCuti'] = $totalJumlahCuti;
                $row['totalDinas'] = $totalJumlahDinas;
                $row['totalH1'] = $totalJumlahH1;
                $row['totalH2'] = $totalJumlahH2;
                $row['totalBlank'] = $totalJumlahBlank;
                $row['totalMangkir'] = $totalJumlahMangkir;

                $totalJumlahTelat += $jumlahTelat;
                $totalP += $totalHadir;
                $totalT += $totalTidakHadir;
                $totalOff += $totalJumlahOff;
                $totalSakit += $totalJumlahSakit;
                $totalIzin += $totalJumlahIzin;
                $totalCuti += $totalJumlahCuti;
                $totalDinas += $totalJumlahDinas;
                $totalH1 += $totalJumlahH1;
                $totalH2 += $totalJumlahH2;
                $totalBlank += $totalJumlahBlank;
                $totalMangkir += $totalJumlahMangkir;

                $departmentAttendance[] = $row;
            }

            $attendanceData[] = [
                'department' => $department->nama_dept,
                'karyawan' => $departmentAttendance,
                'total_jumlah_telat' => $totalJumlahTelat,
                'total_presentase' => $totalKaryawan ? round(($totalT / ($totalKaryawan * 23)) * 100) : 0
            ];
        }

        return Excel::download(new AttendanceExport($attendanceData), 'attendance.xlsx');
    }

    private function getTotalWorkdays($year, $month)
    {
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $totalWorkdays = 0;

        while ($startOfMonth->lte($endOfMonth)) {
            if (!$startOfMonth->isWeekend()) {
                $totalWorkdays++;
            }
            $startOfMonth->addDay();
        }

        return $totalWorkdays;
    }

    // Helper function to determine attendance status
    private function getAttendanceStatus($date, $attendance, $isCuti, $isIzin, $work_start, $morning_start, $afternoon_start, $status_work)
    {
        if ($isCuti) {
            return 'C';
        }

        if ($isIzin) {
            $status = $isIzin->status;
            $keputusan = $isIzin->keputusan;
            $pukul = $isIzin->pukul;

            if ($status == 'Tmk') {
                if ($keputusan == 'Sakit') {
                    return 'S';
                } elseif ($keputusan == 'Ijin') {
                    return 'I';
                } elseif ($keputusan == 'Mangkir') {
                    return 'MK';
                } elseif ($keputusan == 'Tugas Luar') {
                    return 'D';
                } elseif ($keputusan == 'Potong Cuti') {
                    return 'C';
                } elseif ($keputusan == 'Tukar Jadwal Off') {
                    return 'OFF'; // Handle Tukar Jadwal Off
                }
            }

            if ($status == 'Dt' && $attendance) {
                $jam_in = Carbon::parse($attendance->earliest_jam_in);
                if ($jam_in->gte(Carbon::parse($morning_start)) && $jam_in->lt(Carbon::parse($afternoon_start))) {
                    if ($pukul && abs($jam_in->diffInMinutes(Carbon::parse($pukul))) <= 5 && $keputusan == 'Terlambat') {
                        return $status_work;
                    } else {
                        return $status_work;
                    }
                }
            }

            if ($status == 'Tam') {
                // If no attendance between 6 AM and 1 PM
                $jam_in = $attendance ? Carbon::parse($attendance->earliest_jam_in) : null;
                if (!$jam_in || $jam_in->lt(Carbon::parse($morning_start)) || $jam_in->gte(Carbon::parse($afternoon_start))) {
                    return $status_work;
                }
            }

            if ($status == 'Tjo') {
                return 'OFF'; // Specific status for Tukar Jadwal Off
            }
        }


        if ($status_work == 'OFF' && $attendance) {
            $jam_in = Carbon::parse($attendance->earliest_jam_in);
            if ($jam_in->gte(Carbon::parse($morning_start)) && $jam_in->lt(Carbon::parse($afternoon_start))) {
                return $jam_in->gt(Carbon::parse($work_start)) ? 'T' : 'P';
            } else {
                return 'T';
            }
        } else if ($attendance){
            $jam_in = Carbon::parse($attendance->earliest_jam_in);
            if ($jam_in->gte(Carbon::parse($morning_start)) && $jam_in->lt(Carbon::parse($afternoon_start))) {
                return $jam_in->gt(Carbon::parse($work_start)) ? 'T' : $status_work;
            } else {
                return 'T';
            }
        } else {
            return $date->dayOfWeek == Carbon::SATURDAY || $date->dayOfWeek == Carbon::SUNDAY ? 'L' : '';
        }
    }


    // Helper function to determine if the date falls within a leave period
    private function checkCuti($cuti, $nik, $date)
    {
        foreach ($cuti as $c) {
            if ($c->nik == $nik && $date->between(Carbon::parse($c->tgl_cuti), Carbon::parse($c->tgl_cuti_sampai))) {
                return true;
            }
        }
        return false;
    }

    // Helper function to determine if the date falls within an izin period
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

    // Updated function to determine CSS classes for attendance cell
    private function determineAttendanceClass($date, $status)
    {
        $classes = [];

        // Check if the date is a weekend
        if ($date->dayOfWeek == Carbon::SATURDAY || $date->dayOfWeek == Carbon::SUNDAY) {
            switch ($status) {
                case 'T':
                    $classes[] = 'late';
                    break;
                case 'P':
                    $classes[] = ''; // No specific class for present on weekends
                    break;
                case 'M':
                    $classes[] = 'kerja-malem'; // No specific class for present on weekends
                    break;
                case 'LN':
                    $classes[] = 'dark-yellow';
                    break;
                case 'OFF':
                    $classes[] = 'tukar_off'; // New class for Tukar Jadwal Off
                    break;
                default:
                    $classes[] = 'weekend';
                    break;
            }
        } else {
            switch ($status) {
                case 'T':
                    $classes[] = 'late';
                    break;
                case 'LN':
                    $classes[] = 'dark-yellow';
                    break;
                case 'C':
                    $classes[] = 'cuti';
                    break;
                case 'I':
                    $classes[] = 'izin';
                    break;
                case 'M':
                    $classes[] = 'kerja-malem'; // No specific class for present on weekends
                    break;
                case 'S':
                    $classes[] = 'sakit';
                    break;
                case 'MK':
                    $classes[] = 'mangkir';
                    break;
                case 'D':
                    $classes[] = 'tugas_luar';
                    break;
                case 'OFF':
                    $classes[] = 'tukar_off'; // New class for Tukar Jadwal Off
                    break;
                default:
                    break;
            }
        }

        return implode(' ', $classes);
    }





    ///////////////////////////////////////////////////////////////////////////////

    public function timeindex()
    {
        $years = DB::table('presensi')
        ->selectRaw('MIN(YEAR(tgl_presensi)) as earliest_year, MAX(YEAR(tgl_presensi)) as latest_year')
        ->first();

        $earliestYear = $years->earliest_year;
        $latestYear = $years->latest_year;
        return view('laporan.time', compact('earliestYear', 'latestYear'));
    }

    public function exportTime(Request $request)
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
                                ->select('early_time', 'latest_time', 'start_time')
                                ->first();

                            if ($shiftTimes) {
                                // Check if 'early_time', 'start_time', and 'latest_time' are not null
                                $morning_start = $shiftTimes->early_time ? strtotime($shiftTimes->early_time) : null;
                                $work_start = $shiftTimes->start_time ? strtotime($shiftTimes->start_time) : null;
                                $afternoon_start = $shiftTimes->latest_time ? strtotime($shiftTimes->latest_time) : null;
                            } else {
                                // Handle missing shift times by setting to null
                                $morning_start = null;
                                $work_start = null;
                                $afternoon_start = null;
                            }
                        } else {
                            // Handle missing shift pattern for the day
                            $morning_start = null;
                            $work_start = null;
                            $afternoon_start = null;
                        }
                    } else {
                        // Handle missing shift pattern ID
                        $morning_start = null;
                        $work_start = null;
                        $afternoon_start = null;
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

        return Excel::download(new TimeExport($attendanceData), 'attendance.xlsx');
    }
}
