@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Laporan
                </div>
                <h2 class="page-title">
                    Laporan Attendance Table
                </h2>
                <br>
            </div>
        </div>
    </div>
</div>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    });
</script>
@elseif(session('danger'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Danger!',
            text: "{{ session('danger') }}",
            icon: 'danger',
            confirmButtonText: 'Ok'
        });
    });
</script>
@endif
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="/laporan/exportAttendanceView" method="GET">
                            <div class="row">
                                <div class="col-3 col-lg-3">
                                    <div class="form-group">
                                        <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Nama Karyawan" value="{{ request('nama_karyawan') }}">
                                    </div>
                                </div>
                                <div class="col-3 col-lg-3">
                                    <div class="form-group mb-3">
                                        <select name="kode_dept" id="kode_dept" class="form-select">
                                            <option value="">Department</option>
                                            @foreach ($department as $d)
                                            <option {{ request('kode_dept') == $d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3 col-lg-3">
                                    <div class="form-group mb-3">
                                        <select name="pt_karyawan" id="pt_karyawan" class="form-select">
                                            <option value="">Nama PT</option>
                                            @foreach ($uniquePt as $d) <!-- Here $d is a string -->
                                            <option {{ request('pt_karyawan') == $d ? 'selected' : '' }} value="{{ $d }}">{{ $d }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3 col-lg-3">
                                    <div class="form-group mb-3">
                                        <select name="religion_karyawan" id="religion_karyawan" class="form-select">
                                            <option value="">Religion</option>
                                            @foreach ($uniqueReligion as $religion)
                                            <option {{ request('religion_karyawan') == $religion ? 'selected' : '' }} value="{{ $religion }}">{{ $religion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3 col-lg-3">
                                    <div class="form-group mb-3">
                                        <select name="base" id="base" class="form-select">
                                            <option value="">Base</option>
                                            @foreach ($uniqueBase as $base)
                                            <option {{ request('base') == $base ? 'selected' : '' }} value="{{ $base }}">{{ $base }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3 col-lg-3">
                                    <div class="form-group mb-3">
                                        <select name="grade_karyawan" id="grade_karyawan" class="form-select">
                                            <option value="">Grade</option>
                                            @foreach ($uniqueGrade as $grade)
                                            <option {{ request('grade_karyawan') == $grade ? 'selected' : '' }} value="{{ $grade }}">{{ $grade }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3 col-lg-3">
                                    <div class="form-group mb-3">
                                        <select name="status_karyawan" id="status_karyawan" class="form-select">
                                            <option value="">Status Karyawan</option>
                                            @foreach ($uniqueStatusKar as $status)
                                            <option {{ request('status_karyawan') == $status ? 'selected' : '' }} value="{{ $status }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3 col-lg-3">
                                    <div class="form-group mb-3">
                                        <select name="status_employee" id="status_employee" class="form-select">
                                            <option value="">Status Employee</option>
                                            @foreach ($uniqueEmployeeStatus as $status)
                                            <option {{ request('status_employee') == $status ? 'selected' : '' }} value="{{ $status }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-12">
                                    <div class="form-group">
                                        <button class="btn btn-primary w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
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
                        <form action="{{ url('/laporan/exportAttendance') }}" method="GET" autocomplete="off">
                            <div class="row mt-5">
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
                                <div class="col-4">
                                    <div class="form-group">
                                        <button class="btn btn-primary w-100" type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-search">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                <path d="M21 21l-6 -6" />
                                            </svg>
                                            Download Attendance Data
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter">
                                    <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="select_all" class="form-check-input m-0 align-middle" aria-label="Select All">
                                                </th>
                                                <th>Select All Karyawan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($karyawan as $kar)
                                            <tr>
                                                <td class="w-1 pe-0">
                                                    <!-- Assign the contract ID to the checkbox name -->
                                                    <input type="checkbox" name="karyawan_ids[]" value="{{ $kar->id }}" class="form-check-input m-0 align-middle" aria-label="Select task">
                                                </td>
                                                <td class="w-100">
                                                    <a href="#" class="text-reset">
                                                        <b>Karyawan</b> | <b>{{ $kar->nama_lengkap }}</b>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select_all');
        const checkboxes = document.querySelectorAll('input[name="karyawan_ids[]"]');

        selectAllCheckbox.addEventListener('change', function() {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    });
</script>
@endpush
