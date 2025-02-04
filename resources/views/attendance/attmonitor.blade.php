@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Attendance
                </div>
                <h2 class="page-title">
                    Karyawan Attendance
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
                        <form id="filterForm">
                            <div class="row mb-3">
                                <!-- Employee Search -->
                                <div class="col-md-4">
                                    <div class="input-icon mb-3">
                                        <span class="input-icon-addon">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Nama Karyawan" autocomplete="off">
                                        <input type="hidden" name="nip" id="nip">
                                    </div>
                                    <div id="employeeList" class="dropdown-menu" style="display:none; margin-top: -12px;"></div>
                                </div>

                                <!-- Month Filter -->
                                <div class="col-md-3">
                                    <select name="bulan" id="bulan" class="form-control">
                                        <option value="">Pilih Bulan</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Year Filter -->
                                <div class="col-md-3">
                                    <select name="tahun" id="tahun" class="form-control">
                                        <option value="">Pilih Tahun</option>
                                        @for ($i = date('Y'); $i >= date('Y') - 10; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Search Button -->
                                <div class="col-md-2">
                                    <button type="button" id="btnCari" class="btn btn-primary w-100">
                                        Cari
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr style="text-align:center;">
                                            <th>No.</th>
                                            <th>Nip</th>
                                            <th>Nama Karyawan</th>
                                            <th>Department</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Pulang</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="loadpresensi">
                                        <!-- Data will be loaded here -->
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
            <div class="modal-body" id="loadmap"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function () {
        let dropdownSelectTriggered = false;

        // Employee Search
        $('#nama_lengkap').on('input', function () {
            var nama_lengkap = $(this).val().trim();
            if (nama_lengkap.length >= 2) {
                $.ajax({
                    url: '/cuti/getEmployeeNameNIP',
                    type: 'GET',
                    data: { nama_lengkap: nama_lengkap },
                    success: function (response) {
                        var dropdownMenu = $('#employeeList');
                        dropdownMenu.empty();
                        if (response.length > 0) {
                            response.forEach(function (employee) {
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

        $(document).on('click', '#employeeList .dropdown-item', function (e) {
            e.preventDefault();
            $('#nama_lengkap').val($(this).data('nama'));
            $('#nip').val($(this).data('nip'));
            $('#employeeList').hide();
            dropdownSelectTriggered = true;
            $('#nama_lengkap').trigger('change');
        });

        $(document).click(function (e) {
            if (!$(e.target).closest('#employeeList, #nama_lengkap').length) {
                $('#employeeList').hide();
            }
        });

        // Search Button Click
        $('#btnCari').on('click', function () {
            loadPresensi();
        });

        // Enter Key Search
        // $('#nama_lengkap, #bulan, #tahun').on('change keyup', function (e) {
        //     if (e.type === 'change' || (e.type === 'keyup' && e.key === 'Enter')) {
        //         loadPresensi();
        //     }
        // });

        function loadPresensi() {
            var nama_lengkap = $('#nama_lengkap').val().trim();
            var nip = $('#nip').val();
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();

            $.ajax({
                type: 'GET',
                url: '/attendance/get_att',
                data: {
                    _token: "{{ csrf_token() }}",
                    nama_lengkap: nama_lengkap,
                    nip: nip,
                    bulan: bulan,
                    tahun: tahun
                },
                success: function (respond) {
                    $('#loadpresensi').html(respond);
                }
            });
        }
    });
</script>
@endpush
