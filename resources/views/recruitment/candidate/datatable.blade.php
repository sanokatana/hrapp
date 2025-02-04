@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Recruitment
                </div>
                <h2 class="page-title">
                    Candidate Data
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
                    <div class="row mt-2">
                            <div class="col-12">
                                <form action="/recruitment/candidate/data" method="GET">
                                    <div class="row">
                                        <div class="col-3 col-lg-3">
                                            <div class="form-group">
                                                <input type="text" name="nama_candidate" id="nama_candidate" class="form-control" placeholder="Nama Candidate" value="{{ request('nama_candidate') }}">
                                            </div>
                                        </div>
                                        <div class="col-3 col-lg-3">
                                            <div class="form-group mb-3">
                                                <select name="title_job" id="title_job" class="form-select">
                                                    <option value="">Job Opening</option>
                                                    @foreach ($job as $d)
                                                        <option {{ request('title_job') == $d->title ? 'selected' : '' }} value="{{ $d->title }}">{{ $d->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3 col-lg-3">
                                            <div class="form-group mb-3">
                                                <select name="status_candidate" id="status_candidate" class="form-select">
                                                    <option value="">Status Candidate</option>
                                                    <option value="In Process">In Process</option>
                                                    <option value="Hired">Hired</option>
                                                    <option value="Rejected">Rejected</option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-3 col-lg-3">
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
                                            <th>Nama Candidate</th>
                                            <th>Job Opening</th>
                                            <th>Current Stage</th>
                                            <th>Status Form Data</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $d->id}}</td>
                                            <td>{{ $d->candidate_name}}</td>
                                            <td>{{ $d->job_title}}</td>
                                            <td>{{ $d->stage_name}}</td>
                                            <td>{{ $d->status_form}}</td>
                                            <td>{{ $d->status_candidate}}</td>
                                            <td>
                                                <!-- Each button on a separate row within the same cell -->
                                                <div class="form-group">
                                                    <!-- Edit Button -->
                                                    <div class="mb-1">
                                                        <a href="#" class="view btn btn-info btn-sm" candidate_id="{{ $d->candidate_id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <!-- Time Button -->
                                                    <div class="mb-1">
                                                        <a href="#" class="approve btn btn-success btn-sm" id="{{ $d->id }}" data-status="{{ $d->status_form }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checkbox">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M9 11l3 3l8 -8" />
                                                                <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <!-- To Karyawan -->
                                                    <div class="mb-1">
                                                        <a href="#" class="btn btn-primary btn-sm peningkatan"
                                                            id="{{ $d->id }}">
                                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 18 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-rotate-rectangle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.09 4.01l.496 -.495a2 2 0 0 1 2.828 0l7.071 7.07a2 2 0 0 1 0 2.83l-7.07 7.07a2 2 0 0 1 -2.83 0l-7.07 -7.07a2 2 0 0 1 0 -2.83l3.535 -3.535h-3.988" /><path d="M7.05 11.038v-3.988" /></svg>
                                                        </a>
                                                    </div>
                                                    <form action="/recruitment/data/{{$d->id}}/print" method="POST" class="mb-1">
                                                        @csrf
                                                        <a class="btn btn-warning btn-sm print-confirm">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 18 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                                                        </a>
                                                    </form>
                                                    <!-- Delete Button -->
                                                    <div>
                                                        <form action="/recruitment/data/{{$d->id}}/delete" method="POST" style="display: inline;">
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
                                        </tr>
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
<!-- Modal Edit -->
<div class="modal modal-blur fade" id="modal-approveData" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Candidate Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm" action="/recruitment/candidate/data/approve" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="candidate_id">
                    <div class="mb-3">
                        <select name="status_form" id="status_form" class="form-select">
                            <option value="Pending">Pending</option>
                            <option value="Declined">Declined</option>
                            <option value="Verified">Verified</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal View -->
<div class="modal modal-blur fade" id="modal-viewData" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full-width modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Candidate Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadView">

            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-skContract" tabindex="-1" role="dialog" aria-labelledby="modal-skContractLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-skContractLabel">Peningkatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/recruitment/candidate/data/peningkatan" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="dataCandidate" id="dataCandidate">
                    <div class="row">
                        <div class="col-6">
                            <!-- NIK input field -->
                            <div class="form-label">NIK</div>
                            <div class="input-icon mb-3 mt-2">
                                <input type="text" class="form-control" name="nik" id="nik" placeholder="xxx-2024xx" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Opsi</div>
                            <!-- Radio buttons for Automatic or Manual NIK input -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="nik_option" id="nik_auto" value="automatic" checked>
                                <label class="form-check-label" for="nik_auto">Automatic</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="nik_option" id="nik_manual" value="manual">
                                <label class="form-check-label" for="nik_manual">Manual</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                    <div class="col-6">
                            <div class="form-label">NIP</div>
                            <div class="input-icon mb-3">
                                <input type="text" value="" class="form-control" name="nip" id="nip" placeholder="xxxx">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Jabatan</div>
                            <select name="jabatan" id="jabatan" class="form-select">
                                <option value="">Pilih</option>
                                @foreach ($jabatan as $d)
                                <option {{ Request('id') == $d->id ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->nama_jabatan }} - {{ $d->site }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Tgl Masuk</div>
                            <div class="input-icon mb-3">
                                <input type="date" value="" class="form-control" name="tgl_masuk" id="nitgl_masukp" placeholder="xx-xx-xxxx">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Employee Status</div>
                            <select name="employee_status" id="employee_status" class="form-select">
                                <option value="">Pilih</option>
                                <option value="Kontrak">Kontrak</option>
                                <option value="Tetap">Tetap</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">

                    <div class="col-6">
                            <div class="form-label">Grade</div>
                            <div class="input-icon mb-3">
                                <input type="text" value="" class="form-control" name="grade" id="grade" placeholder="B/H/NS">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Base POH</div>
                            <select name="base" id="base" class="form-select">
                                <option value="">Base</option>
                                @foreach ($uniqueBase as $base)
                                <option {{ request('base') == $base ? 'selected' : '' }} value="{{ $base }}">{{ $base }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Nama PT</div>
                            <div class="input-icon mb-3">
                                <input type="text" value="" class="form-control" name="nama_pt" id="nama_pt" placeholder="XXX">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Religion</div>
                            <div class="input-icon mb-3">
                                <input type="text" value="" class="form-control" name="religion" id="religion" placeholder="XXX">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-label">Rek No</div>
                            <div class="input-icon mb-3">
                                <input type="text" value="" class="form-control" name="rek_no" id="rek_no" placeholder="XXXXXX">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-label">Bank Name</div>
                            <div class="input-icon mb-3">
                                <input type="text" value="" class="form-control" name="bank_name" id="bank_name" placeholder="XXX">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-label">Rek Name</div>
                            <div class="input-icon mb-3">
                                <input type="text" value="" class="form-control" name="rek_name" id="rek_name" placeholder="XXXX">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-refresh">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
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

    $(document).ready(function() {
        // Toggle NIK input field based on selected option
        $('input[name="nik_option"]').change(function() {
            if ($('#nik_auto').is(':checked')) {
                $('#nik').prop('readonly', true).val(''); // Set NIK to read-only and clear value
            } else {
                $('#nik').prop('readonly', false); // Enable NIK field for manual input
            }
        });
    });


    $(document).ready(function() {
        // When the approve button is clicked
        $('.approve').click(function() {
            var candidateId = $(this).attr('id'); // Get candidate ID
            var currentStatus = $(this).data('status'); // Get current status from data-status attribute

            // Set the candidate ID in the hidden input
            $('#candidate_id').val(candidateId);

            // Preselect the current status in the dropdown
            $('#status_form').val(currentStatus);

            // Show the modal
            $('#modal-approveData').modal('show');
        });
    });

    $('.peningkatan').click(function() {
            var id = $(this).attr('id');
            $('#dataCandidate').val(id);
            $('#modal-skContract').modal("show");
        });

    $(function() {
        $('.view').click(function() {
            var candidate_id = $(this).attr('candidate_id');
            $.ajax({
                type: 'POST',
                url: '/recruitment/candidate/data/view',
                cache: false,
                data: {
                    _token: "{{ csrf_token();}}",
                    candidate_id: candidate_id
                },
                success: function(respond) {
                    $('#loadView').html(respond);
                }
            });
            $('#modal-viewData').modal("show");
        });

        $(".print-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Yakin?",
                text: "Data Akan Ke Print!",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Continue"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        $(".delete-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Yakin?",
                text: "Candidate Data Akan Ke Delete!",
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
    });
</script>
@endpush
