@extends('layouts.admin.tabler')
@section('content')
@php
use App\Helpers\DateHelper;
@endphp
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Approval
                </div>
                <h2 class="page-title">
                    Approval Izin Absen
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
                                <form action="/approval/izinapproval" method="GET" autocomplete="off">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-month">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                                        <path d="M16 3v4" />
                                                        <path d="M8 3v4" />
                                                        <path d="M4 11h16" />
                                                        <path d="M7 14h.013" />
                                                        <path d="M10.01 14h.005" />
                                                        <path d="M13.01 14h.005" />
                                                        <path d="M16.015 14h.005" />
                                                        <path d="M13.015 17h.005" />
                                                        <path d="M7.01 17h.005" />
                                                        <path d="M10.01 17h.005" />
                                                    </svg>
                                                </span>
                                                <input type="text" value="{{ Request('dari')}}" class="form-control" name="dari" id="dari" placeholder="Dari">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-month">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                                        <path d="M16 3v4" />
                                                        <path d="M8 3v4" />
                                                        <path d="M4 11h16" />
                                                        <path d="M7 14h.013" />
                                                        <path d="M10.01 14h.005" />
                                                        <path d="M13.01 14h.005" />
                                                        <path d="M16.015 14h.005" />
                                                        <path d="M13.015 17h.005" />
                                                        <path d="M7.01 17h.005" />
                                                        <path d="M10.01 17h.005" />
                                                    </svg>
                                                </span>
                                                <input type="text" value="{{ Request('sampai')}}" class="form-control" name="sampai" id="sampai" placeholder="Sampai">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                    </svg>
                                                </span>
                                                <input type="text" value="{{ Request('nik')}}" class="form-control" name="nik" id="nik" placeholder="NIK">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                    </svg>
                                                </span>
                                                <input type="text" value="{{ Request('nama_lengkap')}}" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Nama Karyawan">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <select name="status_approved" id="status_approved" class="form-select">
                                                    <option value="pilih" {{ request('status_approved') === 'pilih' ? 'selected' : '' }}>Pilih Status Manager</option>
                                                    <option value="0" {{ request('status_approved') === '0' ? 'selected' : '' }}>Pending</option>
                                                    <option value="1" {{ request('status_approved') === '1' ? 'selected' : '' }}>Approved</option>
                                                    <option value="2" {{ request('status_approved') === '2' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <button class="btn btn-primary w-100" type="submit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                        <path d="M21 21l-6 -6" />
                                                    </svg>
                                                    Cari Data
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>No.</th>
                                            <th>NIK.</th>
                                            <th>Nama Karyawan</th>
                                            <th>Jabatan</th>
                                            <th>Tanggal Izin</th>
                                            <th>Sampai Tanggal</th>
                                            <th>Jumlah Hari</th>
                                            <th>Tipe Izin</th>
                                            <th>Keterangan</th>
                                            <th>Pukul</th>
                                            <th>Document</th>
                                            <th>Status Manager <br>-------------------<br> Status HRD </th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($izinapproval as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $loop->iteration + $izinapproval->firstItem() -1}} </td>
                                            <td>{{ $d->nik }} </td>
                                            <td>{{ $d->nama_lengkap }}</td>
                                            <td>{{ $d->nama_jabatan }}</td>
                                            <td>@if ($d->tgl_izin)
                                                {{ DateHelper::formatIndonesianDate($d->tgl_izin) }}
                                                @endif
                                            </td>
                                            <td>@if ($d->tgl_izin_akhir)
                                                {{ DateHelper::formatIndonesianDate($d->tgl_izin_akhir) }}
                                                @endif
                                            </td>
                                            <td>{{ $d->jml_hari }} </td>
                                            <td>{{ DateHelper::getStatusText($d->status) }}</td>
                                            <td>{{ $d->keterangan}} </td>
                                            <td>
                                                @if ($d->pukul)
                                                {{ DateHelper::formatTimeToPM($d->pukul) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($d->foto === "No_Document")
                                                <a href="#" class="badge bg-info btnNoDoc" style="width:100px; display:flex; align-items:center; justify-content:center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" style="margin-right: 5px;" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-off">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M3 3l18 18" />
                                                        <path d="M7 3h7l5 5v7m0 4a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-14" />
                                                    </svg>
                                                    No File
                                                </a>
                                                @else
                                                <a href="#" class="badge bg-info btnDocument" style="width:100px; display:flex; align-items:center; justify-content:center" data-id="{{ $d->id }}" data-photo-url="{{ Storage::url('uploads/karyawan/'.$d->nip . '.' .$d->nama_lengkap. '/'.$d->foto) }}" >
                                                    <svg xmlns="http://www.w3.org/2000/svg" style="margin-right: 5px;" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file">
                                                        <path stroke="none" d="M0 0h24V24H0z" fill="none" />
                                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h9l5 5v11a2 2 0 0 1 -2 2z" />
                                                    </svg>
                                                    Check
                                                </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($d->status_approved == 1)
                                                <span class="badge bg-success" style="color: white; width:90px">Approved</span>
                                                @elseif ($d->status_approved == 0)
                                                <span class="badge bg-yellow" style="color: white; width:90px">Pending</span>
                                                @else
                                                <span class="badge bg-red" style="color: white; width:90px">Rejected</span>
                                                @endif
                                                @if ($d->status_approved_hrd == 1)
                                                <span class="badge bg-success mt-1" style="color: white; width:90px">Approved</span>
                                                @elseif ($d->status_approved_hrd == 0)
                                                <span class="badge bg-yellow mt-1" style="color: white; width:90px">Pending</span>
                                                @else
                                                <span class="badge bg-red mt-1" style="color: white; width:90px">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($d->status_approved == 0)
                                                <a href="#" class="badge bg-success btnApprove" style="width:90px; justify-content:space-between" data-id="{{ $d->id }}">
                                                    Approve
                                                </a>
                                                @else
                                                <a href="#" class="badge bg-danger btnBatalApprove" style="width:90px" id="btnBatalApprove" data-id="{{ $d->id }}">
                                                    Batalkan
                                                </a>
                                                @endif
                                                <a href="#" class="badge bg-info btnPrint mt-1" style="width:90px" id="btnPrint" data-id="{{ $d->id }}">
                                                    Print
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $izinapproval->links('vendor.pagination.bootstrap-5')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-izinapproval" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approval Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/approval/approveizin" method="POST">
                    @csrf
                    <input type="hidden" id="id_izin_form" name="id_izin_form">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="status_approved" id="status_approved" class="form-select">
                                    <option value="1">Disetujui</option>
                                    <option value="2">Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group" id="keputusanContainer" style="display: none;">
                                <input placeholder="Keputusan" class="form-control" type="text" id="keputusan" name="keputusan" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100" id="submitBtn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-send-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4.698 4.034l16.302 7.966l-16.302 7.966a.503 .503 0 0 1 -.546 -.124a.555 .555 0 0 1 -.12 -.568l2.468 -7.274l-2.468 -7.274a.555 .555 0 0 1 .12 -.568a.503 .503 0 0 1 .546 -.124z" />
                                        <path d="M6.5 12h14.5" />
                                    </svg>
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-document" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full-width modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Photo Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img style="width: 80%;">
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function() {
        $("#dari").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
        $("#sampai").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        $("[id='status_approved']").change(function() {
            var selectedStatus = $(this).val();
            if (selectedStatus === "2") {
                $("#keputusanContainer").show();
            } else {
                $("#keputusanContainer").hide();
            }
        });

        $(document).on('click', '.btnApprove', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            $('#id_izin_form').val(id);
            $('#modal-izinapproval').modal("show");
        });

        $(document).on('click', '.btnDocument', function(e) {
            e.preventDefault();
            var photoUrl = $(this).data("photo-url");
            // Open the URL in a new tab
            window.open(photoUrl, '_blank');
        });


        $(document).on('click', '.btnNoDoc', function(e) {
            e.preventDefault();
            Swal.fire({
                title: "No File",
                text: "No Document Found",
                icon: "error"
            });
        });

        // Submit button click event
        document.getElementById('submitBtn').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default form submission
            $('#modal-izinapproval').modal("hide");
            // Show confirmation dialog
            Swal.fire({
                title: 'Apakah Yakin ?',
                text: "Form ini Akan Ke Approve!",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Approve!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form when confirmed
                    $('#modal-izinapproval form').submit();
                } else {
                    $('#modal-izinapproval').modal("show");
                }
            });
        });

        $(document).on('click', '.btnBatalApprove', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '/approval/batalapprove/' + id,
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'Approval has been cancelled.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was an issue cancelling the approval.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'There was an issue cancelling the approval.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // Print button click event
        $(document).on('click', '.btnPrint', async function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            try {
                // Fetch form data
                const response = await $.ajax({
                    url: '/approval/printIzin',
                    type: 'GET',
                    data: {
                        id: id
                    },
                    dataType: 'json'
                });

                if (response.error) {
                    throw new Error(response.error);
                }

                // Fetch the PDF template
                const pdfTemplateResponse = await fetch('{{ route("pdfIzin.template") }}',{
                    cache: 'no-cache'
                });
                if (!pdfTemplateResponse.ok) {
                    throw new Error('Failed to fetch the PDF template.');
                }
                const pdfTemplateBytes = await pdfTemplateResponse.arrayBuffer();

                // Load the PDF with pdf-lib
                const {
                    PDFDocument
                } = PDFLib;
                const pdfDoc = await PDFDocument.load(pdfTemplateBytes);

                // Get the form fields
                const form = pdfDoc.getForm();

                // Set form values from the data
                const nameField = form.getTextField('nama_lengkap'); // Replace with actual field name
                nameField.setText(response.nama_lengkap);

                const departmentField = form.getTextField('bagian'); // Replace with actual field name
                departmentField.setText(response.bagian);

                const tglField = form.getTextField('tanggal'); // Replace with actual field name
                tglField.setText(response.tanggal);

                const checkboxes = [{
                        field: 'status',
                        value: 'Tmk'
                    },
                    {
                        field: 'status1',
                        value: 'Dt'
                    },
                    {
                        field: 'status2',
                        value: 'Pa'
                    },
                    {
                        field: 'status3',
                        value: 'Tam'
                    },
                    {
                        field: 'status4',
                        value: 'Tap'
                    },
                    {
                        field: 'status5',
                        value: 'Tjo'
                    },
                    // Add other status fields if needed
                ];

                checkboxes.forEach(({
                    field,
                    value
                }) => {
                    const checkboxField = form.getCheckBox(field); // Replace with actual checkbox field names
                    if (response.status === value) {
                        checkboxField.check();
                    } else {
                        checkboxField.uncheck();
                    }
                });

                const checkboxesKep = [{
                        field: 'ket',
                        value: 'Terlambat'
                    },
                    {
                        field: 'ket1',
                        value: 'Pulang Awal'
                    },
                    {
                        field: 'ket2',
                        value: 'Ijin'
                    },
                    {
                        field: 'ket3',
                        value: 'Mangkir'
                    },
                    {
                        field: 'ket4',
                        value: 'Sakit'
                    },
                    {
                        field: 'ket5',
                        value: 'Lain-lain'
                    },
                    {
                        field: 'ket6',
                        value: 'Tugas Luar'
                    },
                    {
                        field: 'ket7',
                        value: 'Potong Cuti'
                    },
                    {
                        field: 'ket8',
                        value: 'Tukar Jadwal Off'
                    },
                    // Add other status fields if needed
                ];

                if (response.keputusan) {
                    let isMatched = false;

                    checkboxesKep.forEach(({
                        field,
                        value
                    }) => {
                        const checkboxFieldKep = form.getCheckBox(field); // Replace with actual checkbox field names
                        if (response.keputusan === value) {
                            checkboxFieldKep.check();
                            isMatched = true;
                        } else {
                            checkboxFieldKep.uncheck();
                        }
                    });

                    // If no match is found, check 'Lain-lain'
                    if (!isMatched) {
                        const lainLainField = form.getCheckBox('ket5'); // Replace with actual field name for 'Lain-lain'
                        lainLainField.check();
                    }
                } else {
                    // If 'keputusan' is null or empty, uncheck all checkboxes
                    checkboxesKep.forEach(({
                        field
                    }) => {
                        const checkboxFieldKep = form.getCheckBox(field);
                        checkboxFieldKep.uncheck();
                    });
                }

                const keteranganField = form.getTextField('alesan'); // Replace with actual field name for the first field
                const keteranganFieldCont = form.getTextField('alesan1'); // Replace with actual field name for the continuation field

                let maxLength = 51; // The maximum number of characters allowed in the first field

                // Function to split the text by word boundary
                function splitTextByWords(text, maxLength) {
                    if (text.length <= maxLength) {
                        return [text, '']; // If text fits within the limit, no splitting is needed
                    }

                    let lastSpaceIndex = text.lastIndexOf(' ', maxLength); // Find the last space within the limit
                    if (lastSpaceIndex === -1) {
                        // If no space is found, break at the maximum length
                        lastSpaceIndex = maxLength;
                    }

                    const firstPart = text.substring(0, lastSpaceIndex).trim(); // Get the first part
                    const remainingText = text.substring(lastSpaceIndex).trim(); // Get the remaining part

                    return [firstPart, remainingText];
                }

                // Use the function to split the text
                const [firstPart, secondPart] = splitTextByWords(response.keterangan, maxLength);

                // Set text fields
                keteranganField.setText(firstPart);
                if (secondPart) {
                    keteranganFieldCont.setText(secondPart);
                }

                // Serialize the PDF document to bytes
                const pdfBytes = await pdfDoc.save();

                // Create a blob from the PDF bytes
                const blob = new Blob([pdfBytes], { type: 'application/pdf' });

                // Create a URL for the blob
                const blobUrl = window.URL.createObjectURL(blob);

                // Open the PDF in a new tab
                window.open(blobUrl, '_blank');
            } catch (error) {
                console.error('Error generating the PDF:', error);
                Swal.fire(
                    'Error!',
                    'Failed to generate the PDF.',
                    'error'
                );
            }
        });
    });
</script>

@endpush
