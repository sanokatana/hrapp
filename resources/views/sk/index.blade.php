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
                    Surat Kepututsan
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
                                <a href="#" class="btn btn-primary" id="btnAddSK">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Data
                                </a>
                                <a href="#" class="btn btn-primary" id="btnUploadCSV">
                                    Upload Excel
                                </a>
                                <a href="/sk/exportData" class="btn btn-primary">
                                    Export SK Excel
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/sk" method="GET">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="form-group">
                                                <input type="text" name="sk_no" id="sk_no" class="form-control" placeholder="Nomer Kontrak" value="{{ Request('nomer_kontrak')}}">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="form-group">
                                                <input type="text" name="nama_kar" id="nama_kar" class="form-control" placeholder="Nama Karyawan" value="{{ Request('nama_karyawan')}}">
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
                                            <th>Nama Karyawan</th>
                                            <th>No SK</th>
                                            <th>Tgl SK</th>
                                            <th>Nama PT</th>
                                            <th>Masa Probation</th>
                                            <th>Diketahui</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sk as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $loop->iteration + $sk->firstItem() -1 }}</td>
                                            <td>{{ $d->nama_karyawan}}</td>
                                            <td>{{ $d->no_sk}}</td>
                                            <td>{{ $d->tgl_sk}}</td>
                                            <td>{{ $d->nama_pt}}</td>
                                            <td>{{ $d->masa_probation}}</td>
                                            <td>{{ $d->diketahui}}</td>
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
                                                    <form action="/kontrak/{{$d->id}}/print" method="POST" class="mb-1">
                                                        @csrf
                                                        <a class="btn btn-warning btn-sm print-confirm">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 18 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                                                        </a>
                                                    </form>
                                                    <form action="/sk/{{$d->id}}/delete" method="POST">
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
                                {{ $sk->links('vendor.pagination.bootstrap-5')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-inputSk" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/sk/store" method="POST" id="formSK">
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
                                <input type="text" value="" class="form-control" name="nik" id="nik" placeholder="0xx-xxx" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-6">
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
                        <div class="col-6">
                            <div class="form-label">No SK</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="no_sk" id="no_sk" placeholder="No Kontrak">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Tgl SK</div>
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
                                <input type="date" value="" class="form-control" name="tgl_sk" id="tgl_sk" placeholder="">
                            </div>
                        </div>
                    </div><div class="row">
                        <div class="col-12">
                            <div class="form-label">Masa Probation</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="masa_probation" id="masa_probation" placeholder="Masa Probation">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Nama PT</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-versions">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 5m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                                        <path d="M7 7l0 10" />
                                        <path d="M4 8l0 8" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="nama_pt" id="nama_pt" placeholder="Nama PT">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-label">Diketahui</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-cash-banknote">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                        <path d="M3 6m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                        <path d="M18 12l.01 0" />
                                        <path d="M6 12l.01 0" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="diketahui" id="diketahui" placeholder="Nama">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-label">Status</div>
                            <select name="status" id="status" class="form-select">
                                <option value="">Choose</option>
                                <option value="Active">Active</option>
                                <option value="Terminated">Terminated</option>
                                <option value="Non-Active">Non-Active</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <div class="form-label">SK File</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-info">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                        <path d="M11 14h1v4h1" />
                                        <path d="M12 11h.01" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="file_sk" id="file_sk" placeholder="File">
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
<div class="modal modal-blur fade" id="modal-editSK" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit SK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadedEdit">

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
@endsection

@push('myscript')
<script>
    $(function() {
        $('#btnUploadCSV').click(function() {
            $('#modal-uploadContract').modal("show");
        });

        $('.edit').click(function() {
            var id = $(this).attr('id');
            $.ajax({
                type: 'POST',
                url: '/sk/edit',
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
            $('#modal-editSK').modal("show");
        });

        function initializeEventListeners() {
            if ($('#formEditContract').length) {
                console.log('Form Contract found');

                // Handle form submission
                $('#formEditContract').on('submit', function(event) {
                    var reasoning = $('#reasoning').val().trim();

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
        $(document).on('click', '#employeeList .dropdown-item', function(e) {
            e.preventDefault();
            var selectedName = $(this).text();
            var selectedNIK = $(this).data('nik');

            $('#nama_lengkap').val(selectedName);
            $('#nik').val(selectedNIK); // Assuming you want to set employee ID to another field like 'nik'

            $('#employeeList').hide();
        });

        // Hide dropdown on click outside
        $(document).click(function(e) {
            if (!$(e.target).closest('#employeeList').length && !$(e.target).closest('#nama_lengkap').length) {
                $('#employeeList').hide();
            }
        });


        $('#btnAddSK').click(function() {
            $('#modal-inputSk').modal("show");
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

        $('#formSK').submit(function() {
            var nik = $('#nik').val();
            var tgl_sk = $('#tgl_sk').val();
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
            } else if (tgl_sk == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Tgl SK Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#tgl_sk').focus();
                });
                return false;
            }
        });
    });
</script>
@endpush
