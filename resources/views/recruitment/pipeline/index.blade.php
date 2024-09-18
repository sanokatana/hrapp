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
                                        class="nav-link {{ $key === 0 ? 'active' : '' }}" data-bs-toggle="tab"
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

                                                                <!-- Data button -->
                                                                <form
                                                                    action="/recruitment/candidate/{{$candidate->id}}/data"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <a type="submit"
                                                                        class="btn btn-primary btn-sm interview-confirm"
                                                                        style="height:30px; width:30px"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-database">
                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                            <path d="M12 12.75m-4 0a4 1.75 0 1 0 8 0a4 1.75 0 1 0 -8 0" />
                                                                            <path d="M8 12.5v3.75c0 .966 1.79 1.75 4 1.75s4 -.784 4 -1.75v-3.75" />
                                                                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                                        </svg>
                                                                    </a>
                                                                </form>
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
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-checks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 12l5 5l10 -10" /><path d="M2 12l5 5m5 -5l5 -5" /></svg>
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
@endsection

@push('myscript')
<script>
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

        $('.interview').click(function() {
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
                }
            });
            $('#interviewModal').modal("show");
        });
    });
</script>
@endpush
