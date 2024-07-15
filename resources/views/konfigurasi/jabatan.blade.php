@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Konfigurasi
                </div>
                <h2 class="page-title">
                    Jabatan
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
                        <div class="row">
                            <div class="col-12">
                                <a href="#" class="btn btn-primary" id="btnTambahJab">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Jabatan
                                </a>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Jabatan</th>
                                            <th>Department</th>
                                            <th>Jabatan Atasan</th>
                                            <th>Site</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jabatan as $d)
                                        <tr>
                                            <td>{{ $d->id}}</td>
                                            <td>{{ $d->nama_jabatan}}</td>
                                            <td>{{ $d->nama_dept}}</td>
                                            <td>{{ $d->jabatan_atasan}}</td>
                                            <td>{{ $d->site}}</td>
                                            <td>
                                                <div class="form-group">
                                                    <a href="#" class="edit btn btn-info btn-sm" id_jabatan="{{ $d->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                            <path d="M16 5l3 3" />
                                                        </svg>
                                                    </a>
                                                    <form action="/konfigurasi/jabatan/{{$d->id}}/delete" method="POST">
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
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $jabatan->links('vendor.pagination.bootstrap-5')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-inputjab" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/konfigurasi/jabatan/store" method="POST" id="formJabatan">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">Nama Jabatan</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-letter-case">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M17.5 15.5m-3.5 0a3.5 3.5 0 1 0 7 0a3.5 3.5 0 1 0 -7 0" />
                                        <path d="M3 19v-10.5a3.5 3.5 0 0 1 7 0v10.5" />
                                        <path d="M3 13h7" />
                                        <path d="M21 12v7" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="nama_jabatan" id="nama_jabatan" placeholder="Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">Department</div>
                            <select name="kode_dept" id="kode_dept" class="form-select">
                                <option value="">Pilih</option>
                                @foreach ($department as $d)
                                <option {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-label">Jabatan Atasan</div>
                            <select name="atasan_jabatan" id="atasan_jabatan" class="form-select">
                                <option value="">Pilih</option>
                                @foreach ($jabat as $d)
                                <option {{ Request('nama_jabatan') == $d->nama_jabatan ? 'selected' : '' }} value="{{ $d->nama_jabatan }}">{{ $d->nama_jabatan }} - {{ $d->site }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-label">Site</div>
                            <select name="site" id="site" class="form-select">
                                <option value="">Pilih</option>
                                @foreach ($location as $d)
                                <option {{ Request('nama_kantor') == $d->nama_kantor ? 'selected' : '' }} value="{{ $d->nama_kantor }}">{{ $d->nama_kantor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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
<div class="modal modal-blur fade" id="modal-editjab" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadedjab">

            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        $('#btnTambahJab').click(function() {
            $('#modal-inputjab').modal("show");
        });

        $('.edit').click(function() {
            var id = $(this).attr('id_jabatan');
            $.ajax({
                type: 'POST',
                url: '/konfigurasi/jabatan/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(respond) {
                    $('#loadedjab').html(respond);

                }
            });
            $('#modal-editjab').modal("show");
        });


        $(".delete-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Yakin?",
                text: "Data Jabatan Akan Ke Delete!",
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

        $('#formJabatan').submit(function() {
            var nama_jabatan = $('#nama_jabatan').val();
            var kode_dept = $('#kode_dept').val();
            if (nama_jabatan == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Nama Jabatan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#nama_jabatan').focus();
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
    });
</script>
@endpush
