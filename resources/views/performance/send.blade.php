@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Performance</div>
                <h2 class="page-title">Send Email Notification</h2>
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
                        <div class="card-header">
                            <h3 class="card-title">Karyawan Kontrak List</h3>
                        </div>
                        <!-- Add a form for submitting the selected contracts -->
                        <form action="{{ route('contracts.sendEmail') }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter">
                                    <tbody>
                                        @foreach($contracts as $contract)
                                        <tr>
                                            <td class="w-1 pe-0">
                                                <!-- Assign the contract ID to the checkbox name -->
                                                <input type="checkbox" name="selected_contracts[]" value="{{ $contract->id }}" class="form-check-input m-0 align-middle" aria-label="Select task">
                                            </td>
                                            <td class="w-100">
                                                <a href="#" class="text-reset">
                                                    <b>Kontrak</b> | <b>{{ $contract->no_kontrak }}</b> | {{ $contract->nama_lengkap }} - {{ $contract->position }}
                                                </a>
                                            </td>
                                            <td class="text-nowrap text-secondary">
                                                {{ \Carbon\Carbon::parse($contract->end_date)->format('F d, Y') }}
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="#" class="text-secondary">
                                                    {{ $contract->days_left }} hari lagi
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Send Email Notification</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
