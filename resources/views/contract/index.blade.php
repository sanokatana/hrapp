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
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/kontrak" method="GET">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="form-group">
                                                <input type="text" name="nomer_kontrak" id="nomer_kontrak" class="form-control" placeholder="Nomer Kontrak" value="{{ Request('nomer_kontrak')}}">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="form-group">
                                                <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Nomer Kontrak" value="{{ Request('nama_karyawan')}}">
                                            </div>
                                        </div>
                                        <div class="col-2">
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
                                            <td>{{ $loop->iteration}}</td>
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
                                                    <a href="#" class="edit btn btn-info btn-sm" id="{{ $d->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                            <path d="M16 5l3 3" />
                                                        </svg>
                                                    </a>
                                                    </div>
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
                                                    <form action="/kontrak/{{$d->id}}/delete" method="POST">
                                                        @csrf
                                                        <a class="btn btn-danger btn-sm delete-confirm" >
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
                            </div>
                        </div>
                    </div>
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
                    <div class="col-6">
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
                        <div class="col-6">
                            <div class="form-label">Nama Karyawan</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
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
                        <div class="col-6">
                            <div class="form-label">No Kontrak</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 18 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
                                </span>
                                <input type="text" value="" class="form-control" name="no_kontrak" id="no_kontrak" placeholder="No Kontrak">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Contract Type</div>
                            <select name="contract_type" id="contract_type" class="form-select">
                                <option value="">Choose</option>
                                <option value="PKWT">PKWT</option>
                                <option value="PKWTT">PKWTT</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
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
                        <div class="col-6">
                            <div class="form-label">End Date</div>
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
                                <input type="date" value="" class="form-control" name="end_date" id="end_date" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Position</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 18 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-versions"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /><path d="M7 7l0 10" /><path d="M4 8l0 8" /></svg>
                                </span>
                                <input type="text" value="" class="form-control" name="position" id="position" placeholder="Position">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Salary</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 18 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-cash-banknote"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M3 6m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M18 12l.01 0" /><path d="M6 12l.01 0" /></svg>
                                </span>
                                <input type="text" value="" class="form-control" name="salary" id="salary" placeholder="Salary">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Status</div>
                            <select name="status" id="status" class="form-select">
                                <option value="">Choose</option>
                                <option value="Active">Active</option>
                                <option value="Extended">Extended</option>
                                <option value="Terminated">Terminated</option>
                                <option value="Expired">Expired</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Contract File</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 18 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-info"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M11 14h1v4h1" /><path d="M12 11h.01" /></svg>
                                </span>
                                <input type="text" value="" class="form-control" name="contract_file" id="contract_file" placeholder="File">
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
@endsection

@push('myscript')
<script>
    $(function() {

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
                    // var salaryInput = $('#salaryedit');
                    // var salary = salaryInput.val().replace(/[^\d]/g, ''); // Remove all non-digit characters

                    let salaryField = document.getElementById('salaryedit');
                    salaryField.value = salaryField.value.replace(/[^\d]/g, '');

                    // Check if reasoning is empty
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

                $('#salaryedit').on('input', function(e) {
                    let value = e.target.value.replace(/[^\d]/g, ''); // Remove all non-digit characters

                    if (value) {
                        e.target.value = 'Rp ' + parseInt(value, 10).toLocaleString('id-ID');
                    } else {
                        e.target.value = ''; // Clear the input if there's no value
                    }
                });

                // Initialize the input field correctly
                $(document).ready(function() {
                    let salaryInput = $('#salary');
                    let initialValue = salaryInput.val().replace(/[^\d]/g, '');
                    if (initialValue) {
                        salaryInput.val('Rp ' + parseInt(initialValue, 10).toLocaleString('id-ID'));
                    }
                });
            } else {
                console.log('Form Contract not found'); // Debugging
            }
        }



        document.getElementById('salary').addEventListener('input', function (e) {
            // Remove any non-digit characters and format the number as currency
            let value = e.target.value.replace(/[^\d]/g, ''); // Remove all non-digit characters

            // Convert the cleaned-up string back to an integer
            if (value) {
                // Format as currency using Indonesian Rupiah style
                e.target.value = 'Rp ' + parseInt(value, 10).toLocaleString('id-ID');
            } else {
                e.target.value = ''; // Clear the input if there's no value
            }
        });

        $('#nik').on('blur', function () {
                var nik = $(this).val();
                if (nik !== "") {
                    $.ajax({
                        url: '/cuti/getEmployeeByNik',
                        type: 'GET',
                        data: { nik: nik },
                        success: function (response) {
                            if (response) {
                                $('#nama_lengkap').val(response.nama_lengkap);
                            } else {
                                $('#nama_lengkap').val('');
                            }
                        }
                    });
                }
            });

            $('#nama_lengkap').on('input', function () {
                var nama_lengkap = $(this).val().trim();

                if (nama_lengkap.length >= 2) { // Adjust minimum length as needed
                    $.ajax({
                        url: '/cuti/getEmployeeName',
                        type: 'GET',
                        data: { nama_lengkap: nama_lengkap },
                        success: function (response) {
                            var dropdownMenu = $('#employeeList');
                            dropdownMenu.empty();

                            if (response.length > 0) {
                                response.forEach(function (employee) {
                                    dropdownMenu.append('<a class="dropdown-item" href="#" data-nik="' + employee.nik + '">' + employee.nama_lengkap + '</a>');
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
            $(document).on('click', '#employeeList .dropdown-item', function (e) {
                e.preventDefault();
                var selectedName = $(this).text();
                var selectedNIK = $(this).data('nik');

                $('#nama_lengkap').val(selectedName);
                $('#nik').val(selectedNIK); // Assuming you want to set employee ID to another field like 'nik'

                $('#employeeList').hide();
            });

            // Hide dropdown on click outside
            $(document).click(function (e) {
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

        $(".delete-confirm").click(function(e){
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
            let salaryField = document.getElementById('salary');
            salaryField.value = salaryField.value.replace(/[^\d]/g, '');
            var nik = $('#nik').val();
            var no_kontrak = $('#no_kontrak').val();
            var contract_type = $('#contract_type').val();
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
            } else if (no_kontrak == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Nomer Kontrak Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#no_kontrak').focus();
                });
                return false;
            } else if (contract_type == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Contract Type Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#contract_type').focus();
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
</script>
@endpush
