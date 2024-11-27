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
                                <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
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

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs nav-fill" data-bs-toggle="tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tabs-home-7" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    </svg>
                                    Staff</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tabs-profile-7" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                    </svg>
                                    Non Staff</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="tabs-home-7" role="tabpanel">
                                <div class="container-xl">
                                    <div class="row row-cards">
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Staff Hadir Hari Ini</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($historihariNonNS as $d)
                                                        <div class="row">
                                                            @php
                                                            // Extract hours and minutes from the jam_in time and shift's start_time
                                                            $jam_in_time = strtotime($d->jam_in);
                                                            $start_time = strtotime($d->start_time);
                                                            $lateness = '';
                                                            $jam_5pm = strtotime('17:00:00'); // 5 PM cutoff time

                                                            // Calculate lateness
                                                            if ($jam_in_time > $start_time) {
                                                                $hours_diff = floor(($jam_in_time - $start_time) / 3600);
                                                                $minutes_diff = floor((($jam_in_time - $start_time) % 3600) / 60);
                                                                $lateness = ($hours_diff > 0 ? $hours_diff . " Jam " : "") . ($minutes_diff > 0 ? $minutes_diff . " Menit" : "");

                                                                // Check if the jam_in is after 5 PM, if so, consider it On Time
                                                                if ($jam_in_time > $jam_5pm) {
                                                                    $status = "On Time Pulang";
                                                                } else {
                                                                    $status = "Terlambat";
                                                                }
                                                            } else {
                                                                $lateness = "On Time";
                                                                $status = "On Time";
                                                            }
                                                            @endphp

                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar" style="align-content: center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-fingerprint">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                        <path d="M18.9 7a8 8 0 0 1 1.1 5v1a6 6 0 0 0 .8 3" />
                                                                        <path d="M8 11a4 4 0 0 1 8 0v1a10 10 0 0 0 2 6" />
                                                                        <path d="M12 11v2a14 14 0 0 0 2.5 8" />
                                                                        <path d="M8 15a18 18 0 0 0 1.8 6" />
                                                                        <path d="M4.9 19a22 22 0 0 1 -.9 -7v-1a8 8 0 0 1 12 -6.95" />
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <div><strong>{{ $d->nama_lengkap }} - {{ $d->kode_dept }}</strong></div>
                                                                    <div>{{ $d->nama_jabatan }}</div>
                                                                    <div><b>{{ DateHelper::formatIndonesianDate($d->tgl_presensi) }}</b></div>
                                                                    <span class="{{ $status == 'Terlambat' ? 'text-danger' : 'text-success' }}">
                                                                        {{ $status }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="jam-in mt-3">
                                                                    <span class="status {{ $status == 'Terlambat' ? 'text-danger' : 'text-success' }}">{{ $d->jam_in }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Staff Tidak Hadir Hari Ini</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($noAttendanceNonNS as $d)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar" style="align-content: center">
                                                                {{ strtoupper(substr($d->nama_lengkap, 0, 2)) }}
                                                                </span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <div><strong>{{ $d->nama_lengkap }} - {{ $d->kode_dept }}</strong></div>
                                                                    <div>{{ $d->nama_jabatan }}</div>
                                                                    <div><b>{{ DateHelper::formatIndonesianDate($d->tgl_presensi) }}</b></div>
                                                                    <span class="text-danger">
                                                                        Tidak Hadir
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="jam-in mt-3">
                                                                    <span class="status text-danger">00:00</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                             <div class="card-header">
                                                    <h3 class="card-title">Leaderboard Waktu Telat</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($leaderboardTelatNonNS as $person)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar">{{ strtoupper(substr($person->nama_lengkap, 0, 2)) }}</span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <strong>{{ $person->nama_lengkap }} - {{ $person->kode_dept }}</strong>
                                                                </div>
                                                                <div>{{ $person->nama_jabatan }}</div>
                                                                <div class="text-secondary">
                                                                    {{ $person->total_late_minutes  }} Menit
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
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Leaderboard Waktu On Time</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($leaderboardOnTimeNonNS as $person)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar">{{ strtoupper(substr($person->nama_lengkap, 0, 2)) }}</span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <strong>{{ $person->nama_lengkap }} - {{ $person->kode_dept }}</strong>
                                                                </div>
                                                                <div>{{ $person->nama_jabatan }}</div>
                                                                <div class="text-secondary">
                                                                    {{ $person->total_on_time  }} Menit
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


                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Leaderboard Total Telat</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($totalLatenessNonNS as $person)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar">{{ strtoupper(substr($person->nama_lengkap, 0, 2)) }}</span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <strong>{{ $person->nama_lengkap }} - {{ $person->kode_dept }}</strong>
                                                                </div>
                                                                <div>{{ $person->nama_jabatan }}</div>
                                                                <div class="text-secondary">
                                                                    {{ $person->total_late_count }}
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
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Leaderboard Total On Time</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($totalOnTimeNonNS as $person)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar">{{ strtoupper(substr($person->nama_lengkap, 0, 2)) }}</span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <strong>{{ $person->nama_lengkap }} - {{ $person->kode_dept }}</strong>
                                                                </div>
                                                                <div>{{ $person->nama_jabatan }}</div>
                                                                <div class="text-secondary">
                                                                    {{ $person->total_on_time }}
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
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Karyawan Izin</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($KarIzinNow as $d)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar">{{ strtoupper(substr($d->nama_lengkap, 0, 2)) }}</span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <div><strong>{{ $d->nama_lengkap }} - {{ $d->kode_dept }}</strong></div>
                                                                    <div>{{ $d->nama_jabatan }}</div>
                                                                    <div>
                                                                        <b>
                                                                            @if ($d->tgl_izin == $d->tgl_izin_akhir)
                                                                            {{ DateHelper::formatIndonesianDate($d->tgl_izin) }}
                                                                            @elseif (!empty($d->tgl_izin_akhir))
                                                                            {{ DateHelper::formatIndonesianDate($d->tgl_izin) }} - {{ (!empty($d->tgl_izin_akhir) ? DateHelper::formatIndonesianDate($d->tgl_izin_akhir) : '') }}
                                                                            @else
                                                                            {{ DateHelper::formatIndonesianDate($d->tgl_izin) }}
                                                                            @endif
                                                                        </b>
                                                                    </div>
                                                                    <div>{{ DateHelper::getStatusText($d->status) }} - {{$d->keterangan}}</div>
                                                                    <div>{{ $d->pukul}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Karyawan Cuti</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($KarCutiNow as $d)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar">{{ strtoupper(substr($d->nama_lengkap, 0, 2)) }}</span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <div>{{ $d->nama_lengkap }} - {{ $d->kode_dept }} ({{ $d->nama_jabatan }})</div>
                                                                    <div>
                                                                        <b>
                                                                            @if ($d->tgl_cuti == $d->tgl_cuti_sampai)
                                                                            {{ DateHelper::formatIndonesianDate($d->tgl_cuti) }}
                                                                            @else
                                                                            {{ DateHelper::formatIndonesianDate($d->tgl_cuti) }} - {{ DateHelper::formatIndonesianDate($d->tgl_cuti_sampai) }}
                                                                            @endif
                                                                        </b>
                                                                    </div>
                                                                    <div style="word-wrap: break-word; white-space: normal;">{{$d->note}}</div>
                                                                    <div>
                                                                        @if ($d->jenis === 'Cuti Tahunan')
                                                                        Cuti Tahunan
                                                                        @else
                                                                        {{ $d->tipe_cuti }}
                                                                        @endif
                                                                    </div>
                                                                </div>
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
                            <div class="tab-pane" id="tabs-profile-7" role="tabpanel">
                                <div class="container-xl">
                                    <div class="row row-cards">
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Hadir Hari Ini Non Staff</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($historihariNS as $d)
                                                        <div class="row">
                                                            @php
                                                            // Extract hours and minutes from the jam_in time
                                                            $jam_in_time = strtotime($d->jam_in);
                                                            $start_time = strtotime($d->start_time);
                                                            $lateness = '';

                                                            // Calculate lateness
                                                            if ($jam_in_time > $start_time) {
                                                            $hours_diff = floor(($jam_in_time - $start_time) / 3600);
                                                            $minutes_diff = floor((($jam_in_time - $start_time) % 3600) / 60);
                                                            $lateness = ($hours_diff > 0 ? $hours_diff . " Jam " : "") . ($minutes_diff > 0 ? $minutes_diff . " Menit" : "");
                                                            $status = "Terlambat";
                                                            } else {
                                                            $lateness = "On Time";
                                                            $status = "On Time";
                                                            }
                                                            @endphp

                                                            <div class="col-auto" style="align-content: center">
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
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <div><strong>{{ $d->nama_lengkap }} - {{ $d->kode_dept }}</strong></div>
                                                                    <div>{{ $d->nama_jabatan }}</div>
                                                                    <div><b>{{ DateHelper::formatIndonesianDate($d->tgl_presensi) }}</b></div>
                                                                    <span class="{{ $status == 'Terlambat' ? 'text-danger' : 'text-success' }}">
                                                                        {{ $status }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="jam-in mt-3">
                                                                    <span class="status {{ $status == 'Terlambat' ? 'text-danger' : 'text-success' }}">{{ $d->jam_in }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Non Staff Tidak Hadir Hari Ini</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($noAttendanceNS as $d)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar" style="align-content: center">
                                                                {{ strtoupper(substr($d->nama_lengkap, 0, 2)) }}
                                                                </span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <div><strong>{{ $d->nama_lengkap }} - {{ $d->kode_dept }}</strong></div>
                                                                    <div>{{ $d->nama_jabatan }}</div>
                                                                    <div><b>{{ DateHelper::formatIndonesianDate($d->tgl_presensi) }}</b></div>
                                                                    <span class="text-danger">
                                                                        Tidak Hadir
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="jam-in mt-3">
                                                                    <span class="status text-danger">00:00</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Leaderboard Waktu On Time</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($leaderboardOnTimeNS as $person)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar">{{ strtoupper(substr($person->nama_lengkap, 0, 2)) }}</span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <strong>{{ $person->nama_lengkap }} - {{ $person->kode_dept }}</strong>
                                                                </div>
                                                                <div>{{ $person->nama_jabatan }}</div>
                                                                <div class="text-secondary">
                                                                    {{ $person->total_on_time  }} Menit
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

                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Leaderboard Waktu Telat</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($leaderboardTelatNS as $person)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar">{{ strtoupper(substr($person->nama_lengkap, 0, 2)) }}</span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <strong>{{ $person->nama_lengkap }} - {{ $person->kode_dept }}</strong>
                                                                </div>
                                                                <div>{{ $person->nama_jabatan }}</div>
                                                                <div class="text-secondary">
                                                                    {{ $person->total_late_minutes  }} Menit
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
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Leaderboard Total Telat</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($totalLatenessNS as $person)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar">{{ strtoupper(substr($person->nama_lengkap, 0, 2)) }}</span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <strong>{{ $person->nama_lengkap }} - {{ $person->kode_dept }}</strong>
                                                                </div>
                                                                <div>{{ $person->nama_jabatan }}</div>
                                                                <div class="text-secondary">
                                                                    {{ $person->total_late_count }}
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
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Leaderboard Total On Time</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($totalOnTimeNS as $person)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar">{{ strtoupper(substr($person->nama_lengkap, 0, 2)) }}</span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <strong>{{ $person->nama_lengkap }} - {{ $person->kode_dept }}</strong>
                                                                </div>
                                                                <div>{{ $person->nama_jabatan }}</div>
                                                                <div class="text-secondary">
                                                                    {{ $person->total_on_time }}
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
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Karyawan Izin</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($KarIzinNowNS as $d)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar">{{ strtoupper(substr($d->nama_lengkap, 0, 2)) }}</span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <div><strong>{{ $d->nama_lengkap }} - {{ $d->kode_dept }}</strong></div>
                                                                    <div>{{ $d->nama_jabatan }}</div>
                                                                    <div>
                                                                        <b>
                                                                            @if ($d->tgl_izin == $d->tgl_izin_akhir)
                                                                            {{ DateHelper::formatIndonesianDate($d->tgl_izin) }}
                                                                            @else
                                                                            {{ DateHelper::formatIndonesianDate($d->tgl_izin) }} - {{ (!empty($tgl_izin_akhir) ? DateHelper::formatIndonesianDate($tgl_izin_akhir) : '') }}
                                                                            @endif
                                                                        </b>
                                                                    </div>
                                                                    <div>{{ DateHelper::getStatusText($d->status) }} - {{ $d->pukul}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card" style="height: 28rem">
                                                <div class="card-header">
                                                    <h3 class="card-title">Karyawan Cuti</h3>
                                                </div>
                                                <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                                                    <div class="divide-y">
                                                        @foreach ($KarCutiNowNS as $d)
                                                        <div class="row">
                                                            <div class="col-auto" style="align-content: center">
                                                                <span class="avatar">{{ strtoupper(substr($d->nama_lengkap, 0, 2)) }}</span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="text-truncate">
                                                                    <div>{{ $d->nama_lengkap }} - {{ $d->kode_dept }} ({{ $d->nama_jabatan }})</div>
                                                                    <div>
                                                                        <b>
                                                                            @if ($d->tgl_cuti == $d->tgl_cuti_sampai)
                                                                            {{ DateHelper::formatIndonesianDate($d->tgl_cuti) }}
                                                                            @else
                                                                            {{ DateHelper::formatIndonesianDate($d->tgl_cuti) }} - {{ DateHelper::formatIndonesianDate($d->tgl_cuti_sampai) }}
                                                                            @endif
                                                                        </b>
                                                                    </div>
                                                                    <div>
                                                                        @if ($d->jenis === 'Cuti Tahunan')
                                                                        Cuti Tahunan
                                                                        @else
                                                                        {{ $d->tipe_cuti }}
                                                                        @endif
                                                                    </div>
                                                                </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
