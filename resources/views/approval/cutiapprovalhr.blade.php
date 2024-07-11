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
                    Approval
                </div>
                <h2 class="page-title">
                    Approval Pengajuan Cuti HR
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
                            <form action="/approval/cutiapprovalhrd" method="GET" autocomplete="off">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-month">
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
                                                <input type="text" value="{{ Request('dari')}}" class="form-control" name="dari" id="dari" placeholder="Dari">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-month">
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
                                                <input type="text" value="{{ Request('sampai')}}" class="form-control" name="sampai" id="sampai" placeholder="Sampai">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
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
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <select name="status_approved_hrd" id="status_approved_hrd" class="form-select">
                                                    <option value="pilih" {{ request('status_approved_hrd') === 'pilih' ? 'selected' : '' }}>Pilih Status HRD</option>
                                                    <option value="0" {{ request('status_approved_hrd') === '0' ? 'selected' : '' }}>Pending</option>
                                                    <option value="1" {{ request('status_approved_hrd') === '1' ? 'selected' : '' }}>Approved</option>
                                                    <option value="2" {{ request('status_approved_hrd') === '2' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <div class="form-group">
                                                <button class="btn btn-primary w-100" type="submit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
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
                                            <th>Status Manager <br>-------------------<br> Status HRD </th>
                                            <th>Aksi</th>
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
                                                @if ($d->status_approved == 1)
                                                <span class="badge bg-success" style="color: white; width:90px">Approved</span>
                                                @elseif ($d->status_approved == 0)
                                                <span class="badge bg-yellow" style="color: white; width:90px">Pending</span>
                                                @else
                                                <span class="badge bg-red" style="color: white; width:90px">Rejected</span>
                                                @endif
                                                @if ($d->status_approved_hrd == 1)
                                                <span class="badge bg-success mt-1" style="color: white; width:90px">Approved</span>
                                                @elseif ($d->status_approved_hrd == 0)
                                                <span class="badge bg-yellow mt-1" style="color: white; width:90px">Pending</span>
                                                @else
                                                <span class="badge bg-red mt-1" style="color: white; width:90px">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($d->status_approved_hrd == 0)
                                                <a href="#" class="badge bg-primary btnApprove" style="width:100px; justify-content:space-between" data-id="{{ $d->id }}" data-nik="{{ $d->nik}}" data-periode="{{ $d->periode }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="margin:0;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checkbox">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M9 11l3 3l8 -8" />
                                                        <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                                                    </svg>
                                                    Approve
                                                </a>
                                                @else
                                                <a href="#" class="badge bg-danger btnBatalApprove" style="width:100px" id="btnBatalApprove" data-id="{{ $d->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="margin:0;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checkbox">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M9 11l3 3l8 -8" />
                                                        <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                                                    </svg>
                                                    Batalkan
                                                </a>
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
<div class="modal modal-blur fade" id="modal-cutiapproval" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approval Pengajuan Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/approval/approvecutihrd" method="POST">
                    @csrf
                    <input type="hidden" id="id_cuti_form" name="id_cuti_form">
                    <input type="hidden" id="nik_cuti_form" name="nik_cuti_form">
                    <input type="hidden" id="periode_cuti_form" name="periode_cuti_form">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="status_approved_hrd" id="status_approved_hrd" class="form-select">
                                    <option value="1">Disetujui</option>
                                    <option value="2">Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group" id="keputusanContainer" style="display: none;">
                                <input placeholder="Keputusan" class="form-control" type="text"  id="keputusan" name="keputusan"/>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100" id="submitBtn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-send-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4.698 4.034l16.302 7.966l-16.302 7.966a.503 .503 0 0 1 -.546 -.124a.555 .555 0 0 1 -.12 -.568l2.468 -7.274l-2.468 -7.274a.555 .555 0 0 1 .12 -.568a.503 .503 0 0 1 .546 -.124z" />
                                        <path d="M6.5 12h14.5" />
                                    </svg>
                                    Submit
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
        $("#dari").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
        $("#sampai").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        $("[id='status_approved_hrd']").change(function() {
            var selectedStatus = $(this).val();
            if (selectedStatus === "2") {
                $("#keputusanContainer").show();
            } else {
                $("#keputusanContainer").hide();
            }
        });

        $(document).on('click', '.btnApprove', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            var nik =  $(this).data("nik");
            var periode =  $(this).data("periode");
            $('#id_cuti_form').val(id);
            $('#nik_cuti_form').val(nik);
            $('#periode_cuti_form').val(periode);
            $('#modal-cutiapproval').modal("show");
        });

        // Submit button click event
        document.getElementById('submitBtn').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default form submission
            $('#modal-cutiapproval').modal("hide");
            // Show confirmation dialog
            Swal.fire({
                title: 'Apakah Yakin ?',
                text: "Form ini Akan Ke Approve!",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Approve!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form when confirmed
                    $('#modal-cutiapproval form').submit();
                } else {
                    $('#modal-cutiapproval').modal("show");
                }
            });
        });

        $(document).on('click', '.btnBatalApprove', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '/approval/batalapprovecutihrd/' + id,
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Success!',
                                    'Approval has been cancelled.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    response.message,
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
