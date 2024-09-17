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

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="card-body">
            <ul class="steps steps-yellow steps-counter my-1">
                @foreach ($stages as $stage)
                    <li class="step-item @if($stage->id == $candidate->current_stage_id) active @endif">
                        {{ $stage->name }}
                    </li>
                @endforeach
            </ul>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>

</script>
@endpush
