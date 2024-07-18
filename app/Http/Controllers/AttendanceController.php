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
        $filterYear = Carbon::now()->year; // You can add a year filter if needed
        $filterNamaLengkap = $request->input('nama_lengkap');
        $filterKodeDept = $request->input('kode_dept');

        // Get the departments
        $department = DB::table('department')->get();

        // Get the number of days in the selected month
        $daysInMonth = Carbon::create($filterYear, $filterMonth)->daysInMonth;

        // Get karyawan data with filters
        $karyawanQuery = DB::table('karyawan');
        if ($filterNamaLengkap) {
            $karyawanQuery->where('nama_lengkap', 'like', '%' . $filterNamaLengkap . '%');
        }
        if ($filterKodeDept) {
            $karyawanQuery->where('kode_dept', $filterKodeDept);
        }
        $karyawan = $karyawanQuery->get();

        // Get presensi data for the selected month
        $presensi = DB::table('presensi')
            ->select('nik', 'tgl_presensi', DB::raw('MIN(jam_in) as earliest_jam_in'))
            ->whereMonth('tgl_presensi', $filterMonth)
            ->whereYear('tgl_presensi', $filterYear)
            ->groupBy('nik', 'tgl_presensi')
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

        // Process presensi and cuti data to format for display
        $attendanceData = [];
        foreach ($karyawan as $k) {
            $row = [
                'nama_lengkap' => $k->nama_lengkap,
                'attendance' => []
            ];

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = Carbon::create($filterYear, $filterMonth, $i);
                $dateString = $date->toDateString();
                $attendance = $presensi->where('nik', $k->nik)->where('tgl_presensi', $dateString)->first();
                $isCuti = $this->checkCuti($cuti, $k->nik, $date);

                if ($attendance) {
                    Log::info("NIK: {$k->nik}, Date: {$dateString}, Earliest Jam In: {$attendance->earliest_jam_in}");
                }

                $status = $this->getAttendanceStatus($date, $attendance, $isCuti);

                // Check if the date is a national holiday
                if ($liburNasional->contains($dateString)) {
                    $status = 'LN'; // Mark as national holiday
                }

                $row['attendance'][] = [
                    'status' => $status,
                    'class' => $this->getAttendanceClass($date, $status)
                ];
            }


            $attendanceData[] = $row;
        }

        // Prepare data for the view
        $data = [
            'attendanceData' => $attendanceData,
            'daysInMonth' => $daysInMonth,
            'currentMonth' => $filterMonth,
            'currentYear' => $filterYear,
            'department' => $department,
        ];

        return view('attendance.attendance', $data);
    }

    // Helper function to determine attendance status
    private function getAttendanceStatus($date, $attendance, $isCuti)
    {
        if ($isCuti) {
            return 'C';
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

    // Helper function to determine CSS classes for attendance cell
    private function getAttendanceClass($date, $status)
    {
        $classes = [];
        if ($date->dayOfWeek == Carbon::SATURDAY || $date->dayOfWeek == Carbon::SUNDAY) {
            if ($status == 'T') {
                $classes[] = 'late';
            } else if ($status == 'P') {
                $classes[] = '';
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
        $headers = fgetcsv($fileHandle, 1000, ',');

        $data = [];
        $batchSize = 500; // Process 500 rows at a time
        $rowCount = 0;

        while ($row = fgetcsv($fileHandle, 1000, ',')) {
            if (count($row) == 4 && $row[0] !== 'NULL' && $row[1] !== 'NULL') {
                $nip = $row[0];
                $nik = $row[1];
                $tgl_presensi = $row[2];
                $jam_in = $row[3];

                // Check if nip exists in karyawan table
                $karyawanExists = DB::table('karyawan')->where('nik', $nik)->exists();

                if ($karyawanExists) {
                    $data[] = [
                        'nip' => $nip,
                        'nik' => $nik,
                        'tgl_presensi' => Carbon::createFromFormat('m/d/Y', $tgl_presensi)->format('Y-m-d'),
                        'jam_in' => $jam_in,
                    ];
                }
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


    // public function uploadAtt(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'file' => 'required|mimes:csv,txt',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->with('danger', 'Please upload a valid CSV file.');
    //     }

    //     $file = $request->file('file');
    //     $filePath = $file->getRealPath();
    //     $fileHandle = fopen($filePath, 'r');

    //     // Skip the header row
    //     $headers = fgetcsv($fileHandle, 1000, ','); // Specify delimiter as semicolon

    //     $data = [];
    //     while ($row = fgetcsv($fileHandle, 1000, ',')) { // Specify delimiter as semicolon
    //         // Ensure each row has the correct number of columns
    //         if (count($row) == 4) { // Assuming there are exactly 6 columns in your CSV
    //             $data[] = [
    //                 'nip' => $row[0],
    //                 'nik' => $row[1],
    //                 'tgl_presensi' => Carbon::createFromFormat('d/m/Y', $row[2])->format('Y-m-d'),
    //                 'jam_in' => $row[3],

    //             ];
    //         } else {
    //             return redirect()->back()->with('danger', 'Invalid CSV format: Each row must have exactly 7 columns.');
    //         }
    //     }

    //     fclose($fileHandle);

    //     try {
    //         DB::table('presensi')->insert($data);
    //         return redirect()->back()->with('success', 'Data Berhasil Di Simpan');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('danger', 'Data Gagal Di Simpan: ' . $e->getMessage());
    //     }
    // }
}
