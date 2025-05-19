@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Master Data
                </div>
                <h2 class="page-title">
                    Cuti
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
@elseif(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Error!',
            text: "{{ session('error') }}",
            icon: 'error',
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
                                <a href="#" class="btn btn-primary" id="btnTambahCuti">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Data
                                </a>
                                <a href="{{ route('cek.cuti.karyawan') }}" class="btn btn-info" id="btnCekCuti">
                                    Cek Cuti Karyawan
                                </a>
                                <a href="#" class="btn btn-danger" id="btnUploadCuti">
                                    Upload Cuti
                                </a>
                                <a href="/cuti/export" class="btn btn-success">
                                    Export CSV
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/cuti" method="GET">
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-group">
                                                <input type="text" name="nama_kar" id="nama_kar" class="form-control" placeholder="Nama Karyawan" autocomplete="off" value="{{ Request('nama_kar')}}">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <input type="text" name="nik_req" id="nik_req" class="form-control" placeholder="NIK" autocomplete="off" value="{{ Request('nik_req')}}">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <input type="text" name="tahun_req" id="tahun_req" class="form-control" placeholder="Tahun" autocomplete="off" value="{{ Request('tahun_req')}}">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group mb-3">
                                                <select name="kode_dept" id="kode_dept" class="form-select">
                                                    <option value="">Department</option>
                                                    @foreach ($department as $d)
                                                    <option {{ Request('kode_dept')==$d->kode_dept ? 'selected' : ''}} value="{{$d->kode_dept}}">{{$d->nama_dept}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <select name="status" id="status" class="form-select">
                                                    <option value="pilih" {{ request('status') === 'pilih' ? 'selected' : '' }}>Pilih Status</option>
                                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Non Aktif</option>
                                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                                                </select>
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
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Department</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Periode Cuti</th>
                                            <th>Periode Awal</th>
                                            <th>Periode Akhir</th>
                                            <th>Sisa Cuti</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cuti as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $loop->iteration + $cuti->firstItem() -1 }}</td>
                                            <td>{{ $d->nik}}</td>
                                            <td>{{ $d->nama_lengkap}}</td>
                                            <td>{{ $d->nama_dept}}</td>
                                            <td>{{ $d->tgl_masuk}}</td>
                                            <td>{{ $d->tahun}}</td>
                                            <td>{{ $d->periode_awal}}</td>
                                            <td>{{ $d->periode_akhir}}</td>
                                            <td>{{ $d->sisa_cuti}}</td>
                                            <td>@if ($d->status == 0)
                                                <span class="badge bg-danger" style="color: white">Non Aktif</span>
                                                @else
                                                <span class="badge bg-success" style="color: white">Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <a href="#" class="edit btn btn-info btn-sm" id="{{ $d->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                            <path d="M16 5l3 3" />
                                                        </svg>
                                                    </a>
                                                    <form action="/cuti/{{$d->id}}/delete" method="POST">
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
                                            @endforeach
                                    </tbody>
                                </table>
                                {{ $cuti->links('vendor.pagination.bootstrap-5')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-inputcuti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/cuti/store" method="POST" id="formCuti" enctype="multipart/form-data">
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
                        <div class="col-12">
                            <div class="form-label">Periode Cuti</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-stats">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4" />
                                        <path d="M18 14v4h4" />
                                        <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                        <path d="M15 3v4" />
                                        <path d="M7 3v4" />
                                        <path d="M3 11h16" />
                                    </svg>
                                </span>
                                <input type="number" value="" class="form-control" name="tahun" id="tahun" placeholder="Periode" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-label">Periode Awal</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-stats">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4" />
                                        <path d="M18 14v4h4" />
                                        <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                        <path d="M15 3v4" />
                                        <path d="M7 3v4" />
                                        <path d="M3 11h16" />
                                    </svg>
                                </span>
                                <input type="date" value="" class="form-control" name="periode_awal" id="periode_awal" placeholder="Tanggal" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-label">Periode Akhir</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-stats">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4" />
                                        <path d="M18 14v4h4" />
                                        <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                        <path d="M15 3v4" />
                                        <path d="M7 3v4" />
                                        <path d="M3 11h16" />
                                    </svg>
                                </span>
                                <input type="date" value="" class="form-control" name="periode_akhir" id="periode_akhir" placeholder="Tanggal" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">Sisa Cuti</div>
                            <div class="input-icon mb-3">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-number-123">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 10l2 -2v8" />
                                        <path d="M9 8h3a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 0 -1 1v2a1 1 0 0 0 1 1h3" />
                                        <path d="M17 8h2.5a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1 -1.5 1.5h-1.5h1.5a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1 -1.5 1.5h-2.5" />
                                    </svg>
                                </span>
                                <input type="number" value="" class="form-control" name="sisa_cuti" id="sisa_cuti" placeholder="Sisa Cuti" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">Status</label>
                            <div class="form-group mb-3">
                                <select name="status" id="status" class="form-select">
                                    <option value="pilih">Pilih Level</option>
                                    <option value="0">Non-Aktif</option>
                                    <option value="1">Aktif</option>\
                                </select>
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
<!-- Modal Upload CSV -->
<div class="modal modal-blur fade" id="modal-uploadCuti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Cuti Karyawan CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/cuti/uploadCuti" method="POST" id="formCuti" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="file">Upload Excel</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    Simpan
                                </button>
                            </div>
                            <div class="form-group mt-3">
                                <a href="/cuti/downloadTemplate" class="btn btn-secondary w-100">
                                    Download Template Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal modal-blur fade" id="modal-editcuti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditform">

            </div>
        </div>
    </div>
</div>

<!-- Modal for displaying cuti updates -->
<div class="modal modal-blur fade" id="modal-cuti-preview" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pembaruan Cuti Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Karyawan berikut telah melewati periode cuti dan akan diperbarui:</p>
                <div class="table-responsive">
                    <table id="preview-table" class="table table-vcenter table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Karyawan</th>
                                <th>NIK</th>
                                <th>Periode</th>
                                <th>Sisa Cuti Sebelumnya</th>
                                <th>Sisa Cuti Baru</th>
                                <th>Lama Bekerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-confirm-update">Lanjutkan Update</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        $('#btnCekCuti').on('click', function(e) {
            e.preventDefault();

            // Show loading message
            Swal.fire({
                title: 'Memeriksa...',
                text: 'Sedang memeriksa karyawan dengan periode cuti berakhir',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // AJAX call to get employees with expired periods
            $.ajax({
                url: '{{ route("cek.cuti.preview") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    Swal.close();

                    if (response.employees.length > 0) {
                        // Populate the modal with employee data
                        populatePreviewModal(response.employees);
                        $('#modal-cuti-preview').modal('show');
                    } else {
                        Swal.fire({
                            title: 'Informasi',
                            text: 'Tidak ada karyawan yang periode cutinya telah berakhir',
                            icon: 'info'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memeriksa data',
                        icon: 'error'
                    });
                }
            });
        });

        // Function to populate the preview modal
        function populatePreviewModal(employees) {
    var tbody = $('#preview-table tbody');
    tbody.empty();

    // Calculate the new values for each employee
    employees.forEach(function(emp) {
        // Ensure we're working with numbers by explicitly converting
        var sisaCuti = parseInt(emp.sisa_cuti) || 0;
        var pinjam = parseInt(emp.pinjam) || 0;
        var tunda = parseInt(emp.tunda) || 0;

        // Determine entitlement based on employee status
        var entitlement = emp.employee_status === 'Tetap' ? 15 : 12;

        // Calculate employment duration in YEARS at the end of current period
        var joinDate = new Date(emp.tgl_masuk);
        var periodEndDate = new Date(emp.periode_akhir);

        // Calculate total months for display
        var employmentMonths = (periodEndDate.getFullYear() - joinDate.getFullYear()) * 12 +
                             (periodEndDate.getMonth() - joinDate.getMonth());

        // Calculate years for decision logic
        var employmentYears = Math.floor(employmentMonths / 12);

        // Check if this is the first year period (start date equals join date)
        var periodStartDate = new Date(emp.periode_awal);
        var isFirstYearPeriod = (
            periodStartDate.getFullYear() === joinDate.getFullYear() &&
            periodStartDate.getMonth() === joinDate.getMonth() &&
            periodStartDate.getDate() === joinDate.getDate()
        );

        // Determine leave logic based on employment duration
        var newSisaCuti;
        var statusText = '';
        var isNegative = sisaCuti < 0;

        if (employmentYears < 1) {
            // Less than 1 year of employment - keep existing balance
            newSisaCuti = sisaCuti;
            statusText = '<span class="badge bg-info text-white">Under 1 Year</span>';
        } else if (employmentYears === 1) {
            // Exactly 1 year - different rules based on balance
            if (isNegative) {
                // Negative balance - give them full entitlement plus negative balance
                newSisaCuti = entitlement + sisaCuti; // Adding negative = subtracting
                statusText = '<span class="badge bg-warning text-white">1 Year (Negative + New)</span>';
            } else {
                // Positive balance - carry over existing balance
                newSisaCuti = sisaCuti;
                statusText = '<span class="badge bg-primary text-white">1 Year (Carryover)</span>';
            }
        } else {
            // More than 1 year of employment
            if (isNegative) {
                // Negative balance - give them full entitlement plus negative balance
                newSisaCuti = entitlement + sisaCuti;
                statusText = '<span class="badge bg-danger text-white">Negative Balance</span>';
            } else {
                // Positive balance - get fresh entitlement
                newSisaCuti = entitlement;
                statusText = '<span class="badge bg-secondary text-white">Regular Renewal</span>';
            }
        }

        // Adjust for pinjam and tunda
        newSisaCuti = newSisaCuti - pinjam + tunda;

        // Calculate new period
        var newPeriode = parseInt(emp.tahun) + 1;

        // Add calculation explanation for clearer understanding
        var calcExplanation = '';
        if (isNegative) {
            calcExplanation = `<small class="d-block text-muted">${entitlement} + (${sisaCuti}) = ${entitlement + sisaCuti}</small>`;
            if (pinjam > 0 || tunda > 0) {
                calcExplanation += `<small class="d-block text-muted">Adjust: ${pinjam > 0 ? '-'+pinjam : ''} ${tunda > 0 ? '+'+tunda : ''}</small>`;
            }
        }

        var row = `
            <tr>
                <td>${emp.nama_lengkap} ${statusText}</td>
                <td>${emp.nik}</td>
                <td>${emp.tahun} → ${newPeriode}</td>
                <td>${sisaCuti < 0 ? '<span class="text-danger">'+sisaCuti+'</span>' : sisaCuti}</td>
                <td>
                    ${newSisaCuti < 0 ? '<span class="text-danger">'+newSisaCuti+'</span>' : newSisaCuti}
                    ${calcExplanation}
                </td>
                <td>${employmentMonths} months (${employmentYears} years)</td>
            </tr>
        `;
        tbody.append(row);
    });
}

        // Handle confirm button in preview modal
        $('#btn-confirm-update').on('click', function() {
            // Close the preview modal
            $('#modal-cuti-preview').modal('hide');

            // Show loading message
            Swal.fire({
                title: 'Sedang Memproses',
                text: 'Memperbarui data cuti karyawan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Redirect to the actual update route
            window.location.href = '{{ route("cek.cuti.karyawan") }}';
        });

        $('#btnTambahCuti').click(function() {
            $('#modal-inputcuti').modal("show");
        });

        $('#btnUploadCuti').click(function() {
            $('#modal-uploadCuti').modal("show");
        });

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
                                dropdownMenu.append('<a class="dropdown-item" href="#" data-nik="' + employee.nik + '" data-pawal="' + employee.tgl_masuk + '">' + employee.nama_lengkap + '</a>');
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
            var tglMasuk = $(this).data('pawal');

            if (tglMasuk) {
                var periodeAwal = new Date(tglMasuk);
                var periodeAkhir = new Date(periodeAwal);
                periodeAkhir.setFullYear(periodeAkhir.getFullYear() + 1);
                periodeAkhir.setDate(periodeAkhir.getDate() - 1); // Subtract one day for correct end date

                var formattedPeriodeAwal = periodeAwal.toISOString().split('T')[0]; // Format as YYYY-MM-DD
                var formattedPeriodeAkhir = periodeAkhir.toISOString().split('T')[0]; // Format as YYYY-MM-DD
                var tahun = periodeAwal.getFullYear(); // Extract year from tgl_masuk

                $('#periode_awal').val(formattedPeriodeAwal);
                $('#periode_akhir').val(formattedPeriodeAkhir);
                $('#tahun').val(tahun); // Set the extracted year
            }

            $('#nama_lengkap').val(selectedName);
            $('#nik').val(selectedNIK);
            $('#employeeList').hide();
        });

        // Hide dropdown on click outside
        $(document).click(function(e) {
            if (!$(e.target).closest('#employeeList').length && !$(e.target).closest('#nama_lengkap').length) {
                $('#employeeList').hide();
            }
        });

        $('.edit').click(function() {
            var id = $(this).attr('id');
            $.ajax({
                type: 'GET',
                url: '/cuti/' + id + '/edit', // Ensure this route returns the form HTML
                success: function(response) {
                    $('#loadeditform').html(response);
                    $('#modal-editcuti').modal("show");
                }
            });
        });

        $(".delete-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Yakin?",
                text: "Data Cuti Karyawan Akan Ke Delete!",
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

        $('#formCuti').submit(function() {
            var nik = $('#nik').val();
            var tahun = $('#tahun').val();
            var sisa_cuti = $('#sisa_cuti').val();
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
            } else if (tahun == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Tahun Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#tahun').focus();
                });
                return false;
            } else if (sisa_cuti == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Sisa Cuti Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#sisa_cuti').focus();
                });
                return false;
            }
        });
    });
</script>
@endpush
