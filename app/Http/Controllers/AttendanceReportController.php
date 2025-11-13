<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Karyawan;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceReportController extends Controller
{
    public function perPekerja(Request $request): View
    {
        $companyId = session('selected_company_id');
        $cabangId  = session('selected_cabang_id');

        $employees = Karyawan::with(['jabatan', 'cabang'])
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->when($cabangId, fn ($q) => $q->where('cabang_id', $cabangId))
            ->orderBy('nama_lengkap')
            ->get();

        $filters = $request->validate([
            'karyawan_id' => ['nullable', 'integer'],
            'bulan'       => ['nullable', 'integer', 'between:1,12'],
            'tahun'       => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        $selectedEmployeeId = $filters['karyawan_id'] ?? null;
        $month = (int)($filters['bulan'] ?? Carbon::now()->month);
        $year  = (int)($filters['tahun'] ?? Carbon::now()->year);

        $attendance = collect();
        $employee   = null;

        if ($selectedEmployeeId) {
            $employee = $employees->firstWhere('id', (int)$selectedEmployeeId);

            if ($employee) {
                $attendance = Presensi::where('karyawan_id', $employee->id)
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month)
                    ->orderBy('tanggal')
                    ->get()
                    ->map(function (Presensi $record) {
                        $dateValue = $record->tanggal;
                        $dateInstance = $dateValue instanceof Carbon ? $dateValue : Carbon::parse($dateValue);

                        return (object) [
                            'tanggal'      => $dateInstance,
                            'tanggal_label'=> DateHelper::formatIndonesianDate($dateInstance->toDateString()),
                            'jam_masuk'    => $record->jam_masuk,
                            'jam_keluar'   => $record->jam_keluar,
                            'foto_masuk'   => $record->foto_masuk,
                            'foto_keluar'  => $record->foto_keluar,
                        ];
                    });
            }
        }

        $monthNames = $this->monthNames();

        return view('absensi.per-pekerja', [
            'employees'     => $employees,
            'attendance'    => $attendance,
            'selectedEmployee' => $employee,
            'selectedEmployeeId' => $selectedEmployeeId,
            'selectedMonth' => $month,
            'selectedYear'  => $year,
            'monthNames'    => $monthNames,
        ]);
    }

    public function tabel(Request $request): View
    {
        $companyId = session('selected_company_id');
        $cabangId  = session('selected_cabang_id');

        $filters = $request->validate([
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'tahun' => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        $month = (int)($filters['bulan'] ?? Carbon::now()->month);
        $year  = (int)($filters['tahun'] ?? Carbon::now()->year);

        $records = Presensi::with(['karyawan.jabatan', 'karyawan.cabang'])
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->when($companyId, function ($query) use ($companyId) {
                $query->whereHas('karyawan', fn ($q) => $q->where('company_id', $companyId));
            })
            ->when($cabangId, function ($query) use ($cabangId) {
                $query->whereHas('karyawan', fn ($q) => $q->where('cabang_id', $cabangId));
            })
            ->orderBy('tanggal')
            ->orderBy('karyawan_id')
            ->get()
            ->map(function (Presensi $record) {
                $dateValue = $record->tanggal;
                $dateInstance = $dateValue instanceof Carbon ? $dateValue : Carbon::parse($dateValue);

                return (object) [
                    'tanggal'      => $dateInstance,
                    'tanggal_label'=> DateHelper::formatIndonesianDate($dateInstance->toDateString()),
                    'nama'         => $record->karyawan?->nama_lengkap,
                    'jabatan'      => $record->karyawan?->jabatan?->nama,
                    'cabang'       => $record->karyawan?->cabang?->nama,
                    'jam_masuk'    => $record->jam_masuk,
                    'jam_keluar'   => $record->jam_keluar,
                ];
            });

        return view('absensi.tabel', [
            'records'     => $records,
            'selectedMonth' => $month,
            'selectedYear'  => $year,
            'monthNames'    => $this->monthNames(),
        ]);
    }

    private function monthNames(): array
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }
}
