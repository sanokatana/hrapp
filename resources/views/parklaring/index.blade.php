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
                    Surat Parklaring
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
                                <a href="#" class="btn btn-primary" id="btnAddParklaring">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Parklaring
                                </a>

                                <a href="/parklaring/export" class="btn btn-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-export">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M11.5 21h-4.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v5m-5 6h7m-3 -3l3 3l-3 3" />
                                    </svg>
                                    Export Data
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/parklaring" method="GET">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <input type="text" name="no_parklaring" id="no_parklaring" class="form-control" placeholder="Nomor Parklaring" value="{{ Request('no_parklaring')}}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Nama Karyawan" value="{{ Request('nama_karyawan')}}">
                                            </div>
                                        </div>
                                        <div class="col-4">
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
                                            <th>No Parklaring</th>
                                            <th>Nama Karyawan</th>
                                            <th>NIK</th>
                                            <th>Tanggal Terakhir Kerja</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($parklar as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $loop->iteration + $parklar->firstItem() -1 }}</td>
                                            <td>{{ $d->no_parklaring }}</td>
                                            <td>{{ $d->nama_lengkap }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ $d->tgl_terakhir }}</td>
                                            <td>{{ $d->created_at }}</td>
                                            <td>
                                                <div class="form-group d-flex justify-content-center gap-2">
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
                                                    <a href="#" class="btn btn-warning btn-sm print-confirm" data-id="{{ $d->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                                            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                                            <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                                                        </svg>
                                                    </a>
                                                    <form action="/parklaring/{{$d->id}}/delete" method="POST">
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
                                {{ $parklar->links('vendor.pagination.bootstrap-5')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Parklaring -->
<div class="modal modal-blur fade" id="modal-inputParklaring" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Parklaring</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/parklaring/store" method="POST" id="formParklaring">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-label">Nomor Parklaring</div>
                            <div class="d-flex align-items-center mb-2">
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="no_mode" id="auto_no" value="auto" checked>
                                    <span class="form-check-label">Otomatis</span>
                                </label>
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="no_mode" id="manual_no" value="manual">
                                    <span class="form-check-label">Manual</span>
                                </label>
                            </div>
                            <div id="manual_number_container" style="display: none;">
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-text">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                            <path d="M9 9l1 0" />
                                            <path d="M9 13l6 0" />
                                            <path d="M9 17l6 0" />
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control" name="manual_no_parklaring" id="manual_no_parklaring" placeholder="000/HC/SBC/II/2025" autocomplete="off">
                                </div>
                                <small class="form-hint">Format: 000/HC/SBC/II/2025</small>
                            </div>
                        </div>
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
                                        <path stroke="none" d="M0 0h24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                        <path d="M15 19l2 2l4 -4" />
                                    </svg>
                                </span>
                                <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Type or select employee name" autocomplete="off">
                            </div>
                            <div id="employeeList" class="dropdown-menu" style="display:none; margin-top: -12px; width: 100%;">
                                <!-- Dropdown items will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">Tanggal Terakhir Kerja</div>
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
                                <input type="date" value="" class="form-control" name="tgl_terakhir" id="tgl_terakhir">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M14 4l0 4l-6 0l0 -4" />
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

<!-- Modal View Parklaring -->
<div class="modal modal-blur fade" id="modal-viewParklaring" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Parklaring</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadedView">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {

        $('input[name="no_mode"]').change(function() {
            if ($(this).val() === 'manual') {
                $('#manual_number_container').show();
            } else {
                $('#manual_number_container').hide();
                $('#manual_no_parklaring').val('');
            }
        });

        $('#btnAddParklaring').click(function() {
            $('#modal-inputParklaring').modal("show");
        });

        // NIK blur event - fetch employee name
        $('#nama_lengkap').on('input', function() {
            var nama_lengkap = $(this).val().trim();

            if (nama_lengkap.length >= 2) {
                $.ajax({
                    url: '/parklaring/getEmployeeName',
                    type: 'GET',
                    data: {
                        nama_lengkap: nama_lengkap
                    },
                    success: function(response) {
                        var dropdownMenu = $('#employeeList');
                        dropdownMenu.empty();

                        if (response.length > 0) {
                            response.forEach(function(employee) {
                                // Include additional employee info in the dropdown
                                var employeeInfo = employee.nama_lengkap;
                                if (employee.jabatan) {
                                    employeeInfo += ' - ' + employee.jabatan;
                                }
                                if (employee.department) {
                                    employeeInfo += ' (' + employee.department + ')';
                                }

                                dropdownMenu.append('<a class="dropdown-item" href="#" data-nik="' +
                                    employee.nik + '" data-tgl="' +
                                    (employee.tgl_resign || '') + '">' +
                                    employeeInfo + '</a>');
                            });

                            // Show the dropdown properly
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

        // Update the NIK blur handler too
        $('#nik').on('blur', function() {
            var nik = $(this).val();
            if (nik !== "") {
                $.ajax({
                    url: '/parklaring/getEmployeeByNik',
                    type: 'GET',
                    data: {
                        nik: nik
                    },
                    success: function(response) {
                        if (response) {
                            $('#nama_lengkap').val(response.nama_lengkap);

                            // If employee already has a resignation date, use it as default
                            if (response.tgl_resign) {
                                $('#tgl_terakhir').val(response.tgl_resign);
                            }
                        } else {
                            $('#nama_lengkap').val('');
                        }
                    }
                });
            }
        });

        // Update dropdown click handler
        $(document).on('click', '#employeeList .dropdown-item', function(e) {
            e.preventDefault();
            var selectedName = $(this).text().split(' - ')[0]; // Get just the name part
            var selectedNIK = $(this).data('nik');
            var selectedTgl = $(this).data('tgl');

            $('#nama_lengkap').val(selectedName);
            $('#nik').val(selectedNIK);
            if (selectedTgl) {
                $('#tgl_terakhir').val(selectedTgl);
            }
            $('#employeeList').hide();
        });

        // Hide dropdown on click outside
        $(document).click(function(e) {
            if (!$(e.target).closest('#employeeList').length && !$(e.target).closest('#nama_lengkap').length) {
                $('#employeeList').hide();
            }
        });

        // The rest of your JavaScript remains unchanged
        $('.view').click(function() {
            var id = $(this).attr('id');
            $.ajax({
                type: 'POST',
                url: '/parklaring/view',
                cache: false,
                data: {
                    _token: "{{ csrf_token();}}",
                    id: id
                },
                success: function(respond) {
                    $('#loadedView').html(respond);
                }
            });
            $('#modal-viewParklaring').modal("show");
        });

        // Print parklaring
        $('.print-confirm').click(function() {
            var id = $(this).data('id');
            window.open('/parklaring/' + id + '/print', '_blank');
        });

        // Delete confirmation
        $(".delete-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Yakin?",
                text: "Data Parklaring Akan Dihapus!",
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

        // Form validation
        $('#formParklaring').submit(function() {
            var nik = $('#nik').val();
            var nama_lengkap = $('#nama_lengkap').val();
            var tgl_terakhir = $('#tgl_terakhir').val();
            var no_mode = $('input[name="no_mode"]:checked').val();
            var manual_no = $('#manual_no_parklaring').val();

            if (no_mode === 'manual' && manual_no === "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Nomor Parklaring Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#manual_no_parklaring').focus();
                });
                return false;
            } else if (nik === "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'NIK Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#nik').focus();
                });
                return false;
            } else if (nama_lengkap === "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Nama Karyawan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#nama_lengkap').focus();
                });
                return false;
            } else if (tgl_terakhir === "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Tanggal Terakhir Kerja Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#tgl_terakhir').focus();
                });
                return false;
            }

            // If manual mode, validate format
            if (no_mode === 'manual') {
                var regex = /^\d{3}\/HC\/[A-Z]+\/[IVX]+\/\d{4}$/;
                if (!regex.test(manual_no)) {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Format Nomor Parklaring tidak valid (000/HC/SBC/II/2025)',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then(() => {
                        $('#manual_no_parklaring').focus();
                    });
                    return false;
                }
            }
        });
    });
</script>
@endpush
