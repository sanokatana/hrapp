@extends('layouts.admin.tabler')
@section('content')
@php
use App\Helpers\DateHelper;
@endphp
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Overview
                </div>
                <h2 class="page-title">
                    Dashboard
                </h2>
                <br>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <h2 class="page-title" style="font-weight: normal;">
                Hari Ini
            </h2>
            <div class="col-md-6 col-xl-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-secondary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                        <path d="M15 19l2 2l4 -4" />
                                    </svg>

                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $rekapkaryawan->jmlkar}}
                                </div>
                                <div class="text-secondary">
                                    Total Karyawan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-success text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                        <path d="M15 19l2 2l4 -4" />
                                    </svg>

                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $rekappresensi->jmlhadir}}
                                </div>
                                <div class="text-secondary">
                                    Total Hadir
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-danger text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-minus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4c.348 0 .686 .045 1.009 .128" />
                                        <path d="M16 19h6" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $rekappresensi->jmlterlambat != null ? $rekappresensi->jmlterlambat : 0}}
                                </div>
                                <div class="text-secondary">
                                    Total Karyawan Telat
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-files">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
                                        <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $jmlnoatt }}
                                </div>
                                <div class="text-secondary">
                                    Total Tidak Hadir
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-info text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-files">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
                                        <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $rekapizin->jmlizin != null ? $rekapizin->jmlizin : 0}}
                                </div>
                                <div class="text-secondary">
                                    Total Karyawan Izin
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-success text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-files">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
                                        <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                                    </svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $rekapcuti->jmlcuti != null ? $rekapcuti->jmlcuti : 0}}
                                </div>
                                <div class="text-secondary">
                                    Total Karyawan Cuti
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card" style="height: 28rem">
                    <div class="card-header">
                        <h3 class="card-title">Hadir Hari Ini</h3>
                    </div>
                    <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                        <div class="divide-y">
                            @foreach ($historihari as $d)
                            <div class="row">
                                @php
                                $path = Storage::url('uploads/absensi/'.$d->foto_in);
                                // Extract hours and minutes from the jam_in time
                                $jam_in_time = strtotime($d->jam_in);
                                $hours_diff = floor(($jam_in_time - strtotime("08:00")) / 3600);
                                $minutes_diff = floor((($jam_in_time - strtotime("08:00")) % 3600) / 60);

                                // Calculate lateness
                                if ($hours_diff > 0) {
                                if ($minutes_diff > 0) {
                                $lateness = $hours_diff . " Jam " . $minutes_diff . " Menit";
                                } else {
                                $lateness = $hours_diff . " Jam";
                                }
                                } elseif ($minutes_diff > 0) {
                                $lateness = $minutes_diff . " Menit";
                                } else {
                                $lateness = "On Time";
                                }

                                // Determine status based on lateness
                                $status = ($lateness != "On Time") ? "Terlambat" : "On Time";
                                @endphp

                                <div class="col-auto">
                                    @if ($d != null && $d->foto_in != null)
                                    @php
                                    $path = Storage::url('/uploads/absensi/'.$d->foto_in);
                                    @endphp
                                    <span class="avatar">
                                        <img src="{{ url($path) }}" alt="" class="imaged w48 circular-image">
                                    </span>
                                    @else
                                    <span class="avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-fingerprint">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M18.9 7a8 8 0 0 1 1.1 5v1a6 6 0 0 0 .8 3" />
                                            <path d="M8 11a4 4 0 0 1 8 0v1a10 10 0 0 0 2 6" />
                                            <path d="M12 11v2a14 14 0 0 0 2.5 8" />
                                            <path d="M8 15a18 18 0 0 0 1.8 6" />
                                            <path d="M4.9 19a22 22 0 0 1 -.9 -7v-1a8 8 0 0 1 12 -6.95" />
                                        </svg>
                                    </span>
                                    @endif
                                </div>
                                <div class="col">
                                    <div class="text-truncate">
                                        <div>{{ $d->nama_lengkap }}</div>
                                        <div><b>{{ DateHelper::formatIndonesianDate($d->tgl_presensi) }}</b></div>
                                        <span class=" {{ $status == 'Terlambat' ? 'text-danger' : 'text-success' }}">
                                            {{ $status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="jam-in mt-3">
                                        <span class="status text-success">{{ $d->jam_in }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card" style="height: 28rem">
                    <div class="card-header">
                        <h3 class="card-title">Leaderboard On Time</h3>
                    </div>
                    <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                        <div class="divide-y">
                            @foreach ($leaderboardOnTime as $person)
                            <div class="row">
                                <div class="col-auto">
                                    <span class="avatar">{{ strtoupper(substr($person->nama_lengkap, 0, 2)) }}</span>
                                </div>
                                <div class="col">
                                    <div class="text-truncate">
                                        <strong>{{ $person->nama_lengkap }}</strong>
                                    </div>
                                    <div class="text-secondary">
                                        {{ intdiv($person->total_on_time, 60) }} Jam {{ $person->total_on_time % 60 }} Menit
                                    </div>
                                </div>
                                <div class="col-auto align-self-center">
                                    <div class="status status-primary">{{ $loop->iteration }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card" style="height: 28rem">
                    <div class="card-header">
                        <h3 class="card-title">Leaderboard Telat</h3>
                    </div>
                    <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                        <div class="divide-y">
                            @foreach ($leaderboardTelat as $person)
                            <div class="row">
                                <div class="col-auto">
                                    <span class="avatar">{{ strtoupper(substr($person->nama_lengkap, 0, 2)) }}</span>
                                </div>
                                <div class="col">
                                    <div class="text-truncate">
                                        <strong>{{ $person->nama_lengkap }}</strong>
                                    </div>
                                    <div class="text-secondary">
                                        {{ intdiv($person->total_late_minutes, 60) }} Jam {{ $person->total_late_minutes % 60 }} Menit
                                    </div>
                                </div>
                                <div class="col-auto align-self-center">
                                <div class="status status-primary">{{ $loop->iteration }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
