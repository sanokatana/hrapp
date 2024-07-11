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
use App\Helpers\DateHelper;
@endphp
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
                                <a href="#" class="btn btn-primary" id="btnTambahKaryawan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Data
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/karyawan" method="GET">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Nama Karyawan" value="{{ Request('nama_karyawan')}}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group mb-3">
                                                <select name="kode_dept" id="kode_dept" class="form-select">
                                                    <option value="">Department</option>
                                                    @foreach ($department as $d)
                                                    <option {{ Request('kode_dept')==$d->kode_dept ? 'selected' : ''}} value="{{$d->kode_dept}}">{{$d->nama_dept}}</option>
                                                    @endforeach
                                                </select>
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
                        <div class="row mt-2">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>No</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Jabatan</th>
                                            <th>No. Hp</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Foto</th>
                                            <th>Department</th>
                                            <th>Level</th>
                                            <th>Atasan</th>
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
                                            <td>{{ $d->nik}}</td>
                                            <td>{{ $d->nama_lengkap}}</td>
                                            <td>{{ $d->email}}</td>
                                            <td>{{ $d->nama_jabatan}}</td>
                                            <td>{{ $d->no_hp}}</td>
                                            <td>
                                                @if ($d->tgl_masuk)
                                                {{ DateHelper::formatIndonesiaDate($d->tgl_masuk) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (empty($d->foto))
                                                <img src="{{ asset('assets/img/nophoto.jpg')}}" class="avatar" alt="">
                                                @else
                                                <img src="{{ url($path )}}" class="avatar" alt="">
                                                @endif
                                            </td>
                                            <td>{{ $d->nama_dept}}</td>
                                            <td>{{ $d->level }}</td>
                                            <td>{{ $d->nama_atasan}}</td>
                                            <td>
                                                <div class="form-group">
                                                    <a href="#" class="edit btn btn-info btn-sm" nik="{{ $d->nik }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                            <path d="M16 5l3 3" />
                                                        </svg>
                                                    </a>
                                                    <form action="/karyawan/{{$d->nik}}/delete" method="POST">
                                                        @csrf
                                                        <a class="btn btn-danger btn-sm delete-confirm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M4 7l16 0" />
                                                                <path d="M10 11l0 6" />
                                                                <path d="M14 11l0 6" />
                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                            </svg>
                                                        </a>
                                                    </form>
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
                            <div class="form-label">NIK</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="John Doe">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-label">Email</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-phone">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="no_hp" id="no_hp" placeholder="No HP">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Tanggal Masuk</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
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
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Date Of Birth</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
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
                        <div class="col-6">
                            <div class="mb-3">
                                <div class="form-label">Foto Karyawan</div>
                                <input type="file" class="form-control" name="foto" id="foto" accept=".png, .jpg, .jpeg">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Department</div>
                            <select name="kode_dept" id="kode_dept" class="form-select">
                                <option value="">Pilih</option>
                                @foreach ($department as $d)
                                <option {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Jabatan</div>
                            <select name="jabatan" id="jabatan" class="form-select">
                                <option value="">Pilih</option>
                                @foreach ($jabatan as $d)
                                <option {{ Request('id') == $d->id ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-label">Level</div>
                            <select name="level" id="level" class="form-select">
                                <option value="">Pilih</option>
                                <option {{ Request('level') == 'Officer' ? 'selected' : '' }} value="Officer">Officer</option>
                                <option {{ Request('level') == 'Manager' ? 'selected' : '' }} value="Manager">Manager</option>
                                <option {{ Request('level') == 'HRD' ? 'selected' : '' }} value="HRD">HRD</option>
                                <option {{ Request('level') == 'Management' ? 'selected' : '' }} value="Management">Management</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Atasan</div>
                            <select name="nik_atasan" id="nik_atasan" class="form-select">
                                <option value="">Pilih</option>
                                @foreach ($atasan as $d)
                                <option {{ Request('nik_atasan') == $d->nik ? 'selected' : '' }} value="{{ $d->nik }}">{{ $d->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <!-- Additional Fields Start Here -->
                        <div class="col-6">
                            <div class="form-label">Grade</div>
                            <input type="text" value="" class="form-control" name="grade" id="grade" placeholder="Grade">
                        </div>
                        <div class="col-6">
                            <div class="form-label">Employee Status</div>
                            <input type="text" value="" class="form-control" name="employee_status" id="employee_status" placeholder="Employee Status">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Base POH</div>
                            <input type="text" value="" class="form-control" name="base_poh" id="base_poh" placeholder="Base POH">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Nama PT</div>
                            <input type="text" value="" class="form-control" name="nama_pt" id="nama_pt" placeholder="Nama PT">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Sex</div>
                            <input type="text" value="" class="form-control" name="sex" id="sex" placeholder="Sex">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Marital Status</div>
                            <input type="text" value="" class="form-control" name="marital_status" id="marital_status" placeholder="Marital Status">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Birthplace</div>
                            <input type="text" value="" class="form-control" name="birthplace" id="birthplace" placeholder="Birthplace">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Religion</div>
                            <input type="text" value="" class="form-control" name="religion" id="religion" placeholder="Religion">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Address</div>
                            <input type="text" value="" class="form-control" name="address" id="address" placeholder="Address">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Address RT</div>
                            <input type="text" value="" class="form-control" name="address_rt" id="address_rt" placeholder="Address RT">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Address RW</div>
                            <input type="text" value="" class="form-control" name="address_rw" id="address_rw" placeholder="Address RW">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Address Kel</div>
                            <input type="text" value="" class="form-control" name="address_kel" id="address_kel" placeholder="Address Kel">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Address Kec</div>
                            <input type="text" value="" class="form-control" name="address_kec" id="address_kec" placeholder="Address Kec">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Address Kota</div>
                            <input type="text" value="" class="form-control" name="address_kota" id="address_kota" placeholder="Address Kota">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Address Prov</div>
                            <input type="text" value="" class="form-control" name="address_prov" id="address_prov" placeholder="Address Prov">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Kode Pos</div>
                            <input type="text" value="" class="form-control" name="kode_pos" id="kode_pos" placeholder="Kode Pos">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">NIK KTP</div>
                            <input type="text" value="" class="form-control" name="nik_ktp" id="nik_ktp" placeholder="NIK KTP">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Blood Type</div>
                            <input type="text" value="" class="form-control" name="blood_type" id="blood_type" placeholder="Blood Type">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Gelar</div>
                            <input type="text" value="" class="form-control" name="gelar" id="gelar" placeholder="Gelar">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Major</div>
                            <input type="text" value="" class="form-control" name="major" id="major" placeholder="Major">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Kampus</div>
                            <input type="text" value="" class="form-control" name="kampus" id="kampus" placeholder="Kampus">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Job Experience</div>
                            <input type="text" value="" class="form-control" name="job_exp" id="job_exp" placeholder="Job Experience">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Email Personal</div>
                            <input type="text" value="" class="form-control" name="email_personal" id="email_personal" placeholder="Email Personal">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Family Card</div>
                            <input type="text" value="" class="form-control" name="family_card" id="family_card" placeholder="Family Card">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">No NPWP</div>
                            <input type="text" value="" class="form-control" name="no_npwp" id="no_npwp" placeholder="No NPWP">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Alamat NPWP</div>
                            <input type="text" value="" class="form-control" name="alamat_npwp" id="alamat_npwp" placeholder="Alamat NPWP">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">BPJS TK</div>
                            <input type="text" value="" class="form-control" name="bpjstk" id="bpjstk" placeholder="BPJS TK">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">BPJS Kes</div>
                            <input type="text" value="" class="form-control" name="bpjskes" id="bpjskes" placeholder="BPJS Kes">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Rekening No</div>
                            <input type="text" value="" class="form-control" name="rek_no" id="rek_no" placeholder="Rekening No">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Bank Name</div>
                            <input type="text" value="" class="form-control" name="bank_name" id="bank_name" placeholder="Bank Name">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Rekening Name</div>
                            <input type="text" value="" class="form-control" name="rek_name" id="rek_name" placeholder="Rekening Name">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Father's Name</div>
                            <input type="text" value="" class="form-control" name="father_name" id="father_name" placeholder="Father's Name">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Mother's Name</div>
                            <input type="text" value="" class="form-control" name="mother_name" id="mother_name" placeholder="Mother's Name">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD SI Name</div>
                            <input type="text" value="" class="form-control" name="fd_si_name" id="fd_si_name" placeholder="FD SI Name">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD SI NIK</div>
                            <input type="text" value="" class="form-control" name="fd_si_nik" id="fd_si_nik" placeholder="FD SI NIK">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD SI Kota</div>
                            <input type="text" value="" class="form-control" name="fd_si_kota" id="fd_si_kota" placeholder="FD SI Kota">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD SI DOB</div>
                            <input type="date" value="" class="form-control" name="fd_si_dob" id="fd_si_dob" placeholder="FD SI DOB">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD Anak 1 Name</div>
                            <input type="text" value="" class="form-control" name="fd_anak1_name" id="fd_anak1_name" placeholder="FD Anak 1 Name">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD Anak 1 NIK</div>
                            <input type="text" value="" class="form-control" name="fd_anak1_nik" id="fd_anak1_nik" placeholder="FD Anak 1 NIK">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD Anak 1 Kota</div>
                            <input type="text" value="" class="form-control" name="fd_anak1_kota" id="fd_anak1_kota" placeholder="FD Anak 1 Kota">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD Anak 1 DOB</div>
                            <input type="date" value="" class="form-control" name="fd_anak1_dob" id="fd_anak1_dob" placeholder="FD Anak 1 DOB">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD Anak 2 Name</div>
                            <input type="text" value="" class="form-control" name="fd_anak2_name" id="fd_anak2_name" placeholder="FD Anak 2 Name">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD Anak 2 NIK</div>
                            <input type="text" value="" class="form-control" name="fd_anak2_nik" id="fd_anak2_nik" placeholder="FD Anak 2 NIK">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD Anak 2 Kota</div>
                            <input type="text" value="" class="form-control" name="fd_anak2_kota" id="fd_anak2_kota" placeholder="FD Anak 2 Kota">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD Anak 2 DOB</div>
                            <input type="date" value="" class="form-control" name="fd_anak2_dob" id="fd_anak2_dob" placeholder="FD Anak 2 DOB">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD Anak 3 Name</div>
                            <input type="text" value="" class="form-control" name="fd_anak3_name" id="fd_anak3_name" placeholder="FD Anak 3 Name">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD Anak 3 NIK</div>
                            <input type="text" value="" class="form-control" name="fd_anak3_nik" id="fd_anak3_nik" placeholder="FD Anak 3 NIK">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD Anak 3 Kota</div>
                            <input type="text" value="" class="form-control" name="fd_anak3_kota" id="fd_anak3_kota" placeholder="FD Anak 3 Kota">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">FD Anak 3 DOB</div>
                            <input type="date" value="" class="form-control" name="fd_anak3_dob" id="fd_anak3_dob" placeholder="FD Anak 3 DOB">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Emergency Contact Name</div>
                            <input type="text" value="" class="form-control" name="em_name" id="em_name" placeholder="Emergency Contact Name">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Emergency Contact Phone</div>
                            <input type="text" value="" class="form-control" name="em_telp" id="em_telp" placeholder="Emergency Contact Phone">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Emergency Contact Relation</div>
                            <input type="text" value="" class="form-control" name="em_relation" id="em_relation" placeholder="Emergency Contact Relation">
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-label">Emergency Contact Address</div>
                            <input type="text" value="" class="form-control" name="em_alamat" id="em_alamat" placeholder="Emergency Contact Address">
                        </div>
                    </div>
                    <!-- Additional Fields End Here -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
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
@endsection

@push('myscript')
<script>
    $(function() {
        $('#btnTambahKaryawan').click(function() {
            $('#modal-inputkaryawan').modal("show");
        });

        $('.edit').click(function() {
            var nik = $(this).attr('nik');
            $.ajax({
                type: 'POST',
                url: '/karyawan/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token();}}",
                    nik: nik
                },
                success: function(respond) {
                    $('#loadeditform').html(respond);
                }
            });
            $('#modal-editkaryawan').modal("show");
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
            var no_hp = $('#no_Hp').val();
            var kode_dept = $("formKaryawan").find('#kode_dept').val();
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
            } else if (no_hp == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Nomer HP Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#no_hp').focus();
                });
                return false;
            }
        });
    });
</script>
@endpush
