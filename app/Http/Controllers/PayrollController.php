<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Karyawan;
use App\Models\Presensi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PayrollController extends Controller
{
    private const STANDARD_START = '07:30:00';
    private const STANDARD_END   = '16:30:00';
    private const OVERTIME_THRESHOLD = '22:00:00';
    private const PERIOD_LENGTH_DAYS = 14;

    public function perPekerja(Request $request): View
    {
        $companyId = session('selected_company_id');
        $cabangId  = session('selected_cabang_id');

        $employees = Karyawan::with(['jabatan'])
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->when($cabangId, fn ($q) => $q->where('cabang_id', $cabangId))
            ->orderBy('nama_lengkap')
            ->get();

        $filters = $request->validate([
            'karyawan_id'   => ['nullable', 'integer'],
            'tanggal_awal'  => ['nullable', 'date'],
        ]);

        $selectedEmployeeId = $filters['karyawan_id'] ?? null;
        $startDate = isset($filters['tanggal_awal'])
            ? Carbon::parse($filters['tanggal_awal'])->startOfDay()
            : null;
        $endDate = $startDate ? $startDate->copy()->addDays(self::PERIOD_LENGTH_DAYS - 1) : null;

        $employee = $selectedEmployeeId
            ? $employees->firstWhere('id', (int)$selectedEmployeeId)
            : null;

        $rows = collect();
        $totalPay = 0;

        if ($employee && $startDate && $endDate) {
            $rows = $this->buildPayrollRows($employee, $startDate, $endDate);
            $totalPay = $rows->sum('pay');
        }

        return view('payroll.per-pekerja', [
            'employees'           => $employees,
            'selectedEmployee'    => $employee,
            'selectedEmployeeId'  => $selectedEmployeeId,
            'startDate'           => $startDate,
            'endDate'             => $endDate,
            'rows'                => $rows,
            'totalPay'            => $totalPay,
        ]);
    }

    public function tabel(Request $request): View
    {
        $companyId = session('selected_company_id');
        $cabangId  = session('selected_cabang_id');

        $filters = $request->validate([
            'tanggal_awal' => ['nullable', 'date'],
        ]);

        $startDate = isset($filters['tanggal_awal'])
            ? Carbon::parse($filters['tanggal_awal'])->startOfDay()
            : null;
        $endDate = $startDate ? $startDate->copy()->addDays(self::PERIOD_LENGTH_DAYS - 1) : null;

        $employees = Karyawan::with(['jabatan'])
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->when($cabangId, fn ($q) => $q->where('cabang_id', $cabangId))
            ->orderBy('nama_lengkap')
            ->get();

        $summaries = collect();

        if ($startDate && $endDate) {
            $attendance = Presensi::whereBetween('tanggal', [$startDate, $endDate])
                ->whereIn('karyawan_id', $employees->pluck('id'))
                ->get()
                ->groupBy('karyawan_id');

            $summaries = $employees->map(function (Karyawan $employee) use ($attendance, $startDate, $endDate) {
                $rows = $this->buildPayrollRows($employee, $startDate, $endDate, $attendance->get($employee->id));
                return (object) [
                    'karyawan'   => $employee,
                    'total_days' => $rows->where('has_attendance', true)->count(),
                    'total_pay'  => $rows->sum('pay'),
                    'rows'       => $rows,
                ];
            })->filter(fn ($summary) => $summary->total_days > 0 || $summary->total_pay > 0);
        }

        return view('payroll.tabel', [
            'startDate'  => $startDate,
            'endDate'    => $endDate,
            'summaries'  => $summaries,
        ]);
    }

    private function buildPayrollRows(Karyawan $employee, Carbon $startDate, Carbon $endDate, ?Collection $attendanceCollection = null): Collection
    {
        $dailyRate = (float) ($employee->jabatan->daily_rate ?? 0);

        $attendanceByDate = ($attendanceCollection ?? Presensi::where('karyawan_id', $employee->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get())
            ->mapWithKeys(function (Presensi $record) {
                $dateValue = $record->tanggal instanceof Carbon ? $record->tanggal : Carbon::parse($record->tanggal);
                return [$dateValue->toDateString() => $record];
            });

        $period = CarbonPeriod::create($startDate, $endDate);
        $rows = collect();

        foreach ($period as $date) {
            $key = $date->toDateString();
            /** @var Presensi|null $record */
            $record = $attendanceByDate->get($key);

            $pay = 0;
            $isOvertime = false;
            $hasAttendance = false;
            $checkoutTime = null;

            if ($record && $record->jam_masuk) {
                $hasAttendance = true;
                $pay = $dailyRate;

                if ($record->jam_keluar) {
                    $checkoutTime = Carbon::createFromFormat('H:i:s', $record->jam_keluar);
                    $overtimeLimit = Carbon::createFromFormat('H:i:s', self::OVERTIME_THRESHOLD);
                    if ($checkoutTime->greaterThanOrEqualTo($overtimeLimit)) {
                        $pay *= 2;
                        $isOvertime = true;
                    }
                }
            }

            $rows->push((object) [
                'date'            => $date,
                'date_label'      => DateHelper::formatIndonesianDate($date->toDateString()),
                'jam_masuk'       => $record?->jam_masuk ?? self::STANDARD_START,
                'jam_keluar'      => $record?->jam_keluar ?? self::STANDARD_END,
                'has_attendance'  => $hasAttendance,
                'pay'             => $pay,
                'is_overtime'     => $isOvertime,
                'daily_rate'      => $dailyRate,
            ]);
        }

        return $rows;
    }
}
