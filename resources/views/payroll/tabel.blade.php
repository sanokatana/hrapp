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
                <h2 class="page-title">Tabel Periode</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('payroll.tabel') }}" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_awal" class="form-control" value="{{ optional($startDate)->toDateString() }}" required>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                    </div>
                </form>

                @if ($startDate && $endDate)
                    <div class="alert alert-info mt-4" role="alert">
                        Periode diproses: <strong>{{ DateHelper::formatIndonesianDate($startDate->toDateString()) }}</strong>
                        &ndash;
                        <strong>{{ DateHelper::formatIndonesianDate($endDate->toDateString()) }}</strong>
                    </div>
                @endif

                <div class="table-responsive mt-4">
                    <table class="table table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Total Hari Hadir</th>
                                <th>Total Pembayaran</th>
                                <th>Rincian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($summaries as $index => $summary)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $summary->karyawan->nama_lengkap }}</td>
                                    <td>{{ $summary->karyawan->jabatan->nama ?? '-' }}</td>
                                    <td>{{ $summary->total_days }}</td>
                                    <td>Rp {{ number_format($summary->total_pay, 0, ',', '.') }}</td>
                                    <td>
                                        <details>
                                            <summary class="text-primary">Lihat Rincian</summary>
                                            <div class="table-responsive mt-2">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Tanggal</th>
                                                            <th>Jam Masuk</th>
                                                            <th>Jam Pulang</th>
                                                            <th>Tarif</th>
                                                            <th>Total Bayar</th>
                                                            <th>Lembur</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($summary->rows as $row)
                                                            <tr class="{{ $row->has_attendance ? '' : 'table-warning' }}">
                                                                <td>{{ $row->date_label }}</td>
                                                                <td>{{ $row->jam_masuk ?? '-' }}</td>
                                                                <td>{{ $row->jam_keluar ?? '-' }}</td>
                                                                <td>Rp {{ number_format($row->daily_rate, 0, ',', '.') }}</td>
                                                                <td>Rp {{ number_format($row->pay, 0, ',', '.') }}</td>
                                                                <td>{{ $row->is_overtime ? 'Ya' : 'Tidak' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </details>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data payroll untuk periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
