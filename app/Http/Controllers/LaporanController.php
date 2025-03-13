<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\AttendanceExport;
use App\Exports\AbsenExport;
use App\Exports\CutiExport;
use App\Exports\IzinExport;
use App\Exports\TimeExport;
use App\Helpers\DateHelper;
use App\Models\Cuti;
use App\Models\Jabatan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Karyawan;
use App\Models\PengajuanCuti;
use App\Models\Pengajuanizin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LaporanController extends Controller
{

    public function index()
    {
        $years = DB::connection('mysql2')
            ->table('db_absen.att_log')
            ->selectRaw('MIN(YEAR(scan_date)) as earliest_year, MAX(YEAR(scan_date)) as latest_year')
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

        $karyawanQuery = DB::table('karyawan')
            ->where('status_kar', 'Aktif');

        if ($filterNamaLengkap) {
            $karyawanQuery->where('nama_lengkap', 'like', '%' . $filterNamaLengkap . '%');
        }
        if ($filterKodeDept) {
            $karyawanQuery->where('kode_dept', $filterKodeDept);
        }
        $karyawan = $karyawanQuery->get()->groupBy('kode_dept');

        // Get presensi data for the selected month
        $presensi = DB::connection('mysql2')
            ->table('db_absen.att_log as presensi')
            ->select('presensi.pin', DB::raw('DATE(presensi.scan_date) as scan_date'), DB::raw('MIN(TIME(presensi.scan_date)) as earliest_jam_in'))
            ->whereMonth('presensi.scan_date', $filterMonth)
            ->whereYear('presensi.scan_date', $filterYear)
            ->groupBy('presensi.pin', 'presensi.scan_date')
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

        $liburKaryawan = DB::table('libur_kar')
            ->whereMonth('month', $filterMonth)
            ->whereYear('month', $filterYear)
            ->pluck('id', 'nik');

        $liburKarDays = DB::table('libur_kar_day')
            ->whereIn('libur_id', $liburKaryawan)
            ->pluck('tanggal', 'libur_id');

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

                $liburKaryawanId = DB::table('libur_kar')
                    ->where('month', $filterMonth)
                    ->where('nik', $k->nik)
                    ->value('id'); // Get the ID of the libur_kar row for the current karyawan

                $liburKarDays = collect(); // Default empty collection for dates

                if ($liburKaryawanId) {
                    // Fetch all the leave dates for the specific employee
                    $liburKarDays = DB::table('libur_kar_day')
                        ->where('libur_id', $liburKaryawanId)
                        ->pluck('tanggal');
                }

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

                    $attendance = $presensi->where('pin', $k->nip)->where('scan_date', $dateString)->first();
                    $isCuti = $this->checkCuti($cuti, $k->nik, $date);
                    $isIzin = $this->checkIzin($izin, $k->nik, $date);
                    $status = $this->getAttendanceStatus($date, $attendance, $isCuti, $isIzin, $work_start, $morning_start, $afternoon_start, $status_work);


                    // Check if the date is a national holiday
                    if ($liburNasional->contains($dateString)) {
                        $status = 'LN'; // Mark as national holiday
                    }

                    if ($liburKarDays->contains($dateString)) {
                        $status = 'L'; // Mark as employee leave day
                    }

                    if ($attendance) {
                        $jam_in = Carbon::parse($attendance->earliest_jam_in);
                        $workStart = Carbon::parse($work_start);

                        // Check if the attendance time is greater than work_start
                        if ($jam_in->greaterThan($workStart)) {
                            $menitTelat += $jam_in->diffInMinutes($workStart);
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

                $totalJumlahTelat += $totalTidakHadir;
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
                'total_presentase' => $totalKaryawan ? round(($totalT / ($totalKaryawan * $totalWorkdays)) * 100) : 0
            ];
        }

        return Excel::download(new AttendanceExport($attendanceData), 'attendance.xlsx');
    }

    private function getTotalWorkdays($year, $month)
    {
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $totalWorkdays = 0;

        // Get national holidays for the given month
        $liburNasional = DB::table('libur_nasional')
            ->whereMonth('tgl_libur', $month)
            ->whereYear('tgl_libur', $year)
            ->pluck('tgl_libur') // Retrieve only the dates
            ->map(fn($date) => Carbon::parse($date)->toDateString()) // Normalize to Y-m-d format
            ->toArray();

        while ($startOfMonth->lte($endOfMonth)) {
            // Exclude weekends and national holidays
            if (!$startOfMonth->isWeekend() && !in_array($startOfMonth->toDateString(), $liburNasional)) {
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
            if ($date->dayOfWeek == Carbon::SATURDAY || $date->dayOfWeek == Carbon::SUNDAY) {
                // If there is attendance, return 'P' instead of 'L'
                return 'L';
            } else {
                return 'C';
            }
        }

        if ($isIzin) {
            $status = $isIzin->status;
            $keputusan = $isIzin->keputusan;
            $pukul = $isIzin->pukul;

            if ($status == 'Tmk') {
                return $keputusan == 'Sakit' ? 'S' : ($keputusan == 'Ijin' ? 'I' : ($keputusan == 'Mangkir' ? 'MK' : ($keputusan == 'Tugas Luar' ? 'D' : 'C')));
            }

            if ($status == 'Ta') {
                return  $keputusan == 'Ijin' ? 'I' : ($keputusan == 'Tugas Luar' ? 'D' : 'P');
            }

            if ($status == 'Dt' && $attendance) {
                $jam_in = Carbon::parse($attendance->earliest_jam_in);
                if ($jam_in->gte(Carbon::parse($morning_start)) && $jam_in->lt(Carbon::parse($afternoon_start))) {
                    // if ($pukul && abs($jam_in->diffInMinutes(Carbon::parse($pukul))) <= 5 && $keputusan == 'Terlambat') {
                    //     return $status_work;
                    // } else {
                    //     return $status_work;
                    // }
                    return $status_work;
                }
            }

            if ($status == 'Tam') {
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
            if ($jam_in->lt(Carbon::parse($morning_start))) {
                $latest_jam_in_after_morning = Carbon::parse($attendance->latest_jam_in_after_morning ?? $jam_in);
                if ($latest_jam_in_after_morning->gte(Carbon::parse($morning_start))) {
                    return $latest_jam_in_after_morning->gt(Carbon::parse($work_start)) ? 'T' : 'P';
                } else {
                    return 'T'; // No valid attendance after morning_start
                }
            } else {
                return $jam_in->gte(Carbon::parse($morning_start)) && $jam_in->lt(Carbon::parse($afternoon_start))
                    ? ($jam_in->gt(Carbon::parse($work_start)) ? 'T' : 'P')
                    : 'T';
            }
        } else if ($attendance) {
            $jam_in = Carbon::parse($attendance->earliest_jam_in);

            // If attendance is before morning start
            if ($jam_in->lt(Carbon::parse($work_start))) {
                $latest_jam_in_after_morning = Carbon::parse($attendance->latest_jam_in_after_morning ?? $jam_in);

                // Check if there is any valid attendance after the morning start time
                if ($latest_jam_in_after_morning->gte(Carbon::parse($morning_start))) {
                    return $latest_jam_in_after_morning->gt(Carbon::parse($work_start)) ? 'T' : 'P';
                } else {
                    // Handle weekend case separately
                    if ($date->dayOfWeek == Carbon::SATURDAY || $date->dayOfWeek == Carbon::SUNDAY) {
                        // If there is attendance, return 'P' instead of 'L'
                        return 'L';
                    }
                    return ''; // No valid attendance after morning_start
                }
            } else {
                // Attendance occurs during the day, before or after morning_start
                return $jam_in->gte(Carbon::parse($morning_start)) && $jam_in->lt(Carbon::parse($afternoon_start))
                    ? ($jam_in->gt(Carbon::parse($work_start)) ? 'T' : $status_work)
                    : 'T';
            }
        } else {
            // Handle case when there is no attendance
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
                case 'L':
                    $classes[] = 'weekend';
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
        $years = DB::connection('mysql2')
            ->table('db_absen.att_log')
            ->selectRaw('MIN(YEAR(scan_date)) as earliest_year, MAX(YEAR(scan_date)) as latest_year')
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


    public function exportAttendanceView(Request $request)
    {
        $years = DB::connection('mysql2')
            ->table('db_absen.att_log')
            ->selectRaw('MIN(YEAR(scan_date)) as earliest_year, MAX(YEAR(scan_date)) as latest_year')
            ->first();

        $earliestYear = $years->earliest_year;
        $latestYear = $years->latest_year;

        $query = Karyawan::query();
        $query->select('karyawan.*', 'department.nama_dept', 'jabatan.nama_jabatan');
        $query->leftJoin('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        $query->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id');
        $query->orderBy('karyawan.id', 'asc');
        $query->where('karyawan.status_kar', 'Aktif');
        $query->orderBy('karyawan.tgl_masuk', 'asc');

        if (!empty($request->nama_karyawan)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }

        if (!empty($request->pt_karyawan)) {
            $query->where('karyawan.nama_pt', $request->pt_karyawan);
        }

        if (!empty($request->religion_karyawan)) {
            $query->where('karyawan.religion', $request->religion_karyawan);
        }

        if (!empty($request->base)) {
            $query->where('karyawan.base_poh', $request->base);
        }

        if (!empty($request->grade_karyawan)) {
            $query->where('karyawan.grade', $request->grade_karyawan);
        }

        if (!empty($request->status_karyawan)) {
            $query->where('karyawan.status_kar', $request->status_karyawan);
        }

        if (!empty($request->status_employee)) {
            $query->where('karyawan.employee_status', $request->status_employee);
        }

        $karyawan = $query->paginate(15)->appends($request->except('page'));
        $department = DB::table('department')->get();
        $jabatan = DB::table('jabatan')->get();
        $location = DB::table('konfigurasi_lokasi')->get();

        // Fetch unique values for the dropdowns
        $uniquePt = Karyawan::whereNotNull('nama_pt')->distinct()->pluck('nama_pt')->filter();
        $uniqueReligion = Karyawan::whereNotNull('religion')->distinct()->pluck('religion')->filter();
        $uniqueBase = Karyawan::whereNotNull('base_poh')->distinct()->pluck('base_poh')->filter();
        $uniqueGrade = Karyawan::whereNotNull('grade')->distinct()->pluck('grade')->filter();
        $uniqueStatusKar = Karyawan::whereNotNull('status_kar')->distinct()->pluck('status_kar')->filter();
        $uniqueEmployeeStatus = Karyawan::whereNotNull('employee_status')->distinct()->pluck('employee_status')->filter();
        return view('laporan.exportAttendance', compact(
            'earliestYear',
            'latestYear',
            'karyawan',
            'department',
            'jabatan',
            'location',
            'uniquePt',
            'uniqueReligion',
            'uniqueBase',
            'uniqueGrade',
            'uniqueStatusKar',
            'uniqueEmployeeStatus'
        ));
    }

    public function attendanceViewAtasan(Request $request)
    {
        $years = DB::connection('mysql2')
            ->table('db_absen.att_log')
            ->selectRaw('MIN(YEAR(scan_date)) as earliest_year, MAX(YEAR(scan_date)) as latest_year')
            ->first();

        $earliestYear = $years->earliest_year;
        $latestYear = $years->latest_year;

        // Get the current user's details
        $nik = Auth::guard('user')->user()->nik;
        $currentUser = Karyawan::where('nik', $nik)->first();
        $currentUserJabatanId = $currentUser->jabatan;

        // Find all jabatan IDs where the current user's jabatan is the atasan
        $subordinateJabatanIds = Jabatan::where('jabatan_atasan', $currentUserJabatanId)->pluck('id');

        // Get all employees (karyawan) whose jabatan matches any of the subordinate IDs
        $karyawan = Karyawan::whereIn('jabatan', $subordinateJabatanIds)
            ->where('status_kar', 'Aktif') // Ensure only active employees are fetched
            ->orderBy('tgl_masuk', 'asc')
            ->get();

        return view('laporan.attendanceViewAtasan', compact(
            'earliestYear',
            'latestYear',
            'karyawan'
        ));
    }


    public function exportAttendance(Request $request)
    {
        $filterMonth = $request->input('bulan', Carbon::now()->month);
        $filterYear = $request->input('tahun', Carbon::now()->year);
        $karyawanIds = $request->input('karyawan_ids', []);

        // Get selected karyawan data
        $karyawan = DB::table('karyawan')
            ->whereIn('id', $karyawanIds)
            ->get(['nip', 'nama_lengkap', 'kode_dept']);

        // Get presensi data
        $presensi = DB::connection('mysql2')
            ->table('db_absen.att_log as presensi')
            ->select(
                'presensi.pin',
                DB::raw('DATE(presensi.scan_date) as scan_date'),
                DB::raw('CASE
                    WHEN COUNT(*) = 1 THEN MIN(TIME(presensi.scan_date))
                    ELSE MIN(TIME(presensi.scan_date))
                 END as earliest_scan_time'),
                DB::raw('CASE
                    WHEN COUNT(*) = 1 THEN MAX(TIME(presensi.scan_date))
                    ELSE MAX(TIME(presensi.scan_date))
                 END as latest_scan_time')
            )
            ->whereMonth('presensi.scan_date', $filterMonth)
            ->whereYear('presensi.scan_date', $filterYear)
            ->when(count($karyawanIds) > 0, function ($query) use ($karyawan) {
                $query->whereIn('presensi.pin', $karyawan->pluck('nip')->toArray());
            })
            ->groupBy('presensi.pin', DB::raw('DATE(presensi.scan_date)'))
            ->get();

        // Ensure no NULL values
        $presensi = $presensi->map(function ($record) {
            $record->earliest_scan_time = $record->earliest_scan_time ?? ''; // Replace null with empty string
            $record->latest_scan_time = $record->latest_scan_time ?? '';    // Replace null with empty string
            return $record;
        });

        // Group presensi by pin and scan_date
        $presensiGrouped = $presensi->groupBy('pin')->map(function ($records) {
            return $records->groupBy('scan_date');
        });


        // Map karyawan data to presensi
        $karyawanPresensi = $karyawan->map(function ($employee) use ($presensiGrouped) {
            $employeePresensi = $presensiGrouped->get($employee->nip, collect());
            return [
                'employee' => $employee,
                'presensi' => $employeePresensi,
            ];
        });

        // Export attendance
        return Excel::download(new AbsenExport($karyawanPresensi, $filterMonth, $filterYear), 'attendance_times.xlsx');
    }


    public function viewIzin(Request $request)
    {
        $query = Pengajuanizin::query();
        $query->join('karyawan',  'pengajuan_izin.nik', '=', 'karyawan.nik');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        $query->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id');
        $query->select('pengajuan_izin.*', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'department.nama_dept', 'jabatan.nama_jabatan')
            ->where('pengajuan_izin.status', '!=', 'Cuti');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_create', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        if (!empty($request->nama_lengkap)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Handle the status_approved filter
        if ($request->has('status_approved')) {
            if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2' || $request->status_approved === '3') {
                $query->where('status_approved', $request->status_approved);
            }
        }

        // Handle the status_approved_hrd filter
        if ($request->has('status_approved_hrd')) {
            if ($request->status_approved_hrd === '0' || $request->status_approved_hrd === '1' || $request->status_approved_hrd === '2' || $request->status_approved_hrd === '3') {
                $query->where('status_approved_hrd', $request->status_approved_hrd);
            }
        }

        $izinapproval = $query->paginate(20)->appends($request->query());
        $izinapproval->appends($request->all());

        return view('laporan.viewIzin', compact('izinapproval'));
    }

    public function exportIzin()
    {
        $years = DB::table('pengajuan_izin')
            ->selectRaw('MIN(YEAR(tgl_izin)) as earliest_year, MAX(YEAR(tgl_izin_akhir)) as latest_year')
            ->first();

        $earliestYear = $years->earliest_year;
        $latestYear = $years->latest_year;
        return view('laporan.izin', compact('earliestYear', 'latestYear'));
    }

    public function reportIzin(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Validate the inputs
        if (empty($bulan) || empty($tahun)) {
            return redirect()->back()->with('danger', 'Bulan dan Tahun harus dipilih!');
        }

        $fileName = "laporan_pengajuan_izin_{$bulan}_{$tahun}.xlsx";
        return Excel::download(new IzinExport($bulan, $tahun), $fileName);
    }

    public function viewCuti(Request $request)
    {
        $query = PengajuanCuti::query();
        $query->join('karyawan', 'pengajuan_cuti.nik', '=', 'karyawan.nik');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        $query->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id');
        $query->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti');
        $query->select('pengajuan_cuti.*', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'department.nama_dept', 'karyawan.tgl_masuk', 'tipe_cuti.tipe_cuti', 'jabatan.nama_jabatan');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_cuti', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        if (!empty($request->nama_lengkap)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Handle the status_approved filter
        if ($request->has('status_approved')) {
            if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2' || $request->status_approved === '3') {
                $query->where('status_approved', $request->status_approved);
            }
        }

        // Handle the status_approved_hrd filter
        if ($request->has('status_approved_hrd')) {
            if ($request->status_approved_hrd === '0' || $request->status_approved_hrd === '1' || $request->status_approved_hrd === '2' || $request->status_approved_hrd === '3') {
                $query->where('status_approved_hrd', $request->status_approved_hrd);
            }
        }

        $cutiapproval = $query->paginate(10);
        $cutiapproval->appends($request->all());

        // Add sisa_cuti_real for each cuti
        foreach ($cutiapproval as $d) {
            // Fetch sisa_cuti from cuti table
            $cutiRecord = DB::table('cuti')
                ->where('nik', $d->nik)
                ->where('tahun', $d->periode)
                ->first();

            // Calculate sisa_cuti_real
            if ($cutiRecord) {
                $d->sisa_cuti_real = $cutiRecord->sisa_cuti;
            } else {
                $d->sisa_cuti_real = 0; // or any default value
            }
        }

        return view('laporan.viewCuti', compact('cutiapproval'));
    }

    public function exportCuti()
    {
        $years = DB::table('pengajuan_cuti')
            ->selectRaw('MIN(YEAR(tgl_cuti)) as earliest_year, MAX(YEAR(tgl_cuti_sampai)) as latest_year')
            ->first();

        $earliestYear = $years->earliest_year;
        $latestYear = $years->latest_year;
        return view('laporan.cuti', compact('earliestYear', 'latestYear'));
    }

    public function reportCuti(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Validate the inputs
        if (empty($bulan) || empty($tahun)) {
            return redirect()->back()->with('danger', 'Bulan dan Tahun harus dipilih!');
        }

        $fileName = "laporan_pengajuan_cuti_{$bulan}_{$tahun}.xlsx";
        return Excel::download(new CutiExport($bulan, $tahun), $fileName);
    }

    public function viewSisaCuti(Request $request)
    {
        // Join cuti table with karyawan table on NIK
        $query = Cuti::query();
        $query->select('cuti.*', 'karyawan.nama_lengkap', 'karyawan.tgl_masuk', 'department.nama_dept');
        $query->join('karyawan', 'cuti.nik', '=', 'karyawan.nik');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        // Order by NIK
        $query->orderBy('nik', 'asc');
        $query->orderBy('tahun', 'desc');

        // Filter by Nama if provided
        if (!empty($request->nama_kar)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_kar . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }

        if (!empty($request->nik_req)) {
            $query->where('cuti.nik', 'like', '%' . $request->nik_req . '%');
        }

        if (!empty($request->tahun_req)) {
            $query->where('cuti.tahun', 'like', '%' . $request->tahun_req . '%');
        }
        if ($request->has('status')) {
            if ($request->status === '0' || $request->status === '1' || $request->status === '2') {
                $query->where('status', $request->status);
            }
        } else {
            // Default to '0' (Pending) if no status_approved_hrd is provided
            $query->where('status', 1);
        }

        // Paginate the results
        $cuti = $query->paginate(50)->appends($request->query());
        $department = DB::table('department')->get();

        // Return the view with the results
        return view("laporan.viewSisaCuti", compact('cuti', 'department'));
    }

    public function showAttendanceTable(Request $request)
    {

        $years = DB::connection('mysql2')
            ->table('db_absen.att_log')
            ->selectRaw('MIN(YEAR(scan_date)) as earliest_year, MAX(YEAR(scan_date)) as latest_year')
            ->first();

        $earliestYear = $years->earliest_year;
        $latestYear = $years->latest_year;
        // Retrieve month and year filters, default to current month and year
        $filterMonth = $request->input('bulan', Carbon::now()->month);
        $filterYear = $request->input('tahun', Carbon::now()->year);

        // Total days in the selected month and year
        $daysInMonth = Carbon::create($filterYear, $filterMonth, 1)->daysInMonth;

        // Initialize arrays for each category
        $hadir = [];
        $telat = [];
        $izin = [];
        $cuti = [];
        $mangkir = [];

        // Get total active employees excluding grade 'NS'
        $totalKaryawan = DB::table('karyawan')
            ->where('status_kar', 'Aktif')
            ->where('grade', '!=', 'NS')
            ->count();

        // Loop through each day of the selected month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($filterYear, $filterMonth, $day)->format('Y-m-d');

            // Hadir and Telat
            $rekappresensi = DB::connection('mysql2')
                ->table(DB::raw('(
                    SELECT pin, MIN(scan_date) as earliest_scan
                    FROM db_absen.att_log
                    WHERE DATE(scan_date) = "' . $date . '"
                    GROUP BY pin
                ) as presensi'))
                ->selectRaw('
                    COUNT(presensi.pin) as hadir,
                    COUNT(CASE WHEN TIME(presensi.earliest_scan) > "08:00:00" THEN presensi.pin END) as telat
                ')
                ->join('hrmschl.karyawan', 'presensi.pin', '=', 'karyawan.nip')
                ->where('status_kar', 'Aktif')
                ->where('grade', '!=', 'NS')
                ->first();

            $hadir[$day] = $rekappresensi->hadir ?? 0;
            $telat[$day] = $rekappresensi->telat ?? 0;



            // Izin
            $izin[$day] = DB::table('pengajuan_izin')
                ->join('karyawan', 'pengajuan_izin.nip', '=', 'karyawan.nip')
                ->where('tgl_izin', '<=', $date)
                ->where('tgl_izin_akhir', '>=', $date)
                ->where('pengajuan_izin.status_approved', 1)
                ->where('pengajuan_izin.status_approved_hrd', 1)
                ->where('karyawan.grade', '!=', 'NS')
                ->count();

            // Cuti
            $cuti[$day] = DB::table('pengajuan_cuti')
                ->join('karyawan', 'pengajuan_cuti.nip', '=', 'karyawan.nip')
                ->where('tgl_cuti', '<=', $date)
                ->where('tgl_cuti_sampai', '>=', $date)
                ->where('pengajuan_cuti.status_approved', 1)
                ->where('pengajuan_cuti.status_approved_hrd', 1)
                ->where('pengajuan_cuti.status_management', 1)
                ->where('karyawan.grade', '!=', 'NS')
                ->count();

            // Mangkir
            $mangkir[$day] = $totalKaryawan - ($hadir[$day] + $izin[$day] + $cuti[$day]);
        }

        $percentMangkir = [];
        $percentHadir = [];
        $percentIzin = [];
        $percentCuti = [];
        $percentTelat = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            // Mangkir percentage = Mangkir / Total Karyawan * 100
            $percentMangkir[$day] = round(($mangkir[$day] / $totalKaryawan) * 100, 0);


            // Mangkir percentage = Mangkir / Total Karyawan * 100
            $percentIzin[$day] = round(($izin[$day] / $totalKaryawan) * 100, 0);


            $percentCuti[$day] = round(($cuti[$day] / $totalKaryawan) * 100, 0);

            // Hadir percentage = Hadir / Total Karyawan * 100
            $percentHadir[$day] = round(($hadir[$day] / $totalKaryawan) * 100, 0);

            // Telat percentage = Telat / Hadir * 100 (avoid division by zero)
            $percentTelat[$day] = $hadir[$day] > 0 ? round(($telat[$day] / $hadir[$day]) * 100, 0) : 0;
        }

        // Add total percentages
        $totalPercentHadir = round((array_sum($hadir) / ($totalKaryawan * $daysInMonth)) * 100, 2);
        $totalPercentMangkir = round((array_sum($mangkir) / ($totalKaryawan * $daysInMonth)) * 100, 2);
        $totalPercentIzin = round((array_sum($izin) / ($totalKaryawan * $daysInMonth)) * 100, 2);
        $totalPercentCuti = round((array_sum($cuti) / ($totalKaryawan * $daysInMonth)) * 100, 2);
        $totalPercentTelat = array_sum($hadir) > 0 ? round((array_sum($telat) / array_sum($hadir)) * 100, 2) : 0;

        // Return the view with filtered results
        return view('laporan.dailyMonitor', compact(
            'daysInMonth',
            'hadir',
            'telat',
            'izin',
            'cuti',
            'mangkir',
            'totalKaryawan',
            'filterMonth',
            'filterYear',
            'earliestYear',
            'latestYear',
            'totalPercentHadir',
            'totalPercentMangkir',
            'totalPercentTelat',
            'percentHadir',
            'percentMangkir',
            'percentCuti',
            'percentIzin',
            'totalPercentIzin',
            'totalPercentCuti',
            'percentTelat'
        ));
    }

    public function sendDailyReport()
    {
        $hariini = Carbon::now()->format('Y-m-d');

        // Fetch total active employees
        $totalKaryawan = DB::table('karyawan')
            ->where('status_kar', 'Aktif')
            ->where('grade', '!=', 'NS')
            ->count();

        $hadirList = DB::connection('mysql2')
            ->table('db_absen.att_log as presensi')
            ->select('presensi.pin', 'karyawan.nama_lengkap', 'karyawan.kode_dept')
            ->join('hrmschl.karyawan', 'presensi.pin', '=', 'karyawan.nip')
            ->whereDate(DB::raw('DATE(presensi.scan_date)'), $hariini)
            ->where('karyawan.status_kar', 'Aktif')
            ->where('karyawan.grade', '!=', 'NS')
            ->distinct()
            ->get();

        $hadir = $hadirList->count(); // This will count the unique karyawan who have scanned in today

        // Izin List
        $izinList = DB::table('pengajuan_izin')
            ->join('karyawan', 'pengajuan_izin.nip', '=', 'karyawan.nip')
            ->where('tgl_izin', '<=', $hariini)
            ->where('tgl_izin_akhir', '>=', $hariini)
            ->whereNotIn('pengajuan_izin.status_approved', [3]) // Exclude status_approved = 3
            ->whereNotIn('pengajuan_izin.status_approved_hrd', [3]) // Exclude status_approved_hrd = 3
            ->select('karyawan.nama_lengkap', 'pengajuan_izin.status', 'pengajuan_izin.keterangan', 'pengajuan_izin.nip')
            ->get()
            ->map(function ($item) {
                $statusMap = [
                    'Dt' => 'Datang Terlambat',
                    'Tam' => 'Tidak Absen Masuk',
                    'Tap' => 'Tidak Absen Pulang',
                    'Tjo' => 'Tukar Jadwal Off',
                    'Tmk' => 'Tidak Masuk Kerja',
                    'Pa' => 'Pulang Awal',
                    'Ta' => 'Tidak Absen (Masuk & Pulang)'
                ];
                $item->status = $statusMap[$item->status] ?? $item->status;
                return $item;
            });

        $izin = $izinList->count();

        // Cuti List
        $cutiList = DB::table('pengajuan_cuti')
            ->join('karyawan', 'pengajuan_cuti.nip', '=', 'karyawan.nip')
            ->where('tgl_cuti', '<=', $hariini)
            ->where('tgl_cuti_sampai', '>=', $hariini)
            ->whereNotIn('pengajuan_cuti.status_approved', [3]) // Exclude status_approved = 3
            ->whereNotIn('pengajuan_cuti.status_approved_hrd', [3]) // Exclude status_approved_hrd = 3
            ->whereNotIn('pengajuan_cuti.status_management', [3]) // Exclude status_approved_hrd = 3
            ->select('karyawan.nama_lengkap', 'pengajuan_cuti.note', 'pengajuan_cuti.jenis', 'pengajuan_cuti.nip')
            ->get();

        $cuti = $cutiList->count();

        // Telat (Late) list
        $telatList = DB::connection('mysql2')
            ->table(DB::raw('(
            SELECT pin, MIN(scan_date) as earliest_scan
            FROM db_absen.att_log
            WHERE DATE(scan_date) = "' . $hariini . '"
            GROUP BY pin
        ) as presensi'))
            ->select('karyawan.nip as pin', 'karyawan.nik as nik', 'karyawan.nama_lengkap', 'karyawan.kode_dept')
            ->join('hrmschl.karyawan', 'presensi.pin', '=', 'karyawan.nip')
            ->where('status_kar', 'Aktif')
            ->where('grade', '!=', 'NS')
            ->whereTime('earliest_scan', '>', '08:00:00')
            ->get();

        $telat = $telatList->count();

        // Mangkir (Absent without leave) list
        $hadirDanIzinDanCuti = $hadirList->pluck('pin')->map(fn($pin) => (string) $pin)
            ->merge($izinList->pluck('nip')->map(fn($nip) => (string) $nip))
            ->merge($cutiList->pluck('nip')->map(fn($nip) => (string) $nip));


        $mangkirList = DB::table('karyawan')
            ->select('nip as pin', 'nama_lengkap', 'kode_dept', 'nik')
            ->where('status_kar', 'Aktif')
            ->where('grade', '!=', 'NS')
            ->where('kode_dept', '!=', 'Management') // Exclude Management department
            ->whereNotIn('nip', $hadirDanIzinDanCuti) // Exclude hadir, izin, and cuti employees
            ->get();


        $mangkir = $mangkirList->count();

        $telatGrouped = $telatList->groupBy('kode_dept');
        $telatDetails = $telatGrouped->map(function ($items, $dept) {
            $listItems = $items->map(function ($item) {
                return "<li>{$item->nama_lengkap} (NIK: {$item->nik})</li>";
            })->implode('');
            return "<h4>Dept: {$dept}</h4><ul>{$listItems}</ul>";
        })->implode('');

        // Group Mangkir by Department
        $mangkirGrouped = $mangkirList->groupBy('kode_dept');
        $mangkirDetails = $mangkirGrouped->map(function ($items, $dept) {
            $listItems = $items->map(function ($item) {
                return "<li>{$item->nama_lengkap} (NIK: {$item->nik})</li>";
            })->implode('');
            return "<h4>Dept: {$dept}</h4><ul>{$listItems}</ul>";
        })->implode('');

        $izinDetails = $izinList->map(function ($item) {
            return "<li>{$item->nama_lengkap} - <strong>{$item->status}</strong> ({$item->keterangan})</li>";
        })->implode('');

        $cutiDetails = $cutiList->map(function ($item) {
            return "<li>{$item->nama_lengkap} - <strong>{$item->jenis}</strong> ({$item->note})</li>";
        })->implode('');

        // Prepare email content
        $emailContent = "
        <h1>Laporan Kehadiran Karyawan - {$hariini}</h1>
        <p>Total Karyawan: {$totalKaryawan}</p>
        <ul>
            <li><strong>Hadir:</strong> {$hadir}</li>
            <li><strong>Telat:</strong> {$telat}</li>
            <li><strong>Izin:</strong> {$izin}</li>
            <li><strong>Cuti:</strong> {$cuti}</li>
            <li><strong>Mangkir:</strong> {$mangkir}</li>
        </ul>
        <h3>Yang Mangkir:</h3>
        <ul>
            {$mangkirDetails}
        </ul>
        <h3>Yang Telat:</h3>
        <ul>
            {$telatDetails}
        </ul>
        <h3>Yang Izin:</h3>
        <ul>
            {$izinDetails}
        </ul>
        <h3>Yang Cuti:</h3>
        <ul>
            {$cutiDetails}
        </ul>
        <br>
        <p>Silakan cek detail di <a href='https://hrms.ciptaharmoni.com/panel'>HRMS Panel</a>.</p>
        <br><br>
        <p>Terima kasih,</p>
    ";

        // Send email
        $managementEmails = ['al.imron@ciptaharmoni.com'];
        $ccList = ['human.resources@ciptaharmoni.com'];
        $tanggalHari = DateHelper::formatIndonesianDate($hariini);

        foreach ($managementEmails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::html($emailContent, function ($message) use ($email, $ccList, $tanggalHari) {
                    $message->to($email)
                        ->subject("Laporan Kehadiran Karyawan Harian Tanggal {$tanggalHari}")
                        ->cc($ccList)
                        ->priority(1);

                    $message->getHeaders()->addTextHeader('Importance', 'high');
                    $message->getHeaders()->addTextHeader('X-Priority', '1');
                });
            }
        }
        Session::flash('success', 'Laporan Kehadiran Karyawan Harian berhasil dikirim.');

        // Redirect back to the dashboard
        return redirect()->route('panel.dashboardadmin');
    }

    public function viewIzinAtasan(Request $request)
    {
        // Get selected month and year, default to current month if not selected
        $bulan = $request->bulan ?? now()->format('Y-m');

        // Extract year and month
        [$year, $month] = explode('-', $bulan);

        // Query pengajuan_izin for the selected month
        $izinData = PengajuanIzin::whereYear('tgl_create', $year)
            ->whereMonth('tgl_create', $month)
            ->whereNotNull('tgl_status_approved') // Ignore NULL approvals
            ->get();

        // Prepare atasan data
        $atasanStats = [];

        foreach ($izinData as $izin) {
            $nik = $izin->nik;

            // Find karyawan to get their jabatan
            $karyawan = Karyawan::where('nik', $nik)->first();
            if (!$karyawan) continue;

            // Find jabatan to get jabatan_atasan
            $jabatan = Jabatan::where('id', $karyawan->jabatan)->first();
            if (!$jabatan) continue;

            // Find atasan's information
            $atasan = Karyawan::where('jabatan', $jabatan->jabatan_atasan)->first();
            if (!$atasan) continue;

            $atasanjabatan = Jabatan::where('id', $atasan->jabatan)->first();
            if (!$atasanjabatan) continue;

            // Convert dates to Carbon instances
            $tglCreate = Carbon::parse($izin->tgl_create);
            $tglApproved = Carbon::parse($izin->tgl_status_approved);

            // Calculate approval time in days
            $approvalTime = $tglCreate->diffInDays($tglApproved);

            // Store in atasan stats
            $atasanId = $atasan->nik;
            if (!isset($atasanStats[$atasanId])) {
                $atasanStats[$atasanId] = [
                    'nik' => $atasan->nik,
                    'nama_lengkap' => $atasan->nama_lengkap,
                    'kode_dept' => $atasan->kode_dept,
                    'nama_jabatan' => $atasanjabatan->nama_jabatan,
                    'approval_times' => []
                ];
            }

            $atasanStats[$atasanId]['approval_times'][] = $approvalTime;
        }

        // Compute min, max, and average for each atasan
        foreach ($atasanStats as &$stats) {
            if (!empty($stats['approval_times'])) {
                $times = $stats['approval_times'];
                $stats['min_time'] = min($times);
                $stats['max_time'] = max($times);
                $stats['avg_time'] = round(array_sum($times) / count($times), 2);
            } else {
                // Handle case where no approvals were recorded
                $stats['min_time'] = null;
                $stats['max_time'] = null;
                $stats['avg_time'] = null;
            }
        }

        // Convert to collection for pagination
        $izinAtasan = collect(array_values($atasanStats));

        return view('laporan.izinAtasan', compact('izinAtasan'));
    }

    public function viewCutiAtasan(Request $request)
    {
        // Get selected month and year, default to current month if not selected
        $bulan = $request->bulan ?? now()->format('Y-m');

        // Extract year and month
        [$year, $month] = explode('-', $bulan);

        // Query pengajuan_izin for the selected month
        $cutiData = PengajuanCuti::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereNotNull('tgl_status_approved') // Ignore NULL approvals
            ->get();

        // Prepare atasan data
        $atasanStats = [];

        foreach ($cutiData as $cuti) {
            $nik = $cuti->nik;

            // Find karyawan to get their jabatan
            $karyawan = Karyawan::where('nik', $nik)->first();
            if (!$karyawan) continue;

            // Find jabatan to get jabatan_atasan
            $jabatan = Jabatan::where('id', $karyawan->jabatan)->first();
            if (!$jabatan) continue;

            // Find atasan's information
            $atasan = Karyawan::where('jabatan', $jabatan->jabatan_atasan)->first();
            if (!$atasan) continue;

            $atasanjabatan = Jabatan::where('id', $atasan->jabatan)->first();
            if (!$atasanjabatan) continue;

            // Convert dates to Carbon instances
            $tglCreate = Carbon::parse($cuti->created_at);
            $tglApproved = Carbon::parse($cuti->tgl_status_approved);

            // Calculate approval time in days
            $approvalTime = $tglCreate->diffInDays($tglApproved);

            // Store in atasan stats
            $atasanId = $atasan->nik;
            if (!isset($atasanStats[$atasanId])) {
                $atasanStats[$atasanId] = [
                    'nik' => $atasan->nik,
                    'nama_lengkap' => $atasan->nama_lengkap,
                    'kode_dept' => $atasan->kode_dept,
                    'nama_jabatan' => $atasanjabatan->nama_jabatan,
                    'approval_times' => []
                ];
            }

            $atasanStats[$atasanId]['approval_times'][] = $approvalTime;
        }

        // Compute min, max, and average for each atasan
        foreach ($atasanStats as &$stats) {
            if (!empty($stats['approval_times'])) {
                $times = $stats['approval_times'];
                $stats['min_time'] = min($times);
                $stats['max_time'] = max($times);
                $stats['avg_time'] = round(array_sum($times) / count($times), 2);
            } else {
                // Handle case where no approvals were recorded
                $stats['min_time'] = null;
                $stats['max_time'] = null;
                $stats['avg_time'] = null;
            }
        }

        // Convert to collection for pagination
        $cutiAtasan = collect(array_values($atasanStats));

        return view('laporan.cutiAtasan', compact('cutiAtasan'));
    }
}
