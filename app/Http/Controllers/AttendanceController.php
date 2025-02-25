<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{

    public function index(Request $request)
    {

        $filterMonth = $request->input('bulan', Carbon::now()->month);
        $filterYear = $request->input('tahun', Carbon::now()->year);

        // Add a cache key for the last update check
        $lastUpdateKey = "last_update_{$filterMonth}_{$filterYear}";

        // Check if there's new data by comparing timestamps
        $shouldRefreshCache = $this->shouldRefreshCache($filterMonth, $filterYear, $lastUpdateKey);

        if ($shouldRefreshCache) {
            $this->clearAttendanceCache($filterMonth, $filterYear);
        }

        // Get the departments excluding "Security"
        $departments = cache()->remember('departments', 60, function () {
            return DB::table('department')->get();
        });

        // Get the number of days in the selected month
        $daysInMonth = Carbon::create($filterYear, $filterMonth)->daysInMonth;

        $totalWorkdays = $this->getTotalWorkdays($filterYear, $filterMonth);

        // Get karyawan data with filters, excluding "Security" department
        $karyawan = $this->getKaryawanData($request, $filterMonth, $filterYear);

        // Get the earliest and latest years from the presensi table
        $years = DB::connection('mysql2')
            ->table('db_absen.att_log')
            ->selectRaw('MIN(YEAR(scan_date)) as earliest_year, MAX(YEAR(scan_date)) as latest_year')
            ->first();

        $earliestYear = $years->earliest_year;
        $latestYear = $years->latest_year;

        // Get presensi data for the selected month
        // In your index method, update the presensi caching section:
        $presensi = cache()->remember("presensi_{$filterMonth}_{$filterYear}", 60, function () use ($filterMonth, $filterYear) {
            $records = DB::connection('mysql2')
                ->table('db_absen.att_log as presensi')
                ->select([
                    'presensi.pin',
                    DB::raw('DATE(presensi.scan_date) as scan_date'),
                    DB::raw('MIN(TIME(presensi.scan_date)) as earliest_jam_in')
                ])
                ->whereMonth('presensi.scan_date', $filterMonth)
                ->whereYear('presensi.scan_date', $filterYear)
                ->groupBy('presensi.pin', DB::raw('DATE(presensi.scan_date)'))
                ->get();

            // Transform and group the data by pin
            return $records->groupBy(function ($record) {
                return $record->pin;
            })->map(function ($pinRecords) {
                return $pinRecords->map(function ($record) {
                    return (object)[
                        'pin' => $record->pin,
                        'scan_date' => $record->scan_date,
                        'earliest_jam_in' => $record->earliest_jam_in,
                    ];
                });
            });
        });

        // Get national holidays for the selected month
        $liburNasional = cache()->remember("libur_nasional_{$filterMonth}_{$filterYear}", 60, function () use ($filterMonth, $filterYear) {
            return DB::table('libur_nasional')
                ->whereMonth('tgl_libur', $filterMonth)
                ->whereYear('tgl_libur', $filterYear)
                ->pluck('tgl_libur')
                ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'));
        });

        // Cache leave data
        $cuti = cache()->remember("cuti_{$filterMonth}_{$filterYear}", 60, function () use ($filterMonth, $filterYear) {
            return DB::table('pengajuan_cuti')
                ->select('nik', 'tgl_cuti', 'tgl_cuti_sampai')
                ->where('status_approved', 1)
                ->where('status_approved_hrd', 1)
                ->where('status_management', 1)
                ->where(function ($query) use ($filterMonth, $filterYear) {
                    $query->whereMonth('tgl_cuti', $filterMonth)
                        ->whereYear('tgl_cuti', $filterYear)
                        ->orWhereMonth('tgl_cuti_sampai', $filterMonth)
                        ->whereYear('tgl_cuti_sampai', $filterYear);
                })
                ->get();
        });

        // Cache izin data
        $izin = cache()->remember("izin_{$filterMonth}_{$filterYear}", 60, function () use ($filterMonth, $filterYear) {
            return DB::table('pengajuan_izin')
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
        });

        // Cache employee holiday data
        $liburKaryawan = cache()->remember("libur_karyawan_{$filterMonth}_{$filterYear}", 60, function () use ($filterMonth, $filterYear) {
            return DB::table('libur_kar')
                ->whereMonth('month', $filterMonth)
                ->whereYear('month', $filterYear)
                ->pluck('id', 'nik');
        });

        $liburKarDays = DB::table('libur_kar_day')
            ->whereIn('libur_id', $liburKaryawan)
            ->pluck('tanggal', 'libur_id');

        // Process presensi and cuti data to format for display
        $attendanceData = $this->processLargeDataSet(
            $departments,
            $karyawan,
            $presensi,
            $filterMonth,
            $filterYear,
            $daysInMonth,
            $totalWorkdays,
            $liburNasional,
            $cuti,
            $izin,
            $liburKaryawan,
            $liburKarDays
        );

        // Prepare data for the view
        $data = [
            'attendanceData' => $attendanceData,
            'daysInMonth' => $daysInMonth,
            'currentMonth' => $filterMonth,
            'currentYear' => $filterYear,
            'departments' => $departments,
            'earliestYear' => $earliestYear,
            'latestYear' => $latestYear,
        ];

        return view('attendance.attendance', $data);
    }

    private function shouldRefreshCache($month, $year, $lastUpdateKey)
    {
        // Get the last update time from cache
        $lastUpdate = cache()->get($lastUpdateKey);

        // Check for new data in relevant tables
        $latestUpdate = $this->getLatestUpdate($month, $year);

        // If there's no last update time or new data is found, update the cache
        if (!$lastUpdate || $latestUpdate > $lastUpdate) {
            cache()->put($lastUpdateKey, now(), now()->addHours(24));
            return true;
        }

        return false;
    }

    private function getLatestUpdate($month, $year)
    {
        // Get the latest update timestamp from all relevant tables
        $latest = now()->startOfMonth();

        // Check attendance data
        $attendanceUpdate = DB::connection('mysql2')
            ->table('db_absen.att_log')
            ->whereMonth('scan_date', $month)
            ->whereYear('scan_date', $year)
            ->max('scan_date');

        // Check leave data
        $leaveUpdate = DB::table('pengajuan_cuti')
            ->whereMonth('tgl_cuti', $month)
            ->whereYear('tgl_cuti', $year)
            ->max('created_at');

        // Check permission data
        $permissionUpdate = DB::table('pengajuan_izin')
            ->whereMonth('tgl_izin', $month)
            ->whereYear('tgl_izin', $year)
            ->max('tgl_create');

        // Compare all timestamps and return the latest
        $timestamps = array_filter([
            $attendanceUpdate ? Carbon::parse($attendanceUpdate) : null,
            $leaveUpdate ? Carbon::parse($leaveUpdate) : null,
            $permissionUpdate ? Carbon::parse($permissionUpdate) : null,
        ]);

        return empty($timestamps) ? $latest : max($timestamps);
    }

    private const CACHE_PREFIX = 'attendance_';
    private function getCacheKey($type, $month, $year, $additional = '')
    {
        return self::CACHE_PREFIX . "{$type}_{$month}_{$year}_{$additional}";
    }

    private function clearAttendanceCache($month, $year)
    {
        // Clear all relevant cache keys
        cache()->forget("presensi_{$month}_{$year}");
        cache()->forget($this->getCacheKey('departments', $month, $year));
        cache()->forget("libur_nasional_{$month}_{$year}");
        cache()->forget("cuti_{$month}_{$year}");
        cache()->forget("izin_{$month}_{$year}");
        cache()->forget("libur_karyawan_{$month}_{$year}");
        cache()->forget("karyawan_data_{$month}_{$year}_" . md5(''));
    }

    private function getKaryawanData(Request $request, $filterMonth, $filterYear)
    {
        $cacheKey = "karyawan_data_{$filterMonth}_{$filterYear}_" .
            md5($request->input('nama_lengkap') . $request->input('kode_dept'));

        return cache()->remember($cacheKey, now()->addHours(1), function () use ($request) {
            return DB::table('karyawan')
                ->where('status_kar', 'Aktif')
                ->when($request->input('nama_lengkap'), function ($query, $name) {
                    return $query->where('nama_lengkap', 'like', '%' . $name . '%');
                })
                ->when($request->input('kode_dept'), function ($query, $dept) {
                    return $query->where('kode_dept', $dept);
                })
                ->select([
                    'nip',
                    'nik',
                    'nama_lengkap',
                    'kode_dept',
                    'shift_pattern_id',
                    'start_shift'
                ])
                ->get()
                ->groupBy('kode_dept');
        });
    }

    private function processLargeDataSet($departments, $karyawan, $presensi, $filterMonth, $filterYear, $daysInMonth, $totalWorkdays, $liburNasional, $cuti, $izin, $liburKaryawan, $liburKarDays)
    {
        // Pre-process holiday data for faster lookups
        $holidayDates = $liburNasional->flip()->toArray();
        $employeeHolidays = $liburKarDays->flip()->toArray();
        $leaveCache = $this->buildLeaveCache($cuti);
        $permissionCache = $this->buildPermissionCache($izin);

        return collect($departments)->map(function ($department) use (
            $karyawan,
            $presensi,
            $filterMonth,
            $filterYear,
            $daysInMonth,
            $totalWorkdays,
            $holidayDates,
            $leaveCache,
            $permissionCache,
            $employeeHolidays
        ) {
            $departmentKaryawan = $karyawan->get($department->kode_dept, collect());
            $totals = $this->initializeTotals();
            $departmentAttendance = collect();

            // Process all employees in parallel using chunks
            $chunks = $departmentKaryawan->chunk(100);
            foreach ($chunks as $chunk) {
                $chunkResults = $chunk->map(function ($employee) use (
                    $presensi,
                    $filterMonth,
                    $filterYear,
                    $daysInMonth,
                    $holidayDates,
                    $leaveCache,
                    $permissionCache,
                    $employeeHolidays,
                    $totalWorkdays
                ) {
                    return $this->processEmployeeAttendance(
                        $employee,
                        $presensi,
                        $filterMonth,
                        $filterYear,
                        $daysInMonth,
                        $holidayDates,
                        $leaveCache,
                        $permissionCache,
                        $employeeHolidays,
                        $totalWorkdays
                    );
                });

                $departmentAttendance = $departmentAttendance->concat($chunkResults->pluck('row'));
                foreach ($chunkResults as $result) {
                    $this->updateDepartmentTotals($totals, $result['totals']);
                }
            }

            return [
                'department' => $department->nama_dept,
                'karyawan' => $departmentAttendance,
                'total_jumlah_telat' => $totals['jumlahTelat'],
                'total_presentase' => $departmentKaryawan->count() ?
                    round(($totals['T'] / ($departmentKaryawan->count() * $totalWorkdays)) * 100) : 0
            ];
        })->values()->all();
    }

    private function processEmployeeAttendance($employee, $presensi, $filterMonth, $filterYear, $daysInMonth, $holidayDates, $leaveCache, $permissionCache, $employeeHolidays, $totalWorkdays)
    {
        $totals = $this->initializeTotals();
        $row = [
            'nama_lengkap' => $employee->nama_lengkap,
            'attendance' => []
        ];

        // Cache shift data for the entire month
        $shiftData = $this->getEmployeeShiftData($employee, $filterMonth);
        $employeePresensi = $presensi->get($employee->nip, collect());

        // Pre-calculate dates for the month
        $dates = collect(range(1, $daysInMonth))->map(function ($day) use ($filterYear, $filterMonth) {
            return Carbon::create($filterYear, $filterMonth, $day);
        });

        foreach ($dates as $date) {
            $dateString = $date->format('Y-m-d');

            // Quick holiday checks
            if (isset($employeeHolidays[$dateString])) {
                $status = 'L';
            } else {
                // Process regular attendance
                $attendance = $employeePresensi->firstWhere('scan_date', $dateString);
                $shiftTimes = $this->getShiftTimes($date, $shiftData);

                $status = $this->getAttendanceStatus(
                    $date,
                    $attendance,
                    $this->checkCuti($leaveCache, $employee->nik, $date),
                    $this->checkIzin($permissionCache, $employee->nik, $date),
                    $shiftTimes['work_start'],
                    $shiftTimes['morning_start'],
                    $shiftTimes['afternoon_start'],
                    $shiftTimes['status_work']
                );

                $displayStatus = $status;
                if (str_contains($status, ':')) {
                    $displayStatus = explode(':', $status)[0]; // Always take the first part
                }

                // Update totals
                $this->updateTotals($totals, [
                    'status' => $status,
                    'attendance' => $attendance,
                    'shift_times' => $shiftTimes
                ]);
            }

            $row['attendance'][] = [
                'status' => $displayStatus,
                'class' => $this->determineAttendanceClass($date, $status)
            ];
        }

        $row = array_merge($row, $this->calculateEmployeeTotals($totals, $totalWorkdays));

        return [
            'row' => $row,
            'totals' => $totals
        ];
    }

    private function updateDepartmentTotals(&$departmentTotals, $employeeTotals)
    {
        foreach ($employeeTotals as $key => $value) {
            if (isset($departmentTotals[$key])) {
                $departmentTotals[$key] += $value;
            }
        }
    }

    private function calculateEmployeeTotals($totals, $totalWorkdays)
    {
        return [
            'jumlah_telat' => $totals['jumlahTelat'],
            'menit_telat' => $totals['menitTelat'],
            'totalP' => $totals['P'],
            'totalT' => $totals['T'],
            'totalOff' => $totals['Off'],
            'totalSakit' => $totals['Sakit'],
            'totalIzin' => $totals['Izin'],
            'totalCuti' => $totals['Cuti'],
            'totalH1' => $totals['H1'],
            'totalH2' => $totals['H2'],
            'totalDinas' => $totals['Dinas'],
            'totalBlank' => $totals['Blank'],
            'totalMangkir' => $totals['Mangkir'],
            'presentase' => $totalWorkdays > 0 ?
                round(($totals['T'] / $totalWorkdays) * 100, 2) : 0
        ];
    }

    private function getEmployeeShiftData($employee, $filterMonth)
    {
        $key = "shift_data_{$employee->nip}_{$filterMonth}";

        return cache()->remember($key, 60, function () use ($employee) {
            $shiftPatternId = $employee->shift_pattern_id;
            if (!$shiftPatternId) {
                return null;
            }

            $cycleLength = DB::table('shift_pattern_cycle')
                ->where('pattern_id', $shiftPatternId)
                ->count();

            $shiftPattern = DB::table('shift_pattern_cycle')
                ->where('pattern_id', $shiftPatternId)
                ->get()
                ->keyBy('cycle_day');

            $shifts = DB::table('shift')
                ->whereIn('id', $shiftPattern->pluck('shift_id'))
                ->get()
                ->keyBy('id');

            return [
                'pattern_id' => $shiftPatternId,
                'start_shift' => Carbon::parse($employee->start_shift),
                'cycle_length' => $cycleLength,
                'pattern' => $shiftPattern,
                'shifts' => $shifts
            ];
        });
    }

    private function getShiftTimes($date, $shiftData)
    {
        if (!$shiftData) {
            return $this->getDefaultShiftTimes();
        }

        $dayOfWeek = $date->dayOfWeekIso;
        $daysFromStart = $date->diffInDays($shiftData['start_shift']);

        $cycleDay = $shiftData['cycle_length'] == 7 ?
            $dayOfWeek : ($daysFromStart % $shiftData['cycle_length']) + 1;

        $pattern = $shiftData['pattern'][$cycleDay] ?? null;
        if (!$pattern) {
            return $this->getDefaultShiftTimes();
        }

        $shift = $shiftData['shifts'][$pattern->shift_id] ?? null;
        if (!$shift) {
            return $this->getDefaultShiftTimes();
        }

        return [
            'morning_start' => $shift->early_time ? strtotime($shift->early_time) : strtotime('07:00:00'),
            'work_start' => $shift->start_time ? strtotime($shift->start_time) : strtotime('08:00:00'),
            'afternoon_start' => $shift->latest_time ? strtotime($shift->latest_time) : strtotime('13:00:00'),
            'status_work' => $shift->status ?? 'P'
        ];
    }

    private function getDefaultShiftTimes()
    {
        return [
            'morning_start' => strtotime('07:00:00'),
            'work_start' => strtotime('08:00:00'),
            'afternoon_start' => strtotime('13:00:00'),
            'status_work' => 'P'
        ];
    }

    private function initializeTotals()
    {
        return [
            'jumlahTelat' => 0,
            'menitTelat' => 0,
            'P' => 0,
            'T' => 0,
            'Off' => 0,
            'Sakit' => 0,
            'Izin' => 0,
            'Cuti' => 0,
            'H1' => 0,
            'H2' => 0,
            'Dinas' => 0,
            'Blank' => 0,
            'Mangkir' => 0
        ];
    }

    private function updateTotals(&$totals, $dayData)
    {
        $status = $dayData['status'];
        $attendance = $dayData['attendance'];
        $shiftTimes = $dayData['shift_times'];

        if ($attendance) {
            $jam_in = Carbon::parse($attendance->earliest_jam_in);
            $workStart = Carbon::createFromTimestamp($shiftTimes['work_start']);

            if ($jam_in->greaterThan($workStart)) {
                $totals['menitTelat'] += $jam_in->diffInMinutes($workStart);
                $totals['jumlahTelat']++;
            }
        }

        switch ($status) {
            case 'P':
                $totals['P']++;
                break;
            case 'T':
                $totals['T']++;
                break;
            case 'OFF':
                $totals['Off']++;
                break;
            case 'S':
                $totals['Sakit']++;
                break;
            case 'I':
                $totals['Izin']++;
                break;
            case 'C':
                $totals['Cuti']++;
                break;
            case 'H1':
                $totals['H1']++;
                break;
            case 'H2':
                $totals['H2']++;
                break;
            case 'D':
                $totals['Dinas']++;
                break;
            case 'MK':
                $totals['Mangkir']++;
                break;
            case '':
                $totals['Blank']++;
                break;
        }
    }

    // ... rest of your existing helper methods ...

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

        // Check for OFF day after night shift
        if ($status_work === 'OFF' && $attendance) {
            $jam_in = Carbon::parse($attendance->earliest_jam_in);
            // If clock-in is before 12pm (noon), it's likely from previous night shift
            if ($jam_in->format('H:i:s') < '12:00:00') {
                return 'OFF';
            }
        }

        // Check for Cuti - but handle weekends differently for non-shift workers
        if ($isCuti) {
            // For non-shift workers (empty status_work or 'OFF'), show 'L' on weekends
            if (($status_work === 'L' || $status_work === 'OFF') && $date->isWeekend()) {
                return 'L';
            }
            return 'C'; // Show Cuti for all other cases
        }

        // If the employee has izin (Permission), handle multiple izin with priority
        if ($isIzin) {
            // If $isIzin is an array or collection of multiple izin
            $priorityOrder = ['Tmk', 'Ta', 'Dt', 'Tam', 'Tjo'];
            $selectedIzin = null;

            // Convert to array if it's not already
            $izinArray = is_array($isIzin) ? $isIzin : [$isIzin];

            // Find the highest priority izin
            foreach ($priorityOrder as $priority) {
                foreach ($izinArray as $izin) {
                    if ($izin->status === $priority) {
                        $selectedIzin = $izin;
                        break 2; // Break both loops when found
                    }
                }
            }

            // If we found a prioritized izin, handle it
            if ($selectedIzin) {
                switch ($selectedIzin->status) {
                    case 'Tmk':
                        return $this->handleTmkStatus($selectedIzin->keputusan);
                    case 'Ta':
                        return $this->handleTaStatus($selectedIzin->keputusan);
                    case 'Dt':
                        return $this->handleDtStatus($attendance, $morning_start, $afternoon_start, $status_work);
                    case 'Tam':
                        return $this->handleTamStatus($attendance, $morning_start, $afternoon_start, $status_work, $selectedIzin->keputusan);
                    case 'Tjo':
                        return 'OFF';
                }
            }
        }

        // Check if it's a national holiday (LN)
        if ($this->isNationalHoliday($date)) {
            // For shift workers (non-empty status_work), treat it as a regular work day
            if ($status_work !== 'P' && $status_work !== 'OFF') {
                if ($attendance) {
                    return $this->handleAttendance($attendance, $work_start, $morning_start, $afternoon_start, $status_work);
                }
                return ''; // Missing attendance on a holiday for shift worker
            }

            // For non-shift workers or OFF shifts
            if ($attendance) {
                return $this->handleAttendance($attendance, $work_start, $morning_start, $afternoon_start, $status_work);
            }
            return 'LN';
        }

        if ($attendance) {
            return $this->handleAttendance($attendance, $work_start, $morning_start, $afternoon_start, $status_work);
        }

        if ($status_work === 'OFF') {
            return 'OFF';
        }

        if ($status_work === 'L') {
            if ($attendance) {
                return $this->handleAttendance($attendance, $work_start, $morning_start, $afternoon_start, $status_work);
            } else {
                return 'L';
            }
        }

        // For shift workers, don't return 'L' on weekends if they have a non-OFF status_work
        if ($status_work !== 'OFF' && $status_work !== '') {
            return ''; // Empty for missing attendance, regardless of weekend
        }

        // Only return 'L' for weekend if not a shift worker
        return $date->isWeekend() ? 'L' : '';
    }

    // Add this helper method to check for national holidays
    private function isNationalHoliday($date)
    {
        $dateString = $date->format('Y-m-d');

        // Cache the national holidays for better performance
        $cacheKey = 'national_holidays_' . $date->format('Y_m');

        $nationalHolidays = cache()->remember($cacheKey, 60, function () use ($date) {
            return DB::table('libur_nasional')
                ->whereMonth('tgl_libur', $date->month)
                ->whereYear('tgl_libur', $date->year)
                ->pluck('tgl_libur')
                ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
                ->toArray();
        });

        return in_array($dateString, $nationalHolidays);
    }

    // Separate method for handling 'Tmk' (Terlambat - Sakit, Ijin, etc.)
    private function handleTmkStatus($keputusan)
    {
        return match ($keputusan) {
            'Sakit' => 'S:SAKIT', // Internal status code for Sick
            'Ijin' => 'I',
            'Mangkir' => 'MK',
            'Tugas Luar' => 'D',
            default => 'C',
        };
    }

    // Separate method for handling 'Ta' (Ijin or Tugas Luar)
    private function handleTaStatus($keputusan)
    {
        return match ($keputusan) {
            'Ijin' => 'I',
            'Tugas Luar' => 'D',
            default => 'P',
        };
    }

    // Handle the case where the employee is working in the day
    // Handle the case where the employee is working in the day
    private function handleDtStatus($attendance, $morning_start, $afternoon_start, $status_work)
    {
        if (!$attendance) {
            return 'P'; // Return empty if no attendance is available
        }

        // If earliest_jam_in is null, set it to 7:00 AM
        $jam_in = Carbon::parse($attendance->earliest_jam_in ?? '07:00');

        if ($jam_in->gte(Carbon::parse($morning_start)) && $jam_in->lt(Carbon::parse($afternoon_start))) {
            return $status_work;
        }

        return 'P'; // No valid attendance during working hours
    }

    // Handle the case where the employee is working with delayed attendance
    private function handleTamStatus($attendance, $morning_start, $afternoon_start, $status_work, $keputusan)
    {
        if (!$attendance) {
            if ($keputusan === 'Tugas Luar') {
                return 'D';
            }
            return 'P'; // Return empty if no attendance is available
        }

        // If earliest_jam_in is null, set it to 7:00 AM
        $jam_in = Carbon::parse($attendance->earliest_jam_in ?? '07:00');

        if (!$jam_in || $jam_in->lt(Carbon::parse($morning_start)) || $jam_in->gte(Carbon::parse($afternoon_start))) {
            return $status_work;
        }
        return ''; // No valid attendance during working hours
    }

    // Handle the case where the employee is on 'OFF' status
    private function handleOffStatus($attendance, $work_start, $morning_start, $afternoon_start)
    {
        if (!$attendance) {
            return 'OFF'; // Return empty if no attendance is available
        }

        // If earliest_jam_in is null, set it to 7:00 AM
        $jam_in = Carbon::parse($attendance->earliest_jam_in ?? '07:00');

        if ($jam_in->lt(Carbon::parse($morning_start))) {
            return $this->handleLateOrOnTimeAttendance($attendance, $work_start, $morning_start);
        } else {
            return $this->handleAttendance($attendance, $work_start, $morning_start, $afternoon_start, 'T');
        }
    }

    // Handle attendance checks (late or present)
    private function handleAttendance($attendance, $work_start, $morning_start, $afternoon_start, $status_work)
    {
        if (!$attendance) {
            return ''; // Return empty if no attendance is available
        }

        // If earliest_jam_in is null, set it to 7:00 AM
        $jam_in = Carbon::parse($attendance->earliest_jam_in ?? '07:00');

        return $jam_in->gte(Carbon::parse($morning_start)) && $jam_in->lt(Carbon::parse($afternoon_start))
            ? ($jam_in->gt(Carbon::parse($work_start)) ? 'T' : $status_work)
            : 'T'; // Late if after work_start
    }

    // Helper function to determine if the date falls within a leave period
    private function buildLeaveCache($cuti)
    {
        $cache = [];
        foreach ($cuti as $leave) {
            $start = Carbon::parse($leave->tgl_cuti);
            $end = Carbon::parse($leave->tgl_cuti_sampai);
            $key = $leave->nik;

            if (!isset($cache[$key])) {
                $cache[$key] = [];
            }

            $current = $start->copy();
            while ($current->lte($end)) {
                $cache[$key][$current->format('Y-m-d')] = true;
                $current->addDay();
            }
        }
        return $cache;
    }

    private function buildPermissionCache($izin)
    {
        $cache = [];
        foreach ($izin as $permission) {
            $start = Carbon::parse($permission->tgl_izin);
            $end = Carbon::parse($permission->tgl_izin_akhir);
            $key = $permission->nik;

            if (!isset($cache[$key])) {
                $cache[$key] = [];
            }

            $current = $start->copy();
            while ($current->lte($end)) {
                $dateKey = $current->format('Y-m-d');
                // Initialize array for this date if it doesn't exist
                if (!isset($cache[$key][$dateKey])) {
                    $cache[$key][$dateKey] = [];
                }
                // Add this permission to the array
                $cache[$key][$dateKey][] = $permission;
                $current->addDay();
            }
        }
        return $cache;
    }

    private function checkIzin($permissionCache, $nik, $date)
    {
        $dateKey = $date->format('Y-m-d');

        // If no permissions exist for this date
        if (!isset($permissionCache[$nik][$dateKey])) {
            return null;
        }

        // If only one permission exists, return it directly
        if (count($permissionCache[$nik][$dateKey]) === 1) {
            return $permissionCache[$nik][$dateKey][0];
        }

        // If multiple permissions exist, sort by priority and return the highest priority one
        $permissions = $permissionCache[$nik][$dateKey];
        $priorityOrder = ['Tmk', 'Ta', 'Dt', 'Tam', 'Tjo'];

        // Find the highest priority permission
        foreach ($priorityOrder as $priority) {
            foreach ($permissions as $permission) {
                if ($permission->status === $priority) {
                    return $permission;
                }
            }
        }

        // If no priority match found, return the first permission
        return $permissions[0];
    }

    // Updated helper methods
    private function checkCuti($leaveCache, $nik, $date)
    {
        return isset($leaveCache[$nik][$date->format('Y-m-d')]);
    }

    // Updated function to determine CSS classes for attendance cell
    private function determineAttendanceClass($date, $status)
    {
        $classes = [];

        // Split status if it's a special case
        $displayStatus = $status;
        $type = null;

        if (str_contains($status, ':')) {
            $parts = explode(':', $status);
            $displayStatus = $parts[0];
            $type = $parts[1] ?? null;
        }

        // Check if the date is a weekend
            switch ($displayStatus) {
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
                    $classes[] = '';
                    break;
                case 'S':
                    // Check if it's Sick or Siang
                    if ($type === 'SAKIT') {
                        $classes[] = 'sakit';
                    } else {
                        $classes[] = 'shift-siang';
                    }
                    break;
                case 'MK':
                    $classes[] = 'mangkir';
                    break;
                case 'D':
                    $classes[] = 'tugas_luar';
                    break;
                case 'OFF':
                    $classes[] = 'tukar_off';
                    break;
                default:
                    break;
            }


        return implode(' ', $classes);
    }

    public function uploadAtt(Request $request)
    {
        set_time_limit(600); // Increase maximum execution time to 5 minutes

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('danger', 'Please upload a valid CSV file.');
        }

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $fileHandle = fopen($filePath, 'r');

        // Skip the header row
        $headers = fgetcsv($fileHandle, 1000, ';');

        $data = [];
        $batchSize = 500; // Process 500 rows at a time
        $rowCount = 0;

        while ($row = fgetcsv($fileHandle, 1000, ';')) {
            if (count($row) == 4) {
                $nip = $row[0];
                $nik = $row[1];
                $tgl_presensi = $row[2];
                $jam_in = $row[3];

                $data[] = [
                    'nip' => $nip,
                    'nik' => $nik,
                    'tgl_presensi' => Carbon::createFromFormat('d/m/Y', $tgl_presensi)->format('Y-m-d'),
                    'jam_in' => $jam_in,
                ];
            }

            $rowCount++;
            if ($rowCount % $batchSize == 0) {
                // Insert batch
                DB::table('presensi')->insert($data);
                $data = []; // Reset data array
            }
        }

        // Insert any remaining data
        if (!empty($data)) {
            DB::table('presensi')->insert($data);
        }

        fclose($fileHandle);

        return redirect()->back()->with('success', 'Data Berhasil Di Simpan');
    }

    public function att_monitoring()
    {
        return view('attendance.attmonitor');
    }

    public function get_att(Request $request)
    {
        $nama_lengkap = $request->nama_lengkap;
        $nip = $request->nip;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $query = DB::connection('mysql2')
            ->table('db_absen.att_log as presensi')
            ->selectRaw('DATE(presensi.scan_date) as tanggal, presensi.pin, karyawan.nik, karyawan.nama_lengkap, department.nama_dept, TIME(presensi.scan_date) as jam_in')
            ->join('hrmschl.karyawan as karyawan', 'presensi.pin', '=', 'karyawan.nip') // Join karyawan to get nip
            ->join('hrmschl.department as department', 'karyawan.kode_dept', '=', 'department.kode_dept');

        if ($nama_lengkap) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $nama_lengkap . '%');
        }

        if ($nip) {
            $query->where('presensi.pin', $nip);
        }

        if ($bulan && $tahun) {
            // Convert the bulan and tahun to a start and end date for filtering
            $startDate = Carbon::createFromFormat('Y-m', $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT))->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT))->endOfMonth();

            // Apply whereBetween to filter scan_date
            $query->whereBetween('presensi.scan_date', [$startDate, $endDate]);
        }

        $query->orderBy('presensi.scan_date', 'asc')
            ->orderBy('jam_in', 'asc'); // Ensure proper ordering by scan_date and jam_in

        $presensi = $query->get();


        // Fetch approved izin data for the relevant dates and NIPs
        $izin = DB::table('pengajuan_izin')
            ->select('nik', 'tgl_izin', 'tgl_izin_akhir', 'status', 'keputusan', 'pukul')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->get();

        // Process records to find min and max jam_in
        $processedPresensi = [];
        foreach ($presensi as $record) {
            $tanggal = $record->tanggal;
            $nip = $record->pin;
            $nik = $record->nik;
            $jam_in = $record->jam_in;
            $time = strtotime($jam_in);
            // Determine the current day of the week (1 = Monday, 7 = Sunday)
            $dayOfWeek = Carbon::parse($tanggal)->dayOfWeekIso;

            // Get the employee's shift pattern ID
            $shiftPatternId = DB::table('karyawan')
                ->where('nip', $nip)
                ->value('shift_pattern_id');

            if ($shiftPatternId) {
                // Get the pattern start date for this employee
                $patternStartDate = DB::table('karyawan')
                    ->where('nip', $nip)
                    ->value('start_shift');

                if ($patternStartDate) {
                    // Get pattern length
                    $patternLength = DB::table('shift_pattern_cycle')
                        ->where('pattern_id', $shiftPatternId)
                        ->count();

                    // Calculate days since pattern start
                    $daysSinceStart = Carbon::parse($patternStartDate)
                        ->diffInDays(Carbon::parse($tanggal));

                    if ($patternLength == 7) {
                        // Get the current day of week (1 = Monday, 7 = Sunday)
                        $currentDayOfWeek = Carbon::parse($tanggal)->dayOfWeekIso;

                        // For 7-day cycles, simply use the current day of week
                        $currentCycleDay = $currentDayOfWeek;
                        $prevDayCycleDay = $currentDayOfWeek == 1 ? 7 : $currentDayOfWeek - 1;
                    } else {
                        // For non-weekly patterns, use straight count from start date
                        $currentCycleDay = ($daysSinceStart % $patternLength) + 1;
                        $prevDayCycleDay = (($daysSinceStart - 1) % $patternLength) + 1;
                    }

                    // Find the corresponding shift ID for the current cycle day
                    $shiftId = DB::table('shift_pattern_cycle')
                        ->where('pattern_id', $shiftPatternId)
                        ->where('cycle_day', $currentCycleDay)
                        ->value('shift_id');

                    if ($shiftId) {
                        // Fetch the shift times and type
                        $shiftTimes = DB::table('shift')
                            ->where('id', $shiftId)
                            ->select('early_time', 'latest_time', 'start_time', 'status')
                            ->first();

                        if ($shiftTimes) {
                            if ($shiftTimes->status === 'OFF') {
                                $key = $tanggal . '_' . $nip;
                                if (!isset($processedPresensi[$key])) {
                                    $processedPresensi[$key] = [
                                        'tanggal' => $tanggal,
                                        'nip' => $nip,
                                        'nama_lengkap' => $record->nama_lengkap,
                                        'nama_dept' => $record->nama_dept,
                                        'jam_masuk' => '',
                                        'jam_pulang' => '',
                                        'shift_start_time' => '08:00:00' // Default time for OFF shifts
                                    ];
                                }

                                // For OFF shifts, any scan will be recorded
                                if (empty($processedPresensi[$key]['jam_masuk'])) {
                                    $processedPresensi[$key]['jam_masuk'] = $jam_in;
                                } elseif ($time > strtotime($processedPresensi[$key]['jam_masuk'])) {
                                    $processedPresensi[$key]['jam_pulang'] = $jam_in;
                                }
                            } else {
                                // Handle regular shifts with default times if needed
                                if ($shiftTimes->early_time === NULL || $shiftTimes->latest_time === NULL || $shiftTimes->start_time === NULL) {
                                    if ($shiftTimes->status === 'L' || $dayOfWeek >= 6) {
                                        // Weekend or holiday shift defaults
                                        $shift_start = strtotime('07:00:00');
                                        $window_start = strtotime('06:00:00');
                                        $window_end = strtotime('13:00:00');
                                    } else {
                                        // Weekday defaults
                                        $shift_start = strtotime('08:00:00');
                                        $window_start = strtotime('07:00:00');
                                        $window_end = strtotime('13:00:00');
                                    }
                                } else {
                                    // Use defined shift times
                                    $shift_start = strtotime($shiftTimes->start_time);
                                    $window_start = strtotime($shiftTimes->early_time);
                                    $window_end = strtotime($shiftTimes->latest_time);
                                }


                            $prevDayDate = Carbon::parse($tanggal)->subDay();
                            $prevDayCycleDay = (($daysSinceStart - 1) % $patternLength) + 1;

                            $prevDayShift = DB::table('shift_pattern_cycle')
                                ->where('pattern_id', $shiftPatternId)
                                ->where('cycle_day', $prevDayCycleDay)
                                ->join('shift', 'shift.id', '=', 'shift_pattern_cycle.shift_id')
                                ->value('shift.status');

                            if ($shiftTimes->status === 'M') { // Night shift
                                $key = $tanggal . '_' . $nip;
                                if (!isset($processedPresensi[$key])) {
                                    $processedPresensi[$key] = [
                                        'tanggal' => $tanggal,
                                        'nip' => $nip,
                                        'nama_lengkap' => $record->nama_lengkap,
                                        'nama_dept' => $record->nama_dept,
                                        'jam_masuk' => '',
                                        'jam_pulang' => '',
                                        'shift_start_time' => $shiftTimes->start_time
                                    ];
                                }

                                if ($time >= $window_start && $time <= $window_end) {
                                    if (
                                        empty($processedPresensi[$key]['jam_masuk']) ||
                                        $time < strtotime($processedPresensi[$key]['jam_masuk'])
                                    ) {
                                        $processedPresensi[$key]['jam_masuk'] = $jam_in;
                                    }
                                } elseif ($time <= strtotime('12:00:00')) {
                                    $prevKey = Carbon::parse($tanggal)->subDay()->format('Y-m-d') . '_' . $nip;
                                    if (isset($processedPresensi[$prevKey])) {
                                        $processedPresensi[$prevKey]['jam_pulang'] = $jam_in;
                                    }
                                }
                            } else { // Other shifts (P, S, MD) or OFF
                                if ($time <= strtotime('12:00:00') && $prevDayShift === 'M') {
                                    // If early morning scan and previous day was night shift
                                    $prevKey = Carbon::parse($tanggal)->subDay()->format('Y-m-d') . '_' . $nip;
                                    if (isset($processedPresensi[$prevKey])) {
                                        $processedPresensi[$prevKey]['jam_pulang'] = $jam_in;
                                    }
                                } else {
                                    // Normal day shift processing
                                    $key = $tanggal . '_' . $nip;
                                    if (!isset($processedPresensi[$key])) {
                                        $processedPresensi[$key] = [
                                            'tanggal' => $tanggal,
                                            'nip' => $nip,
                                            'nama_lengkap' => $record->nama_lengkap,
                                            'nama_dept' => $record->nama_dept,
                                            'jam_masuk' => '',
                                            'jam_pulang' => '',
                                            'shift_start_time' => date('H:i:s', $shift_start)
                                        ];
                                    }
                                    if ($time >= $window_start && $time <= $window_end) {
                                        if (
                                            empty($processedPresensi[$key]['jam_masuk']) ||
                                            $time < strtotime($processedPresensi[$key]['jam_masuk'])
                                        ) {
                                            $processedPresensi[$key]['jam_masuk'] = $jam_in;
                                        }
                                    } elseif ($time >= strtotime($shiftTimes->latest_time)) {
                                        $processedPresensi[$key]['jam_pulang'] = $jam_in;
                                    }
                                }
                            }
                        }
                        }
                    }
                }
            }
        }

        // Convert the processed data to a collection for the view
        $presensi = collect($processedPresensi);

        return view("attendance.getatt", compact('presensi'));
    }

    public function daymonitor()
    {
        return view('attendance.daymonitor');
    }

    public function showdaymonitor(Request $request)
    {
        $tanggal = $request->tanggal;

        // Define the next day for fetching the data
        $nextDay = Carbon::parse($tanggal)->addDay()->toDateString();

        $query = DB::connection('mysql2')
            ->table('db_absen.att_log as presensi')
            ->selectRaw('DATE(presensi.scan_date) as tanggal, presensi.pin, karyawan.nik, karyawan.nama_lengkap, department.nama_dept, TIME(presensi.scan_date) as jam_in')
            ->join('hrmschl.karyawan as karyawan', 'presensi.pin', '=', 'karyawan.nip') // Join karyawan to get nip
            ->join('hrmschl.department as department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->whereRaw('DATE(presensi.scan_date) = ?', [$tanggal]) // Filter only for the current day
            ->groupBy('presensi.pin', 'tanggal', 'karyawan.nik', 'karyawan.nama_lengkap', 'department.nama_dept', 'presensi.scan_date')
            ->orderBy('presensi.scan_date', 'asc')
            ->orderBy('jam_in', 'asc') // Ensure proper ordering by scan_date and jam_in
            ->orderBy('department.nama_dept', 'asc');

        $presensi = $query->get();


        // Fetch approved izin data for the relevant dates and NIPs
        $izin = DB::table('pengajuan_izin')
            ->select('nik', 'tgl_izin', 'tgl_izin_akhir', 'status', 'keputusan', 'pukul')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->get();

        // Process records to find min and max jam_in
        $processedPresensi = [];
        foreach ($presensi as $record) {
            $tanggal = $record->tanggal;
            $nip = $record->pin;
            $nik = $record->nik;
            $time = strtotime($record->jam_in);

            // Determine the current day of the week (1 = Monday, 7 = Sunday)
            $dayOfWeek = Carbon::parse($tanggal)->dayOfWeekIso;

            $shiftPatternId = DB::table('karyawan')
                ->where('nip', $nip)
                ->value('shift_pattern_id');

            if ($shiftPatternId) {
                // Find the corresponding shift ID for the current cycle day
                $shiftId = DB::table('shift_pattern_cycle')
                    ->where('pattern_id', $shiftPatternId)
                    ->where('cycle_day', $dayOfWeek)
                    ->value('shift_id');

                if ($shiftId) {
                    // Fetch the early_time and latest_time from the shifts table
                    $shiftTimes = DB::table('shift')
                        ->where('id', $shiftId)
                        ->select('early_time', 'latest_time', 'start_time')
                        ->first();

                    if ($shiftTimes) {
                        $morning_start = $shiftTimes->early_time ? strtotime($shiftTimes->early_time) : null;
                        $afternoon_start = $shiftTimes->latest_time ? strtotime($shiftTimes->latest_time) : null;
                        $shift_start_time = $shiftTimes->start_time ? strtotime($shiftTimes->start_time) : null; // Default to 08:00:00 if not set
                    } else {
                        $morning_start = null;
                        $afternoon_start = null;
                        $shift_start_time = null;
                    }
                } else {
                    $morning_start = null;
                    $afternoon_start = null;
                    $shift_start_time = null;
                }
            } else {
                $morning_start = null;
                $afternoon_start = null;
                $shift_start_time = null;
            }

            // Set default values if no shift times are available
            if ($morning_start === null) {
                $morning_start = strtotime('05:00:00');
            }
            if ($afternoon_start === null) {
                $afternoon_start = strtotime('13:00:00');
            }
            if ($shift_start_time === null) {
                $shift_start_time = strtotime('08:00:00');
            }

            $key = $tanggal . '_' . $nip;

            // If the time is before the morning start time, it should be considered as the previous day's jam_pulang
            if ($tanggal == $nextDay && $time < $morning_start) {
                $prev_tanggal = Carbon::parse($tanggal)->subDay()->toDateString();
                $prev_key = $prev_tanggal . '_' . $nip;

                if (!isset($processedPresensi[$prev_key])) {
                    $processedPresensi[$prev_key] = [
                        'tanggal' => $prev_tanggal,
                        'nip' => $nip,
                        'nama_lengkap' => $record->nama_lengkap,
                        'nama_dept' => $record->nama_dept,
                        'jam_masuk' => '',
                        'jam_pulang' => '',
                        'shift_start_time' => $shift_start_time, // Add shift start time here
                    ];
                }

                // Overwrite jam_pulang if the new jam_in is later
                if (empty($processedPresensi[$prev_key]['jam_pulang']) || $time > strtotime($processedPresensi[$prev_key]['jam_pulang'])) {
                    $processedPresensi[$prev_key]['jam_pulang'] = $record->jam_in;
                }
            } else {
                if (!isset($processedPresensi[$key])) {
                    $processedPresensi[$key] = [
                        'tanggal' => $tanggal,
                        'nip' => $nip,
                        'nama_lengkap' => $record->nama_lengkap,
                        'nama_dept' => $record->nama_dept,
                        'jam_masuk' => '',
                        'jam_pulang' => '',
                        'shift_start_time' => $shift_start_time, // Add shift start time here
                    ];
                }

                if ($time >= $morning_start && $time < $afternoon_start) {
                    // Set the earliest jam_in as jam_masuk
                    if (empty($processedPresensi[$key]['jam_masuk']) || $time < strtotime($processedPresensi[$key]['jam_masuk'])) {
                        $processedPresensi[$key]['jam_masuk'] = $record->jam_in;
                    }
                } elseif ($time >= $afternoon_start) {
                    // Set the latest jam_in as jam_pulang
                    if (empty($processedPresensi[$key]['jam_pulang']) || $time > strtotime($processedPresensi[$key]['jam_pulang'])) {
                        $processedPresensi[$key]['jam_pulang'] = $record->jam_in;
                    }
                }
            }

            // Apply the izin logic using nik
            $isIzin = $this->checkIzin($izin, $nik, Carbon::parse($tanggal));
            if ($isIzin) {
                $status = $isIzin->status;
                $keputusan = $isIzin->keputusan;
                $pukul = $isIzin->pukul;

                if ($status == 'Tam' && !$processedPresensi[$key]['jam_masuk']) {
                    $processedPresensi[$key]['jam_masuk'] = $pukul;
                }

                if ($status == 'Tap' && !$processedPresensi[$key]['jam_pulang']) {
                    $processedPresensi[$key]['jam_pulang'] = $pukul;
                }
            }
        }

        // Convert the processed data to a collection for the view
        $presensi = collect($processedPresensi);

        return view("attendance.getatt", compact('presensi'));
    }

    public function database()
    {
        return view('attendance.database');
    }

    public function databaseupdate()
    {
        try {
            // Truncate the presensi table in the default connection
            DB::statement('TRUNCATE TABLE presensi');

            // Insert data from att_log in mysql2 connection to hrmschl.presensi in the default connection
            DB::connection('mysql2')->transaction(function () {
                DB::connection('mysql2')->statement("
                    INSERT INTO hrmschl.presensi (nip, nik, tgl_presensi, jam_in)
                    SELECT
                        al.pin AS nip,
                        k.nik AS nik, -- Fetch nik from the karyawan table
                        DATE(al.scan_date) AS tgl_presensi,
                        TIME(al.scan_date) AS jam_in
                    FROM
                        db_absen.att_log al
                    LEFT JOIN
                        hrmschl.karyawan k ON al.pin = k.nip
                ");
            });

            return redirect()->back()->with('success', 'Database updated successfully!');
        } catch (\Exception $e) {
            Log::error('Database update failed: ' . $e->getMessage());
            return redirect()->back()->with('danger', 'Failed to update the database.');
        }
    }
}
