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

        <!-- Button to Show Form -->
        <div class="row mt-4">
            <div class="col text-center">
                <a href="#" id="fillFormButton" class="btn btn-primary btn-pill w-50">
                    Fill Form
                </a>
            </div>
        </div>

        <!-- Form to be shown/hidden -->
        <div class="row mt-4" id="formContainer" style="display: none;">
            <div class="col-12">
                <form action="https://httpbin.org/post" method="POST" class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form elements</h4>
                    </div>
                    <div class="card-body">
                        <!-- Form fields here -->
                    </div>
                    <div class="card-footer text-end">
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary ms-auto">Submit Form</button>
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
    document.getElementById('fillFormButton').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        var formContainer = document.getElementById('formContainer');
        if (formContainer.style.display === 'none') {
            formContainer.style.display = 'block'; // Show form
        } else {
            formContainer.style.display = 'none'; // Hide form
        }
    });
</script>
@endpush
