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
            ->where('status_kar', 'Aktif');


        if ($filterNamaLengkap) {
            $karyawanQuery->where('nama_lengkap', 'like', '%' . $filterNamaLengkap . '%');
        }
        if ($filterKodeDept) {
            $karyawanQuery->where('kode_dept', $filterKodeDept);
        }
        $karyawan = $karyawanQuery->get()->groupBy('kode_dept');

        // Get the earliest and latest years from the presensi table
        $years = DB::connection('mysql2')
            ->table('db_absen.att_log')
            ->selectRaw('MIN(YEAR(scan_date)) as earliest_year, MAX(YEAR(scan_date)) as latest_year')
            ->first();

        $earliestYear = $years->earliest_year;
        $latestYear = $years->latest_year;

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
                        $morning_start = strtotime('07:00:00');
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

                    // Calculate late minutes if attendance exists and earliest_jam_in is greater than work_start
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
