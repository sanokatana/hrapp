@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Master Data
                </div>
                <h2 class="page-title">
                    Karyawan
                </h2>
                <br>
            </div>
        </div>
    </div>
</div>
@php
use Carbon\Carbon;
use App\Helpers\DateHelper;
@endphp
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
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
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group"></div>
                                <a href="#" class="btn btn-primary" id="btnTambahKaryawan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Data
                                </a>
                                <a href="#" class="btn btn-primary" id="btnUploadCSV">
                                    Upload CSV
                                </a>
                                <a href="/karyawan/export" class="btn btn-primary">
                                    Export CSV
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/karyawan" method="GET">
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
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>No</th>
                                            <th>NIP</th>
                                            <th>NIK</th>
                                            <th>Nama Karyawan</th>
                                            <th>Department</th>
                                            <th>Jabatan</th>
                                            <th>Email</th>
                                            <th>No. Hp</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Work Period</th>
                                            <th>Status</th>
                                            <th>Foto</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($karyawan as $d)
                                        @php
                                        $path = Storage::url('uploads/karyawan/'.$d->foto)
                                        @endphp
                                        <tr style="text-align: center;">
                                            <td>{{ $loop->iteration + $karyawan->firstItem() -1 }}</td>
                                            <td>{{ $d->nip}}</td>
                                            <td>{{ $d->nik}}</td>
                                            <td>{{ $d->nama_lengkap}}</td>
                                            <td>{{ $d->nama_dept}}</td>
                                            <td>{{ $d->nama_jabatan}}</td>
                                            <td>{{ $d->email}}</td>
                                            <td>{{ $d->no_hp}}</td>
                                            <td>
                                                @if ($d->tgl_masuk)
                                                {{ DateHelper::formatIndonesiaDate($d->tgl_masuk) }}
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                $tgl_masuk = Carbon::parse($d->tgl_masuk);
                                                $now = Carbon::now();
                                                $work_period = $tgl_masuk->diff($now);
                                                echo $work_period->y . ' Tahun ' . $work_period->m . ' Bulan';
                                                @endphp
                                            </td>
                                            <td>{{ $d->status_kar}}</td>
                                            <td>
                                                @if (empty($d->foto))
                                                <img src="{{ asset('assets/img/nophoto.jpg')}}" class="avatar" alt="">
                                                @else
                                                <img src="{{ url($path )}}" class="avatar" alt="">
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Each button on a separate row within the same cell -->
                                                <div class="form-group">
                                                    <!-- Edit Button -->
                                                    <div class="mb-1">
                                                        <a href="#" class="edit btn btn-info btn-sm" id="{{ $d->id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                <path d="M16 5l3 3" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <!-- Time Button -->
                                                    <div class="mb-1">
                                                        <a href="#" class="time btn btn-success btn-sm"
                                                            data-nik="{{ $d->nik }}"
                                                            data-shift-pattern-id="{{ $d->shift_pattern_id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-hour-1">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                                <path d="M12 7v5" />
                                                                <path d="M12 12l2 -3" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <!-- Delete Button -->
                                                    <div>
                                                        <form action="/karyawan/{{$d->nik}}/delete" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm delete-confirm" style="border: none; padding-top: 3px; padding-bottom: 3px; padding-right: 5px; padding-left: 5px;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M4 7l16 0" />
                                                                    <path d="M10 11l0 6" />
                                                                    <path d="M14 11l0 6" />
                                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            @endforeach
                                    </tbody>
                                </table>
                                {{ $karyawan->links('vendor.pagination.bootstrap-5')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-inputkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full-width modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/karyawan/store" method="POST" id="formKaryawan" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-3">
                            <div class="form-label">Nomer Mesin</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M15 8l2 0" />
                                        <path d="M15 12l2 0" />
                                        <path d="M7 16l10 0" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="nip" id="nip" placeholder="10101">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-label">NIK</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M15 8l2 0" />
                                        <path d="M15 12l2 0" />
                                        <path d="M7 16l10 0" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="nik" id="nik" placeholder="10101">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-label">Nama Karyawan</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="John Doe">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-label">Tanggal Masuk</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                        <path d="M16 3l0 4" />
                                        <path d="M8 3l0 4" />
                                        <path d="M4 11l16 0" />
                                        <path d="M8 15h2v2h-2z" />
                                    </svg>
                                </span>
                                <input type="date" value="" class="form-control" name="tgl_masuk" id="tgl_masuk" placeholder="Tanggal Masuk">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-label">Email Perusahaan</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                                        <path d="M3 7l9 6l9 -6" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="email" id="email" placeholder="@ciptaharmoni.com">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-label">Nomer HP</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-phone">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="no_hp" id="no_hp" placeholder="No HP">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-label">Date Of Birth</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                        <path d="M16 3l0 4" />
                                        <path d="M8 3l0 4" />
                                        <path d="M4 11l16 0" />
                                        <path d="M8 15h2v2h-2z" />
                                    </svg>
                                </span>
                                <input type="date" value="" class="form-control" name="DOB" id="DOB" placeholder="">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-label">Foto Karyawan</div>
                            <input type="file" class="form-control" name="foto" id="foto" accept=".png, .jpg, .jpeg">
                        </div>
                        <!-- Additional Fields Start Here -->
                        <div class="col-3">
                            <div class="form-label">Grade</div>
                            <input type="text" value="" class="form-control" name="grade" id="grade" placeholder="Grade">
                        </div>
                        <div class="col-3">
                            <div class="form-label">Nomer Kontrak</div>
                            <select name="no_kontrak" id="no_kontrak" class="form-select">
                                <option value="">Pilih</option>
                                <option value="">Create New Contract</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <div class="form-label">Employee Status</div>
                            <!-- <input type="text" value="" class="form-control" name="employee_status" id="employee_status" placeholder="Employee Status"> -->
                            <select name="employee_status" id="employee_status" class="form-select">
                                <option value="">Pilih</option>
                                <option value="Kontrak">Kontrak</option>
                                <option value="PKWT">PKWT</option>
                                <option value="PKWTT">PKWTT</option>
                                <option value="Tetap">Tetap</option>
                                <option value="Probation">Probation</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <div class="form-label">Site</div>
                            <select name="base_poh" id="base_poh" class="form-select">
                                <option value="">Pilih</option>
                                @foreach ($location as $d)
                                <option {{ Request('nama_kantor') == $d->nama_kantor ? 'selected' : '' }} value="{{ $d->nama_kantor }}">{{ $d->nama_kantor }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Nama PT</div>
                            <input type="text" value="" class="form-control" name="nama_pt" id="nama_pt" placeholder="Nama PT">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Sex</div>
                            <input type="text" value="" class="form-control" name="sex" id="sex" placeholder="Sex">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Marital Status</div>
                            <input type="text" value="" class="form-control" name="tax_status" id="tax_status" placeholder="Tax Status">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Birthplace</div>
                            <input type="text" value="" class="form-control" name="birthplace" id="birthplace" placeholder="Birthplace">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Religion</div>
                            <input type="text" value="" class="form-control" name="religion" id="religion" placeholder="Religion">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Status Karyawan</div>
                            <select name="status_kar" id="status_kar" class="form-select">
                                <option value="">Pilih Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <h5 class="modal-title"><u>Department Details</u></h5>
                        <div class="col-3">
                            <div class="form-label">Department</div>
                            <select name="kode_dept" id="kode_dept" class="form-select">
                                <option value="">Pilih</option>
                                @foreach ($department as $d)
                                <option {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <div class="form-label">Jabatan</div>
                            <select name="jabatan" id="jabatan" class="form-select">
                                <option value="">Pilih</option>
                                @foreach ($jabatan as $d)
                                <option {{ Request('id') == $d->id ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->id }} - {{ $d->nama_jabatan }} - {{ $d->site }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <h5 class="modal-title"><u>Address Karyawan</u></h5>
                        <div class="col-3">
                            <div class="form-label">Address</div>
                            <input type="text" value="" class="form-control" name="address" id="address" placeholder="Address">
                        </div>
                        <div class="col-3">
                            <div class="form-label">Address RT</div>
                            <input type="text" value="" class="form-control" name="address_rt" id="address_rt" placeholder="Address RT">
                        </div>
                        <div class="col-3">
                            <div class="form-label">Address RW</div>
                            <input type="text" value="" class="form-control" name="address_rw" id="address_rw" placeholder="Address RW">
                        </div>
                        <div class="col-3">
                            <div class="form-label">Address Kel</div>
                            <input type="text" value="" class="form-control" name="address_kel" id="address_kel" placeholder="Address Kel">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Address Kec</div>
                            <input type="text" value="" class="form-control" name="address_kec" id="address_kec" placeholder="Address Kec">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Address Kota</div>
                            <input type="text" value="" class="form-control" name="address_kota" id="address_kota" placeholder="Address Kota">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Address Prov</div>
                            <input type="text" value="" class="form-control" name="address_prov" id="address_prov" placeholder="Address Prov">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Kode Pos</div>
                            <input type="text" value="" class="form-control" name="kode_pos" id="kode_pos" placeholder="Kode Pos">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <h5 class="modal-title"><u>Education & Experience</u></h5>
                        <div class="col-3">
                            <div class="form-label">Gelar</div>
                            <input type="text" value="" class="form-control" name="gelar" id="gelar" placeholder="Gelar">
                        </div>
                        <div class="col-3">
                            <div class="form-label">Major</div>
                            <input type="text" value="" class="form-control" name="major" id="major" placeholder="Major">
                        </div>
                        <div class="col-3">
                            <div class="form-label">Kampus</div>
                            <input type="text" value="" class="form-control" name="kampus" id="kampus" placeholder="Kampus">
                        </div>
                        <div class="col-3">
                            <div class="form-label">Job Experience</div>
                            <input type="text" value="" class="form-control" name="job_exp" id="job_exp" placeholder="Job Experience">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <h5 class="modal-title"><u>Data Pribadi</u></h5>
                        <div class="col-3 mt-2">
                            <div class="form-label">NIK KTP</div>
                            <input type="text" value="" class="form-control" name="nik_ktp" id="nik_ktp" placeholder="NIK KTP">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Blood Type</div>
                            <input type="text" value="" class="form-control" name="blood_type" id="blood_type" placeholder="Blood Type">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Email Personal</div>
                            <input type="text" value="" class="form-control" name="email_personal" id="email_personal" placeholder="Email Personal">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Family Card</div>
                            <input type="text" value="" class="form-control" name="family_card" id="family_card" placeholder="Family Card">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">No NPWP</div>
                            <input type="text" value="" class="form-control" name="no_npwp" id="no_npwp" placeholder="No NPWP">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Alamat NPWP</div>
                            <input type="text" value="" class="form-control" name="alamat_npwp" id="alamat_npwp" placeholder="Alamat NPWP">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">BPJS TK</div>
                            <input type="text" value="" class="form-control" name="bpjstk" id="bpjstk" placeholder="BPJS TK">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">BPJS Kes</div>
                            <input type="text" value="" class="form-control" name="bpjskes" id="bpjskes" placeholder="BPJS Kes">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <h5 class="modal-title"><u>Bank Detail</u></h5>
                        <div class="col-3 mt-2">
                            <div class="form-label">Rekening No</div>
                            <input type="text" value="" class="form-control" name="rek_no" id="rek_no" placeholder="Rekening No">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Bank Name</div>
                            <input type="text" value="" class="form-control" name="bank_name" id="bank_name" placeholder="Bank Name">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Rekening Name</div>
                            <input type="text" value="" class="form-control" name="rek_name" id="rek_name" placeholder="Rekening Name">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <h5 class="modal-title"><u>Family Data</u></h5>
                        <div class="col-3 mt-2">
                            <div class="form-label">Father's Name</div>
                            <input type="text" value="" class="form-control" name="father_name" id="father_name" placeholder="Father's Name">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Mother's Name</div>
                            <input type="text" value="" class="form-control" name="mother_name" id="mother_name" placeholder="Mother's Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3 mt-2">
                            <div class="form-label">Nama Pasangan</div>
                            <input type="text" value="" class="form-control" name="fd_si_name" id="fd_si_name" placeholder="FD SI Name">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">NIK Pasangan</div>
                            <input type="text" value="" class="form-control" name="fd_si_nik" id="fd_si_nik" placeholder="FD SI NIK">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Kota Lahir Pasangan</div>
                            <input type="text" value="" class="form-control" name="fd_si_kota" id="fd_si_kota" placeholder="FD SI Kota">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">DOB Pasangan</div>
                            <input type="date" value="" class="form-control" name="fd_si_dob" id="fd_si_dob" placeholder="FD SI DOB">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Nama Anak Pertama</div>
                            <input type="text" value="" class="form-control" name="fd_anak1_name" id="fd_anak1_name" placeholder="FD Anak 1 Name">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">NIK Anak Pertama</div>
                            <input type="text" value="" class="form-control" name="fd_anak1_nik" id="fd_anak1_nik" placeholder="FD Anak 1 NIK">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Kota Lahir Anak Pertama</div>
                            <input type="text" value="" class="form-control" name="fd_anak1_kota" id="fd_anak1_kota" placeholder="FD Anak 1 Kota">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">DOB Anak Pertama</div>
                            <input type="date" value="" class="form-control" name="fd_anak1_dob" id="fd_anak1_dob" placeholder="FD Anak 1 DOB">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Nama Anak Kedua</div>
                            <input type="text" value="" class="form-control" name="fd_anak2_name" id="fd_anak2_name" placeholder="FD Anak 2 Name">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">NIK Anak Kedua</div>
                            <input type="text" value="" class="form-control" name="fd_anak2_nik" id="fd_anak2_nik" placeholder="FD Anak 2 NIK">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Kota Lahir Anak Kedua</div>
                            <input type="text" value="" class="form-control" name="fd_anak2_kota" id="fd_anak2_kota" placeholder="FD Anak 2 Kota">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">DOB Anak Kedua</div>
                            <input type="date" value="" class="form-control" name="fd_anak2_dob" id="fd_anak2_dob" placeholder="FD Anak 2 DOB">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Nama Anak Ketiga</div>
                            <input type="text" value="" class="form-control" name="fd_anak3_name" id="fd_anak3_name" placeholder="FD Anak 3 Name">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">NIK Anak Kedua</div>
                            <input type="text" value="" class="form-control" name="fd_anak3_nik" id="fd_anak3_nik" placeholder="FD Anak 3 NIK">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Kota Lahir Anak Ketiga</div>
                            <input type="text" value="" class="form-control" name="fd_anak3_kota" id="fd_anak3_kota" placeholder="FD Anak 3 Kota">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">DOB Anak Ketiga</div>
                            <input type="date" value="" class="form-control" name="fd_anak3_dob" id="fd_anak3_dob" placeholder="FD Anak 3 DOB">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <h5 class="modal-title"><u>Emergency Contact</u></h5>
                        <div class="col-3 mt-2">
                            <div class="form-label">Emergency Contact Name</div>
                            <input type="text" value="" class="form-control" name="em_name" id="em_name" placeholder="Emergency Contact Name">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Emergency Contact Phone</div>
                            <input type="text" value="" class="form-control" name="em_telp" id="em_telp" placeholder="Emergency Contact Phone">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Emergency Contact Relation</div>
                            <input type="text" value="" class="form-control" name="em_relation" id="em_relation" placeholder="Emergency Contact Relation">
                        </div>
                        <div class="col-3 mt-2">
                            <div class="form-label">Emergency Contact Address</div>
                            <input type="text" value="" class="form-control" name="em_alamat" id="em_alamat" placeholder="Emergency Contact Address">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <h5 class="modal-title"><u>Perlengkapan File</u></h5>
                        <div class="col-md-6">
                            <div class="form-check mt-2 d-flex align-items-center">
                                <input type="checkbox" value="" class="form-check-input" name="status_photo" id="status_photo">
                                <label class="form-check-label ms-2" for="status_photo">Photo Karyawan</label>
                            </div>
                            <div class="form-check mt-2 d-flex align-items-center">
                                <input type="checkbox" value="" class="form-check-input" name="status_ktp" id="status_ktp">
                                <label class="form-check-label ms-2" for="status_ktp">KTP</label>
                            </div>
                            <div class="form-check mt-2 d-flex align-items-center">
                                <input type="checkbox" value="" class="form-check-input" name="status_kk" id="status_kk">
                                <label class="form-check-label ms-2" for="status_kk">Family Card</label>
                            </div>
                            <div class="form-check mt-2 d-flex align-items-center">
                                <input type="checkbox" value="" class="form-check-input" name="status_npwp" id="status_npwp">
                                <label class="form-check-label ms-2" for="status_npwp">NPWP</label>
                            </div>
                            <div class="form-check mt-2 d-flex align-items-center">
                                <input type="checkbox" value="" class="form-check-input" name="status_sim" id="status_sim">
                                <label class="form-check-label ms-2" for="status_sim">SIM</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check mt-2 d-flex align-items-center">
                                <input type="checkbox" value="" class="form-check-input" name="status_ijazah" id="status_ijazah">
                                <label class="form-check-label ms-2" for="status_ijazah">Ijazah</label>
                            </div>
                            <div class="form-check mt-2 d-flex align-items-center">
                                <input type="checkbox" value="" class="form-check-input" name="status_skck" id="status_skck">
                                <label class="form-check-label ms-2" for="status_skck">SKCK</label>
                            </div>
                            <div class="form-check mt-2 d-flex align-items-center">
                                <input type="checkbox" value="" class="form-check-input" name="status_cv" id="status_cv">
                                <label class="form-check-label ms-2" for="status_cv">CV</label>
                            </div>
                            <div class="form-check mt-2 d-flex align-items-center">
                                <input type="checkbox" value="" class="form-check-input" name="status_applicant" id="status_applicant">
                                <label class="form-check-label ms-2" for="status_applicant">Application</label>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-3">
                        <h5 class="modal-title"><u>Upload Files</u></h5>

                        <div class="col-md-6">
                            <div class="form-group mt-2 d-flex">
                                <input type="file" class="form-control" name="file_photo" id="file_photo" style="width: 50%;">
                                <label class="ms-5 align-self-center" for="file_photo">Photo Karyawan</label>
                            </div>
                            <div class="form-group mt-2 d-flex">
                                <input type="file" class="form-control" name="file_ktp" id="file_ktp" style="width: 50%;">
                                <label class="ms-5 align-self-center" for="file_ktp">KTP</label>
                            </div>
                            <div class="form-group mt-2 d-flex">
                                <input type="file" class="form-control" name="file_kk" id="file_kk" style="width: 50%;">
                                <label class="ms-5 align-self-center" for="file_kk">Family Card</label>
                            </div>
                            <div class="form-group mt-2 d-flex">
                                <input type="file" class="form-control" name="file_npwp" id="file_npwp" style="width: 50%;">
                                <label class="ms-5 align-self-center" for="file_npwp">NPWP</label>
                            </div>
                            <div class="form-group mt-2 d-flex">
                                <input type="file" class="form-control" name="file_sim" id="file_sim" style="width: 50%;">
                                <label class="ms-5 align-self-center" for="file_sim">SIM</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mt-2 d-flex">
                                <input type="file" class="form-control" name="file_ijazah" id="file_ijazah" style="width: 50%;">
                                <label class="ms-5 align-self-center" for="file_ijazah">Ijazah</label>
                            </div>
                            <div class="form-group mt-2 d-flex">
                                <input type="file" class="form-control" name="file_skck" id="file_skck" style="width: 50%;">
                                <label class="ms-5 align-self-center" for="file_skck">SKCK</label>
                            </div>
                            <div class="form-group mt-2 d-flex">
                                <input type="file" class="form-control" name="file_cv" id="file_cv" style="width: 50%;">
                                <label class="ms-5 align-self-center" for="file_cv">CV</label>
                            </div>
                            <div class="form-group mt-2 d-flex">
                                <input type="file" class="form-control" name="file_applicant" id="file_applicant" style="width: 50%;">
                                <label class="ms-5 align-self-center" for="file_applicant">Application</label>
                            </div>
                        </div>
                    </div>


                    <!-- Additional Fields End Here -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M16 19h6" />
                                        <path d="M19 16v6" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                    </svg>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Upload CSV -->
<div class="modal modal-blur fade" id="modal-uploadKaryawanCSV" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Data Karyawan CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/karyawan/uploadKaryawan" method="POST" id="formKaryawan" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="file">Upload Excel</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary w-100">Simpan</button>
                            </div>
                            <div class="form-group mt-3">
                                <a href="/karyawan/downloadTemplate" class="btn btn-secondary w-100">Download Template Excel</a>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- Modal Edit -->
<div class="modal modal-blur fade" id="modal-editkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full-width modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditform">

            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-editshift" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Shift Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/karyawan/storeshift" method="POST" id="formShift">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <select name="shift_pattern_id" id="shift_pattern_id" class="form-select">
                                <option value="">Pilih Shift</option>
                                @foreach ($shift as $d)
                                <option value="{{ $d->id }}">{{ $d->pattern_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <input type="date" value="" class="form-control" name="start_shift" id="start_shift" placeholder="">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100" type="submit">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- CONTRACT -->

<div class="modal modal-blur fade" id="modal-inputContract" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/kontrak/store" method="POST" id="formContract">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">NIK</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M15 8l2 0" />
                                        <path d="M15 12l2 0" />
                                        <path d="M7 16l10 0" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="nik" id="nik" placeholder="10101" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">No Kontrak</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="no_kontrak" id="no_kontrak" placeholder="No Kontrak">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Contract Type</div>
                            <select name="contract_type" id="contract_type" class="form-select">
                                <option value="">Choose</option>
                                <option value="PKWT">PKWT</option>
                                <option value="PKWTT">PKWTT</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Start Date</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                        <path d="M16 3l0 4" />
                                        <path d="M8 3l0 4" />
                                        <path d="M4 11l16 0" />
                                        <path d="M8 15h2v2h-2z" />
                                    </svg>
                                </span>
                                <input type="date" value="" class="form-control" name="start_date" id="start_date" placeholder="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">End Date</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                        <path d="M16 3l0 4" />
                                        <path d="M8 3l0 4" />
                                        <path d="M4 11l16 0" />
                                        <path d="M8 15h2v2h-2z" />
                                    </svg>
                                </span>
                                <input type="date" value="" class="form-control" name="end_date" id="end_date" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Position</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-versions">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 5m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                                        <path d="M7 7l0 10" />
                                        <path d="M4 8l0 8" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="position" id="position" placeholder="Position">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Salary</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-cash-banknote">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                        <path d="M3 6m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                        <path d="M18 12l.01 0" />
                                        <path d="M6 12l.01 0" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="salary" id="salary" placeholder="Salary">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Status</div>
                            <select name="status" id="status" class="form-select">
                                <option value="">Choose</option>
                                <option value="Active">Active</option>
                                <option value="Extended">Extended</option>
                                <option value="Terminated">Terminated</option>
                                <option value="Expired">Expired</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Contract File</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-info">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                        <path d="M11 14h1v4h1" />
                                        <path d="M12 11h.01" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="contract_file" id="contract_file" placeholder="File">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M16 19h6" />
                                        <path d="M19 16v6" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                    </svg>
                                    Simpan
                                </button>
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

        $('#nik').on('input', function() {
            var nik = $(this).val();

            if (nik) {
                $.ajax({
                    url: '/contracts',
                    method: 'GET',
                    data: {
                        nik: nik
                    },
                    success: function(response) {
                        var $select = $('#no_kontrak');
                        $select.empty();
                        $select.append('<option value="">Pilih</option>');
                        $select.append('<option value="">Create New Contract</option>');

                        $.each(response, function(index, contract) {
                            $select.append('<option value="' + contract.no_kontrak + '">' + contract.no_kontrak + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.error('Failed to fetch contracts:', xhr);
                    }
                });
            } else {
                $('#no_kontrak').empty().append('<option value="">Pilih</option><option value="">Create New Contract</option>');
            }
        });

        $('#btnTambahKaryawan').click(function() {
            $('#modal-inputkaryawan').modal("show");
        });

        $('#btnUploadCSV').click(function() {
            $('#modal-uploadKaryawanCSV').modal("show");
        });

        $('.edit').click(function() {
            var id = $(this).attr('id');
            $.ajax({
                type: 'POST',
                url: '/karyawan/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token();}}",
                    id: id
                },
                success: function(respond) {
                    $('#loadeditform').html(respond);
                    initializeEventListeners(); // Reinitialize after content is loaded
                }
            });
            $('#modal-editkaryawan').modal("show");
        });

        function initializeEventListeners() {
            if ($('#formEditKaryawan').length) {
                console.log('Form Contract found');

                $('#no_kontrak_edit').on('change', function() {
                    var noKontrak = $(this).val();

                    if (noKontrak) {
                        $.ajax({
                            url: '/contract/type',
                            method: 'GET',
                            data: {
                                no_kontrak: noKontrak
                            },
                            success: function(response) {
                                // Assuming response contains the contract type
                                console.log('Received contract type:', response.contract_type);
                                $('#employee_status_edit').val(response.contract_type);
                            },
                            error: function(xhr) {
                                console.error('Failed to fetch contract type:', xhr);
                            }
                        });
                    } else {
                        $('#employee_status_edit').val('');
                    }
                });
            } else {
                console.log('Form Contract not found'); // Debugging
            }
        }

        $('.time').click(function() {
            var nik = $(this).data('nik');
            var shiftPatternId = $(this).data('shift-pattern-id');

            // Set the form action with the correct nik if needed
            $('#formShift').attr('action', '/karyawan/storeshift/' + nik);

            // Update the selected shift pattern in the dropdown
            $('#shift_pattern_id').val(shiftPatternId);

            $.ajax({
                url: '/karyawan/getshift/' + nik, // Adjust the URL based on your route
                method: 'GET',
                success: function(data) {
                    // Set the start_shift value from the response
                    $('#start_shift').val(data.start_shift);

                    // Show the modal
                    $('#modal-editshift').modal("show");
                },
                error: function() {
                    alert('Failed to retrieve shift details.');
                }
            });
        });

        $(".delete-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Yakin?",
                text: "Data Karyawan Akan Ke Delete!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        $('#formKaryawan').submit(function() {
            var nik = $('#nik').val();
            var nama_lengkap = $('#nama_lengkap').val();
            var jabatan = $('#jabatan').val();
            var kode_dept = $("#formKaryawan").find('#kode_dept').val();
            if (nik == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'NIK Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#nik').focus();
                });
                return false;
            } else if (nama_lengkap == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Nama Lengkap Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#nama_lengkap').focus();
                });
                return false;
            } else if (jabatan == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Jabatan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#jabatan').focus();
                });
                return false;
            } else if (kode_dept == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Department Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#kode_dept').focus();
                });
                return false;
            }
        });

        $('#no_kontrak').on('change', function() {
            var noKontrak = $(this).val();

            if (noKontrak) {
                $.ajax({
                    url: '/contract/type',
                    method: 'GET',
                    data: {
                        no_kontrak: noKontrak
                    },
                    success: function(response) {
                        // Assuming response contains the contract type
                        $('#employee_status').val(response.contract_type);
                    },
                    error: function(xhr) {
                        console.error('Failed to fetch contract type:', xhr);
                    }
                });
            } else {
                $('#employee_status').val('');
            }

            if (noKontrak === '') {
                $('#modal-inputContract').modal('show');
            }
        });

        $('#formContract').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Update the contract dropdown
                    var nik = $('#nik').val();

                    $.ajax({
                        url: '/contracts',
                        method: 'GET',
                        data: {
                            nik: nik
                        },
                        success: function(response) {
                            var $select = $('#no_kontrak');
                            $select.empty();
                            $select.append('<option value="">Pilih</option>');
                            $select.append('<option value="">Create New Contract</option>');

                            $.each(response, function(index, contract) {
                                $select.append('<option value="' + contract.no_kontrak + '">' + contract.no_kontrak + '</option>');
                            });

                            // Close the contract modal and show the formKaryawan modal
                            $('#modal-inputContract').modal('hide');
                            $('#modal-inputkaryawan').modal('show');
                        },
                        error: function(xhr) {
                            console.error('Failed to fetch contracts:', xhr);
                        }
                    });
                },
                error: function(xhr) {
                    console.error('Failed to create contract:', xhr);
                }
            });
        });
    });
</script>

@endpush
