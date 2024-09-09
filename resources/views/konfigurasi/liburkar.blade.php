@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Konfigurasi
                </div>
                <h2 class="page-title">
                    Custom Libur Karyawan
                </h2>
                <br>
            </div>
        </div>
    </div>
</div>
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        document.addEventListener('DOMContentLoaded', function () {
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
                                <a href="#" class="btn btn-primary" id="btnTambahLibur">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Data
                                </a>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>ID</th>
                                            <th>Nama Karyawan</th>
                                            <th>Bulan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                        @foreach($liburkars as $d)
                                            <tr>
                                                <td>{{ $d->id}}</td>
                                                <td>{{ $d->nama_lengkap}}</td>
                                                <td>
                                                    {{ DateTime::createFromFormat('!m', $d->month)->format('F') }}
                                                </td>
                                                <td>
                                                    <!-- Each button on a separate row within the same cell -->
                                                    <div class="form-group">
                                                        <!-- Edit Button -->
                                                        <div class="mb-1">
                                                            <a href="#" class="edit btn btn-info btn-sm" id="{{ $d->id }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 18 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                    <path
                                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                    <path d="M16 5l3 3" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                        <!-- Time Button -->
                                                        <div class="mb-1">
                                                            <a href="#" class="btn btn-success btn-sm view-days"
                                                                data-libur-id="{{ $d->id }}">
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
                                                        <!-- Delete Button -->
                                                        <div>
                                                            <form action="/konfigurasi/liburkar/{{$d->id}}/delete"
                                                                method="POST" style="display: inline;">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-sm delete-confirm"
                                                                    style="border: none; padding-top: 3px; padding-bottom: 3px; padding-right: 5px; padding-left: 5px;">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 18 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                        <path d="M4 7l16 0" />
                                                                        <path d="M10 11l0 6" />
                                                                        <path d="M14 11l0 6" />
                                                                        <path
                                                                            d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                        <path
                                                                            d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
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
<div class="modal modal-blur fade" id="modal-inputlibur" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Custom Libur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/konfigurasi/liburkar/store" method="POST" id="formLibur">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">NIK</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M15 8l2 0" />
                                        <path d="M15 12l2 0" />
                                        <path d="M7 16l10 0" />
                                    </svg>
                                </span>
                                <input type="text" value="" class="form-control" name="nik" id="nik" placeholder="10101"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-label">Nama Karyawan</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                        <path d="M15 19l2 2l4 -4" />
                                    </svg>
                                </span>
                                <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap"
                                    placeholder="Type or select employee name" autocomplete="off">
                            </div>
                            <div id="employeeList" class="dropdown-menu" style="display:none; margin-top: -12px">
                                <!-- Dropdown items will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">Bulan</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-calendar">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                        <path d="M16 3v4" />
                                        <path d="M8 3v4" />
                                        <path d="M4 11h16" />
                                        <path d="M11 15h1" />
                                        <path d="M12 15v3" />
                                    </svg>
                                </span>
                                <select name="bulan" id="bulan" class="form-control">
                                    <option value="">Bulan</option>
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ $month }}" {{ request('bulan') == $month ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
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
<div class="modal modal-blur fade" id="modal-editlibur" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Custom Libur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditform">

            </div>
        </div>
    </div>
</div>
<!-- Modal for Viewing and Editing Leave Days -->
<div class="modal fade" id="daysModal" tabindex="-1" role="dialog" aria-labelledby="daysModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="margin-top: 160px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="daysModalLabel">Libur Karyawan Days</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="daysForm">
                    <input type="hidden" id="dayId" name="pattern_id" value="">
                    <table class="table table-striped" id="daysTable">
                        <thead>
                            <tr style="text-align: center;">
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary" id="addDayBtn">Add Day</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
    <script>
        $(function () {

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

            $('#btnTambahLibur').click(function () {
                $('#modal-inputlibur').modal("show");
            });

            $('.edit').click(function () {
                var id = $(this).attr('id');
                $.ajax({
                    type: 'POST',
                    url: '/konfigurasi/liburkar/edit',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function (respond) {
                        $('#loadeditform').html(respond);
                    }
                });
                $('#modal-editlibur').modal("show");
            });

            $(".delete-confirm").click(function (e) {
                var form = $(this).closest('form');
                e.preventDefault();
                Swal.fire({
                    title: "Apakah Yakin?",
                    text: "Data Cabang Akan Ke Delete!",
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

            $('#formLibur').submit(function (event) {
                function showWarning(message, selector) {
                    Swal.fire({
                        title: 'Warning!',
                        text: message,
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then(() => {
                        $(selector).focus();
                    });
                }

                var fields = [{
                    selector: '#nama_lengkap',
                    message: 'Nama Lengkap Harus Diisi'
                },
                {
                    selector: '#nik',
                    message: 'NIK Harus Diisi'
                }
                ];

                for (var i = 0; i < fields.length; i++) {
                    if ($(fields[i].selector).val() === "") {
                        showWarning(fields[i].message, fields[i].selector);
                        return false;
                    }
                }

                // No need to call event.preventDefault() or this.submit() here
                // because if the form is valid, it will submit naturally
            });

            // Handle the "View days" button click
            $('.view-days').click(function () {
                var liburId = $(this).data('libur-id');
                $('#dayId').val(liburId);
                $('#daysModal').modal('show');
                loadDays(liburId);
            });

            function loadDays(liburId) {
                $.ajax({
                    url: '/konfigurasi/liburkar/' + liburId + '/days',
                    method: 'GET',
                    success: function (data) {
                        var rows = '';
                        var hasDays = data.days.length > 0;

                        // Toggle button text based on existence of days
                        if (hasDays) {
                            $('#daysForm button[type="submit"]').text('Save Changes');
                        } else {
                            $('#daysForm button[type="submit"]').text('Save New');
                        }

                        $.each(data.days, function (index, day) {
                            var formattedDate = new Date(day.tanggal).toISOString().split('T')[0]; // Format date as YYYY-MM-DD
                            rows += '<tr>';
                            rows += '<td style="text-align: center;">' + day.id + '</td>';
                            rows += '<td><input type="date" class="form-control" name="tanggal[]" value="' + formattedDate + '" style="text-align: center;"></td>';
                            rows += '<td style="text-align: center;vertical-align: middle;">' +
                                '<form action="/konfigurasi/liburkar/days/' + day.id + '" method="POST" class="delete-day-form">' +
                                '@csrf' +
                                '<button type="submit" class="btn btn-danger btn-sm delete-day-btn">Delete</button>' +
                                '</form>' +
                                '</td>';
                            rows += '</tr>';
                        });
                        $('#daysTable tbody').html(rows);
                    }
                });
            }

            $('#addDayBtn').click(function () {
                var newRow = '<tr>';
                newRow += '<td style="text-align: center;">New</td>';
                newRow += '<td><input type="date" class="form-control" name="tanggal[]" style="text-align: center;"></td>';
                newRow += '<td style="text-align: center;vertical-align: middle;"><button type="button" class="btn btn-danger btn-sm delete-day-btn">Delete</button></td>';
                newRow += '</tr>';
                $('#daysTable tbody').append(newRow);
            });

            $('#daysForm').submit(function (event) {
                event.preventDefault();
                var formData = $(this).serialize(); // Serialize the form data
                formData += '&_token=' + encodeURIComponent("{{ csrf_token() }}");

                // Check if 'Save New' or 'Save Changes'
                var action = $('#daysForm button[type="submit"]').text() === 'Save New' ? 'create' : 'update';

                $.ajax({
                    url: '/konfigurasi/liburkar/' + $('#dayId').val() + '/days/' + action,
                    method: 'POST',
                    data: formData, // Send serialized form data directly
                    success: function (response) {
                        $('#daysModal').modal('hide');
                        location.reload(); // Refresh the page or update the table as needed
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText); // Log errors if any
                    }
                });
            });

        });
    </script>

@endpush
