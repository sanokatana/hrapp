@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Time Attendance
                </div>
                <h2 class="page-title">
                    Time Attendance Table
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
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ url('/timeatt/table') }}" method="GET" autocomplete="off">
                                    <div class="row">
                                        <div class="col-12">
                                            <form action="{{ url('/attendance/table') }}" method="GET" autocomplete="off">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="input-icon mb-3">
                                                            <span class="input-icon-addon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-calendar-month">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                                                    <path d="M16 3v4" />
                                                                    <path d="M8 3v4" />
                                                                    <path d="M4 11h16" />
                                                                    <path d="M7 14h.013" />
                                                                    <path d="M10.01 14h.005" />
                                                                    <path d="M13.01 14h.005" />
                                                                    <path d="M16.015 14h.005" />
                                                                    <path d="M13.015 17h.005" />
                                                                    <path d="M7.01 17h.005" />
                                                                    <path d="M10.01 17h.005" />
                                                                </svg>
                                                            </span>
                                                            <select name="bulan" id="bulan" class="form-control">
                                                                <option value="">Bulan</option>
                                                                @foreach (range(1, 12) as $month)
                                                                <option value="{{ $month }}" {{ request('bulan') == $month ? 'selected' : '' }}>
                                                                    {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="input-icon mb-3">
                                                            <span class="input-icon-addon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-calendar-year">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                                                    <path d="M16 3v4" />
                                                                    <path d="M8 3v4" />
                                                                    <path d="M4 11h16" />
                                                                    <path d="M7 14h.013" />
                                                                    <path d="M10.01 14h.005" />
                                                                    <path d="M13.01 14h.005" />
                                                                    <path d="M16.015 14h.005" />
                                                                    <path d="M13.015 17h.005" />
                                                                    <path d="M7.01 17h.005" />
                                                                    <path d="M10.01 17h.005" />
                                                                </svg>
                                                            </span>
                                                            <select name="tahun" id="tahun" class="form-control">
                                                                <option value="">Tahun</option>
                                                                @for ($year = $earliestYear; $year <= $latestYear; $year++) <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                                                    {{ $year }}
                                                                    </option>
                                                                    @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="input-icon mb-3">
                                                            <span class="input-icon-addon">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-user">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                                </svg>
                                                            </span>
                                                            <input type="text" value="{{ request('nama_lengkap') }}" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Nama Karyawan">
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group mb-3">
                                                            <select name="kode_dept" id="kode_dept" class="form-select">
                                                                <option value="">Department</option>
                                                                @foreach ($departments as $d)
                                                                <option {{ request('kode_dept')==$d->kode_dept ? 'selected' : ''}} value="{{$d->kode_dept}}">{{$d->nama_dept}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <div class="form-group">
                                                            <button class="btn btn-primary w-100" type="submit">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-search">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                                    <path d="M21 21l-6 -6" />
                                                                </svg>
                                                                Cari Data
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr style="text-align:center;">
                                            <th style="border-color: black; border-style: solid; border-width: 1px; color: black">Nama Karyawan</th>
                                            <th style="border-color: black; border-style: solid; border-width: 1px; color: black">Department</th>
                                            @for($i = 1; $i <= $daysInMonth; $i++) <th style="border-color: black; border-style: solid; border-width: 1px; color: black" class="{{ $currentMonth == Carbon\Carbon::now()->month && $i == Carbon\Carbon::now()->day ? 'today' : '' }}">
                                                {{ $i }}
                                                </th>
                                                @endfor
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : purple;">Total Jam Kerja</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : purple;">Total Jam Kerja Department</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attendanceData as $department)
                                        @php
                                        $departmentTotalHours = 0;
                                        $firstRow = true;
                                        $rowspan = count($department['karyawan']);
                                        @endphp
                                        @foreach($department['karyawan'] as $row)
                                        <tr>
                                            <td class="customTb">{{ $row['nama_lengkap'] }}</td>
                                            @if ($firstRow)
                                            <td class="customTb" rowspan="{{ $rowspan }}">{{ $department['department'] }}</td>
                                            @endif
                                            @php
                                            $firstRow = false;
                                            @endphp
                                            @foreach($row['attendance'] as $day)
                                            <td class="customTb">
                                                <b><span>{{ $day['hours'] }}</span></b>
                                            </td>
                                            @endforeach
                                            <td class="customTb">{{ $row['total_jam_kerja'] }}</td>
                                            @if ($loop->first)
                                            <td class="customTb" rowspan="{{ $rowspan }}">{{ $department['total_hours'] }}</td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
