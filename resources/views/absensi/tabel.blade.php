@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Absensi</div>
                <h2 class="page-title">Tabel Periode</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('absensi.tabel') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select" required>
                            @foreach ($monthNames as $number => $label)
                                <option value="{{ $number }}" {{ (int)$selectedMonth === (int)$number ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tahun</label>
                        @php
                            $currentYear = now()->year;
                            $yearOptions = range($currentYear - 5, $currentYear + 2);
                        @endphp
                        <select name="tahun" class="form-select" required>
                            @foreach ($yearOptions as $year)
                                <option value="{{ $year }}" {{ (int)$selectedYear === (int)$year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                    </div>
                </form>

                <div class="table-responsive mt-4">
                    <table class="table table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Cabang</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $row->tanggal_label }}</td>
                                    <td>{{ $row->nama ?? '-' }}</td>
                                    <td>{{ $row->jabatan ?? '-' }}</td>
                                    <td>{{ $row->cabang ?? '-' }}</td>
                                    <td>{{ $row->jam_masuk ?? '-' }}</td>
                                    <td>{{ $row->jam_keluar ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data absensi untuk periode ini.</td>
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
