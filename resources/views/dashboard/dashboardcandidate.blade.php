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
                    <li class="step-item">Fill Form</li>
                    <li class="step-item">Form Filled</li>
                    <li class="step-item active">Interview 1</li>
                    <li class="step-item">Interview 2</li>
                    <li class="step-item">Offering Letter</li>
                    <li class="step-item">Contract Signage</li>
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
