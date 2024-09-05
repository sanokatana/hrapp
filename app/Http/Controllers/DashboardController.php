<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            ->orderBy('sub.tanggal', 'asc')
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
            $isIzin = $this->checkIzin($izin, $nip, $date);
            $item->jam_kerja = $work_start;
            $jam_masuk_time = strtotime($item->jam_masuk);
            $jam_pulang_time = strtotime($item->jam_pulang);

            if ($jam_masuk_time < $morning_start) {
                $prev_date = Carbon::parse($item->tanggal)->subDay()->toDateString();
                $item->tanggal = $prev_date; // Adjust the date for early in time
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
        $hariini = date("Y-m-d");
        $bulanini = date("m");
        $tahunini = date("Y");

        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(presensi.nip) as jmlhadir, SUM(IF(presensi.jam_in > "08:00:00",1,0)) as jmlterlambat')
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->where('presensi.tgl_presensi', $hariini)
            ->first();

        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('COUNT(*) as jmlizin')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->where(function ($query) use ($hariini) {
                $query->where('tgl_izin', '<=', $hariini)
                    ->where('tgl_izin_akhir', '>=', $hariini);
            })
            ->first();

        $rekapcuti = DB::table('pengajuan_cuti')
            ->selectRaw('COUNT(*) as jmlcuti')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->where(function ($query) use ($hariini) {
                $query->where('tgl_cuti', '<=', $hariini)
                    ->where('tgl_cuti_sampai', '>=', $hariini);
            })
            ->first();

        $rekapkaryawan = DB::table('karyawan')
            ->selectRaw('COUNT(karyawan.nip) as jmlkar')
            ->where('status_kar', 'Aktif')
            ->first();

        $jmlnoatt = DB::table('karyawan')
            ->leftJoin('presensi', function ($join) use ($hariini) {
                $join->on('karyawan.nip', '=', 'presensi.nip')
                    ->where('presensi.tgl_presensi', '=', $hariini);
            })
            ->where('status_kar', 'Aktif')
            ->whereNull('presensi.nip')
            ->count();

        // Get historical attendance data for NS and non-NS employees
        $historihariNS = DB::table('presensi')
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
            ->whereDate('presensi.tgl_presensi', $hariini)
            ->whereBetween('presensi.jam_in', ['05:00:00', '13:00:00'])
            ->where('karyawan.grade', 'NS')
            ->orderBy('presensi.jam_in', 'desc')
            ->select('presensi.*', 'karyawan.nama_lengkap', 'karyawan.kode_dept' , 'jabatan.nama_jabatan')
            ->get();

        $historihariNonNS = DB::table('presensi')
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
            ->join(
                DB::raw('(SELECT nip, MIN(jam_in) as min_jam_in
                                FROM presensi
                                WHERE DATE(tgl_presensi) = "' . $hariini . '"
                                AND jam_in BETWEEN "05:00:00" AND "13:00:00"
                                GROUP BY nip) as min_presensi'),
                'presensi.nip',
                '=',
                'min_presensi.nip'
            )
            ->where('presensi.jam_in', '=', DB::raw('min_presensi.min_jam_in'))
            ->whereDate('presensi.tgl_presensi', $hariini)
            ->whereBetween('presensi.jam_in', ['05:00:00', '13:00:00'])
            ->where('karyawan.grade', '!=', 'NS')
            ->orderBy('presensi.jam_in', 'desc')
            ->select('presensi.*', 'karyawan.nama_lengkap', 'karyawan.kode_dept' , 'jabatan.nama_jabatan')
            ->get();

        // Get the leaderboard for lateness for NS and non-NS employees
        $subquery = DB::table('presensi')
            ->select('nip', 'tgl_presensi', DB::raw('MIN(jam_in) as earliest_jam_in'))
            ->whereBetween(DB::raw('TIME(jam_in)'), ['06:00:00', '13:00:00'])
            ->whereRaw('MONTH(tgl_presensi) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_presensi) = ?', [$tahunini])
            ->groupBy('nip', 'tgl_presensi');

        $leaderboardTelatNS = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
            ->mergeBindings($subquery)
            ->join('karyawan', 'sub.nip', '=', 'karyawan.nip')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
            ->where('karyawan.grade', 'NS')
            ->select('sub.nip', 'karyawan.kode_dept' , 'jabatan.nama_jabatan', 'karyawan.nama_lengkap', DB::raw('SUM(CASE WHEN sub.earliest_jam_in > "08:00:00" THEN (HOUR(sub.earliest_jam_in) * 60 + MINUTE(sub.earliest_jam_in)) - (8 * 60) ELSE 0 END) as total_late_minutes'))
            ->groupBy('sub.nip', 'karyawan.nama_lengkap' , 'karyawan.kode_dept' , 'jabatan.nama_jabatan')
            ->orderBy('total_late_minutes', 'desc')
            ->limit(10)
            ->get();

        $leaderboardTelatNonNS = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
            ->mergeBindings($subquery)
            ->join('karyawan', 'sub.nip', '=', 'karyawan.nip')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
            ->where('karyawan.grade', '!=', 'NS')
            ->select('sub.nip', 'karyawan.kode_dept' , 'jabatan.nama_jabatan', 'karyawan.nama_lengkap', DB::raw('SUM(CASE WHEN sub.earliest_jam_in > "08:00:00" THEN (HOUR(sub.earliest_jam_in) * 60 + MINUTE(sub.earliest_jam_in)) - (8 * 60) ELSE 0 END) as total_late_minutes'))
            ->groupBy('sub.nip', 'karyawan.nama_lengkap', 'karyawan.kode_dept' , 'jabatan.nama_jabatan')
            ->orderBy('total_late_minutes', 'desc')
            ->limit(10)
            ->get();


        // Total lateness count for NS and non-NS employees
        $totalLatenessNS = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
            ->mergeBindings($subquery)
            ->join('karyawan', 'sub.nip', '=', 'karyawan.nip')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
            ->where('karyawan.grade', 'NS')
            ->where('sub.earliest_jam_in', '>', '08:00:00')  // Only count entries where they
            ->select('karyawan.nama_lengkap', 'karyawan.kode_dept' , 'jabatan.nama_jabatan', DB::raw('COUNT(sub.nip) as total_late_count'))
            ->groupBy('sub.nip', 'karyawan.nama_lengkap', 'karyawan.kode_dept' , 'jabatan.nama_jabatan')
            ->orderBy('total_late_count', 'desc')
            ->limit(10)
            ->get();

        $totalLatenessNonNS = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
            ->mergeBindings($subquery)
            ->join('karyawan','sub.nip', '=', 'karyawan.nip')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
            ->where('karyawan.grade', '!=', 'NS')
            ->where('sub.earliest_jam_in', '>', '08:00:00')  // Only count entries where they
            ->select('karyawan.nama_lengkap', 'karyawan.kode_dept' , 'jabatan.nama_jabatan', DB::raw('COUNT(sub.nip) as total_late_count'))
            ->groupBy('sub.nip', 'karyawan.nama_lengkap', 'karyawan.kode_dept' , 'jabatan.nama_jabatan')
            ->orderBy('total_late_count', 'desc')
            ->limit(10)
            ->get();


        // Leaderboard for on-time performance for NS and non-NS employees
        $leaderboardOnTimeNS = DB::table('presensi')
            ->select('presensi.nip', 'karyawan.kode_dept' , 'jabatan.nama_jabatan', 'karyawan.nama_lengkap', DB::raw('SUM(CASE WHEN presensi.jam_in < "08:00:00" THEN (8 * 60) - (HOUR(presensi.jam_in) * 60 + MINUTE(presensi.jam_in)) ELSE 0 END) as total_on_time'))
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
            ->whereRaw('MONTH(presensi.tgl_presensi) = ?', [$bulanini])
            ->whereRaw('YEAR(presensi.tgl_presensi) = ?', [$tahunini])
            ->where('karyawan.grade', 'NS')
            ->where('presensi.jam_in', '<', '08:00:00')
            ->groupBy('presensi.nip', 'karyawan.nama_lengkap', 'karyawan.kode_dept' , 'jabatan.nama_jabatan')
            ->orderBy('total_on_time', 'desc')
            ->limit(10)
            ->get();

        $leaderboardOnTimeNonNS = DB::table('presensi')
            ->select('presensi.nip','karyawan.kode_dept' , 'jabatan.nama_jabatan', 'karyawan.nama_lengkap', DB::raw('SUM(CASE WHEN presensi.jam_in < "08:00:00" THEN (8 * 60) - (HOUR(presensi.jam_in) * 60 + MINUTE(presensi.jam_in)) ELSE 0 END) as total_on_time'))
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
            ->whereRaw('MONTH(presensi.tgl_presensi) = ?', [$bulanini])
            ->whereRaw('YEAR(presensi.tgl_presensi) = ?', [$tahunini])
            ->where('karyawan.grade', '!=', 'NS')
            ->where('presensi.jam_in', '<', '08:00:00')
            ->groupBy('presensi.nip', 'karyawan.nama_lengkap', 'karyawan.kode_dept' , 'jabatan.nama_jabatan')
            ->orderBy('total_on_time', 'desc')
            ->limit(10)
            ->get();

        $KarIzinNow = DB::table('pengajuan_izin')
            ->join('karyawan', 'pengajuan_izin.nip', '=', 'karyawan.nip')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
            ->where(function ($query) use ($hariini) {
                $query->where('tgl_izin', '<=', $hariini)
                    ->where('tgl_izin_akhir', '>=', $hariini);
            })
            ->where('karyawan.grade', '!=', 'NS')
            ->select('karyawan.nama_lengkap', 'pengajuan_izin.tgl_izin', 'pengajuan_izin.tgl_izin_akhir', 'pengajuan_izin.status', 'pengajuan_izin.pukul', 'karyawan.kode_dept' , 'jabatan.nama_jabatan')
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
            ->select('karyawan.nama_lengkap', 'pengajuan_cuti.tgl_cuti', 'pengajuan_cuti.tgl_cuti_sampai', 'pengajuan_cuti.jenis', 'jabatan.nama_jabatan', 'pengajuan_cuti.tipe', 'tipe_cuti.tipe_cuti', 'karyawan.kode_dept') // Select the necessary fields
            ->get();

        $KarIzinNowNS = DB::table('pengajuan_izin')
        ->join('karyawan', 'pengajuan_izin.nip', '=', 'karyawan.nip')
        ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with jabatan table
        ->where(function ($query) use ($hariini) {
            $query->where('tgl_izin', '<=', $hariini)
                ->where('tgl_izin_akhir', '>=', $hariini);
        })
        ->where('karyawan.grade', 'NS')
        ->select('karyawan.nama_lengkap', 'pengajuan_izin.tgl_izin', 'pengajuan_izin.tgl_izin_akhir', 'pengajuan_izin.status', 'pengajuan_izin.pukul', 'karyawan.kode_dept' , 'jabatan.nama_jabatan')
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
            'historihariNonNS',
            'leaderboardTelatNS',
            'leaderboardTelatNonNS',
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
