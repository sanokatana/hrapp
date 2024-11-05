@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Performance</div>
                <h2 class="page-title">Check Contract</h2>
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
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter">
                                <tbody>
                                    @foreach($contracts as $contract)
                                    <tr>
                                        <td class="w-100">
                                            <a href="#" id="{{ $contract->id }}" class="text-reset view">
                                                <b>Kontrak</b> | <b>{{ $contract->no_kontrak }}</b> | {{ $contract->nama_lengkap }} - {{ $contract->position }}
                                            </a>
                                        </td>
                                        <td class="text-nowrap text-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                <path d="M16 3v4"></path>
                                                <path d="M8 3v4"></path>
                                                <path d="M4 11h16"></path>
                                                <path d="M11 15h1"></path>
                                                <path d="M12 15v3"></path>
                                            </svg>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-viewContract" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadedView">

            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $('.view').click(function() {
        var id = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: '/kontrak/view',
            cache: false,
            data: {
                _token: "{{ csrf_token();}}",
                id: id
            },
            success: function(respond) {
                $('#loadedView').html(respond);
            }
        });
        $('#modal-viewContract').modal("show");
    });
</script>
@endpush
