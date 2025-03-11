@extends('layouts.candidate.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Dashboard
                </div>
                <h2 class="page-title">
                    Candidate
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
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="card-body">
                <ul class="steps steps-yellow steps-counter my-1">
                    @foreach ($stages as $stage)
                    <li class="step-item @if($stage->id == $candidate->current_stage_id) active @endif">
                        <div>{{ $stage->name }}</div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row mt-5">
            <div class="card-body">
                <div class="card-body card-body-scrollable-shadow">
                    <div>
                        <!-- Candidate Data Notification -->
                        @if($candidateData)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <!-- Candidate Avatar or Initials -->
                                <div class="col-auto">
                                    <span
                                        class="avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-info-hexagon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                                            <path d="M12 9h.01" />
                                            <path d="M11 12h1v4h1" />
                                        </svg></span>
                                </div>

                                <div class="col">
                                    <strong>{{ $candidate->nama_candidate }}</strong>, your candidate data is currently
                                </div>

                                <!-- Candidate Data Information -->
                                <div class="col-auto align-self-center">
                                    <div class="text-truncate">
                                        @if($statusForm == 'Verified')
                                        <span class="badge bg-green text-green-fg">Verified</span>
                                        @elseif($statusForm == 'Pending')
                                        <span class="badge bg-yellow text-yellow-fg">Pending</span>
                                        @elseif($statusForm == 'Declined')
                                        <span class="badge bg-red text-red-fg">Declined</span>
                                        @else
                                        <span class="badge bg-blue text-blue-fg">Not Submitted</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add a horizontal line to separate the candidate data notification from the interviews -->
                        <hr class="my-2"> <!-- Optional: you can adjust the spacing with `my-3` (margin top/bottom) -->
                        @endif
                    </div>

                    <div>
                        <!-- Interview List -->
                        @forelse ($interview as $data)
                        <div class="list-group-item mt-5">
                            <div class="row align-items-center">
                                <!-- Candidate Avatar or Initials -->
                                <div class="col-auto">
                                    <span class="avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-info-hexagon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                                            <path d="M12 9h.01" />
                                            <path d="M11 12h1v4h1" />
                                        </svg></span>
                                </div>

                                <!-- Candidate Information -->
                                <div class="col">
                                    <div class="text-truncate">
                                        <strong>{{ $data->candidate_name }}</strong>
                                        @if($data->status == 'Completed')
                                        you have passed the <strong>{{ $data->stage_name }}</strong> stage.
                                        @else
                                        you have an <strong>Interview</strong> for the <strong>{{ $data->stage_name }}</strong> stage.
                                        @endif
                                    </div>
                                    <div class="text-secondary">
                                        Interview scheduled for
                                        <strong>{{ DateHelper::formatIndonesiaDate($data->interview_date) }}</strong>
                                        {{ $data->interview_time }} <strong>({{ \Carbon\Carbon::createFromFormat('H:i:s', $data->interview_time)->format('g:i A') }})</strong>

                                    </div>
                                </div>

                                <!-- Optional Actions or Badge -->
                                <div class="col-auto align-self-center">
                                    <div class="text-secondary mt-n1">
                                        @if($data->status == 'Completed')
                                        <span class="badge bg-green text-green-fg">Interview Completed</span>
                                        @elseif($data->status == 'Scheduled')
                                        <span class="badge bg-yellow text-yellow-fg">Interview Scheduled</span>
                                        @elseif($data->status == 'Unscheduled')
                                        <span class="badge bg-red text-red-fg">Interview Unscheduled</span>
                                        @else
                                        <span class="badge bg-blue text-blue-fg">Interview Not Submitted</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Optional: Add a horizontal line between each interview item -->
                        @if(!$loop->last)
                        <hr class="my-2">
                        @endif

                        @empty
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col text-truncate">
                                    <div class="d-block text-secondary text-truncate mt-n1">
                                        No interview applications available.
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforelse
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
