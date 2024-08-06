@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Attendance
                </div>
                <h2 class="page-title">
                    Time Karyawan Attendance
                </h2>
                <br>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                            <path d="M15 19l2 2l4 -4" />
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Nama Karyawan" autocomplete="off">
                                    <input type="hidden" name="nip" id="nip"> <!-- Hidden input for NIP -->
                                </div>
                                <div id="employeeList" class="dropdown-menu" style="display:none; margin-top: -12px;">
                                    <!-- Dropdown items will be populated by JavaScript -->
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr style="text-align:center;">
                                            <th>No.</th>
                                            <th>Nip</th>
                                            <th>Nama Karyawan</th>
                                            <th>Department</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Total Jam Kerja</th>
                                        </tr>
                                    </thead>
                                    <tbody id="loadpresensi">

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
<div class="modal modal-blur fade" id="modal-tampilkanpeta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lokasi Presensi Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadmap">

            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(document).ready(function() {
        $('#nama_lengkap').on('input', function() {
            var nama_lengkap = $(this).val().trim();

            if (nama_lengkap.length >= 2) {
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
                                dropdownMenu.append('<a class="dropdown-item" href="#" data-nip="' + employee.nip + '" data-nama="' + employee.nama_lengkap + '">' + employee.nama_lengkap + '</a>');
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
            var selectedName = $(this).data('nama');
            var selectedNIP = $(this).data('nip');

            $('#nama_lengkap').val(selectedName); // Set the full name in the input field
            $('#nip').val(selectedNIP); // Set NIP to hidden input field

            $('#employeeList').hide();

            // Trigger change event to fetch attendance data immediately
            $('#nama_lengkap').trigger('change');
        });

        $(document).click(function(e) {
            if (!$(e.target).closest('#employeeList').length && !$(e.target).closest('#nama_lengkap').length) {
                $('#employeeList').hide();
            }
        });

        $("#nama_lengkap").change(function(e) {
            var nama_lengkap = $(this).val();
            var nip = $('#nip').val();

            $.ajax({
                type: 'GET',
                url: '/timeatt/get_att',
                data: {
                    _token: "{{ csrf_token() }}",
                    nama_lengkap: nama_lengkap,
                    nip: nip
                },
                cache: false,
                success: function(respond) {
                    $("#loadpresensi").html(respond);
                }
            });
        });
    });
</script>
@endpush
