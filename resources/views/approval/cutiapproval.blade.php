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
                    Approval Pengajuan Cuti
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
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>Nama Karyawan</th>
                                            <th>Jabatan</th>
                                            <th>Periode</th>
                                            <th>Tanggal Cuti <br> Sampai Tanggal</th>
                                            <th>Jmlh</th>
                                            <th>Kar Pengganti</th>
                                            <th>Note</th>
                                            <th>Jenis Cuti</th>
                                            <th>Tipe Cuti</th>
                                            <th> HRD <br>-------------------<br> Atasan <br>-------------------<br> Management</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cutiapproval as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $d->nama_lengkap }}</td>
                                            <td>{{ $d->nama_jabatan }}</td>
                                            <td>{{ $d->periode }}</td>
                                            <td>@if ($d->tgl_cuti)
                                                {{ DateHelper::formatIndonesianDate($d->tgl_cuti) }}
                                                @endif
                                                <br>
                                                @if ($d->tgl_cuti_sampai === $d->tgl_cuti)
                                                @else
                                                {{ DateHelper::formatIndonesianDate($d->tgl_cuti_sampai) }}
                                                @endif
                                            </td>
                                            <td>{{ $d->jml_hari }} </td>
                                            <td>{{ $d->kar_ganti}} </td>
                                            <td>{{ $d->note }}</td>
                                            <td>{{ $d->jenis }}</td>
                                            <td>{{ $d->tipe_cuti }}</td>
                                            <td>
                                                @if ($d->status_approved_hrd == 1)
                                                <span class="badge bg-success" style="color: white; width:90px">Approved</span>
                                                @elseif ($d->status_approved_hrd == 0)
                                                <span class="badge bg-yellow" style="color: white; width:90px">Pending</span>
                                                @elseif ($d->status_approved == 2)
                                                <span class="badge bg-red" style="color: white; width:90px">Rejected</span>
                                                @else
                                                <span class="badge bg-red" style="color: white; width:90px">Cancelled</span>
                                                @endif
                                                <br>
                                                @if ($d->status_approved == 1)
                                                <span class="badge bg-success mt-1" style="color: white; width:90px">Approved</span>
                                                @elseif ($d->status_approved == 0)
                                                <span class="badge bg-yellow mt-1" style="color: white; width:90px">Pending</span>
                                                @elseif ($d->status_approved == 2)
                                                <span class="badge bg-red mt-1" style="color: white; width:90px">Rejected</span>
                                                @else
                                                <span class="badge bg-red mt-1" style="color: white; width:90px">Cancelled</span>
                                                @endif
                                                <br>
                                                @if ($d->status_management == 1)
                                                <span class="badge bg-success mt-1" style="color: white; width:90px">Approved</span>
                                                @elseif ($d->status_management == 0)
                                                <span class="badge bg-yellow mt-1" style="color: white; width:90px">Pending</span>
                                                @elseif ($d->status_management == 2)
                                                <span class="badge bg-red mt-1" style="color: white; width:90px">Rejected</span>
                                                @else
                                                <span class="badge bg-red mt-1" style="color: white; width:90px">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                // Get the current user's nik and jabatan
                                                $currentUserNik = Auth::guard('user')->user()->nik;
                                                $currentUserJabatan = \App\Models\Karyawan::where('nik', $currentUserNik)->value('jabatan');

                                                // Get the jabatan_atasan for the current leave request
                                                $jabatan = \App\Models\Karyawan::where('nik', $d->nik)->value('jabatan');

                                                $jabatanAtasan = \App\Models\Jabatan::where('id', $jabatan)->value('jabatan_atasan');

                                                // Check if the current user is an atasan (supervisor)
                                                $isAtasan = $currentUserJabatan == $jabatanAtasan;

                                                // Check if the current user is from the Management level
                                                $isManagement = Auth::guard('user')->user()->level == 'Management';
                                                @endphp

                                                {{-- Logic to prioritize Atasan or Management --}}
                                                @if ($d->status_approved_hrd == 1)
                                                @if ($isAtasan && $isManagement)
                                                {{-- If both roles, prioritize Atasan --}}
                                                @if ($d->status_approved == 0)
                                                <a href="#" class="badge bg-success btnApprove" style="width:120px;" data-id="{{ $d->id }}" data-nik="{{ $d->nik }}" data-periode="{{ $d->periode }}">
                                                    Approve
                                                </a>
                                                @else
                                                <a href="#" class="badge bg-danger btnBatalApprove" style="width:120px;" data-id="{{ $d->id }}">
                                                    Batalkan
                                                </a>
                                                @endif
                                                @elseif ($isAtasan)
                                                {{-- If only Atasan, show approval button based on status_approved --}}
                                                @if ($d->status_approved == 0)
                                                <a href="#" class="badge bg-success btnApprove" style="width:120px;" data-id="{{ $d->id }}" data-nik="{{ $d->nik }}" data-periode="{{ $d->periode }}">
                                                    Approve
                                                </a>
                                                @else
                                                <a href="#" class="badge bg-danger btnBatalApprove" style="width:120px;" data-id="{{ $d->id }}">
                                                    Batalkan
                                                </a>
                                                @endif
                                                <br>
                                                @elseif ($isManagement)
                                                {{-- If only Management, show approval button based on status_management --}}
                                                @if ($d->status_approved == 1)
                                                @if ($d->status_management == 0)
                                                <a href="#" class="badge bg-success btnApprove" style="width:120px;" data-id="{{ $d->id }}" data-nik="{{ $d->nik }}" data-periode="{{ $d->periode }}">
                                                    Approve
                                                </a>
                                                @else
                                                <a href="#" class="badge bg-danger btnBatalApprove" style="width:120px;" data-id="{{ $d->id }}">
                                                    Batalkan
                                                </a>
                                                @endif
                                                @else
                                                <a class="badge bg-yellow btnWait" style="width:120px" id="btnWait" name="btnWait" data-id="{{ $d->id }}">
                                                    Waiting Atasan
                                                </a>
                                                <br>
                                                @endif
                                                @endif
                                                @else
                                                <a class="badge bg-yellow btnWait" style="width:120px" id="btnWait" name="btnWait" data-id="{{ $d->id }}">
                                                    Waiting HRD
                                                </a>
                                                <br>
                                                @endif
                                                {{-- Print Button --}}
                                                <a href="#" class="badge bg-info btnPrint mt-1" style="width:120px;" id="btnPrint" data-id="{{ $d->id }}">
                                                    Print
                                                </a>
                                            </td>


                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $cutiapproval->links('vendor.pagination.bootstrap-5')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-cutiapproval" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approval Pengajuan Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/approval/approvecuti" method="POST">
                    @csrf
                    <input type="hidden" id="id_cuti_form" name="id_cuti_form">
                    <input type="hidden" id="nik_cuti_form" name="nik_cuti_form">
                    <input type="hidden" id="periode_cuti_form" name="periode_cuti_form">
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


        $(document).on('click', '.btnApprove', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            var nik = $(this).data("nik");
            var periode = $(this).data("periode");
            $('#id_cuti_form').val(id);
            $('#nik_cuti_form').val(nik);
            $('#periode_cuti_form').val(periode);
            $('#modal-cutiapproval').modal("show");
        });

        // Submit button click event
        document.getElementById('submitBtn').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default form submission
            $('#modal-cutiapproval').modal("hide");
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
                    $('#modal-cutiapproval form').submit();
                } else {
                    $('#modal-cutiapproval').modal("show");
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
                confirmButtonText: 'Yes, Batalkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '/approval/batalapprovecuti/' + id,
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Success!',
                                    'Approval has been cancelled.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    response.message,
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btnPrint', async function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            try {
                // Fetch form data
                const response = await $.ajax({
                    url: '/approval/printCuti',
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
                const pdfTemplateResponse = await fetch('{{ route("pdfCuti.template") }}', {
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

                const jabatanField = form.getTextField('jabatan'); // Replace with actual field name
                jabatanField.setText(response.jabatan);

                const mulaiField = form.getTextField('mulai_kerja'); // Replace with actual field name
                mulaiField.setText(response.mulai);

                const periodeField = form.getTextField('periode'); // Replace with actual field name
                periodeField.setText(response.periode);

                const sisaCutiField = form.getTextField('sisa_hak'); // Replace with actual field name
                sisaCutiField.setText(response.sisa_cuti);

                const jmlField = form.getTextField('jml_hari'); // Replace with actual field name
                jmlField.setText(response.jml_hari);

                const sisaSetelahField = form.getTextField('sisa_cuti'); // Replace with actual field name
                sisaSetelahField.setText(response.sisa_setelah);

                const karField = form.getTextField('karyawan_ganti'); // Replace with actual field name
                karField.setText(response.kar_ganti);

                const tglField = form.getTextField('tgl_cuti'); // Replace with actual field name
                tglField.setText(response.tanggal);

                const karNama = form.getTextField('nama_kar'); // Replace with actual field name
                karNama.setText(response.nama_lengkap);

                const atasanNama = form.getTextField('nama_atasan'); // Replace with actual field name
                atasanNama.setText(response.nama_atasan);

                const hrNama = form.getTextField('nama_hr'); // Replace with actual field name
                hrNama.setText(response.nama_hr);

                const noteField = form.getTextField('note'); // Replace with actual field name for the first field
                const noteFieldCont = form.getTextField('note1'); // Replace with actual field name for the continuation field

                let maxLength = 90; // The maximum number of characters allowed in the first field

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
                const [firstPart, secondPart] = splitTextByWords(response.note, maxLength);

                // Set text fields
                noteField.setText(firstPart);
                if (secondPart) {
                    noteFieldCont.setText(secondPart);
                }

                // Serialize the PDF document to bytes
                const pdfBytes = await pdfDoc.save();

                // Create a blob from the PDF bytes
                const blob = new Blob([pdfBytes], {
                    type: 'application/pdf'
                });

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
        // Select all elements with the class 'btnBatalApprove'
        document.querySelectorAll('.btnWait').forEach(function(button) {
            button.addEventListener('click', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu Verifikasi',
                    text: 'Pengajuan ini masih menunggu Verifikasi dan Approval',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#f39c12',
                });
            });
        });
    });
</script>
@endpush
