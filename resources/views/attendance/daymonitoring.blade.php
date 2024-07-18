@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Attendance
                </div>
                <h2 class="page-title">
                    Day Monitoring
                </h2>
                <br>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    <div class="row mt-2">
                            <div class="col-12">
                                <form action="/attendance/daymonitoring" method="GET">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Nama Karyawan" value="{{ request('nama_karyawan') }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Tanggal Presensi" id="tanggal" name="tanggal" autocomplete="off" value="{{ request('tanggal') }}">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <button class="btn btn-primary w-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                        <path d="M21 21l-6 -6" />
                                                    </svg>
                                                    Cari
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr style="text-align:center;">
                                            <th>No.</th>
                                            <th>Nip</th>
                                            <th>Nama Karyawan</th>
                                            <th>Department</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Pulang</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($presensi as $d)
                                        @php
                                        // Standard office start time
                                        $startTime = strtotime('08:00:00');
                                        $jamInTime = strtotime($d->jam_masuk);
                                        // Calculate delay in hours and minutes
                                        $delayHours = 0;
                                        $delayMinutes = 0;
                                        if ($jamInTime > $startTime) {
                                        $delayInSeconds = $jamInTime - $startTime;
                                        $delayHours = floor($delayInSeconds / 3600);
                                        $delayMinutes = floor(($delayInSeconds % 3600) / 60);
                                        }
                                        @endphp
                                        <tr style="text-align: center; ">
                                            <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                            <td style="vertical-align: middle;">{{ $d->nip }}</td>
                                            <td style="vertical-align: middle;">{{ $d->nama_lengkap }}</td>
                                            <td style="vertical-align: middle;">{{ $d->nama_dept }}</td>
                                            <td style="vertical-align: middle;">{{ $d->tanggal }}</td>
                                            <td style="vertical-align: middle;">{{ $d->jam_masuk }}</td>
                                            <td style="vertical-align: middle;">
                                                {!! $d->jam_pulang != null ? $d->jam_pulang : '<span class="badge bg-danger" style="color: white;">Belum Absen</span>' !!}
                                            </td>
                                            <td style="vertical-align: middle;">
                                                @if ($jamInTime > $startTime)
                                                <div class="row">
                                                    <span class="badge bg-yellow text-yellow-fg" style="color: white;">Terlambat</span>
                                                    <span class="badge bg-yellow-lt" style="color: white;">
                                                        {{ $delayHours > 0 ? $delayHours . ' Jam ' : '' }}{{ $delayMinutes > 0 ? $delayMinutes . ' Menit' : '' }}
                                                    </span>
                                                </div>
                                                @else
                                                <div class="row">
                                                    <span class="badge bg-green text-yellow-fg" style="color: white;">Tepat Waktu</span>
                                                    <span class="badge bg-green-lt" style="color: white;">
                                                        On Time
                                                    </span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $presensi->links('vendor.pagination.bootstrap-5')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function() {
        $("#tanggal").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        })
    });
</script>
@endpush
