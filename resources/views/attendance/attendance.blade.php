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
                    Attendance Table
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
                        <div class="row mb-3">
                            <div class="col-12">
                                <a href="#" class="btn btn-primary" id="btnUploadAtt">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Upload Cuti
                                </a>
                            </div>
                        </div>
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
                                                    @for ($year = $earliestYear; $year <= $latestYear; $year++)
                                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
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
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr style="text-align:center;">
                                            <th style="border-color: black; border-style: solid; border-width: 1px; color: black">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Nama Karyawan &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                                            <th style="border-color: black; border-style: solid; border-width: 1px; color: black">Department</th>
                                            @for($i = 1; $i <= $daysInMonth; $i++) <th style="border-color: black; border-style: solid; border-width: 1px; color: black" class="{{ $currentMonth == Carbon\Carbon::now()->month && $i == Carbon\Carbon::now()->day ? 'today' : '' }}">
                                                {{ $i }}
                                                </th>
                                                @endfor
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : purple;">Telat</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : purple;">Presentase T</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : purple;">Jumlah Telat</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : purple;">Presentase</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : purple;">Menit Telat</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : purple;">P</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: black; background-color : yellow;">T</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : red;">OFF</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : green;">S</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : purple;">I</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : black;">C</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : purple;">D</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : pink;">H2</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : grey;">H1</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : grey;">Mangkir</th>
                                                <th style="border-color: black; border-style: solid; border-width: 1px; color: white; background-color : grey;">Blank</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attendanceData as $department)
                                        @php
                                        $firstRow = true;
                                        $rowspan = count($department['karyawan']);
                                        @endphp
                                        @foreach($department['karyawan'] as $row)
                                        <tr>
                                            <td style="border-color: black; border-style: solid; border-width: 1px;">{{ $row['nama_lengkap'] }}</td>
                                            @if ($firstRow)
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;" rowspan="{{ $rowspan }}">{{ $department['department'] }}</td>
                                            @php $firstRow = false; @endphp
                                            @endif
                                            @foreach($row['attendance'] as $day)
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;" class="{{ $day['class'] }}">
                                                @if($day['status'] == 'T' && ($currentMonth == Carbon\Carbon::now()->month && $i == Carbon\Carbon::now()->day))
                                                <span>{{ $day['status'] }}</span>
                                                @elseif($day['status'] == 'LN')
                                                LN
                                                @else
                                                {{ $day['status'] }}
                                                @endif
                                            </td>
                                            @endforeach
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['totalT'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['presentase'] }}%</td>
                                            @if ($loop->first)
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;" rowspan="{{ $rowspan }}">{{ $department['total_jumlah_telat'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;" rowspan="{{ $rowspan }}">{{ $department['total_presentase'] }}%</td>
                                            @endif
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['menit_telat'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['totalP'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['totalT'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['totalOff'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['totalSakit'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['totalIzin'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['totalCuti'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['totalDinas'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['totalCuti'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['totalCuti'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['totalMangkir'] }}</td>
                                            <td style="text-align: center; vertical-align:middle; border-color: black; border-style: solid; border-width: 1px;">{{ $row['totalBlank'] }}</td>
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
<!-- Modal Upload CSV -->
<div class="modal modal-blur fade" id="modal-uploadatt" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Attendance Karyawan CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/attendance/uploadAtt" method="POST" id="formAtt" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="file">Upload CSV</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    Simpan
                                </button>
                            </div>
                            <div class="form-group mt-3">
                                <a href="#" class="btn btn-secondary w-100">
                                    Download Template CSV
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        $('#btnUploadAtt').click(function() {
            $('#modal-uploadatt').modal("show");
        });
    });
</script>
@endpush
