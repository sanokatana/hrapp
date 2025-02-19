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
        $presensi = cache()->remember("presensi_{$filterMonth}_{$filterYear}", 60 * 24, function () use ($filterMonth, $filterYear) {
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
        $liburNasional = cache()->remember("libur_nasional_{$filterMonth}_{$filterYear}", 60 * 24, function() use ($filterMonth, $filterYear) {
            return DB::table('libur_nasional')
                ->whereMonth('tgl_libur', $filterMonth)
                ->whereYear('tgl_libur', $filterYear)
                ->pluck('tgl_libur')
                ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'));
        });

        // Cache leave data
        $cuti = cache()->remember("cuti_{$filterMonth}_{$filterYear}", 60 * 24, function() use ($filterMonth, $filterYear) {
            return DB::table('pengajuan_cuti')
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
        });

        // Cache izin data
        $izin = cache()->remember("izin_{$filterMonth}_{$filterYear}", 60 * 24, function() use ($filterMonth, $filterYear) {
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
        $liburKaryawan = cache()->remember("libur_karyawan_{$filterMonth}_{$filterYear}", 60 * 24, function() use ($filterMonth, $filterYear) {
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

    private function getKaryawanData(Request $request, $filterMonth, $filterYear)
    {
        $cacheKey = "karyawan_data_{$filterMonth}_{$filterYear}_" .
            md5($request->input('nama_lengkap') . $request->input('kode_dept'));

        return cache()->remember($cacheKey, now()->addHours(24), function () use ($request) {
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
        return collect($departments)->map(function ($department) use (
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
        ) {
            $departmentKaryawan = $karyawan->get($department->kode_dept, collect());
            $totals = $this->initializeTotals();

            // Process employees in chunks of 100
            $chunks = $departmentKaryawan->chunk(100);
            $departmentAttendance = collect();

            foreach ($chunks as $chunk) {
                foreach ($chunk as $employee) {
                    $employeeData = $this->processEmployeeAttendance(
                        $employee,
                        $presensi,
                        $filterMonth,
                        $filterYear,
                        $daysInMonth,
                        $liburNasional,
                        $cuti,
                        $izin,
                        $liburKaryawan,
                        $liburKarDays,
                        $totalWorkdays
                    );

                    $departmentAttendance->push($employeeData['row']);
                    $this->updateDepartmentTotals($totals, $employeeData['totals']);
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

    private function processEmployeeAttendance($employee, $presensi, $filterMonth, $filterYear, $daysInMonth, $liburNasional, $cuti, $izin, $liburKaryawan, $liburKarDays, $totalWorkdays)
    {
        $totals = $this->initializeTotals();
        $row = [
            'nama_lengkap' => $employee->nama_lengkap,
            'attendance' => []
        ];

        // Get employee's shift data
        $shiftData = $this->getEmployeeShiftData($employee, $filterMonth);

        // Process each day
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::create($filterYear, $filterMonth, $i);
            $dateString = $date->toDateString();

            $dayData = $this->processDayAttendance(
                $date,
                $employee,
                $presensi,
                $shiftData,
                $liburNasional,
                $cuti,
                $izin,
                $liburKarDays
            );

            $row['attendance'][] = [
                'status' => $dayData['status'],
                'class' => $this->determineAttendanceClass($date, $dayData['status'])
            ];

            $this->updateTotals($totals, $dayData);
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

    private function processDayAttendance($date, $employee, $presensi, $shiftData, $liburNasional, $cuti, $izin, $liburKarDays)
    {
        $dateString = $date->toDateString();

        // Get employee's attendance for this date
        $employeePresensi = $presensi->get($employee->nip);
        $attendance = $employeePresensi ? $employeePresensi->firstWhere('scan_date', $dateString) : null;

        // Get shift times
        $shiftTimes = $this->getShiftTimes($date, $shiftData);

        // Check various conditions
        $isCuti = $this->checkCuti($cuti, $employee->nik, $date);
        $isIzin = $this->checkIzin($izin, $employee->nik, $date);

        // Get attendance status
        $status = $this->getAttendanceStatus(
            $date,
            $attendance,
            $isCuti,
            $isIzin,
            $shiftTimes['work_start'],
            $shiftTimes['morning_start'],
            $shiftTimes['afternoon_start'],
            $shiftTimes['status_work']
        );

        // Override status for holidays
        if ($liburNasional->contains($dateString)) {
            $status = 'LN';
        }
        if ($liburKarDays->contains($dateString)) {
            $status = 'L';
        }

        return [
            'status' => $status,
            'attendance' => $attendance,
            'shift_times' => $shiftTimes
        ];
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
        // If the employee is on leave (Cuti)
        if ($isCuti) {
            if ($date->isWeekend()) {
                return 'L'; // Absent on weekends due to leave
            }
            return 'C'; // Cuti during the week
        }

        // If the employee has izin (Permission)
        if ($isIzin) {
            $status = $isIzin->status;
            $keputusan = $isIzin->keputusan;

            // Handling different izin statuses
            switch ($status) {
                case 'Tmk':
                    return $this->handleTmkStatus($keputusan);
                case 'Ta':
                    return $this->handleTaStatus($keputusan);
                case 'Dt':
                    return $this->handleDtStatus($attendance, $morning_start, $afternoon_start, $status_work);
                case 'Tam':
                    return $this->handleTamStatus($attendance, $morning_start, $afternoon_start, $status_work);
                case 'Tjo':
                    return 'OFF'; // Tukar Jadwal Off status
            }
        }

        // Handle standard attendance cases
        if ($status_work === 'OFF' && $attendance) {
            return $this->handleOffStatus($attendance, $work_start, $morning_start, $afternoon_start);
        }

        if ($attendance) {
            return $this->handleAttendance($attendance, $work_start, $morning_start, $afternoon_start, $status_work);
        }

        // No attendance, return empty for weekdays, 'L' for weekends
        return $date->isWeekend() ? 'L' : '';
    }

    // Separate method for handling 'Tmk' (Terlambat - Sakit, Ijin, etc.)
    private function handleTmkStatus($keputusan)
    {
        return match ($keputusan) {
            'Sakit' => 'S',
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
            return ''; // Return empty if no attendance is available
        }

        // If earliest_jam_in is null, set it to 7:00 AM
        $jam_in = Carbon::parse($attendance->earliest_jam_in ?? '07:00');

        if ($jam_in->gte(Carbon::parse($morning_start)) && $jam_in->lt(Carbon::parse($afternoon_start))) {
            return $status_work;
        }

        return ''; // No valid attendance during working hours
    }

    // Handle the case where the employee is working with delayed attendance
    private function handleTamStatus($attendance, $morning_start, $afternoon_start, $status_work)
    {
        if (!$attendance) {
            return ''; // Return empty if no attendance is available
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
            return ''; // Return empty if no attendance is available
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
                        $morning_start = $shiftTimes->early_time ? strtotime($shiftTimes->early_time) : strtotime('06:00:00');
                        $afternoon_start = $shiftTimes->latest_time ? strtotime($shiftTimes->latest_time) : strtotime('13:00:00');
                        $shift_start_time = $shiftTimes->start_time ? strtotime($shiftTimes->start_time) : strtotime('08:00:00'); // Default to 08:00:00 if not set
                    } else {
                        $morning_start = strtotime('06:00:00');
                        $afternoon_start = strtotime('13:00:00');
                        $shift_start_time = strtotime('08:00:00'); // Default shift start time
                    }
                } else {
                    $morning_start = strtotime('06:00:00');
                    $afternoon_start = strtotime('13:00:00');
                    $shift_start_time = strtotime('08:00:00'); // Default shift start time
                }
            } else {
                $morning_start = strtotime('06:00:00');
                $afternoon_start = strtotime('13:00:00');
                $shift_start_time = strtotime('08:00:00'); // Default shift start time
            }

            // Check if this time should be attributed to the previous day
            if ($time < $morning_start) {
                $prev_tanggal = Carbon::parse($tanggal)->subDay()->toDateString();
                $key = $prev_tanggal . '_' . $nip;

                // Initialize the entry for the previous day if not already present
                if (!isset($processedPresensi[$key])) {
                    $processedPresensi[$key] = [
                        'tanggal' => $prev_tanggal,
                        'nip' => $nip,
                        'nama_lengkap' => $record->nama_lengkap,
                        'nama_dept' => $record->nama_dept,
                        'jam_masuk' => '',
                        'jam_pulang' => '',
                        'shift_start_time' => $shift_start_time, // Add shift start time here
                    ];
                }

                // Always overwrite jam_pulang for the previous day if the new jam_in is later
                $processedPresensi[$key]['jam_pulang'] = $jam_in;
            } else {
                $key = $tanggal . '_' . $nip;

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
                    // Only set jam_masuk if it’s earlier than the existing value or if it's empty
                    if (empty($processedPresensi[$key]['jam_masuk']) || $time < strtotime($processedPresensi[$key]['jam_masuk'])) {
                        $processedPresensi[$key]['jam_masuk'] = $jam_in;
                    }
                } elseif ($time >= $afternoon_start) {
                    // Always overwrite jam_pulang if the new jam_in is later
                    $processedPresensi[$key]['jam_pulang'] = $jam_in;
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
