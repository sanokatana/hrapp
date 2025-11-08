<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Karyawan;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $karyawan = Auth::guard('karyawan')->user();

        if (!$karyawan instanceof Karyawan) {
            return redirect()->route('login');
        }

        $karyawan->loadMissing(['jabatan', 'department', 'lokasi']);

        $today = Carbon::today();
        $lateThreshold = '09:00:00';

        $todayRecord = Presensi::with('lokasi')
            ->where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        $monthRecords = Presensi::with('lokasi')
            ->where('karyawan_id', $karyawan->id)
            ->whereYear('tanggal', $today->year)
            ->whereMonth('tanggal', $today->month)
            ->orderByDesc('tanggal')
            ->get();

        $summary = (object) [
            'presentDays' => $monthRecords->whereNotNull('jam_masuk')->count(),
            'lateDays' => $monthRecords->filter(fn ($record) => $this->isLate($record->jam_masuk, $lateThreshold))->count(),
            'withoutCheckout' => $monthRecords->filter(fn ($record) => $record->jam_masuk && !$record->jam_keluar)->count(),
        ];

        $history = $monthRecords->map(function ($record) use ($lateThreshold) {
            $status = 'Belum Absen Masuk';
            $statusClass = 'text-danger';
            $lateness = null;

            if ($record->jam_masuk) {
                $status = 'Tepat Waktu';
                $statusClass = 'text-success';

                if ($this->isLate($record->jam_masuk, $lateThreshold)) {
                    $status = 'Terlambat';
                    $statusClass = 'text-danger';
                    $lateness = $this->formatMinutes($this->minutesLate($record->jam_masuk, $lateThreshold));
                }
            }

            $pulangStatus = null;
            $pulangStatusClass = null;

            if ($record->jam_masuk && !$record->jam_keluar) {
                $pulangStatus = 'Belum Absen Pulang';
                $pulangStatusClass = 'text-warning';
            }

            $jamMasukLabel = $record->jam_masuk
                ? Carbon::createFromFormat('H:i:s', $record->jam_masuk)->format('H:i')
                : null;

            $jamKeluarLabel = $record->jam_keluar
                ? Carbon::createFromFormat('H:i:s', $record->jam_keluar)->format('H:i')
                : null;

            return (object) [
                'tanggal' => $record->tanggal,
                'tanggal_label' => DateHelper::formatIndonesianDate($record->tanggal->toDateString()),
                'jam_masuk' => $record->jam_masuk,
                'jam_masuk_label' => $jamMasukLabel,
                'jam_keluar' => $record->jam_keluar,
                'jam_keluar_label' => $jamKeluarLabel,
                'status' => $status,
                'status_class' => $statusClass,
                'lateness' => $lateness,
                'pulang_status' => $pulangStatus,
                'pulang_status_class' => $pulangStatusClass,
                'lokasi' => optional($record->lokasi)->nama_kantor,
            ];
        })->values();

        $monthNames = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        $monthIndex = (int) $today->format('n');
        $monthName = $monthNames[$monthIndex] ?? $today->format('F');
        $year = (int) $today->format('Y');

        return view('dashboard.dashboard', [
            'karyawan' => $karyawan,
            'todayRecord' => $todayRecord,
            'summary' => $summary,
            'history' => $history,
            'monthName' => $monthName,
            'year' => $year,
        ]);
    }

    public function dashboardadmin()
    {
        return view('dashboard.dashboardadmin');
    }

    private function isLate(?string $time, string $threshold): bool
    {
        if (!$time) {
            return false;
        }

        $scanTime = Carbon::createFromFormat('H:i:s', $time);
        $thresholdTime = Carbon::createFromFormat('H:i:s', $threshold);

        return $scanTime->greaterThan($thresholdTime);
    }

    private function minutesLate(string $time, string $threshold): int
    {
        $scanTime = Carbon::createFromFormat('H:i:s', $time);
        $thresholdTime = Carbon::createFromFormat('H:i:s', $threshold);

        return $thresholdTime->diffInMinutes($scanTime);
    }

    private function formatMinutes(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        $parts = [];

        if ($hours > 0) {
            $parts[] = $hours . ' Jam';
        }

        if ($remainingMinutes > 0 || empty($parts)) {
            $parts[] = $remainingMinutes . ' Menit';
        }

        return implode(' ', $parts);
    }
}
