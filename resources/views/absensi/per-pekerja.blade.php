@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Absensi</div>
                <h2 class="page-title">Per Pekerja</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('absensi.per-pekerja') }}" class="row g-3 align-items-end">
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
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select" required>
                            @foreach ($monthNames as $number => $label)
                                <option value="{{ $number }}" {{ (int)$selectedMonth === (int)$number ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                    </div>
                </form>

                @if (!$selectedEmployee)
                    <div class="alert alert-warning mt-4" role="alert">
                        Pilih pekerja dan periode untuk melihat rekap absensi.
                    </div>
                @else
                    <div class="mt-4">
                        <h3 class="h4 mb-1">{{ $selectedEmployee->nama_lengkap }}</h3>
                        <div class="text-muted">{{ $monthNames[$selectedMonth] ?? '-' }} {{ $selectedYear }}</div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="w-1">No</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Foto Masuk</th>
                                    <th>Foto Pulang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendance as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row->tanggal_label }}</td>
                                        <td>{{ $row->jam_masuk ?? '-' }}</td>
                                        <td>{{ $row->jam_keluar ?? '-' }}</td>
                                        <td>
                                            @if ($row->foto_masuk)
                                                <a href="{{ asset('storage/' . $row->foto_masuk) }}" target="_blank">Lihat</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($row->foto_keluar)
                                                <a href="{{ asset('storage/' . $row->foto_keluar) }}" target="_blank">Lihat</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data absensi untuk periode ini.</td>
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
