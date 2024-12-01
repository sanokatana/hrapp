<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $nip = Auth::guard('karyawan')->user()->nip;
        $tgl_presensi = date("Y-m-d"); // Extract the date part for checking existing records
        $scan_date = date("Y-m-d H:i:s"); // Current date and time

        // Join karyawan and jabatan tables to get nama_jabatan
        $namaUser = DB::table('karyawan')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->where('karyawan.nip', $nip)
            ->select('karyawan.*', 'jabatan.nama_jabatan')
            ->first();

        // Split nama_lengkap into first and last names
        $nameParts = explode(' ', $namaUser->nama_lengkap);
        $firstName = $nameParts[0];
        $lastName = end($nameParts);
        $namaUser->first_name = $firstName;
        $namaUser->last_name = $lastName;

        // Truncate nama_jabatan to the first two words
        $jobTitleParts = explode(' ', $namaUser->nama_jabatan);
        if (count($jobTitleParts) > 2) {
            $initials = strtoupper($jobTitleParts[0][0] . $jobTitleParts[1][0]);
            $remainingTitle = implode(' ', array_slice($jobTitleParts, 2));
            $namaUser->nama_jabatan = $initials . ' ' . $remainingTitle;
        } else {
            $namaUser->nama_jabatan;
        }

        $shiftPatternId = DB::table('karyawan')
            ->where('nip', $nip)
            ->value('shift_pattern_id');

        $startShift = Carbon::parse(DB::table('karyawan')
            ->where('nip', $nip)
            ->value('start_shift'));

        // Calculate cycle length from shift_pattern_cycle table
        $cycleLength = DB::table('shift_pattern_cycle')
            ->where('pattern_id', $shiftPatternId)
            ->count();
        $date = Carbon::parse();

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
                // Fetch the early_time and latest_time from the shifts table
                $shiftTimes = DB::table('shift')
                    ->where('id', $shiftId)
                    ->select('early_time', 'latest_time', 'start_time', 'status')
                    ->first();

                $morning_start = strtotime($shiftTimes->early_time);
                $work_start = strtotime($shiftTimes->start_time);
                $afternoon_start = strtotime($shiftTimes->latest_time);
            } else {
                // Default values if no shift is found
                $morning_start = strtotime('05:00:00');
                $work_start = strtotime('08:00:00');
                $afternoon_start = strtotime('13:00:00');
            }
        } else {
            // Default values if no shift pattern is found
            $morning_start = strtotime('06:00:00');
            $work_start = strtotime('08:00:00');
            $afternoon_start = strtotime('13:00:00');
        }

        // Fetch the arrival time dynamically based on the shift
        $arrival = DB::connection('mysql2')->table('att_log')
            ->where('pin', $nip)
            ->whereDate('scan_date', $tgl_presensi)
            ->whereTime('scan_date', '>=', date('H:i:s', $morning_start)) // Use $morning_start dynamically
            ->whereTime('scan_date', '<=', date('H:i:s', $afternoon_start)) // Use $work_start dynamically
            ->orderBy('scan_date', 'asc')
            ->first();

        // Fetch the departure time dynamically based on the shift
        $departure = DB::connection('mysql2')->table('att_log')
            ->where('pin', $nip)
            ->whereDate('scan_date', $tgl_presensi)
            ->whereTime('scan_date', '>', date('H:i:s', $afternoon_start)) // Use $afternoon_start dynamically
            ->orderBy('scan_date', 'desc')
            ->first();

        // Fetch approved izin data for the current month
        $izin = DB::table('pengajuan_izin')
            ->select('nip', 'tgl_izin', 'tgl_izin_akhir', 'status', 'keputusan', 'pukul')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->whereRaw('MONTH(tgl_izin) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_izin) = ?', [$tahunini])
            ->get();

        $historibulanini = DB::connection('mysql2')->table(DB::raw("(SELECT
            DATE(scan_date) as tanggal,
            TIME(MIN(scan_date)) as jam_masuk,
            TIME(MAX(scan_date)) as jam_pulang,
            pin
            FROM att_log
            WHERE pin = ?
            AND MONTH(scan_date) = ?
            AND YEAR(scan_date) = ?
            GROUP BY DATE(scan_date), pin) as sub"))
            ->select('sub.tanggal', 'sub.jam_masuk', 'sub.jam_pulang')
            ->orderBy('sub.tanggal', 'desc')
            ->setBindings([$nip, $bulanini, $tahunini])
            ->get();

        // Calculate total notifications
        $totalNotif = $this->calculateNotifications($historibulanini, $izin, $bulanini, $tahunini, $nip);


        // Process the presensi data to adjust for izin
        $processedHistoribulanini = $historibulanini->map(function ($item) use ($izin, $nip) {
            $shiftPatternId = DB::table('karyawan')
                ->where('nip', $nip)
                ->value('shift_pattern_id');

            $startShift = Carbon::parse(DB::table('karyawan')
                ->where('nip', $nip)
                ->value('start_shift'));

            // Calculate cycle length from shift_pattern_cycle table
            $cycleLength = DB::table('shift_pattern_cycle')
                ->where('pattern_id', $shiftPatternId)
                ->count();
            $date = Carbon::parse($item->tanggal);

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

            $isIzin = $this->checkIzin($izin, $nip, $date);
            $item->jam_kerja = $work_start;
            $jam_masuk_time = strtotime($item->jam_masuk);
            $jam_pulang_time = strtotime($item->jam_pulang);

            if ($jam_masuk_time < $morning_start) {
                $prev_date = Carbon::parse($item->tanggal)->subDay()->toDateString();
                $item->tanggal = $prev_date; // Adjust the date for early in time
            }

            if ($jam_masuk_time > $afternoon_start) {
                $item->jam_masuk = null; // If jam_masuk is before 1 PM, it should be null
            }

            if ($jam_pulang_time < $afternoon_start) {
                $item->jam_pulang = null; // If jam_pulang is before 1 PM, it should be null
            }

            if ($isIzin) {
                $status = $isIzin->status;
                $pukul = $isIzin->pukul;

                if ($status == 'Tam' && !$item->jam_masuk) {
                    $item->jam_masuk = $pukul;
                }

                if ($status == 'Tap' && !$item->jam_pulang) {
                    $item->jam_pulang = $pukul;
                }

                if ($status == 'Dt') {
                    $item->jam_masuk = $pukul;
                }
            }

            return $item;
        });

        // Calculate lateness based on adjusted jam_masuk times
        $rekappresensi = $processedHistoribulanini->reduce(function ($carry, $item) {
            $nip = Auth::guard('karyawan')->user()->nip;
            $shiftPatternId = DB::table('karyawan')
                ->where('nip', $nip)
                ->value('shift_pattern_id');

            $startShift = Carbon::parse(DB::table('karyawan')
                ->where('nip', $nip)
                ->value('start_shift'));

            // Calculate cycle length from shift_pattern_cycle table
            $cycleLength = DB::table('shift_pattern_cycle')
                ->where('pattern_id', $shiftPatternId)
                ->count();
            $date = Carbon::parse();

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
                    // Fetch the early_time and latest_time from the shifts table
                    $shiftTimes = DB::table('shift')
                        ->where('id', $shiftId)
                        ->select('early_time', 'latest_time', 'start_time', 'status')
                        ->first();
                    $work_start = strtotime($shiftTimes->start_time);
                } else {
                    $work_start = strtotime('08:00:00');
                }
            } else {
                $work_start = strtotime('08:00:00');
            }
            $lateness_threshold = strtotime('+1 minute', $work_start);
            $jam_masuk_time = strtotime($item->jam_masuk);

            // Increment total days and lateness count based on adjusted jam_masuk
            $carry['jmlhadir'] += 1;
            if ($jam_masuk_time > $lateness_threshold) {
                $carry['jmlterlambat'] += 1;
            }

            return $carry;
        }, ['jmlhadir' => 0, 'jmlterlambat' => 0]);

        $rekappresensi = (object) $rekappresensi;

        $historiizin = DB::table('pengajuan_izin')
            ->whereRaw('MONTH(tgl_izin)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_izin)="' . $tahunini . '"')
            ->where('nip', $nip)
            ->orderBy('tgl_izin')
            ->get();

        $historicuti = DB::table('pengajuan_cuti')
            ->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti')
            ->whereRaw('MONTH(pengajuan_cuti.tgl_cuti) = ?', [$bulanini])
            ->whereRaw('YEAR(pengajuan_cuti.tgl_cuti) = ?', [$tahunini])
            ->where('pengajuan_cuti.nip', $nip)
            ->select('pengajuan_cuti.*', 'tipe_cuti.tipe_cuti')
            ->orderBy('pengajuan_cuti.tgl_cuti')
            ->get();

        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('IFNULL(SUM(IF(status != "s", 1, 0)), 0) as jmlizin, IFNULL(SUM(IF(status="s", 1, 0)), 0) as jmlsakit')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tgl_izin) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_izin) = ?', [$tahunini])
            ->first();


        $rekapcuti = DB::table('pengajuan_cuti')
            ->selectRaw('count(id) as jmlcuti')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tgl_cuti)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_cuti)="' . $tahunini . '"')
            ->first();

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('dashboard.dashboard', compact('arrival', 'departure', 'processedHistoribulanini', 'namabulan', 'bulanini', 'tahunini', 'namaUser', 'rekappresensi', 'historiizin', 'historicuti', 'rekapizin', 'rekapcuti', 'totalNotif'));
    }

    // =============================================== DASHBOARD NOTIF CONTROLLER =============================================== //
    // =============================================== DASHBOARD NOTIF CONTROLLER =============================================== //
    // =============================================== DASHBOARD NOTIF CONTROLLER =============================================== //

    private function calculateNotifications($historibulanini, $izin, $bulanini, $tahunini, $nip)
    {
        // Initialize notifications array
        $notifications = [];

        // Generate all possible dates for the current month excluding weekends
        $dates = collect();
        $currentDate = Carbon::createFromFormat('Y-m-d', "{$tahunini}-{$bulanini}-01");
        $endDate = Carbon::now()->subDay();

        while ($currentDate <= $endDate) {
            $dayOfWeek = $currentDate->dayOfWeek;
            if ($dayOfWeek != Carbon::SATURDAY && $dayOfWeek != Carbon::SUNDAY) {
                $dates->push($currentDate->toDateString());
            }
            $currentDate->addDay();
        }

        // Process each date in the current month
        foreach ($dates as $dateString) {
            $date = Carbon::parse($dateString); // Convert the string to a Carbon instance

            $hasPresensi = $historibulanini->contains('tanggal', $dateString);
            $isIzin = $this->checkIzin($izin, $nip, $date);

            $shiftPatternId = DB::table('karyawan')
                ->where('nip', $nip)
                ->value('shift_pattern_id');

            $startShift = Carbon::parse(DB::table('karyawan')
                ->where('nip', $nip)
                ->value('start_shift'));

            // Calculate cycle length from shift_pattern_cycle table
            $cycleLength = DB::table('shift_pattern_cycle')
                ->where('pattern_id', $shiftPatternId)
                ->count();

            $daysFromStart = $date->diffInDays($startShift);
            $dayOfWeek = $date->dayOfWeekIso;

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
                    // Fetch the early_time and latest_time from the shifts table
                    $shiftTimes = DB::table('shift')
                        ->where('id', $shiftId)
                        ->select('early_time', 'latest_time', 'start_time', 'status', 'end_time')
                        ->first();

                    $morning_start = strtotime($shiftTimes->early_time);
                    $afternoon_start = strtotime($shiftTimes->latest_time);
                    $work_start = strtotime($shiftTimes->start_time);
                    $end_time = strtotime($shiftTimes->end_time);
                } else {
                    // Default values if no shift is found
                    $morning_start = strtotime('05:00:00');
                    $afternoon_start = strtotime('13:00:00');
                    $work_start = strtotime('08:00:00');
                    $end_time = strtotime('17:00:00');
                }
            } else {
                // Default values if no shift pattern is found
                $morning_start = strtotime('06:00:00');
                $afternoon_start = strtotime('13:00:00');
                $work_start = strtotime('08:00:00');
                $end_time = strtotime('17:00:00');
            }

            // ... (the rest of the code remains unchanged)
            $details = [];

            if (!$hasPresensi && !$isIzin) {
                // No presensi and no izin for this date
                $notifications[] = [
                    'tanggal' => $date,
                    'details' => [
                        [
                            'status' => 'Tidak Masuk Kerja',
                            'status_class' => 'text-warning',
                            'jam_masuk' => 'No Data',
                            'jam_pulang' => 'No Data'
                        ]
                    ]
                ];
            } else if ($hasPresensi) {
                // Find presensi data for the date
                $presensiData = $historibulanini->firstWhere('tanggal', $dateString);
                $jam_masuk_time = strtotime($presensiData->jam_masuk);
                $jam_pulang_time = strtotime($presensiData->jam_pulang);
                $lateness_threshold = $work_start;

                if ($jam_masuk_time < $morning_start) {
                    $prev_date = Carbon::parse($presensiData->tanggal)->subDay()->toDateString();
                    $presensiData->tanggal = $prev_date; // Adjust the date for early in time
                }

                if ($jam_pulang_time < $afternoon_start) {
                    $presensiData->jam_pulang = null; // If jam_pulang is before 1 PM, it should be null
                }

                if ($isIzin) {
                    $status = $isIzin->status;
                    $keputusan = $isIzin->keputusan;

                    if ($status == 'Dt' && $keputusan == 'Terlambat') {
                        // Skip lateness notification if there's a valid Dt Terlambat izin
                        continue;
                    }

                    if ($status == 'Pa' && $keputusan == 'Pulang Awal') {
                        // Skip early leave notification if there's a valid Pa Pulang Awal izin
                        continue;
                    }

                    if ($status == 'Tam' && is_null($presensiData->jam_masuk)) {
                        // Skip no scan in notification if there's a valid Tam izin
                        $details[] = [
                            'status' => "Izin Tidak Absen Masuk",
                            'status_class' => "text-info",
                            'jam_masuk' => 'No Data',
                            'jam_pulang' => $presensiData->jam_pulang ? $presensiData->jam_pulang : "No Data"
                        ];
                        continue;
                    }

                    if ($status == 'Tap' && is_null($presensiData->jam_pulang)) {
                        // Skip no scan out notification if there's a valid Tap izin
                        $details[] = [
                            'status' => "Izin Tidak Absen Pulang",
                            'status_class' => "text-info",
                            'jam_masuk' => $presensiData->jam_masuk ? $presensiData->jam_masuk : 'No Data',
                            'jam_pulang' => 'No Data'
                        ];
                        continue;
                    }
                } else {
                    if ($jam_masuk_time >= $lateness_threshold) {
                        $details[] = [
                            'status' => "Terlambat",
                            'status_class' => "text-danger",
                            'jam_masuk' => $presensiData->jam_masuk,
                            'jam_pulang' => $presensiData->jam_pulang ? $presensiData->jam_pulang : "No Data"
                        ];
                    }

                    if (is_null($presensiData->jam_masuk)) {
                        $details[] = [
                            'status' => 'Tidak Absen Masuk',
                            'status_class' => 'text-warning',
                            'jam_masuk' => 'No Data',
                            'jam_pulang' => $presensiData->jam_pulang
                        ];
                    }

                    if (is_null($presensiData->jam_pulang)) {
                        $details[] = [
                            'status' => 'Tidak Absen Pulang',
                            'status_class' => 'text-warning',
                            'jam_masuk' => $presensiData->jam_masuk ? $presensiData->jam_masuk : 'No Data',
                            'jam_pulang' => 'No Data'
                        ];
                    }

                    if ($presensiData->jam_pulang && $jam_pulang_time < $end_time) {
                        $details[] = [
                            'status' => 'Pulang Awal',
                            'status_class' => 'text-warning',
                            'jam_masuk' => $presensiData->jam_masuk ? $presensiData->jam_masuk : 'No Data',
                            'jam_pulang' => $presensiData->jam_pulang
                        ];
                    }
                }

                if (count($details) > 0) {
                    $notifications[] = [
                        'tanggal' => $presensiData->tanggal,
                        'details' => $details
                    ];
                }
            } else if ($isIzin) {
                // Handle cases where there is no presensi data but there is izin
                $status = $isIzin->status;
                $keputusan = $isIzin->keputusan;

                if ($status == 'Tam') {
                    // Tam izin with no presensi data
                    $notifications[] = [
                        'tanggal' => $date,
                        'details' => [
                            [
                                'status' => 'Tidak Absen Pulang',
                                'status_class' => 'text-warning',
                                'jam_masuk' => 'No Data',
                                'jam_pulang' => 'No Data'
                            ]
                        ]
                    ];
                } elseif ($status == 'Tap') {
                    // Tap izin with no presensi data
                    $notifications[] = [
                        'tanggal' => $date,
                        'details' => [
                            [
                                'status' => 'Tidak Absen Masuk',
                                'status_class' => 'text-warning',
                                'jam_masuk' => 'No Data',
                                'jam_pulang' => 'No Data'
                            ]
                        ]
                    ];
                }
            }
        }

        $totalNotif = count($notifications);

        return $totalNotif;
    }


    private function checkIzin($izin, $nip, $date)
    {
        foreach ($izin as $item) {
            if ($item->nip == $nip && $date->between(Carbon::parse($item->tgl_izin), Carbon::parse($item->tgl_izin_akhir))) {
                return $item;
            }
        }
        return false;
    }

    // =============================================== DASHBOARD ADMIN CONTROLLER =============================================== //
    // =============================================== DASHBOARD ADMIN CONTROLLER =============================================== //
    // =============================================== DASHBOARD ADMIN CONTROLLER =============================================== //

    public function dashboardadmin()
    {
        $user = Auth::guard('user')->user();
        $role = $user->level;


        if ($role === 'Admin' || $role === 'Management') {
            $hariini = date("Y-m-d");
            $bulanini = date("m");
            $tahunini = date("Y");

            $rekappresensi = DB::connection('mysql2') // Assuming db_absen is on mysql2
                ->table('db_absen.att_log as presensi')
                ->selectRaw('COUNT(presensi.pin) as jmlhadir, SUM(IF(TIME(presensi.scan_date) > "08:00:00", 1, 0)) as jmlterlambat')
                ->join('hrmschl.karyawan', 'presensi.pin', '=', 'karyawan.nip')
                ->whereDate(DB::raw('DATE(presensi.scan_date)'), $hariini)
                ->first();


            $rekapizin = DB::table('pengajuan_izin')
                ->selectRaw('COUNT(*) as jmlizin')
                ->where('status_approved', 1)
                ->where('status_approved_hrd', 1)
                ->where(function ($query) use ($hariini) {
                    $query->where('tgl_izin', '<=', $hariini)
                        ->where(function ($subQuery) use ($hariini) {
                            $subQuery->where('tgl_izin_akhir', '>=', $hariini)
                                ->orWhereNull('tgl_izin_akhir')
                                ->orWhere('tgl_izin_akhir', ''); // Handle empty string case
                        });
                })
                ->first();

            $rekapcuti = DB::table('pengajuan_cuti')
                ->selectRaw('COUNT(*) as jmlcuti')
                ->where('status_approved', 1)
                ->where('status_approved_hrd', 1)
                ->where(function ($query) use ($hariini) {
                    $query->where('tgl_cuti', '<=', $hariini)
                        ->where(function ($subQuery) use ($hariini) {
                            $subQuery->where('tgl_cuti_sampai', '>=', $hariini)
                                ->orWhereNull('tgl_cuti_sampai')
                                ->orWhere('tgl_cuti_sampai', ''); // Handle empty string case
                        });
                })
                ->first();

            $rekapkaryawan = DB::table('karyawan')
                ->selectRaw('COUNT(karyawan.nip) as jmlkar')
                ->where('status_kar', 'Aktif')
                ->first();

            $jmlnoatt = DB::table('karyawan')
                ->leftJoin('db_absen.att_log as presensi', function ($join) use ($hariini) {
                    $join->on('karyawan.nip', '=', 'presensi.pin')
                        ->whereDate(DB::raw('DATE(presensi.scan_date)'), '=', $hariini);
                })
                ->where('status_kar', 'Aktif')
                ->whereNull('presensi.pin')
                ->count();


            $historihariNonNS = DB::connection('mysql2')
                ->table('db_absen.att_log as presensi')
                ->join('hrmschl.karyawan', 'presensi.pin', '=', 'karyawan.nip') // Use 'pin' from 'att_log'
                ->join('hrmschl.jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->join('hrmschl.shift_pattern_cycle', function ($join) {
                    $join->on('shift_pattern_cycle.pattern_id', '=', 'karyawan.shift_pattern_id')
                        ->on(DB::raw('
                            CASE
                                WHEN DAYOFWEEK(DATE(presensi.scan_date)) = 1 THEN 7
                                ELSE DAYOFWEEK(DATE(presensi.scan_date)) - 1
                            END
                        '), '=', 'shift_pattern_cycle.cycle_day');
                })
                ->join('hrmschl.shift', 'shift_pattern_cycle.shift_id', '=', 'shift.id')
                ->leftJoin('hrmschl.libur_nasional', function ($join) use ($hariini) {
                    $join->on(DB::raw('DATE(presensi.scan_date)'), '=', 'libur_nasional.tgl_libur');
                })
                ->whereDate(DB::raw('DATE(presensi.scan_date)'), $hariini)
                ->where('karyawan.grade', '!=', 'NS')
                ->whereIn(DB::raw('(presensi.pin, presensi.scan_date)'), function ($query) use ($hariini) {
                    $query->select('pin', DB::raw('MIN(scan_date)'))
                        ->from('db_absen.att_log')
                        ->whereDate('scan_date', $hariini)
                        ->groupBy('pin');
                })
                ->select(
                    'presensi.*',
                    'karyawan.nama_lengkap',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan',
                    'shift.start_time',
                    DB::raw('DATE(presensi.scan_date) as tgl_presensi'),
                    DB::raw('TIME(presensi.scan_date) as jam_in'),
                    'libur_nasional.tgl_libur' // Include holiday date for validation
                )
                ->orderBy(DB::raw('TIME(presensi.scan_date)'), 'DESC') // Optional: sort by earliest time first
                ->get();


            // Get the list of karyawan who do not have attendance today
            $noAttendanceNonNS = DB::connection('mysql2')
                ->table('hrmschl.karyawan')
                ->leftJoin('db_absen.att_log as presensi', function ($join) use ($hariini) {
                    $join->on('karyawan.nip', '=', 'presensi.pin') // Use 'pin' from 'att_log'
                        ->whereDate(DB::raw('DATE(presensi.scan_date)'), $hariini);
                })
                ->join('hrmschl.jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->whereNull('presensi.pin') // Filter for karyawan with no attendance
                ->where('karyawan.grade', '!=', 'NS') // Exclude NS grade
                ->where('jabatan.kode_dept', '!=', 'Management')
                ->where('status_kar', 'Aktif')
                ->whereNotExists(function ($query) use ($hariini) {
                    $query->select(DB::raw(1))
                        ->from('hrmschl.pengajuan_izin')
                        ->whereColumn('pengajuan_izin.nip', 'hrmschl.karyawan.nip')
                        ->where('pengajuan_izin.tgl_izin', '<=', $hariini) // Start date is before or on today
                        ->where('pengajuan_izin.tgl_izin_akhir', '>=', $hariini) // End date is after or on today
                        ->whereNotNull('pengajuan_izin.tgl_izin_akhir') // Exclude null tgl_izin_akhir
                        ->where('pengajuan_izin.tgl_izin_akhir', '!=', ''); // Exclude empty tgl_izin_akhir
                })
                ->whereNotExists(function ($query) use ($hariini) {
                    $query->select(DB::raw(1))
                        ->from('hrmschl.pengajuan_cuti')
                        ->whereColumn('pengajuan_cuti.nip', 'hrmschl.karyawan.nip')
                        ->where('pengajuan_cuti.tgl_cuti', '<=', $hariini) // Start date is before or on today
                        ->where('pengajuan_cuti.tgl_cuti_sampai', '>=', $hariini) // End date is after or on today
                        ->whereNotNull('pengajuan_cuti.tgl_cuti_sampai') // Exclude null tgl_cuti_sampai
                        ->where('pengajuan_cuti.tgl_cuti_sampai', '!=', ''); // Exclude empty tgl_cuti_sampai
                })
                ->whereNotExists(function ($query) use ($hariini) {
                    $query->select(DB::raw(1))
                        ->from('hrmschl.libur_nasional')
                        ->whereDate('libur_nasional.tgl_libur', $hariini); // Exclude holidays
                })
                ->select(
                    'karyawan.*',
                    'jabatan.nama_jabatan',
                    DB::raw('CURRENT_DATE as tgl_presensi'), // Using the current date
                    DB::raw('\'00:00\' as jam_in') // Set jam_in to 00:00 for no attendance
                )
                ->get();

            $KarIzinNow = DB::table('pengajuan_izin')
                ->join('karyawan', 'pengajuan_izin.nip', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
                ->where('tgl_izin', '<=', $hariini) // Start date is before or on today
                ->where('tgl_izin_akhir', '>=', $hariini) // End date is after or on today
                ->whereNotNull('tgl_izin_akhir') // Exclude null tgl_izin_akhir
                ->where('tgl_izin_akhir', '!=', '') // Exclude empty tgl_izin_akhir
                ->where('karyawan.grade', '!=', 'NS') // Exclude grade NS
                ->select(
                    'karyawan.nama_lengkap',
                    'pengajuan_izin.keterangan',
                    'pengajuan_izin.tgl_izin',
                    'pengajuan_izin.tgl_izin_akhir',
                    'pengajuan_izin.status',
                    'pengajuan_izin.pukul',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan'
                )
                ->get();

            // Fetch employees who are on cuti today
            $KarCutiNow = DB::table('pengajuan_cuti')
                ->join('karyawan', 'pengajuan_cuti.nip', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
                ->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti') // Join with tipe_cuti table
                ->where(function ($query) use ($hariini) {
                    $query->where('tgl_cuti', '<=', $hariini)
                        ->where('tgl_cuti_sampai', '>=', $hariini);
                })
                ->where('karyawan.grade', '!=', 'NS')
                ->select('karyawan.nama_lengkap', 'pengajuan_cuti.note', 'pengajuan_cuti.tgl_cuti', 'pengajuan_cuti.tgl_cuti_sampai', 'pengajuan_cuti.jenis', 'jabatan.nama_jabatan', 'pengajuan_cuti.tipe', 'tipe_cuti.tipe_cuti', 'karyawan.kode_dept') // Select the necessary fields
                ->get();

            return view('dashboard.admin', compact(
                'rekapizin',
                'rekapcuti',
                'rekappresensi',
                'rekapkaryawan',
                'jmlnoatt',
                'historihariNonNS',
                'noAttendanceNonNS',
                'KarIzinNow',
                'KarCutiNow',
            ));
        } else {
            $hariini = date("Y-m-d");
            $bulanini = date("m");
            $tahunini = date("Y");

            $rekappresensi = DB::connection('mysql2') // Assuming db_absen is on mysql2
                ->table('db_absen.att_log as presensi')
                ->selectRaw('COUNT(presensi.pin) as jmlhadir, SUM(IF(TIME(presensi.scan_date) > "08:00:00", 1, 0)) as jmlterlambat')
                ->join('hrmschl.karyawan', 'presensi.pin', '=', 'karyawan.nip')
                ->whereDate(DB::raw('DATE(presensi.scan_date)'), $hariini)
                ->first();


            $rekapizin = DB::table('pengajuan_izin')
                ->selectRaw('COUNT(*) as jmlizin')
                ->where('status_approved', 1)
                ->where('status_approved_hrd', 1)
                ->where(function ($query) use ($hariini) {
                    $query->where('tgl_izin', '<=', $hariini)
                        ->where(function ($subQuery) use ($hariini) {
                            $subQuery->where('tgl_izin_akhir', '>=', $hariini)
                                ->orWhereNull('tgl_izin_akhir')
                                ->orWhere('tgl_izin_akhir', ''); // Handle empty string case
                        });
                })
                ->first();

            $rekapcuti = DB::table('pengajuan_cuti')
                ->selectRaw('COUNT(*) as jmlcuti')
                ->where('status_approved', 1)
                ->where('status_approved_hrd', 1)
                ->where(function ($query) use ($hariini) {
                    $query->where('tgl_cuti', '<=', $hariini)
                        ->where(function ($subQuery) use ($hariini) {
                            $subQuery->where('tgl_cuti_sampai', '>=', $hariini)
                                ->orWhereNull('tgl_cuti_sampai')
                                ->orWhere('tgl_cuti_sampai', ''); // Handle empty string case
                        });
                })
                ->first();

            $rekapkaryawan = DB::table('karyawan')
                ->selectRaw('COUNT(karyawan.nip) as jmlkar')
                ->where('status_kar', 'Aktif')
                ->first();

            $jmlnoatt = DB::table('karyawan')
                ->leftJoin('db_absen.att_log as presensi', function ($join) use ($hariini) {
                    $join->on('karyawan.nip', '=', 'presensi.pin')
                        ->whereDate(DB::raw('DATE(presensi.scan_date)'), '=', $hariini);
                })
                ->where('status_kar', 'Aktif')
                ->whereNull('presensi.pin')
                ->count();


            // Get historical attendance data for NS employees
            $historihariNS = DB::connection('mysql2')
                ->table('db_absen.att_log as presensi')
                ->join('hrmschl.karyawan', 'presensi.pin', '=', 'karyawan.nip') // Use 'pin' from 'att_log'
                ->join('hrmschl.jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->join('hrmschl.shift_pattern_cycle', function ($join) {
                    $join->on('shift_pattern_cycle.pattern_id', '=', 'karyawan.shift_pattern_id')
                        ->on(DB::raw('
                CASE
                    WHEN DAYOFWEEK(DATE(presensi.scan_date)) = 1 THEN 7
                    ELSE DAYOFWEEK(DATE(presensi.scan_date)) - 1
                END
            '), '=', 'shift_pattern_cycle.cycle_day');
                })
                ->join('hrmschl.shift', 'shift_pattern_cycle.shift_id', '=', 'shift.id')
                ->whereDate(DB::raw('DATE(presensi.scan_date)'), $hariini)
                ->where('karyawan.grade', 'NS')
                ->whereBetween(DB::raw('TIME(presensi.scan_date)'), [DB::raw('shift.early_time'), DB::raw('shift.latest_time')])
                ->orderBy(DB::raw('TIME(presensi.scan_date)'), 'desc')
                ->select(
                    'presensi.*',
                    'karyawan.nama_lengkap',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan',
                    'shift.start_time',
                    DB::raw('DATE(presensi.scan_date) as tgl_presensi'),
                    DB::raw('TIME(presensi.scan_date) as jam_in')
                )
                ->get();

            $noAttendanceNS = DB::connection('mysql2')
                ->table('hrmschl.karyawan')
                ->leftJoin('db_absen.att_log as presensi', function ($join) use ($hariini) {
                    $join->on('karyawan.nip', '=', 'presensi.pin') // Use 'pin' from 'att_log'
                        ->whereDate(DB::raw('DATE(presensi.scan_date)'), $hariini);
                })
                ->join('hrmschl.jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->whereNull('presensi.pin') // Filter for karyawan with no attendance
                ->where('karyawan.grade', 'NS') // Exclude NS grade
                ->where('jabatan.kode_dept', '!=', 'Management')
                ->select(
                    'karyawan.*',
                    'jabatan.nama_jabatan',
                    DB::raw('CURRENT_DATE as tgl_presensi'), // Using the current date
                    DB::raw('\'00:00\' as jam_in') // Set jam_in to 00:00 for no attendance
                )
                ->get();


            $historihariNonNS = DB::connection('mysql2')
                ->table('db_absen.att_log as presensi')
                ->join('hrmschl.karyawan', 'presensi.pin', '=', 'karyawan.nip') // Use 'pin' from 'att_log'
                ->join('hrmschl.jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->join('hrmschl.shift_pattern_cycle', function ($join) {
                    $join->on('shift_pattern_cycle.pattern_id', '=', 'karyawan.shift_pattern_id')
                        ->on(DB::raw('
                            CASE
                                WHEN DAYOFWEEK(DATE(presensi.scan_date)) = 1 THEN 7
                                ELSE DAYOFWEEK(DATE(presensi.scan_date)) - 1
                            END
                        '), '=', 'shift_pattern_cycle.cycle_day');
                })
                ->join('hrmschl.shift', 'shift_pattern_cycle.shift_id', '=', 'shift.id')
                ->leftJoin('hrmschl.libur_nasional', function ($join) use ($hariini) {
                    $join->on(DB::raw('DATE(presensi.scan_date)'), '=', 'libur_nasional.tgl_libur');
                })
                ->whereDate(DB::raw('DATE(presensi.scan_date)'), $hariini)
                ->where('karyawan.grade', '!=', 'NS')
                ->whereIn(DB::raw('(presensi.pin, presensi.scan_date)'), function ($query) use ($hariini) {
                    $query->select('pin', DB::raw('MIN(scan_date)'))
                        ->from('db_absen.att_log')
                        ->whereDate('scan_date', $hariini)
                        ->groupBy('pin');
                })
                ->select(
                    'presensi.*',
                    'karyawan.nama_lengkap',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan',
                    'shift.start_time',
                    DB::raw('DATE(presensi.scan_date) as tgl_presensi'),
                    DB::raw('TIME(presensi.scan_date) as jam_in'),
                    'libur_nasional.tgl_libur' // Include holiday date for validation
                )
                ->orderBy(DB::raw('TIME(presensi.scan_date)'), 'DESC') // Optional: sort by earliest time first
                ->get();


            // Get the list of karyawan who do not have attendance today
            $noAttendanceNonNS = DB::connection('mysql2')
                ->table('hrmschl.karyawan')
                ->leftJoin('db_absen.att_log as presensi', function ($join) use ($hariini) {
                    $join->on('karyawan.nip', '=', 'presensi.pin') // Use 'pin' from 'att_log'
                        ->whereDate(DB::raw('DATE(presensi.scan_date)'), $hariini);
                })
                ->join('hrmschl.jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->whereNull('presensi.pin') // Filter for karyawan with no attendance
                ->where('karyawan.grade', '!=', 'NS') // Exclude NS grade
                ->where('jabatan.kode_dept', '!=', 'Management')
                ->where('status_kar', 'Aktif')
                ->whereNotExists(function ($query) use ($hariini) {
                    $query->select(DB::raw(1))
                        ->from('hrmschl.pengajuan_izin')
                        ->whereColumn('pengajuan_izin.nip', 'hrmschl.karyawan.nip')
                        ->where('pengajuan_izin.tgl_izin', '<=', $hariini) // Start date is before or on today
                        ->where('pengajuan_izin.tgl_izin_akhir', '>=', $hariini) // End date is after or on today
                        ->whereNotNull('pengajuan_izin.tgl_izin_akhir') // Exclude null tgl_izin_akhir
                        ->where('pengajuan_izin.tgl_izin_akhir', '!=', ''); // Exclude empty tgl_izin_akhir
                })
                ->whereNotExists(function ($query) use ($hariini) {
                    $query->select(DB::raw(1))
                        ->from('hrmschl.pengajuan_cuti')
                        ->whereColumn('pengajuan_cuti.nip', 'hrmschl.karyawan.nip')
                        ->where('pengajuan_cuti.tgl_cuti', '<=', $hariini) // Start date is before or on today
                        ->where('pengajuan_cuti.tgl_cuti_sampai', '>=', $hariini) // End date is after or on today
                        ->whereNotNull('pengajuan_cuti.tgl_cuti_sampai') // Exclude null tgl_cuti_sampai
                        ->where('pengajuan_cuti.tgl_cuti_sampai', '!=', ''); // Exclude empty tgl_cuti_sampai
                })
                ->whereNotExists(function ($query) use ($hariini) {
                    $query->select(DB::raw(1))
                        ->from('hrmschl.libur_nasional')
                        ->whereDate('libur_nasional.tgl_libur', $hariini); // Exclude holidays
                })
                ->select(
                    'karyawan.*',
                    'jabatan.nama_jabatan',
                    DB::raw('CURRENT_DATE as tgl_presensi'), // Using the current date
                    DB::raw('\'00:00\' as jam_in') // Set jam_in to 00:00 for no attendance
                )
                ->get();



            // Subquery to get the earliest jam_in for each employee per day
            $subquery = DB::connection('mysql2')
                ->table('db_absen.att_log as presensi')
                ->select(
                    'presensi.pin',
                    DB::raw('DATE(presensi.scan_date) as tgl_presensi'),
                    DB::raw('TIME(MIN(presensi.scan_date)) as earliest_jam_in'),
                    'shift.start_time'
                )
                ->join('karyawan', 'presensi.pin', '=', 'karyawan.nip')
                ->join('shift_pattern_cycle', function ($join) {
                    $join->on('shift_pattern_cycle.pattern_id', '=', 'karyawan.shift_pattern_id')
                        ->on(DB::raw('
                        CASE
                            WHEN DAYOFWEEK(DATE(presensi.scan_date)) = 1 THEN 7
                            ELSE DAYOFWEEK(DATE(presensi.scan_date)) - 1
                        END
                    '), '=', 'shift_pattern_cycle.cycle_day');
                })
                ->join('shift', 'shift_pattern_cycle.shift_id', '=', 'shift.id')
                ->whereRaw('MONTH(presensi.scan_date) = ?', [$bulanini])
                ->whereRaw('YEAR(presensi.scan_date) = ?', [$tahunini])
                ->groupBy('presensi.pin', 'tgl_presensi', 'shift.start_time');



            // Leaderboard for lateness for NS employees
            // Leaderboard for lateness for NS employees
            $leaderboardTelatNS = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
                ->mergeBindings($subquery)
                ->join('karyawan', 'sub.pin', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->select(
                    'sub.pin',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan',
                    'karyawan.nama_lengkap',
                    DB::raw('SUM(
                CASE
                    WHEN sub.earliest_jam_in > sub.start_time
                    THEN TIMESTAMPDIFF(MINUTE, sub.start_time, sub.earliest_jam_in)
                    ELSE 0
                END
            ) as total_late_minutes')
                )
                ->where('karyawan.grade', 'NS')
                ->groupBy('sub.pin', 'karyawan.nama_lengkap', 'karyawan.kode_dept', 'jabatan.nama_jabatan')
                ->orderBy('total_late_minutes', 'desc')
                ->limit(10)
                ->get();

            // Leaderboard for lateness for Non-NS employees
            $leaderboardTelatNonNS = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
                ->mergeBindings($subquery)
                ->join('karyawan', 'sub.pin', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->select(
                    'sub.pin',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan',
                    'karyawan.nama_lengkap',
                    DB::raw('SUM(
                CASE
                    WHEN sub.earliest_jam_in > sub.start_time
                    THEN TIMESTAMPDIFF(MINUTE, sub.start_time, sub.earliest_jam_in)
                    ELSE 0
                END
            ) as total_late_minutes')
                )
                ->where('karyawan.grade', '!=', 'NS')
                ->groupBy('sub.pin', 'karyawan.nama_lengkap', 'karyawan.kode_dept', 'jabatan.nama_jabatan')
                ->orderBy('total_late_minutes', 'desc')
                ->limit(10)
                ->get();

            $subquery = DB::connection('mysql2')
                ->table('db_absen.att_log as presensi')
                ->select(
                    'presensi.pin as nip',
                    DB::raw('DATE(presensi.scan_date) as tgl_presensi'),
                    DB::raw('MIN(TIME(presensi.scan_date)) as earliest_jam_in'),
                    'shift.start_time',
                    DB::raw(
                        '
                    CASE
                        WHEN MIN(TIME(presensi.scan_date)) > shift.start_time THEN 1
                        ELSE 0
                    END as is_late'
                    )
                )
                ->join('karyawan', 'presensi.pin', '=', 'karyawan.nip')
                ->join('shift_pattern_cycle', function ($join) {
                    $join->on('shift_pattern_cycle.pattern_id', '=', 'karyawan.shift_pattern_id')
                        ->on(DB::raw('
                        CASE
                            WHEN DAYOFWEEK(DATE(presensi.scan_date)) = 1 THEN 7
                            ELSE DAYOFWEEK(DATE(presensi.scan_date)) - 1
                        END
                    '), '=', 'shift_pattern_cycle.cycle_day');
                })
                ->join('shift', 'shift_pattern_cycle.shift_id', '=', 'shift.id')
                ->whereRaw('MONTH(presensi.scan_date) = ?', [$bulanini])
                ->whereRaw('YEAR(presensi.scan_date) = ?', [$tahunini])
                ->groupBy('presensi.pin', 'tgl_presensi', 'shift.start_time');



            // Query for total lateness count for NS grade
            // Leaderboard for total lateness count for NS employees
            $totalLatenessNS = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
                ->mergeBindings($subquery)
                ->join('karyawan', 'sub.nip', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->select(
                    'karyawan.nama_lengkap',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan',
                    DB::raw('SUM(sub.is_late) as total_late_count')
                )
                ->where('karyawan.grade', 'NS')
                ->groupBy('karyawan.nama_lengkap', 'karyawan.kode_dept', 'jabatan.nama_jabatan')
                ->orderBy('total_late_count', 'desc')
                ->limit(10)
                ->get();

            // Query for total lateness count for Non-NS grade
            $totalLatenessNonNS = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
                ->mergeBindings($subquery)
                ->join('karyawan', 'sub.nip', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->select(
                    'karyawan.nama_lengkap',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan',
                    DB::raw('SUM(sub.is_late) as total_late_count')
                )
                ->where('karyawan.grade', '!=', 'NS')
                ->groupBy('karyawan.nama_lengkap', 'karyawan.kode_dept', 'jabatan.nama_jabatan')
                ->orderBy('total_late_count', 'desc')
                ->limit(10)
                ->get();



            // Query for NS grade on-time leaderboard
            $subquery = DB::connection('mysql2')
                ->table('db_absen.att_log as presensi')
                ->select(
                    'presensi.pin as nip',  // Using 'pin' for 'nip'
                    DB::raw('DATE(presensi.scan_date) as tgl_presensi'),  // Extracting date for tgl_presensi
                    DB::raw('MIN(TIME(presensi.scan_date)) as earliest_jam_in'),  // Extracting time for jam_in
                    'shift.start_time'
                )
                ->join('karyawan', 'presensi.pin', '=', 'karyawan.nip')  // Joining on pin to karyawan.nip
                ->join('shift_pattern_cycle', function ($join) {
                    $join->on('shift_pattern_cycle.pattern_id', '=', 'karyawan.shift_pattern_id')
                        ->on(DB::raw('
                        CASE
                            WHEN DAYOFWEEK(DATE(presensi.scan_date)) = 1 THEN 7
                            ELSE DAYOFWEEK(DATE(presensi.scan_date)) - 1
                        END
                    '), '=', 'shift_pattern_cycle.cycle_day');
                })
                ->join('shift', 'shift_pattern_cycle.shift_id', '=', 'shift.id')
                ->whereRaw('MONTH(presensi.scan_date) = ?', [$bulanini])  // Filtering by month using scan_date
                ->whereRaw('YEAR(presensi.scan_date) = ?', [$tahunini])  // Filtering by year using scan_date
                ->groupBy('presensi.pin', 'tgl_presensi', 'shift.start_time');  // Grouping by necessary fields


            // Leaderboard for on-time for NS employees
            $leaderboardOnTimeNS = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
                ->mergeBindings($subquery)
                ->join('karyawan', 'sub.nip', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->select(
                    'sub.nip',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan',
                    'karyawan.nama_lengkap',
                    DB::raw('SUM(
        CASE
            WHEN sub.earliest_jam_in <= sub.start_time
            THEN TIMESTAMPDIFF(MINUTE, sub.earliest_jam_in, sub.start_time)
            ELSE 0
        END
    ) as total_on_time')
                )
                ->where('karyawan.grade', 'NS')
                ->groupBy('sub.nip', 'karyawan.nama_lengkap', 'karyawan.kode_dept', 'jabatan.nama_jabatan')
                ->orderBy('total_on_time', 'desc')
                ->limit(10)
                ->get();

            // Leaderboard for on-time for Non-NS employees
            $leaderboardOnTimeNonNS = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
                ->mergeBindings($subquery)
                ->join('karyawan', 'sub.nip', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->select(
                    'sub.nip',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan',
                    'karyawan.nama_lengkap',
                    DB::raw('SUM(
        CASE
            WHEN sub.earliest_jam_in <= sub.start_time
            THEN TIMESTAMPDIFF(MINUTE, sub.earliest_jam_in, sub.start_time)
            ELSE 0
        END
    ) as total_on_time')
                )
                ->where('karyawan.grade', '!=', 'NS')
                ->groupBy('sub.nip', 'karyawan.nama_lengkap', 'karyawan.kode_dept', 'jabatan.nama_jabatan')
                ->orderBy('total_on_time', 'desc')
                ->limit(10)
                ->get();


            $subquery = DB::connection('mysql2')
                ->table('db_absen.att_log as presensi')
                ->select(
                    'presensi.pin as nip',
                    DB::raw('DATE(presensi.scan_date) as tgl_presensi'),
                    DB::raw('MIN(TIME(presensi.scan_date)) as earliest_jam_in'),
                    'shift.start_time',
                    DB::raw(
                        '
                    CASE
                        WHEN MIN(TIME(presensi.scan_date)) < shift.start_time THEN 1
                        ELSE 0
                    END as is_ontime'
                    )
                )
                ->join('karyawan', 'presensi.pin', '=', 'karyawan.nip')
                ->join('shift_pattern_cycle', function ($join) {
                    $join->on('shift_pattern_cycle.pattern_id', '=', 'karyawan.shift_pattern_id')
                        ->on(DB::raw('
                        CASE
                            WHEN DAYOFWEEK(DATE(presensi.scan_date)) = 1 THEN 7
                            ELSE DAYOFWEEK(DATE(presensi.scan_date)) - 1
                        END
                    '), '=', 'shift_pattern_cycle.cycle_day');
                })
                ->join('shift', 'shift_pattern_cycle.shift_id', '=', 'shift.id')
                ->whereRaw('MONTH(presensi.scan_date) = ?', [$bulanini])
                ->whereRaw('YEAR(presensi.scan_date) = ?', [$tahunini])
                ->groupBy('presensi.pin', 'tgl_presensi', 'shift.start_time');



            // Query for total lateness count for NS grade
            // Leaderboard for total lateness count for NS employees
            $totalOnTimeNS = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
                ->mergeBindings($subquery)
                ->join('karyawan', 'sub.nip', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->select(
                    'karyawan.nama_lengkap',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan',
                    DB::raw('SUM(sub.is_ontime) as total_on_time')
                )
                ->where('karyawan.grade', 'NS')
                ->where('jabatan.kode_dept', '!=', 'Management')
                ->groupBy('karyawan.nama_lengkap', 'karyawan.kode_dept', 'jabatan.nama_jabatan')
                ->orderBy('total_on_time', 'desc')
                ->limit(10)
                ->get();

            // Query for total lateness count for Non-NS grade
            $totalOnTimeNonNS = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
                ->mergeBindings($subquery)
                ->join('karyawan', 'sub.nip', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
                ->select(
                    'karyawan.nama_lengkap',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan',
                    DB::raw('SUM(sub.is_ontime) as total_on_time')
                )
                ->where('karyawan.grade', '!=', 'NS')
                ->where('jabatan.kode_dept', '!=', 'Management')
                ->groupBy('karyawan.nama_lengkap', 'karyawan.kode_dept', 'jabatan.nama_jabatan')
                ->orderBy('total_on_time', 'desc')
                ->limit(10)
                ->get();


            $KarIzinNow = DB::table('pengajuan_izin')
                ->join('karyawan', 'pengajuan_izin.nip', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
                ->where('tgl_izin', '<=', $hariini) // Start date is before or on today
                ->where('tgl_izin_akhir', '>=', $hariini) // End date is after or on today
                ->whereNotNull('tgl_izin_akhir') // Exclude null tgl_izin_akhir
                ->where('tgl_izin_akhir', '!=', '') // Exclude empty tgl_izin_akhir
                ->where('karyawan.grade', '!=', 'NS') // Exclude grade NS
                ->select(
                    'karyawan.nama_lengkap',
                    'pengajuan_izin.keterangan',
                    'pengajuan_izin.tgl_izin',
                    'pengajuan_izin.tgl_izin_akhir',
                    'pengajuan_izin.status',
                    'pengajuan_izin.pukul',
                    'karyawan.kode_dept',
                    'jabatan.nama_jabatan'
                )
                ->get();



            // Fetch employees who are on cuti today
            $KarCutiNow = DB::table('pengajuan_cuti')
                ->join('karyawan', 'pengajuan_cuti.nip', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
                ->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti') // Join with tipe_cuti table
                ->where(function ($query) use ($hariini) {
                    $query->where('tgl_cuti', '<=', $hariini)
                        ->where('tgl_cuti_sampai', '>=', $hariini);
                })
                ->where('karyawan.grade', '!=', 'NS')
                ->select('karyawan.nama_lengkap', 'pengajuan_cuti.note', 'pengajuan_cuti.tgl_cuti', 'pengajuan_cuti.tgl_cuti_sampai', 'pengajuan_cuti.jenis', 'jabatan.nama_jabatan', 'pengajuan_cuti.tipe', 'tipe_cuti.tipe_cuti', 'karyawan.kode_dept') // Select the necessary fields
                ->get();

            $KarIzinNowNS = DB::table('pengajuan_izin')
                ->join('karyawan', 'pengajuan_izin.nip', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
                ->where(function ($query) use ($hariini) {
                    $query->where('tgl_izin', '<=', $hariini)
                        ->where(function ($subQuery) use ($hariini) {
                            $subQuery->where('tgl_izin_akhir', '>=', $hariini)
                                ->orWhereNull('tgl_izin_akhir')
                                ->orWhere('tgl_izin_akhir', ''); // Handle empty string case
                        });
                })
                ->where('karyawan.grade', 'NS')
                ->select('karyawan.nama_lengkap', 'pengajuan_izin.tgl_izin', 'pengajuan_izin.tgl_izin_akhir', 'pengajuan_izin.status', 'pengajuan_izin.pukul', 'karyawan.kode_dept', 'jabatan.nama_jabatan')
                ->get();


            // Fetch employees who are on cuti today
            $KarCutiNowNS = DB::table('pengajuan_cuti')
                ->join('karyawan', 'pengajuan_cuti.nip', '=', 'karyawan.nip')
                ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
                ->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti') // Join with tipe_cuti table
                ->where(function ($query) use ($hariini) {
                    $query->where('tgl_cuti', '<=', $hariini)
                        ->where('tgl_cuti_sampai', '>=', $hariini);
                })
                ->where('karyawan.grade', 'NS')
                ->select('karyawan.nama_lengkap', 'pengajuan_cuti.tgl_cuti', 'pengajuan_cuti.tgl_cuti_sampai', 'pengajuan_cuti.jenis', 'jabatan.nama_jabatan', 'pengajuan_cuti.tipe', 'tipe_cuti.tipe_cuti', 'karyawan.kode_dept') // Select the necessary fields
                ->get();

            return view('dashboard.dashboardadmin', compact(
                'rekapizin',
                'rekapcuti',
                'rekappresensi',
                'rekapkaryawan',
                'jmlnoatt',
                'historihariNS',
                'noAttendanceNS',
                'historihariNonNS',
                'noAttendanceNonNS',
                'leaderboardTelatNS',
                'leaderboardTelatNonNS',
                'totalOnTimeNS',
                'totalOnTimeNonNS',
                'KarIzinNow',
                'KarCutiNow',
                'KarIzinNowNS',
                'KarCutiNowNS',
                'totalLatenessNS',
                'totalLatenessNonNS',
                'leaderboardOnTimeNS',
                'leaderboardOnTimeNonNS'
            ));
        }
    }



    public function dashboardcandidate()
    {
        $candidateId = Auth::guard('candidate')->user()->id;

        // Retrieve candidate information
        $candidate = DB::table('candidates')
            ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id')
            ->join('hiring_stages', 'candidates.current_stage_id', '=', 'hiring_stages.id')
            ->select(
                'candidates.*',
                'job_openings.title as job_title',
                'job_openings.recruitment_type_id',
                'hiring_stages.name as current_stage_name',
                'hiring_stages.id as current_stage_id'
            )
            ->where('candidates.id', $candidateId)
            ->first();

        // Retrieve interviews related to this candidate
        $interview = DB::table('interviews')
            ->join('candidates', 'interviews.candidate_id', '=', 'candidates.id')
            ->join('hiring_stages', 'interviews.stage_id', '=', 'hiring_stages.id')
            ->leftJoin('candidate_data', 'candidates.id', '=', 'candidate_data.candidate_id')
            ->select(
                'interviews.*',
                'candidates.nama_candidate as candidate_name',
                'hiring_stages.name as stage_name',
                'candidate_data.status_form'
            )
            ->where('interviews.candidate_id', $candidateId)
            ->get();

        // Retrieve all stages related to this candidate's recruitment type
        $stages = DB::table('hiring_stages')
            ->where('recruitment_type_id', $candidate->recruitment_type_id)
            ->orderBy('sequence', 'asc')
            ->get();

        // Retrieve candidate data and handle null case
        $candidateData = DB::table('candidate_data')->where('candidate_id', $candidateId)->first();
        $statusForm = $candidateData ? $candidateData->status_form : null; // Check if $candidateData is null

        return view('dashboard.dashboardcandidate', compact('candidate', 'stages', 'statusForm', 'interview', 'candidateData'));
    }


    public function accountSetting()
    {
        $user = Auth::guard('user')->user();
        $nik = $user->nik;

        $karyawan = DB::table('karyawan')
            ->select('karyawan.nama_lengkap', 'department.nama_dept as department_name', 'jabatan.nama_jabatan as jabatan_name', 'karyawan.nik', 'karyawan.nip', 'karyawan.email')
            ->leftJoin('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->where('karyawan.nik', $nik)
            ->first();

        return view('konfigurasi.setting', compact('karyawan'));
    }




    public function updatePassword(Request $request)
    {

        $user = Auth::guard('user')->user();
        $nik = $user->nik;
        $user = User::where('nik', $nik)->firstOrFail();

        // Check if a new password is provided and if it matches the confirmation
        if ($request->filled('password')) {
            if ($request->input('password') === $request->input('password_confirmation')) {
                // Hash the new password and update
                $user->password = Hash::make($request->input('password'));
            } else {
                return Redirect::back()->with(['danger' => 'Password confirmation does not match the new password.']);
            }
        }

        try {
            $user->save();
            return Redirect::back()->with(['success' => 'Profile Berhasil Di Update']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['danger' => 'Profile Gagal Di Update']);
        }
    }
}
