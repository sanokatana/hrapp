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
                    Pipeline
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
            title: 'Sudah!',
            text: "{{ session('danger') }}",
            icon: 'warning',
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
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs nav-fill" data-bs-toggle="tabs" role="tablist">
                                @foreach ($recruitmentData as $key => $data)
                                <li class="nav-item" role="presentation">
                                    <a href="#tab-{{ $data['type']->id }}"
                                        class="nav-link {{ $key === 0 ? 'active' : '' }}" style="font-weight: bold; font-size: 16px;" data-bs-toggle="tab"
                                        aria-selected="{{ $key === 0 ? 'true' : 'false' }}" role="tab">
                                        {{ $data['type']->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                @foreach ($recruitmentData as $key => $data)
                                <div class="tab-pane {{ $key === 0 ? 'active show' : '' }}"
                                    id="tab-{{ $data['type']->id }}" role="tabpanel">
                                    @foreach ($data['stagesWithCandidates'] as $stageData)
                                    <div class="row mt-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4>{{ $stageData['stage']->name }} Stage</h4>
                                                <table class="table table-vcenter card-table table-striped"
                                                    style="table-layout: fixed; width: 100%;">
                                                    <thead>
                                                        <tr style="text-align: center;">
                                                            <th>Nama Candidate</th>
                                                            <th>Email</th>
                                                            <th>Job Opening</th>
                                                            <th> Status Data <br>-------------------<br> Status Recruitment </th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($stageData['candidates'] as $candidate)
                                                        <tr style="text-align: center;">
                                                            <td>{{ $candidate->nama_candidate }}</td>
                                                            <td>{{ $candidate->email }}</td>
                                                            <td>{{ $candidate->job_title }}</td>
                                                            <td>
                                                                @if($candidate->status_form == 'Verified')
                                                                <span class="badge bg-success" style="color: white; width:120px">Verified</span>
                                                                @elseif ($candidate->status_form == 'Pending')
                                                                <span class="badge bg-warning" style="color: white; width:120px">Pending</span>
                                                                @elseif ($candidate->status_form == 'Declined')
                                                                <span class="badge bg-danger" style="color: white; width:120px">Declined</span>
                                                                @endif
                                                                <br>
                                                                <span class="badge bg-{{ $candidate->status == 'In Process' ? 'primary' : ($candidate->status == 'Rejected' ? 'danger' : 'success') }}" style="color: white; width:120px">
                                                                    {{ $candidate->status }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <!-- Proceed to next stage button -->
                                                                <form
                                                                    action="/recruitment/candidate/{{$candidate->id}}/next"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <a type="submit"
                                                                        class="btn btn-success btn-sm next-confirm"
                                                                        style="height:30px; width:30px"><svg
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24" viewBox="0 0 20 24"
                                                                            fill="currentColor"
                                                                            class="icon icon-tabler icons-tabler-filled icon-tabler-player-track-next">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none" />
                                                                            <path
                                                                                d="M2 5v14c0 .86 1.012 1.318 1.659 .753l8 -7a1 1 0 0 0 0 -1.506l-8 -7c-.647 -.565 -1.659 -.106 -1.659 .753z" />
                                                                            <path
                                                                                d="M13 5v14c0 .86 1.012 1.318 1.659 .753l8 -7a1 1 0 0 0 0 -1.506l-8 -7c-.647 -.565 -1.659 -.106 -1.659 .753z" />
                                                                        </svg>
                                                                    </a>
                                                                </form>

                                                                <!-- Previous stage button -->
                                                                <form
                                                                    action="/recruitment/candidate/{{$candidate->id}}/back"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <a type="submit"
                                                                        class="btn btn-secondary btn-sm back-confirm"
                                                                        style="height:30px; width:30px"><svg
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24" viewBox="0 0 20 24"
                                                                            fill="currentColor"
                                                                            class="icon icon-tabler icons-tabler-filled icon-tabler-player-track-prev">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none" />
                                                                            <path
                                                                                d="M20.341 4.247l-8 7a1 1 0 0 0 0 1.506l8 7c.647 .565 1.659 .106 1.659 -.753v-14c0 -.86 -1.012 -1.318 -1.659 -.753z" />
                                                                            <path
                                                                                d="M9.341 4.247l-8 7a1 1 0 0 0 0 1.506l8 7c.647 .565 1.659 .106 1.659 -.753v-14c0 -.86 -1.012 -1.318 -1.659 -.753z" />
                                                                        </svg>
                                                                    </a>
                                                                </form>

                                                                <!-- Reject button -->

                                                                <button type="button" style="height:30px; width:30px" class="btn btn-danger btn-sm reject-confirm" data-candidate-id="{{ $candidate->id }}" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                                                    <svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24" height="24" viewBox="0 0 20 24"
                                                                        fill="none" stroke="currentColor"
                                                                        stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-ban">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                                        <path d="M5.7 5.7l12.6 12.6" />
                                                                    </svg>
                                                                </button>

                                                                <!-- Interview button -->

                                                                <a href="#" style="height:30px; width:30px" class="interview btn btn-info btn-sm" id="{{ $candidate->id }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 24" fill="none" stroke="currentColor"
                                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-stats">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                        <path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4" />
                                                                        <path d="M18 14v4h4" />
                                                                        <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                                                        <path d="M15 3v4" />
                                                                        <path d="M7 3v4" />
                                                                        <path d="M3 11h16" />
                                                                    </svg>
                                                                </a>

                                                                <br>
                                                                <!-- View Data Button -->

                                                                <a href="#" style="height:30px; width:30px" class="view btn btn-info btn-sm mt-2" candidate_id="{{ $candidate->id }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                    </svg>
                                                                </a>

                                                                <!-- Verify Data Button -->
                                                                <a href="#" style="height:30px; width:30px" class="approve btn btn-success btn-sm mt-2" id="{{ $candidate->id }}" data-status="{{ $candidate->status_form }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checkbox">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                        <path d="M9 11l3 3l8 -8" />
                                                                        <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                                                                    </svg>
                                                                </a>

                                                                <!-- Print Data Button -->
                                                                <a href="#" style="height:30px; width:30px" class="btn btn-warning btn-sm print-data mt-2" data-candidate-id="{{ $candidate->id }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                                                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                                                        <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                                                                    </svg>
                                                                </a>

                                                                <!-- Peningkatan Button -->
                                                                <a href="#" style="height:30px; width:30px" class="btn btn-primary btn-sm peningkatan mt-2" id="{{ $candidate->id }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-rotate-rectangle">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                        <path d="M10.09 4.01l.496 -.495a2 2 0 0 1 2.828 0l7.071 7.07a2 2 0 0 1 0 2.83l-7.07 7.07a2 2 0 0 1 -2.83 0l-7.07 -7.07a2 2 0 0 1 0 -2.83l3.535 -3.535h-3.988" />
                                                                        <path d="M7.05 11.038v-3.988" />
                                                                    </svg>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="interviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="interviewModalLabel">Schedule Interview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadInterview">

            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                                <textarea class="form-control" name="reject_reason" id="reject_reason" placeholder="Reason" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 12l5 5l10 -10" />
                                        <path d="M2 12l5 5m5 -5l5 -5" />
                                    </svg>
                                    Continue
                                </button>
                            </div>
                        </div>
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

<!-- Modal Approve -->
<div class="modal modal-blur fade" id="modal-approveData" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Candidate Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm" class="ajax-form" onsubmit="return handleStatusSubmit(event)">
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

<!-- Modal Peningkatan -->
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
    function handleStatusSubmit(event) {
        event.preventDefault();

        const formData = new FormData(event.target);
        const candidateId = document.getElementById('candidate_id').value;
        const statusForm = document.getElementById('status_form').value;
        const submitButton = event.target.querySelector('button[type="submit"]');

        submitButton.disabled = true;

        // Ensure the site-wide loader is hidden
        document.getElementById('loader-wrapper').style.display = 'none';

        // Close the modal first
        $('#modal-approveData').modal('hide');

        // Show loading animation using SweetAlert
        Swal.fire({
            title: 'Processing Approval',
            html: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
            allowEscapeKey: false,
            showConfirmButton: false
        });

        // Rest of your existing fetch code...
        fetch('/recruitment/candidate/data/approve', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then(() => {
                        // Hide loader before redirect
                        document.getElementById('loader-wrapper').style.display = 'none';
                        window.location.href = data.redirect || window.location.href;
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Something went wrong',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            })
            .finally(() => {
                submitButton.disabled = false;
                // Ensure loader is hidden
                document.getElementById('loader-wrapper').style.display = 'none';
            });

        return false;
    }

    $(document).ready(function() {
        // View button click
        $('.view').click(function(e) {
            e.preventDefault();
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
                    var modal = new bootstrap.Modal(document.getElementById('modal-viewData'));
                    modal.show();
                }
            });
        });


        $('.approve').click(function(e) {
            e.preventDefault();
            var candidateId = $(this).attr('id');
            var currentStatus = $(this).data('status');

            // First get the candidate_data id
            $.ajax({
                type: 'POST',
                url: '/recruitment/candidate/get-data-id',
                data: {
                    _token: "{{ csrf_token() }}",
                    candidate_id: candidateId
                },
                beforeSend: function() {
                    // Show SweetAlert loader
                    Swal.fire({
                        title: 'Loading',
                        html: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close(); // Close the loading indicator

                    if (response.success && response.data_id) {
                        $('#candidate_id').val(response.data_id);
                        $('#status_form').val(currentStatus);
                        var modal = new bootstrap.Modal(document.getElementById('modal-approveData'));
                        modal.show();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Candidate data not found',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to fetch candidate data',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                },
                complete: function() {
                    // Make sure the site-wide loader is hidden
                    document.getElementById('loader-wrapper').style.display = 'none';
                }
            });
        });

        // Peningkatan button click
        $('.peningkatan').click(function(e) {
            e.preventDefault();
            var candidateId = $(this).attr('id');

            // First get the candidate_data id
            $.ajax({
                type: 'POST',
                url: '/recruitment/candidate/get-data-id',
                data: {
                    _token: "{{ csrf_token() }}",
                    candidate_id: candidateId
                },
                beforeSend: function() {
                    // Show SweetAlert loader
                    Swal.fire({
                        title: 'Loading',
                        html: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close(); // Close the loading indicator

                    if (response.success && response.data_id) {
                        $('#dataCandidate').val(response.data_id);
                        var modal = new bootstrap.Modal(document.getElementById('modal-skContract'));
                        modal.show();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Candidate data not found',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to fetch candidate data',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                },
                complete: function() {
                    // Make sure the site-wide loader is hidden
                    document.getElementById('loader-wrapper').style.display = 'none';
                }
            });
        });

        // Print confirm click
        $(".print-data").click(function(e) {
            e.preventDefault();
            var candidateId = $(this).data('candidate-id');

            // First, get the candidate_data ID
            $.ajax({
                type: 'POST',
                url: '/recruitment/candidate/get-data-id',
                data: {
                    _token: "{{ csrf_token() }}",
                    candidate_id: candidateId
                },
                success: function(response) {
                    if (response.success && response.data_id) {
                        // Create and submit the print form
                        var form = $('<form>', {
                            'method': 'POST',
                            'action': '/recruitment/data/' + response.data_id + '/print',
                            'target': '_blank'
                        });

                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value': "{{ csrf_token() }}"
                        }));

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
                                Swal.fire({
                                    title: 'Preparing Document',
                                    html: 'Please wait...',
                                    allowOutsideClick: false,
                                    showConfirmButton: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        setTimeout(() => {
                                            form.appendTo('body').submit();
                                            form.remove();
                                            setTimeout(() => {
                                                Swal.close();
                                            }, 500);
                                        }, 1000);
                                    }
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Candidate data not found',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to fetch candidate data',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            });
        });
    });

    $(function() {
        $(document).on('click', '.reject-confirm', function() {
            var candidateId = $(this).data('candidate-id'); // Fetch candidate ID from the button
            $('#rejectForm').attr('action', '/recruitment/candidate/' + candidateId + '/reject');
        });

        $(".next-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Yakin?",
                text: "Candidate Naik Stage",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Next"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        $(".back-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Yakin?",
                text: "Candidate Turun Stage",
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

        $('.interview').click(function(e) {
    e.preventDefault(); // Prevent default behavior
    var id = $(this).attr('id');
    $.ajax({
        type: 'POST',
        url: '/recruitment/candidate/interview/get',
        cache: false,
        data: {
            _token: "{{ csrf_token();}}",
            id: id
        },
        success: function(respond) {
            $('#loadInterview').html(respond);
            // Update modal initialization
            var interviewModal = new bootstrap.Modal(document.getElementById('interviewModal'), {
                backdrop: 'static',
                keyboard: false,
                focus: true
            });

            // Prevent scrolling when modal opens
            $('#interviewModal').on('show.bs.modal', function () {
                $(this).css('display', 'block');
                var modalHeight = $(this).find('.modal-dialog').height();
                $(this).find('.modal-dialog').css({
                    top: 'calc(50% - ' + (modalHeight/2) + 'px)',
                    transform: 'translateY(0)'
                });
            });

            interviewModal.show();
        }
    });
});
    });
</script>
@endpush
