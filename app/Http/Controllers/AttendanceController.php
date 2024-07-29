<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
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
            ->whereNotIn('kode_dept', function ($query) {
                $query->select('kode_dept')
                    ->from('department')
                    ->where('nama_dept', '=', 'Security');
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
            $totalKaryawan = count($departmentKaryawan);

            foreach ($departmentKaryawan as $k) {
                $jumlahTelat = 0;
                $menitTelat = 0;
                $totalHadir = 0;
                $totalTidakHadir = 0;

                $row = [
                    'nama_lengkap' => $k->nama_lengkap,
                    'attendance' => []
                ];

                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $date = Carbon::create($filterYear, $filterMonth, $i);
                    $dateString = $date->toDateString();
                    $attendance = $presensi->where('nip', $k->nip)->where('tgl_presensi', $dateString)->first();
                    $isCuti = $this->checkCuti($cuti, $k->nik, $date);
                    $isIzin = $this->checkIzin($izin, $k->nik, $date);
                    $status = $this->getAttendanceStatus($date, $attendance, $isCuti, $isIzin);


                    // Check if the date is a national holiday
                    if ($liburNasional->contains($dateString)) {
                        $status = 'LN'; // Mark as national holiday
                    }

                    // Calculate late minutes if attendance exists and status is 'T'
                    if ($attendance) {
                        $jam_in = Carbon::parse($attendance->earliest_jam_in);
                        if ($status === 'T') {
                            $menitTelat += $jam_in->diffInMinutes(Carbon::parse('08:00:00'));
                            $jumlahTelat++;
                        }
                    }

                    if ($status === 'P') {
                        $totalHadir++;
                    } elseif ($status === 'T') {
                        $totalTidakHadir++;
                    }

                    $row['attendance'][] = [
                        'status' => $status,
                        'class' => $this->determineAttendanceClass($date, $status)
                    ];
                }

                $row['jumlah_telat'] = $jumlahTelat;
                $row['menit_telat'] = $menitTelat;
                $row['presentase'] = round(($totalHadir / $daysInMonth) * 100, 2);
                $row['totalP'] = $totalHadir;
                $row['totalT'] = $totalTidakHadir;

                $totalJumlahTelat += $jumlahTelat;
                $totalP += $totalHadir;
                $totalT += $totalTidakHadir;

                $departmentAttendance[] = $row;
            }

            $attendanceData[] = [
                'department' => $department->nama_dept,
                'karyawan' => $departmentAttendance,
                'total_jumlah_telat' => $totalJumlahTelat,
                'total_presentase' => $totalKaryawan ? round(($totalT / ($totalKaryawan * $daysInMonth)) * 100, 2) : 0
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

    // Helper function to determine attendance status
    private function getAttendanceStatus($date, $attendance, $isCuti, $isIzin)
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
                    return 'M';
                } elseif ($keputusan == 'Tugas Luar') {
                    return 'TL';
                } elseif ($keputusan == 'Potong Cuti') {
                    return 'C';
                } elseif ($keputusan == 'Tukar Jadwal Off') {
                    return 'OFF'; // Handle Tukar Jadwal Off
                }
            }

            if ($status == 'Dt' && $attendance) {
                $jam_in = Carbon::parse($attendance->earliest_jam_in);
                if ($jam_in->gte(Carbon::parse('06:00:00')) && $jam_in->lt(Carbon::parse('13:00:00'))) {
                    if ($pukul && abs($jam_in->diffInMinutes(Carbon::parse($pukul))) <= 5 && $keputusan == 'Terlambat') {
                        return 'P';
                    } else {
                        return 'P';
                    }
                }
            }

            if ($status == 'Tam') {
                // If no attendance between 6 AM and 1 PM
                $jam_in = $attendance ? Carbon::parse($attendance->earliest_jam_in) : null;
                if (!$jam_in || $jam_in->lt(Carbon::parse('06:00:00')) || $jam_in->gte(Carbon::parse('13:00:00'))) {
                    return 'P';
                }
            }

            if ($status == 'Tjo') {
                return 'OFF'; // Specific status for Tukar Jadwal Off
            }
        }


        if ($attendance) {
            $jam_in = Carbon::parse($attendance->earliest_jam_in);
            if ($jam_in->gte(Carbon::parse('06:00:00')) && $jam_in->lt(Carbon::parse('13:00:00'))) {
                return $jam_in->gt(Carbon::parse('08:00:00')) ? 'T' : 'P';
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
                case 'S':
                    $classes[] = 'sakit';
                    break;
                case 'M':
                    $classes[] = 'mangkir';
                    break;
                case 'TL':
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


    public function daymonitoring(Request $request)
    {
        $query = DB::table('presensi')
            ->selectRaw('DATE(tgl_presensi) as tanggal, presensi.nip, nama_lengkap, nama_dept, MIN(jam_in) as jam_masuk, MAX(jam_in) as jam_pulang')
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->groupBy('tanggal', 'presensi.nip', 'nama_lengkap', 'nama_dept');

        if ($request->filled('nama_karyawan')) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('tgl_presensi', $request->tanggal);
        }
        $query->orderBy('tanggal', 'desc');
        $presensi = $query->paginate(10)->appends($request->all());

        return view("attendance.daymonitoring", compact('presensi'));
    }

    public function att_monitoring()
    {
        return view('attendance.attmonitor');
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
        foreach ($presensi as $record) {
            $tanggal = $record->tanggal;
            $nip = $record->nip;
            $nik = $record->nik; // Get the nik from the record
            $time = strtotime($record->jam_in);
            $morning_start = strtotime('06:00:00');
            $afternoon_start = strtotime('13:00:00');

            // If the time is before 6 AM, it should be considered as the previous day's jam_pulang
            if ($time < $morning_start) {
                $prev_tanggal = Carbon::parse($tanggal)->subDay()->toDateString();
                $key = $prev_tanggal . '_' . $nip;

                if (!isset($processedPresensi[$key])) {
                    $processedPresensi[$key] = [
                        'tanggal' => $prev_tanggal,
                        'nip' => $nip,
                        'nama_lengkap' => $record->nama_lengkap,
                        'nama_dept' => $record->nama_dept,
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                    ];
                }

                if (is_null($processedPresensi[$key]['jam_pulang']) || $time > strtotime($processedPresensi[$key]['jam_pulang'])) {
                    $processedPresensi[$key]['jam_pulang'] = $record->jam_in;
                }
            } else {
                $key = $tanggal . '_' . $nip;

                if (!isset($processedPresensi[$key])) {
                    $processedPresensi[$key] = [
                        'tanggal' => $tanggal,
                        'nip' => $nip,
                        'nama_lengkap' => $record->nama_lengkap,
                        'nama_dept' => $record->nama_dept,
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                    ];
                }

                if ($time >= $morning_start && $time < $afternoon_start) {
                    if (is_null($processedPresensi[$key]['jam_masuk']) || $time < strtotime($processedPresensi[$key]['jam_masuk'])) {
                        $processedPresensi[$key]['jam_masuk'] = $record->jam_in;
                    }
                } elseif ($time >= $afternoon_start) {
                    if (is_null($processedPresensi[$key]['jam_pulang']) || $time > strtotime($processedPresensi[$key]['jam_pulang'])) {
                        $processedPresensi[$key]['jam_pulang'] = $record->jam_in;
                    }
                }
            }

            // Apply the izin logic using nik
            $isIzin = $this->checkIzin($izin, $nik, Carbon::parse($tanggal));
            if ($isIzin) {
                $status = $isIzin->status;
                $keputusan = $isIzin->keputusan;
                if ($status == 'Dt' && $keputusan == 'Terlambat') {
                    $processedPresensi[$key]['jam_masuk'] = '08:00:00';
                }

                if ($status == 'Pa' && $keputusan == 'Pulang Awal') {
                    $processedPresensi[$key]['jam_pulang'] = '17:00:00';
                }

                if ($status == 'Tam' && !$processedPresensi[$key]['jam_masuk']) {
                    $processedPresensi[$key]['jam_masuk'] = '08:00:00';
                }

                if ($status == 'Tap' && !$processedPresensi[$key]['jam_pulang']) {
                    $processedPresensi[$key]['jam_pulang'] = '17:00:00';
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

        $query = DB::table('presensi')
            ->selectRaw('DATE(tgl_presensi) as tanggal, presensi.nip, presensi.nik, nama_lengkap, nama_dept, jam_in')
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');

        if ($tanggal) {
            $query->whereDate('tgl_presensi', $tanggal);
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
        foreach ($presensi as $record) {
            $tanggal = $record->tanggal;
            $nip = $record->nip;
            $nik = $record->nik; // Get the nik from the record
            $time = strtotime($record->jam_in);
            $morning_start = strtotime('06:00:00');
            $afternoon_start = strtotime('13:00:00');

            // If the time is before 6 AM, it should be considered as the previous day's jam_pulang
            if ($time < $morning_start) {
                $prev_tanggal = Carbon::parse($tanggal)->subDay()->toDateString();
                $key = $prev_tanggal . '_' . $nip;

                if (!isset($processedPresensi[$key])) {
                    $processedPresensi[$key] = [
                        'tanggal' => $prev_tanggal,
                        'nip' => $nip,
                        'nama_lengkap' => $record->nama_lengkap,
                        'nama_dept' => $record->nama_dept,
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                    ];
                }

                if (is_null($processedPresensi[$key]['jam_pulang']) || $time > strtotime($processedPresensi[$key]['jam_pulang'])) {
                    $processedPresensi[$key]['jam_pulang'] = $record->jam_in;
                }
            } else {
                $key = $tanggal . '_' . $nip;

                if (!isset($processedPresensi[$key])) {
                    $processedPresensi[$key] = [
                        'tanggal' => $tanggal,
                        'nip' => $nip,
                        'nama_lengkap' => $record->nama_lengkap,
                        'nama_dept' => $record->nama_dept,
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                    ];
                }

                if ($time >= $morning_start && $time < $afternoon_start) {
                    if (is_null($processedPresensi[$key]['jam_masuk']) || $time < strtotime($processedPresensi[$key]['jam_masuk'])) {
                        $processedPresensi[$key]['jam_masuk'] = $record->jam_in;
                    }
                } elseif ($time >= $afternoon_start) {
                    if (is_null($processedPresensi[$key]['jam_pulang']) || $time > strtotime($processedPresensi[$key]['jam_pulang'])) {
                        $processedPresensi[$key]['jam_pulang'] = $record->jam_in;
                    }
                }
            }

            // Apply the izin logic using nik
            $isIzin = $this->checkIzin($izin, $nik, Carbon::parse($tanggal));
            if ($isIzin) {
                $status = $isIzin->status;
                $keputusan = $isIzin->keputusan;
                if ($status == 'Dt' && $keputusan == 'Terlambat') {
                    $processedPresensi[$key]['jam_masuk'] = '08:00:00';
                }

                if ($status == 'Pa' && $keputusan == 'Pulang Awal') {
                    $processedPresensi[$key]['jam_pulang'] = '17:00:00';
                }

                if ($status == 'Tam' && !$processedPresensi[$key]['jam_masuk']) {
                    $processedPresensi[$key]['jam_masuk'] = '08:00:00';
                }

                if ($status == 'Tap' && !$processedPresensi[$key]['jam_pulang']) {
                    $processedPresensi[$key]['jam_pulang'] = '17:00:00';
                }
            }
        }

        // Convert the processed data to a collection for the view
        $presensi = collect($processedPresensi);

        return view("attendance.getatt", compact('presensi'));
    }
}
