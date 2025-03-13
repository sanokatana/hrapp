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
                                            <div class="btn-group ms-2">
                                                <button class="btn btn-sm btn-primary print-contract" data-id="{{ $contract->id }}" style="margin-right: 5px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"></path>
                                                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                                                        <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z"></path>
                                                    </svg>
                                                    Print
                                                </button>
                                                <button class="btn btn-sm btn-warning contract-action" data-id="{{ $contract->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                        <path d="M16 5l3 3"></path>
                                                    </svg>
                                                    Action
                                                </button>
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
<!-- Print Contract Modal -->
<div class="modal modal-blur fade" id="modal-printContract" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="printContractForm">
                    <input type="hidden" id="print_contract_id" name="contract_id">

                    <div class="mb-3">
                        <label class="form-label">Contract Type</label>
                        <select class="form-select" name="contract_type" id="contract_type" required>
                            <option value="">Select Type</option>
                            <option value="3_bulan">3 Bulan</option>
                            <option value="kontrak">Kontrak</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Management</label>
                        <select class="form-select" name="management" id="management" required>
                            <option value="">Select Management</option>
                            <option value="Andreas Audyanto">Pak Audy</option>
                            <option value="Setia Iskandar Rusli	">Pak Setia</option>
                            <option value="Al Imron">Al Imron</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"></path>
                                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                                <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z"></path>
                            </svg>
                            Print Contract
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-skContract" tabindex="-1" role="dialog" aria-labelledby="modal-skContractLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-skContractLabel">Contract Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/performance/peningkatanOrExtend" method="POST" id="formKontrak" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="contractId">

                    <!-- Action Selection -->
                    <div class="form-group">
                        <label for="actionType">Choose Action</label>
                        <select class="form-control" id="actionType" name="actionType">
                            <option value="">Pilih</option>
                            <option value="extend">Extend Contract</option>
                            <option value="peningkatan">Peningkatan (Tetap)</option>
                            <option value="tidak_lanjut">Tidak Lanjut</option>
                            <option value="mengakhiri">Mengakhiri</option>
                        </select>
                    </div>

                    <!-- Extend Contract Fields -->
                    <div id="extendFields" style="display:none;">
                        <div class="form-group mt-3">
                            <label for="new_start_date">New Start Date</label>
                            <input type="date" class="form-control" id="new_start_date" name="new_start_date" placeholder="Enter New Start Date">
                        </div>

                        <div class="form-group mt-3">
                            <label for="new_end_date">New End Date</label>
                            <div class="input-group">
                                <select class="form-select" id="new_end_date_duration">
                                    <option value="" selected>Choose Duration...</option>
                                    <option value="1">1 Month</option>
                                    <option value="3">3 Months</option>
                                    <option value="4">4 Months</option>
                                    <option value="6">6 Months</option>
                                    <option value="12">12 Months</option>
                                </select>
                                <input type="date" class="form-control" id="new_end_date" name="new_end_date" placeholder="Calculated End Date" readonly>
                            </div>
                        </div>
                    </div>


                    <!-- Peningkatan Fields -->
                    <div id="peningkatanFields" style="display:none;">
                        <div class="form-group mt-3">
                            <label for="tgl_sk">Tanggal SK</label>
                            <input type="date" class="form-control" id="tgl_sk" name="tgl_sk">
                        </div>
                        <div class="form-group mt-3">
                            <label for="masa_probation">Masa Probation</label>
                            <input type="number" class="form-control" id="masa_probation" name="masa_probation">
                        </div>
                        <div class="form-group mt-3">
                            <label for="diketahui">Diketahui Oleh</label>
                            <input type="text" class="form-control" id="diketahui" name="diketahui">
                        </div>
                    </div>

                    <div id="tidakLanjutFields" style="display:none;">
                        <div class="form-group mt-3">
                            <label for="alasan">Alasan</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Mengakhiri Fields -->
                    <div id="mengakhiriFields" style="display:none;">
                        <div class="form-group mt-3">
                            <label for="tgl_mengakhiri">Tanggal Mengakhiri</label>
                            <input type="date" class="form-control" id="tgl_mengakhiri" name="tgl_mengakhiri">
                        </div>
                        <div class="form-group mt-3">
                            <label for="alasan_mengakhiri">Alasan Mengakhiri</label>
                            <textarea class="form-control" id="alasan_mengakhiri" name="alasan_mengakhiri" rows="3"></textarea>
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

    document.addEventListener('DOMContentLoaded', () => {
        const startDateInput = document.getElementById('new_start_date');
        const durationSelect = document.getElementById('new_end_date_duration');
        const endDateInput = document.getElementById('new_end_date');

        function calculateEndDate(startDate, months) {
            if (!startDate || !months) return '';

            const start = new Date(startDate);
            start.setMonth(start.getMonth() + parseInt(months, 10));
            start.setDate(start.getDate() - 1); // Subtract one day

            return start.toISOString().split('T')[0]; // Format as YYYY-MM-DD
        }

        // Recalculate the end date when either the start date or duration changes
        startDateInput.addEventListener('input', () => {
            endDateInput.value = calculateEndDate(startDateInput.value, durationSelect.value);
        });

        durationSelect.addEventListener('change', () => {
            endDateInput.value = calculateEndDate(startDateInput.value, durationSelect.value);
        });
    });

    $('#actionType').change(function() {
        var actionType = $(this).val();
        $('#extendFields').hide();
        $('#peningkatanFields').hide();
        $('#tidakLanjutFields').hide();
        $('#mengakhiriFields').hide();

        switch(actionType) {
            case 'extend':
                $('#extendFields').show();
                break;
            case 'peningkatan':
                $('#peningkatanFields').show();
                break;
            case 'tidak_lanjut':
                $('#tidakLanjutFields').show();
                break;
            case 'mengakhiri':
                $('#mengakhiriFields').show();
                break;
        }
    });

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

    $('.print-contract').click(function(e) {
        e.preventDefault();
        const contractId = $(this).data('id');
        $('#print_contract_id').val(contractId);
        $('#modal-printContract').modal('show');
    });

    // Print Contract Form Submit
    $('#printContractForm').submit(function(e) {
        e.preventDefault();
        const contractId = $('#print_contract_id').val();
        const contractType = $('#contract_type').val();
        const management = $('#management').val();

        // Open print window in new tab with all parameters
        window.open(`/performance/printEvaluation/${contractId}?type=${contractType}&management=${management}`, '_blank');

        // Close the modal
        $('#modal-printContract').modal('hide');
    });

    // Contract Action Button Handler
    $('.contract-action').click(function(e) {
        e.preventDefault();
        const contractId = $(this).data('id');
        $('#contractId').val(contractId);
        $('#modal-skContract').modal('show');
    });
</script>
@endpush