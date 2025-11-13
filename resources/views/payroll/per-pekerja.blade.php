@extends('layouts.admin.tabler')

@php
use App\Helpers\DateHelper;
@endphp

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Payroll</div>
                <h2 class="page-title">Per Pekerja</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('payroll.per-pekerja') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Pekerja</label>
                        <select name="karyawan_id" class="form-select" required>
                            <option value="">Pilih Pekerja</option>
                            @foreach ($employees as $item)
                                <option value="{{ $item->id }}" {{ (int)($selectedEmployeeId ?? 0) === $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_awal" class="form-control" value="{{ optional($startDate)->toDateString() }}" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Hitung</button>
                    </div>
                </form>

                @if (!$selectedEmployee || !$startDate)
                    <div class="alert alert-info mt-4" role="alert">
                        Pilih pekerja dan tentukan tanggal mulai untuk menghitung payroll.
                    </div>
                @else
                    <div class="row mt-4 g-3">
                        <div class="col-md-6">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="text-uppercase text-muted fw-bold">Periode</div>
                                    <div class="h3 mb-0">
                                        {{ DateHelper::formatIndonesianDate($startDate->toDateString()) }}
                                        &ndash;
                                        {{ DateHelper::formatIndonesianDate($endDate->toDateString()) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="text-uppercase text-muted fw-bold">Total Pembayaran</div>
                                    <div class="h3 mb-0">Rp {{ number_format($totalPay, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="w-1">No</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Tarif / Hari</th>
                                    <th>Total Bayar</th>
                                    <th>Lembur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rows as $index => $row)
                                    <tr class="{{ $row->has_attendance ? '' : 'table-warning' }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row->date_label }}</td>
                                        <td>{{ $row->jam_masuk ?? '-' }}</td>
                                        <td>{{ $row->jam_keluar ?? '-' }}</td>
                                        <td>Rp {{ number_format($row->daily_rate, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($row->pay, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($row->is_overtime)
                                                <span class="badge bg-green" style="color: white;">Ya</span>
                                            @else
                                                <span class="badge bg-primary" style="color: white;">Tidak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada catatan payroll untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
