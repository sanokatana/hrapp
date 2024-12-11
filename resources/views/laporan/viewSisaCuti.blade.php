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
                    Cuti Karywan
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
@elseif(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Error!',
            text: "{{ session('error') }}",
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
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/cuti" method="GET">
                                    <div class="row">
                                    <div class="col-2">
                                            <div class="form-group">
                                                <input type="text" name="nama_kar" id="nama_kar" class="form-control" placeholder="Nama Karyawan" autocomplete="off" value="{{ Request('nama_kar')}}">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <input type="text" name="nik_req" id="nik_req" class="form-control" placeholder="NIK" autocomplete="off" value="{{ Request('nik_req')}}">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <input type="text" name="tahun_req" id="tahun_req" class="form-control" placeholder="Tahun" autocomplete="off" value="{{ Request('tahun_req')}}">
                                            </div>
                                        </div>
                                        <div class="col-2">
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
                                                <select name="status" id="status" class="form-select">
                                                    <option value="pilih" {{ request('status') === 'pilih' ? 'selected' : '' }}>Pilih Status</option>
                                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Non Aktif</option>
                                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2">
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
                        <div class="row mt-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>No</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Department</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Periode Cuti</th>
                                            <th>Periode Awal</th>
                                            <th>Periode Akhir</th>
                                            <th>Sisa Cuti</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cuti as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $loop->iteration + $cuti->firstItem() -1 }}</td>
                                            <td>{{ $d->nik}}</td>
                                            <td>{{ $d->nama_lengkap}}</td>
                                            <td>{{ $d->nama_dept}}</td>
                                            <td>{{ $d->tgl_masuk}}</td>
                                            <td>{{ $d->tahun}}</td>
                                            <td>{{ $d->periode_awal}}</td>
                                            <td>{{ $d->periode_akhir}}</td>
                                            <td>{{ $d->sisa_cuti}}</td>
                                            <td>
                                                @if ($d->status == 0)
                                                <span class="badge bg-danger" style="color: white">Non Aktif</span>
                                                @else
                                                <span class="badge bg-success" style="color: white">Aktif</span>
                                                @endif
                                            </td>
                                            @endforeach
                                    </tbody>
                                </table>
                                {{ $cuti->links('vendor.pagination.bootstrap-5')}}
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
</script>
@endpush
