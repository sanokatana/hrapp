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
                    Candidate
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
                                <a href="#" class="btn btn-primary" id="btnAddCandidate">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Candidate
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/recruitment/candidate" method="GET">
                                    <div class="row">
                                        <div class="col-3 col-lg-3">
                                            <div class="form-group">
                                                <input type="text" name="candidate_name" id="candidate_name" class="form-control" placeholder="Nama Candidate" value="{{ request('candidate_name') }}">
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
                                                    <option value="">All Status</option>
                                                    <option {{ request('status_candidate') == "In Process" ? 'selected' : '' }} value="In Process">In Process</option>
                                                    <option {{ request('status_candidate') == "Hired" ? 'selected' : '' }} value="Hired">Hired</option>
                                                    <option {{ request('status_candidate') == "Rejected" ? 'selected' : '' }} value="Rejected">Rejected</option>
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
                                        <tr>
                                            <th>No</th>
                                            <th>Candidate Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Job Opening</th>
                                            <th>Current Stage</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($candidate as $d)
                                        <tr>
                                            <td>{{ $d->id}}</td>
                                            <td>{{ $d->nama_candidate}}</td>
                                            <td>{{ $d->username}}</td>
                                            <td>{{ $d->email}}</td>
                                            <td>{{ $d->job_opening_name}}</td>
                                            <td>{{ $d->hiring_stages_name}}</td>
                                            <td>{{ $d->status}}</td>
                                            <td>
                                                <div class="form-group">
                                                    <div>
                                                        <a href="#" class="edit btn btn-info btn-sm" id="{{ $d->id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                <path d="M16 5l3 3" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="#" class="karyawan-confirm btn btn-success btn-sm" id="{{ $d->id }}" data-bs-toggle="modal" data-bs-target="#confirmationModal">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-progress-down">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M10 20.777a8.942 8.942 0 0 1 -2.48 -.969" />
                                                                <path d="M14 3.223a9.003 9.003 0 0 1 0 17.554" />
                                                                <path d="M4.579 17.093a8.961 8.961 0 0 1 -1.227 -2.592" />
                                                                <path d="M3.124 10.5c.16 -.95 .468 -1.85 .9 -2.675l.169 -.305" />
                                                                <path d="M6.907 4.579a8.954 8.954 0 0 1 3.093 -1.356" />
                                                                <path d="M12 9v6" />
                                                                <path d="M15 12l-3 3l-3 -3" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <form action="/recruitment/candidate/{{$d->id}}/delete" method="POST">
                                                            @csrf
                                                            <a class="btn btn-danger btn-sm delete-confirm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
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
<div class="modal fade" id="modal-confirmCandidate" tabindex="-1" aria-labelledby="modal-confirmCandidateLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-confirmCandidateLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>What action would you like to take?</p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="action" id="actionHire" value="hire">
                    <label class="form-check-label" for="actionHire">Hire</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="action" id="actionReject" value="reject">
                    <label class="form-check-label" for="actionReject">Reject</label>
                </div>
                <div id="rejectReasonContainer" class="mt-3" style="display: none;">
                    <label for="rejectReason" class="form-label">Reason for Rejection</label>
                    <textarea id="rejectReason" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmCandidateAction">Submit</button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-inputCandidate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/recruitment/candidate/store" method="POST" id="formCandidate">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Nama Candidate</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-minus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4c.348 0 .686 .045 1.009 .128" />
                                        <path d="M16 19h6" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="nama_candidate" id="nama_candidate" placeholder="John">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Email</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                                        <path d="M3 7l9 6l9 -6" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="email" id="email" placeholder="@gmail.com">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Job Opening</div>
                            <div class="input-icon mb-3">
                                <select name="job_opening_id" id="job_opening_id" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ($job as $d)
                                    <option {{ Request('id') == $d->id ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Notes</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-note">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M13 20l7 -7" />
                                        <path d="M13 20v-6a1 1 0 0 1 1 -1h6v-7a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="notes" id="notes" placeholder="Note">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Interview Date</div>
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
                                <input type="date" value="" class="form-control" name="interview_date" id="interview_date" placeholder="Officer">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Interview Time</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 4m0 1a1 1 0 0 1 1 -1h14a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-14a1 1 0 0 1 -1 -1z" />
                                        <path d="M12 7v5l3 3" />
                                        <path d="M4 12h1" />
                                        <path d="M19 12h1" />
                                        <path d="M12 19v1" />
                                    </svg>
                                </span>
                                <input type="time" value="" class="form-control" name="interview_time" id="interview_time" placeholder="Officer">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Interviewer</div>
                            <div class="input-icon mb-3">
                                <select name="interviewer" id="interviewer" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ($interviewer as $d)
                                    <option {{ Request('nama_lengkap') == $d->nama_lengkap ? 'selected' : '' }} value="{{ $d->nama_lengkap }}">{{ $d->nama_lengkap }} | {{ $d->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Interviewer 2</div>
                            <div class="input-icon mb-3">
                                <select name="interviewer2" id="interviewer2" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ($interviewer as $d)
                                    <option {{ Request('nama_lengkap') == $d->nama_lengkap ? 'selected' : '' }} value="{{ $d->nama_lengkap }}">{{ $d->nama_lengkap }} | {{ $d->nama_jabatan }}</option>
                                    @endforeach
                                </select>
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
<!-- Modal Edit -->
<div class="modal modal-blur fade" id="modal-editCandidate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadedCandidate">

            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        $('#btnAddCandidate').click(function() {
            $('#modal-inputCandidate').modal("show");
        });

        $('.edit').click(function() {
            var id = $(this).attr('id');
            $.ajax({
                type: 'POST',
                url: '/recruitment/candidate/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token();}}",
                    id: id
                },
                success: function(respond) {
                    $('#loadedCandidate').html(respond);
                }
            });
            $('#modal-editCandidate').modal("show");
        });

        $(".delete-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Yakin?",
                text: "Data Department Akan Ke Delete!",
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

        $('#formCandidate').submit(function() {
            var nama_candidate = $('#nama_candidate').val();
            if (nama_candidate == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Candidate Name Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#nama_candidate').focus();
                });
                return false;
            }
        });
    });

    $(document).ready(function() {
        var candidateId = null;

        // Show modal and set candidate ID
        $('.karyawan-confirm').click(function() {
            candidateId = $(this).attr('id'); // Get candidate ID
            $('#modal-confirmCandidate').modal('show');
        });

        // Toggle reject reason input based on action
        $('input[name="action"]').change(function() {
            var action = $(this).val();
            if (action === 'reject') {
                $('#rejectReasonContainer').show();
            } else {
                $('#rejectReasonContainer').hide();
            }
        });

        // Handle form submission
        $('#confirmCandidateAction').click(function() {
            var action = $('input[name="action"]:checked').val();
            var rejectReason = $('#rejectReason').val();

            if (!action) {
                alert('Please select an action.');
                return;
            }

            if (action === 'reject' && rejectReason.trim() === '') {
                alert('Please provide a reason for rejection.');
                return;
            }

            var url = action === 'hire' ?
                '/recruitment/candidate/' + candidateId + '/hired' :
                '/recruitment/candidate/' + candidateId + '/reject';

            // AJAX request
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                    reject_reason: action === 'reject' ? rejectReason : null
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then(() => {
                            window.location.reload(); // Refresh the page to reflect changes
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            });
        });
    });
</script>
@endpush
