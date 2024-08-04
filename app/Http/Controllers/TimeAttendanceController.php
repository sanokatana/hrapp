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

                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $date = Carbon::create($filterYear, $filterMonth, $i);
                    $dateString = $date->toDateString();

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

                        if ($status == 'Tmk') {
                            $totalHours = 8;
                        } elseif ($status == 'Dt' && $keputusan == 'Terlambat') {
                            $jamMasuk = '08:00:00';
                        } elseif ($status == 'Pa' && $keputusan == 'Pulang Awal') {
                            $jamPulang = '17:00:00';
                        } elseif ($status == 'Tam') {
                            $jamMasuk = '08:00:00';
                        } elseif ($status == 'Tap') {
                            $jamPulang = '17:00:00';
                        }
                    }

                    // Calculate working hours if jamMasuk and jamPulang are available
                    if ($jamMasuk && $jamPulang) {
                        $jamMasukCarbon = Carbon::parse($jamMasuk);
                        $jamPulangCarbon = Carbon::parse($jamPulang);

                        if ($jamPulangCarbon->gt($jamMasukCarbon)) {
                            $minutesWorked = $jamMasukCarbon->diffInMinutes($jamPulangCarbon);
                            $totalHours = round(($minutesWorked - 60) / 60, 1); // Subtract 1 hour for rest and round to nearest decimal
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
}
