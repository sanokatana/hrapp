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
                    Laporan
                </div>
                <h2 class="page-title">
                    Karyawan Cuti
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
            icon: 'error',
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
                                <form action="/laporan/viewCuti" method="GET" autocomplete="off">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-month">
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
                                                <input type="date" value="{{ Request('dari')}}" class="form-control" name="dari" id="dari" placeholder="Dari">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-month">
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
                                                <input type="date" value="{{ Request('sampai')}}" class="form-control" name="sampai" id="sampai" placeholder="Sampai">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                    </svg>
                                                </span>
                                                <input type="text" value="{{ Request('nik')}}" class="form-control" name="nik" id="nik" placeholder="NIK">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                    </svg>
                                                </span>
                                                <input type="text" value="{{ Request('nama_lengkap')}}" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Nama Karyawan">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <select name="status_approved" id="status_approved" class="form-select">
                                                    <option value="pilih" {{ request('status_approved') === 'pilih' ? 'selected' : '' }}>Pilih Status Manager</option>
                                                    <option value="0" {{ request('status_approved') === '0' ? 'selected' : '' }}>Pending</option>
                                                    <option value="1" {{ request('status_approved') === '1' ? 'selected' : '' }}>Approved</option>
                                                    <option value="2" {{ request('status_approved') === '2' ? 'selected' : '' }}>Rejected</option>
                                                    <option value="2" {{ request('status_approved') === '3' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <select name="status_approved_hrd" id="status_approved_hrd" class="form-select">
                                                    <option value="pilih" {{ request('status_approved_hrd') === 'pilih' ? 'selected' : '' }}>Pilih Status Manager</option>
                                                    <option value="0" {{ request('status_approved_hrd') === '0' ? 'selected' : '' }}>Pending</option>
                                                    <option value="1" {{ request('status_approved_hrd') === '1' ? 'selected' : '' }}>Approved</option>
                                                    <option value="2" {{ request('status_approved_hrd') === '2' ? 'selected' : '' }}>Rejected</option>
                                                    <option value="2" {{ request('status_approved_hrd') === '3' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button class="btn btn-primary w-100" type="submit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
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
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>No.</th>
                                            <th>NIK.</th>
                                            <th>Nama Karyawan</th>
                                            <th>Jabatan</th>
                                            <th>Mulai Kerja</th>
                                            <th>Tanggal Cuti</th>
                                            <th>Sampai Tanggal</th>
                                            <th>Periode</th>
                                            <th>Sisa Cuti</th>
                                            <th>Sisa Cuti Master</th>
                                            <th>Jumlah Hari</th>
                                            <th>Sisa Setelah</th>
                                            <th>Kar Pengganti</th>
                                            <th>Note</th>
                                            <th>Jenis Cuti</th>
                                            <th>Tipe Cuti</th>
                                            <th> HRD <br>-------------------<br> Manager <br>-------------------<br> Management</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cutiapproval as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $loop->iteration + $cutiapproval->firstItem() -1}} </td>
                                            <td>{{ $d->nik }} </td>
                                            <td>{{ $d->nama_lengkap }}</td>
                                            <td>{{ $d->nama_jabatan }}</td>
                                            <td>{{ DateHelper::formatIndonesianDate($d->tgl_masuk) }}</td>
                                            <td>@if ($d->tgl_cuti)
                                                {{ DateHelper::formatIndonesianDate($d->tgl_cuti) }}
                                                @endif
                                            </td>
                                            <td>@if ($d->tgl_cuti_sampai)
                                                {{ DateHelper::formatIndonesianDate($d->tgl_cuti_sampai) }}
                                                @endif
                                            </td>
                                            <td>{{ $d->periode }}</td>
                                            <td>{{ $d->sisa_cuti }}</td>
                                            <td>@if (!is_null($d->sisa_cuti))
                                                <span class="badge bg-warning" style="color: white; width:30px">{{ $d->sisa_cuti_real}}</span>
                                                @endif
                                            </td>
                                            <td>{{ $d->jml_hari }} </td>
                                            <td>{{ $d->sisa_cuti_setelah}} </td>
                                            <td>{{ $d->kar_ganti}} </td>
                                            <td>{{ $d->note }}</td>
                                            <td>{{ $d->jenis }}</td>
                                            <td>{{ $d->tipe_cuti }}</td>
                                            <td>
                                                @if ($d->status_approved_hrd == 1)
                                                <span class="badge bg-success mt-1" style="color: white; width:90px">Approved</span>
                                                @elseif ($d->status_approved_hrd == 0)
                                                <span class="badge bg-yellow mt-1" style="color: white; width:90px">Pending</span>
                                                @elseif ($d->status_management == 2)
                                                <span class="badge bg-red mt-1" style="color: white; width:90px">Rejected</span>
                                                @else
                                                <span class="badge bg-red" style="color: white; width:90px">Cancelled</span>
                                                @endif
                                                <br>
                                                @if ($d->status_approved == 1)
                                                <span class="badge bg-success" style="color: white; width:90px">Approved</span>
                                                @elseif ($d->status_approved == 0)
                                                <span class="badge bg-yellow" style="color: white; width:90px">Pending</span>
                                                @elseif ($d->status_approved == 2)
                                                <span class="badge bg-red" style="color: white; width:90px">Rejected</span>
                                                @else
                                                <span class="badge bg-red" style="color: white; width:90px">Cancelled</span>
                                                @endif
                                                <br>
                                                @if ($d->status_management == 1)
                                                <span class="badge bg-success mt-1" style="color: white; width:90px">Approved</span>
                                                @elseif ($d->status_management == 0)
                                                <span class="badge bg-yellow mt-1" style="color: white; width:90px">Pending</span>
                                                @elseif ($d->status_management == 2)
                                                <span class="badge bg-red mt-1" style="color: white; width:90px">Rejected</span>
                                                @else
                                                <span class="badge bg-red" style="color: white; width:90px">Cancelled</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $cutiapproval->links('vendor.pagination.bootstrap-5')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
@endpush
