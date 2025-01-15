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
                    Attendance Daily
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
                                <form action="{{ url('/laporan/dailyMonitor') }}" method="GET" autocomplete="off">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-calendar-month">
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
                                        <div class="col-4">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-calendar-year">
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
                                                    @for ($year = $earliestYear; $year <= $latestYear; $year++)
                                                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                                        {{ $year }}
                                                        </option>
                                                        @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <div class="form-group">
                                                <button class="btn btn-primary w-100" type="submit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-search">
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
                        <div class="row">
                            <div class="col-12 table-responsive">
                                @php
                                use Carbon\Carbon;

                                // Get filtered values or default to the current year and month
                                $selectedYear = request('tahun', date('Y'));
                                $selectedMonth = request('bulan', date('m'));

                                // Get the total days in the selected month
                                $daysInMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->daysInMonth;
                                @endphp
                                <table class="table table-hover">
                                    <thead>
                                        <tr style="text-align:center;">
                                            <th style="border: 1px solid black; color: black;">Kategori</th>
                                            @for ($i = 1; $i <= $daysInMonth; $i++)
                                                @php
                                                $date=Carbon::createFromDate($selectedYear, $selectedMonth, $i);
                                                $isWeekend=$date->isSaturday() || $date->isSunday();
                                                @endphp
                                                <th style="border: 1px solid black; text-align: center; {{ $isWeekend ? 'background-color: purple; color: white;' : 'color: black;' }}">
                                                    {{ $i }}
                                                </th>
                                                @endfor

                                                <th style="border: 1px solid black; background-color: purple; color: white;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="border: 1px solid black; text-align: center;">Karyawan Hadir</td>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                                <td style="border: 1px solid black; text-align: center;">{{ $hadir[$i] }}</td>
                                                @endfor
                                                <td style="border: 1px solid black; text-align: center; background-color: purple; color: white;">{{ array_sum($hadir) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid black; text-align: center;">Karyawan Telat</td>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                                <td style="border: 1px solid black; text-align: center;">{{ $telat[$i] }}</td>
                                                @endfor
                                                <td style="border: 1px solid black; text-align: center; background-color: purple; color: white;">{{ array_sum($telat) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid black; text-align: center;">Karyawan Izin</td>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                                <td style="border: 1px solid black; text-align: center;">{{ $izin[$i] }}</td>
                                                @endfor
                                                <td style="border: 1px solid black; text-align: center; background-color: purple; color: white;">{{ array_sum($izin) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid black; text-align: center;">Karyawan Cuti</td>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                                <td style="border: 1px solid black; text-align: center;">{{ $cuti[$i] }}</td>
                                                @endfor
                                                <td style="border: 1px solid black; text-align: center; background-color: purple; color: white;">{{ array_sum($cuti) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid black; text-align: center;">Karyawan Mangkir</td>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                                <td style="border: 1px solid black; text-align: center;">{{ $mangkir[$i] }}</td>
                                                @endfor
                                                <td style="border: 1px solid black; text-align: center; background-color: purple; color: white;">{{ array_sum($mangkir) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid black; text-align: center;">% Karyawan Hadir</td>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                                <td style="border: 1px solid black; text-align: center;">{{ $percentHadir[$i] }}%</td>
                                                @endfor
                                                <td style="border: 1px solid black; text-align: center; background-color: purple; color: white;">
                                                    {{ $totalPercentHadir }}%
                                                </td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid black; text-align: center;">% Karyawan Mangkir</td>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                                <td style="border: 1px solid black; text-align: center;">{{ $percentMangkir[$i] }}%</td>
                                                @endfor
                                                <td style="border: 1px solid black; text-align: center; background-color: purple; color: white;">
                                                    {{ $totalPercentMangkir }}%
                                                </td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid black; text-align: center;">% Karyawan Izin</td>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                                <td style="border: 1px solid black; text-align: center;">{{ $percentIzin[$i] }}%</td>
                                                @endfor
                                                <td style="border: 1px solid black; text-align: center; background-color: purple; color: white;">
                                                    {{ $totalPercentIzin }}%
                                                </td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid black; text-align: center;">% Karyawan Cuti</td>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                                <td style="border: 1px solid black; text-align: center;">{{ $percentCuti[$i] }}%</td>
                                                @endfor
                                                <td style="border: 1px solid black; text-align: center; background-color: purple; color: white;">
                                                    {{ $totalPercentCuti }}%
                                                </td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid black; text-align: center;">% Karyawan Telat</td>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                                <td style="border: 1px solid black; text-align: center;">{{ $percentTelat[$i] }}%</td>
                                                @endfor
                                                <td style="border: 1px solid black; text-align: center; background-color: purple; color: white;">
                                                    {{ $totalPercentTelat }}%
                                                </td>
                                        </tr>

                                        <tr>
                                            <td style="border: 1px solid black; text-align: center; font-weight: bold;">Total Karyawan</td>
                                            @for($i = 1; $i <= $daysInMonth; $i++)
                                                <td style="border: 1px solid black; text-align: center;">{{ $totalKaryawan }}</td>
                                                @endfor
                                                <td style="border: 1px solid black; text-align: center; background-color: purple; color: white; font-weight: bold;">{{ $totalKaryawan }}</td>
                                        </tr>
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
