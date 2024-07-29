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
        $nik = Auth::guard('karyawan')->user()->nik;

        // Join karyawan and jabatan tables to get nama_jabatan
        $namaUser = DB::table('karyawan')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->where('karyawan.nik', $nik)
            ->select('karyawan.*', 'jabatan.nama_jabatan')
            ->first();

        // Split nama_lengkap into first and last names
        $nameParts = explode(' ', $namaUser->nama_lengkap);
        $firstName = $nameParts[0];
        $lastName = end($nameParts);
        $namaUser->first_name = $firstName;
        $namaUser->last_name = $lastName;

        $presensihariini = DB::table('presensi')->where('nik', $nik)
            ->where('tgl_presensi', $hariini)
            ->first();

        // Fetch approved izin data for the current month
        $izin = DB::table('pengajuan_izin')
            ->select('nik', 'tgl_izin', 'tgl_izin_akhir', 'status', 'keputusan', 'pukul')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->whereRaw('MONTH(tgl_izin) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_izin) = ?', [$tahunini])
            ->get();

        $historibulanini = DB::table(DB::raw("(SELECT
        DATE(tgl_presensi) as tanggal,
        MIN(jam_in) as jam_masuk,
        MAX(jam_in) as jam_pulang,
        nik
        FROM presensi
        WHERE nik = ?
            AND MONTH(tgl_presensi) = ?
            AND YEAR(tgl_presensi) = ?
        GROUP BY DATE(tgl_presensi), nik) as sub"))
            ->leftJoin('presensi as p', function ($join) {
                $join->on('sub.tanggal', '=', DB::raw('DATE(p.tgl_presensi)'))
                    ->on('sub.nik', '=', 'p.nik')
                    ->whereRaw('p.jam_in = sub.jam_masuk OR p.jam_in = sub.jam_pulang');
            })
            ->select('sub.tanggal', 'sub.jam_masuk', 'sub.jam_pulang', DB::raw('MAX(p.foto_in) as foto_in'), DB::raw('MAX(p.foto_out) as foto_out'))
            ->groupBy('sub.tanggal', 'sub.jam_masuk', 'sub.jam_pulang')
            ->orderBy('sub.tanggal', 'asc')
            ->setBindings([$nik, $bulanini, $tahunini])
            ->get();

        // Process the presensi data to adjust for izin
        $processedHistoribulanini = $historibulanini->map(function ($item) use ($izin, $nik) {
            $date = Carbon::parse($item->tanggal);
            $isIzin = $this->checkIzin($izin, $nik, $date);

            $morning_start = strtotime('06:00:00');
            $afternoon_start = strtotime('13:00:00');
            $jam_masuk_time = strtotime($item->jam_masuk);
            $jam_pulang_time = strtotime($item->jam_pulang);

            if ($jam_masuk_time < $morning_start) {
                $prev_date = Carbon::parse($item->tanggal)->subDay()->toDateString();
                $item->tanggal = $prev_date; // Adjust the date for early in time
            }

            if ($jam_pulang_time > strtotime('18:00:00')) {
                $item->jam_pulang = '18:00:00'; // Cap the jam_pulang if it's after 6 PM
            } elseif ($jam_pulang_time < $afternoon_start) {
                $item->jam_pulang = null; // If jam_pulang is before 1 PM, it should be null
            }

            if ($isIzin) {
                $status = $isIzin->status;
                $keputusan = $isIzin->keputusan;

                if ($status == 'Dt' && $keputusan == 'Terlambat') {
                    $item->jam_masuk = '08:00:00';
                }

                if ($status == 'Pa' && $keputusan == 'Pulang Awal') {
                    $item->jam_pulang = '17:00:00';
                }

                if ($status == 'Tam' && !$item->jam_masuk) {
                    $item->jam_masuk = '08:00:00';
                }

                if ($status == 'Tap' && !$item->jam_pulang) {
                    $item->jam_pulang = '17:00:00';
                }
            }

            return $item;
        });

        // Calculate lateness based on adjusted jam_masuk times
        $rekappresensi = $processedHistoribulanini->reduce(function ($carry, $item) {
            $lateness_threshold = strtotime('08:01:00');
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
            ->where('nik', $nik)
            ->orderBy('tgl_izin')
            ->get();

        $historicuti = DB::table('pengajuan_cuti')
            ->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti')
            ->whereRaw('MONTH(pengajuan_cuti.tgl_cuti) = ?', [$bulanini])
            ->whereRaw('YEAR(pengajuan_cuti.tgl_cuti) = ?', [$tahunini])
            ->where('pengajuan_cuti.nik', $nik)
            ->select('pengajuan_cuti.*', 'tipe_cuti.tipe_cuti')
            ->orderBy('pengajuan_cuti.tgl_cuti')
            ->get();

        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('SUM(IF(status != "s",1,0)) as jmlizin, SUM(IF(status="s",1,0)) as jmlsakit')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_izin)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_izin)="' . $tahunini . '"')
            ->first();

        $rekapcuti = DB::table('pengajuan_cuti')
            ->selectRaw('count(id) as jmlcuti')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_cuti)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_cuti)="' . $tahunini . '"')
            ->first();

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('dashboard.dashboard', compact('presensihariini', 'processedHistoribulanini', 'namabulan', 'bulanini', 'tahunini', 'namaUser', 'rekappresensi', 'historiizin', 'historicuti', 'rekapizin', 'rekapcuti'));
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



    public function dashboardadmin()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m");
        $tahunini = date("Y");

        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "08:00:00",1,0)) as jmlterlambat')
            ->where('tgl_presensi', $hariini)
            ->first();

        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin, SUM(IF(status="s",1,0)) as jmlsakit')
            ->where('tgl_izin', $hariini)
            ->where('status_approved', 1)
            ->first();

        $rekapkaryawan = DB::table('karyawan')
            ->selectRaw('COUNT(nik) as jmlkar')
            ->first();

        $historihari = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->whereDate('tgl_presensi', $hariini)
            ->orderBy('tgl_presensi')
            ->select('presensi.*', 'karyawan.nama_lengkap')
            ->get();

        $leaderboardTelat = DB::table('presensi')
            ->select('presensi.nik', 'karyawan.nama_lengkap', DB::raw('SUM(CASE WHEN jam_in > "08:00:00" THEN (HOUR(jam_in) * 60 + MINUTE(jam_in)) - (8 * 60) ELSE 0 END) as total_late_minutes'))
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->whereRaw('MONTH(tgl_presensi) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_presensi) = ?', [$tahunini])
            ->where('jam_in', '>', '08:00:00')
            ->groupBy('presensi.nik', 'karyawan.nama_lengkap')
            ->orderBy('total_late_minutes', 'desc')
            ->limit(10)
            ->get();

        $leaderboardOnTime = DB::table('presensi')
            ->select('presensi.nik', 'karyawan.nama_lengkap', DB::raw('SUM(CASE WHEN jam_in < "08:00:00" THEN (8 * 60) - (HOUR(jam_in) * 60 + MINUTE(jam_in)) ELSE 0 END) as total_on_time'))
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->whereRaw('MONTH(tgl_presensi) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_presensi) = ?', [$tahunini])
            ->where('jam_in', '<', '08:00:00')
            ->groupBy('presensi.nik', 'karyawan.nama_lengkap')
            ->orderBy('total_on_time', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.dashboardadmin', compact('rekapizin', 'rekappresensi', 'rekapkaryawan', 'historihari', 'leaderboardTelat', 'leaderboardOnTime'));
    }
}
