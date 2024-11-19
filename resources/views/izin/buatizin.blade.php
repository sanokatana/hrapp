@extends('layouts.presensi')

@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal {
        max-height: 450px !important;
    }
    .datepicker-date-display {
        background-color: #4989EF !important;
    }
    .btn {
        border-radius: 200px;
    }
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999; /* Ensure it appears above everything */
    }

    .loading-overlay span {
        display: inline-block;
        width: 50px;
        height: 50px;
        border: 5px solid #ffffff;
        border-top: 5px solid #4989EF;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="/presensi/izin" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Pengajuan Absen</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
<div class="row" style="margin-top: 70px;" id="page1">
    <div class="col">
        <form id="formizin">
            @csrf
            <div class="form-group">
                <input type="text" id="tgl_izin" name="tgl_izin" class="datepicker form-control" placeholder="Tanggal">
            </div>
            <div class="form-group">
                <input type="text" id="tgl_izin_akhir" name="tgl_izin_akhir" class="datepicker form-control" placeholder="Sampai Tanggal">
            </div>
            <div class="form-group">
                <input type="number" id="jml_hari" name="jml_hari" class="form-control" placeholder="Berapa Hari" value="0">
            </div>
            <div class="form-group">
                <label for="tipe" class="col-form-label">Tipe Absen</label>
                <select name="status" id="status" class="form-control">
                    <option disabled selected value> -- Pilih -- </option>
                    <option value="Tmk">Tidak Masuk Kerja</option>
                    <option value="Dt">Datang Terlambat</option>
                    <option value="Pa">Pulang Awal</option>
                    <option value="Tam">Tidak Absen Masuk</option>
                    <option value="Tap">Tidak Absen Pulang</option>
                    <option value="Tjo">Tukar Jadwal Off</option>
                </select>
            </div>
            <div class="form-group" id="pukulContainer" style="display: none;">
                <label for="tipe" class="col-form-label">Pukul:</label>
                <input type="time" name="pukul" id="pukul" class="form-control" placeholder="Pukul Waktu">
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-primary btn-block" id="nextButton">Next</button>
            </div>
        </form>
    </div>
</div>

<div class="row" style="margin-top: 70px; display: none;" id="page2">
    <div class="col">
        <form method="POST" action="/presensi/storeizin" id="formizinPage2" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="hidden_tgl_izin" name="tgl_izin">
            <input type="hidden" id="hidden_tgl_izin_akhir" name="tgl_izin_akhir">
            <input type="hidden" id="hidden_jml_hari" name="jml_hari">
            <input type="hidden" id="hidden_status" name="status">
            <input type="hidden" id="hidden_pukul" name="pukul">

            <div class="form-group">
                <label for="keterangan" class="col-form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="form-control"></textarea>
            </div>
            <div class="custom-file-upload" id="fileUpload1">
                <input type="file" name="foto[]" id="fileuploadInput" accept=".png, .jpg, .jpeg" multiple>
                <label for="fileuploadInput">
                    <span>
                        <strong>
                            <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                            <i>Upload Dokumen</i>
                        </strong>
                    </span>
                </label>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-primary btn-block" id="backButton">Back</button>
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </form>
    </div>
</div>
<div class="loading-overlay" id="loadingOverlay">
    <span></span>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        var currYear = (new Date()).getFullYear();

        $(".datepicker").datepicker({
            format: "yyyy-mm-dd"
        });

        $("#status").change(function() {
            var selectedStatus = $(this).val();
            if (selectedStatus === "Dt" || selectedStatus === "Pa" || selectedStatus === "Tam" || selectedStatus === "Tap") {
                $("#pukulContainer").show();
            } else {
                $("#pukulContainer").hide();
            }
        });

        function calculateDays() {
            var tgl_izin = $("#tgl_izin").val();
            var tgl_izin_akhir = $("#tgl_izin_akhir").val();

            if (tgl_izin && tgl_izin_akhir) {
                var start = new Date(tgl_izin);
                var end = new Date(tgl_izin_akhir);
                var diffTime = Math.abs(end - start);
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Including start day
                $("#jml_hari").val(diffDays);
            } else if (tgl_izin) {
                $("#jml_hari").val(1);
            } else {
                $("#jml_hari").val(0);
            }
        }

        $("#tgl_izin, #tgl_izin_akhir").change(function() {
            calculateDays();
        });

        $("#nextButton").click(function() {
            var tgl_izin = $("#tgl_izin").val();
            var tgl_izin_akhir = $("#tgl_izin_akhir").val();
            var jml_hari = $("#jml_hari").val();
            var status = $("#status").val();
            var pukul = $("#pukul").val();

            if (tgl_izin == "" || status == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Tanggal dan Status Harus Diisi',
                    icon: 'warning',
                });
            } else {
                // Hide Page 1 and Show Page 2
                $("#page1").hide();
                $("#page2").show();

                // Populate hidden inputs for the second form
                $("#hidden_tgl_izin").val(tgl_izin);
                $("#hidden_tgl_izin_akhir").val(tgl_izin_akhir);
                $("#hidden_jml_hari").val(jml_hari);
                $("#hidden_status").val(status);
                $("#hidden_pukul").val(pukul);
            }
        });

        $("#fileuploadInput").change(function(event) {
            var files = event.target.files;
            var fileNames = Array.from(files).map(file => file.name);

            // Display the file names using SweetAlert
            if (fileNames.length > 0) {
                Swal.fire({
                    title: 'Selected Files',
                    text: fileNames.join("\n"),
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        });

        $("#backButton").click(function() {
            // Hide Page 2 and Show Page 1
            $("#page2").hide();
            $("#page1").show();
        });

        $("#formizinPage2").submit(function(event) {
            var keterangan = $("#keterangan").val();

            if (keterangan === "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Keterangan Harus Diisi',
                    icon: 'warning',
                });
                event.preventDefault(); // Prevent form submission
            } else {
                // Show the full-screen loading overlay
                $("#loadingOverlay").css("display", "flex");

                // Disable all buttons to prevent interactions
                $("button").prop("disabled", true);

                // Allow the form to proceed
                return true;
            }
        });
    });
</script>
@endpush
