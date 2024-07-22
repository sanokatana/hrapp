<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Get filter inputs
        $filterMonth = $request->input('bulan', Carbon::now()->month);
        $filterYear = Carbon::now()->year; // You can add a year filter if needed
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

        $izin = DB::table('pengajuan_izin')
            ->select('nik', 'tgl_izin', 'tgl_izin_akhir')
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

                    if ($status === 'T') {
                        $jumlahTelat++;
                        $menitTelat += Carbon::parse($attendance->earliest_jam_in)->diffInMinutes(Carbon::parse('08:05:00'));
                        $totalTidakHadir++;
                    }
                    if ($status === 'P') {
                        $totalHadir++;
                    }

                    $row['attendance'][] = [
                        'status' => $status,
                        'class' => $this->getAttendanceClass($date, $status)
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
            return 'I';
        }

        if ($attendance) {
            $jam_in = Carbon::parse($attendance->earliest_jam_in);
            if ($date->dayOfWeek == Carbon::SATURDAY || $date->dayOfWeek == Carbon::SUNDAY) {
                return $jam_in->gt(Carbon::parse('08:05:00')) ? 'T' : 'P';
            } else {
                return $jam_in->gt(Carbon::parse('08:05:00')) ? 'T' : 'P';
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

    private function checkIzin($izin, $nik, $date)
    {
        foreach ($izin as $c) {
            if ($c->nik == $nik && $date->between(Carbon::parse($c->tgl_izin), Carbon::parse($c->tgl_izin_akhir))) {
                return true;
            }
        }
        return false;
    }

    // Helper function to determine CSS classes for attendance cell
    private function getAttendanceClass($date, $status)
    {
        $classes = [];
        if ($date->dayOfWeek == Carbon::SATURDAY || $date->dayOfWeek == Carbon::SUNDAY) {
            if ($status == 'T') {
                $classes[] = 'late';
            } else if ($status == 'P') {
                $classes[] = '';
            } else if ($status == 'LN') {
                $classes[] = 'dark-yellow';
            } else {
                $classes[] = 'weekend';
            }
        } else {
            if ($status == 'T') {
                $classes[] = 'late';
            } else if ($status == 'LN') {
                $classes[] = 'dark-yellow';
            } else if ($status == 'C') {
                $classes[] = 'cuti';
            } else if ($status == 'I') {
                $classes[] = 'izin';
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

        $query = DB::table('presensi')
            ->selectRaw('DATE(tgl_presensi) as tanggal, presensi.nip, nama_lengkap, nama_dept, MIN(jam_in) as jam_masuk, MAX(jam_in) as jam_pulang')
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->groupBy('tanggal', 'presensi.nip', 'nama_lengkap', 'nama_dept');

        if ($nama_lengkap) {
            $query->where('nama_lengkap', 'like', '%' . $nama_lengkap . '%');
        }

        $query->orderBy('tgl_presensi', 'desc');
        $presensi = $query->get();

        return view("attendance.getatt", compact('presensi'));
    }

}
