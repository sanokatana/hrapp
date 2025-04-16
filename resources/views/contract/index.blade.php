@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Employee
                </div>
                <h2 class="page-title">
                    Contract
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
                                <a href="#" class="btn btn-primary" id="btnAddContract">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Data
                                </a>

                                <a href="#" class="btn btn-warning" id="btnCheckContract">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                        <path d="M12 7v5l3 3" />
                                        <path d="M15 19l2 2l4 -4" />
                                    </svg>
                                    Check Contract
                                </a>

                                <a href="#" class="btn btn-primary" id="btnUploadCSV">
                                    Upload Excel
                                </a>
                                <a href="/kontrak/exportData" class="btn btn-primary">
                                    Export Contract Excel
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/kontrak" method="GET">
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <input type="text" name="nomer_kontrak" id="nomer_kontrak" class="form-control" placeholder="Nomer Kontrak" value="{{ Request('nomer_kontrak')}}">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Nama Karyawan" value="{{ Request('nama_karyawan')}}">
                                            </div>
                                        </div>
                                        <div class="col-3 col-lg-3">
                                            <div class="form-group mb-3">
                                                <select name="status_kontrak" id="status_kontrak" class="form-select">
                                                    <option value="" {{ request('status_kontrak') == '' ? 'selected' : '' }}>All Status</option>
                                                    <option value="Active" {{ request('status_kontrak') == 'Active' ? 'selected' : '' }}>Active</option>
                                                    <option value="Terminated" {{ request('status_kontrak') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                                                    <option value="Expired" {{ request('status_kontrak') == 'Expired' ? 'selected' : '' }}>Expired</option>
                                                    <option value="Extended" {{ request('status_kontrak') == 'Extended' ? 'selected' : '' }}>Non-Active</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
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
                                        <tr style="text-align: center;">
                                            <th>No</th>
                                            <th>No Contract</th>
                                            <th>Nama Karyawan</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Contract Type</th>
                                            <th>Position</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contract as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $loop->iteration + $contract->firstItem() -1 }}</td>
                                            <td>{{ $d->no_kontrak}}</td>
                                            <td>{{ $d->nama_lengkap}}</td>
                                            <td>{{ $d->start_date}}</td>
                                            <td>{{ $d->end_date}}</td>
                                            <td>{{ $d->contract_type}}</td>
                                            <td>{{ $d->position}}</td>
                                            <td>{{ $d->status}}</td>
                                            <td>
                                                <div class="form-group">
                                                    <div class="mb-1">
                                                        <a href="#" class="btn btn-success btn-sm view"
                                                            id="{{ $d->id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 18 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <div class="mb-1">
                                                        <a href="#" class="btn btn-primary btn-sm peningkatan"
                                                            id="{{ $d->id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-rotate-rectangle">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M10.09 4.01l.496 -.495a2 2 0 0 1 2.828 0l7.071 7.07a2 2 0 0 1 0 2.83l-7.07 7.07a2 2 0 0 1 -2.83 0l-7.07 -7.07a2 2 0 0 1 0 -2.83l3.535 -3.535h-3.988" />
                                                                <path d="M7.05 11.038v-3.988" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <div class="mb-1">
                                                        <a href="#" class="btn btn-warning btn-sm print-confirm" data-id="{{ $d->id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                                                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                                                <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <form action="/kontrak/{{$d->id}}/delete" method="POST">
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
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $contract->links('vendor.pagination.bootstrap-5')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Print Selection Modal -->
<div id="printModal" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                <button class="btn btn-primary btn-block print-option w-100" data-type="Sales">Sales</button>
                </div>
                <div class="col-12 mt-4">
                <button class="btn btn-secondary btn-block print-option w-100" data-type="Non-Sales">Non-Sales</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-inputContract" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/kontrak/store" method="POST" id="formContract">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">NIK</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M15 8l2 0" />
                                        <path d="M15 12l2 0" />
                                        <path d="M7 16l10 0" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="nik" id="nik" placeholder="10101" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-label">Nama Karyawan</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                        <path d="M15 19l2 2l4 -4" />
                                    </svg>
                                </span>
                                <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Type or select employee name" autocomplete="off">
                            </div>
                            <div id="employeeList" class="dropdown-menu" style="display:none; margin-top: -12px">
                                <!-- Dropdown items will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">No Kontrak</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="no_kontrak" id="no_kontrak" placeholder="No Kontrak">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">Start Date</div>
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
                                <input type="date" value="" class="form-control" name="start_date" id="start_date" placeholder="">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-label">End Date</div>
                            <div class="mb-3">
                                <select name="end_date_selection" id="end_date_selection" class="form-select">
                                    <option value="">Choose Duration</option>
                                    <option value="1">1 Bulan</option>
                                    <option value="3">3 Bulan</option>
                                    <option value="4">4 Bulan</option>
                                    <option value="6">6 Bulan</option>
                                    <option value="12">12 Bulan</option>
                                    <option value="manual">Manual</option>
                                </select>
                                <input
                                    type="date"
                                    class="form-control mt-4 d-none"
                                    name="end_date_manual"
                                    id="end_date_manual"
                                    placeholder="Choose Date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">Position</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-versions">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 5m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                                        <path d="M7 7l0 10" />
                                        <path d="M4 8l0 8" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="position" id="position" placeholder="Position">
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
<div class="modal modal-blur fade" id="modal-editContract" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadedEdit">

            </div>
        </div>
    </div>
</div>

<!-- Modal View -->
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

<div class="modal modal-blur fade" id="modal-uploadContract" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Data Contract Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/kontrak/uploadKontrak" method="POST" id="formKontrak" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="file">Upload CSV</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary w-100">Simpan</button>
                            </div>
                            <div class="form-group mt-3">
                                <a href="/kontrak/export" class="btn btn-secondary w-100">Download Template CSV</a>
                            </div>
                        </div>
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
                            <input type="text" class="form-control" id="masa_probation" name="masa_probation">
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


    $(function() {

        $('#btnCheckContract').click(function() {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah anda yakin ingin memeriksa kontrak yang sudah expired?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, periksa',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang memeriksa kontrak',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Make AJAX call to check contracts
                    $.ajax({
                        url: '/kontrak/check-expired',
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: `${response.count} kontrak telah diperbarui statusnya menjadi Expired`,
                                icon: 'success'
                            }).then(() => {
                                // Reload the page to show updated statuses
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memeriksa kontrak',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });


        document.getElementById('end_date_selection').addEventListener('change', function() {
            const manualInput = document.getElementById('end_date_manual');
            if (this.value === 'manual') {
                manualInput.classList.remove('d-none');
            } else {
                manualInput.classList.add('d-none');
                manualInput.value = ''; // Clear the value if not manual
            }
        });

        $('#btnUploadCSV').click(function() {
            $('#modal-uploadContract').modal("show");
        });

        $('.edit').click(function() {
            var id = $(this).attr('id');
            $.ajax({
                type: 'POST',
                url: '/kontrak/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(respond) {
                    $('#loadedEdit').html(respond);
                    console.log("Content loaded into #loadedEdit"); // Debugging
                    initializeEventListeners(); // Reinitialize after content is loaded
                }
            });
            $('#modal-editContract').modal("show");
        });

        function initializeEventListeners() {
            if ($('#formEditContract').length) {
                console.log('Form Contract found');

                // Handle form submission
                $('#formEditContract').on('submit', function(event) {
                    var reasoning = $('#reasoning').val().trim();
                    if (reasoning === "") {
                        event.preventDefault(); // Prevent form submission
                        Swal.fire({
                            title: 'Warning!',
                            text: 'Reasoning Harus Diisi',
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        }).then(() => {
                            $('#reasoning').focus();
                        });
                    }
                });
            } else {
                console.log('Form Contract not found'); // Debugging
            }
        }


        $('#nik').on('blur', function() {
            var nik = $(this).val();
            if (nik !== "") {
                $.ajax({
                    url: '/cuti/getEmployeeByNik',
                    type: 'GET',
                    data: {
                        nik: nik
                    },
                    success: function(response) {
                        if (response) {
                            $('#nama_lengkap').val(response.nama_lengkap);
                        } else {
                            $('#nama_lengkap').val('');
                        }
                    }
                });
            }
        });

        $('#nama_lengkap').on('input', function() {
            var nama_lengkap = $(this).val().trim();

            if (nama_lengkap.length >= 2) { // Adjust minimum length as needed
                $.ajax({
                    url: '/cuti/getEmployeeName',
                    type: 'GET',
                    data: {
                        nama_lengkap: nama_lengkap
                    },
                    success: function(response) {
                        var dropdownMenu = $('#employeeList');
                        dropdownMenu.empty();

                        if (response.length > 0) {
                            response.forEach(function(employee) {
                                dropdownMenu.append('<a class="dropdown-item" href="#" data-nik="' + employee.nik + '" data-tgl="' + employee.tgl_masuk + '" data-position="' + employee.position + '">' + employee.nama_lengkap + '</a>');
                            });

                            dropdownMenu.show();
                        } else {
                            dropdownMenu.hide();
                        }
                    }
                });
            } else {
                $('#employeeList').hide();
            }
        });

        // Handle dropdown item click
        $(document).on('click', '#employeeList .dropdown-item', function(e) {
            e.preventDefault();
            var selectedName = $(this).text();
            var selectedNIK = $(this).data('nik');
            var selectedTgl = $(this).data('tgl');
            var selectedPosition = $(this).data('position');

            $('#nama_lengkap').val(selectedName);
            $('#nik').val(selectedNIK); // Assuming you want to set employee ID to another field like 'nik'
            $('#start_date').val(selectedTgl); // Assuming you want to set employee ID to another field like 'nik'
            $('#position').val(selectedPosition); // Assuming you want to set employee ID to another field like 'nik'
            $('#employeeList').hide();
        });

        // Hide dropdown on click outside
        $(document).click(function(e) {
            if (!$(e.target).closest('#employeeList').length && !$(e.target).closest('#nama_lengkap').length) {
                $('#employeeList').hide();
            }
        });


        $('#btnAddContract').click(function() {
            $('#modal-inputContract').modal("show");
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

        $('#actionType').change(function() {
            var actionType = $(this).val();
            if (actionType == 'extend') {
                $('#extendFields').show();
                $('#peningkatanFields').hide();
            } else if (actionType == 'peningkatan') {
                $('#extendFields').hide();
                $('#peningkatanFields').show();
            }
        });

        $('.peningkatan').click(function() {
            var id = $(this).attr('id');
            $('#contractId').val(id);
            $('#modal-skContract').modal("show");
        });

        $(".delete-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Yakin?",
                text: "Kontrak Akan Ke Delete!",
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
        $('#formContract').submit(function() {
            // let salaryField = document.getElementById('salary');
            // salaryField.value = salaryField.value.replace(/[^\d]/g, '');
            var nik = $('#nik').val();
            var no_kontrak = $('#no_kontrak').val();
            var start_date = $('#start_date').val();
            var reasoning = $('#reasoning').val();
            if (nik == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'NIK Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#nik').focus();
                });
                return false;
            } else if (start_date == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Start Date Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#start_date').focus();
                });
                return false;
            } else if (reasoning == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Reasoning Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#reasoning').focus();
                });
                return false;
            }
        });
    });

    $(document).ready(function() {
        let contractId = null;

        // Open modal on print button click
        $(".print-confirm").click(function(e) {
            e.preventDefault();
            contractId = $(this).data("id");
            $("#printModal").modal("show");
        });

        // Handle Sales or Non-Sales print option
        $(".print-option").click(function() {
            let type = $(this).data("type");
            if (contractId) {
                let url = "/kontrak/" + contractId + "/print?type=" + type;
                window.open(url, '_blank'); // Open in new tab
            }
        });
    });

</script>
@endpush
