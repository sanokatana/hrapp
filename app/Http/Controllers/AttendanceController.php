<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            ->select('nik', 'tgl_presensi', 'jam_in')
            ->whereMonth('tgl_presensi', $filterMonth)
            ->whereYear('tgl_presensi', $filterYear)
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
            $jam_in = Carbon::parse($attendance->jam_in);
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
}
