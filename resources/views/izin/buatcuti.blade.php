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
     border-radius: 200px
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
    <div class="pageTitle">Pengajuan Cuti</div>
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
                <input type="text" id="periode" name="periode" class="form-control" placeholder="Periode" value="{{ $periode }}">
            </div>
            <div class="form-group">
                <input type="number" id="sisa_cuti" name="sisa_cuti" class="form-control" placeholder="Sisa Cuti" disabled value="{{ $cutiGet->sisa_cuti}}">
            </div>
            <div class="form-group">
                <input type="text" id="tgl_cuti" name="tgl_cuti" class="datepicker form-control" placeholder="Tanggal Pengajuan Cuti">
            </div>
            <div class="form-group">
                <input type="text" id="tgl_cuti_sampai" name="tgl_cuti_sampai" class="datepicker form-control" placeholder="Sampai Tanggal">
            </div>
            <div class="form-group">
                <input type="number" id="jml_hari" name="jml_hari" class="form-control" placeholder="Berapa Hari" disabled>
            </div>
            <div class="form-group">
                <input type="number" id="sisa_cuti_setelah" name="sisa_cuti_setelah" class="form-control" placeholder="Sisa Setelah Permohonan" disabled>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-primary btn-block" id="nextButton">Next</button>
            </div>
        </form>
    </div>
</div>

<div class="row" style="margin-top: 70px; display: none;" id="page2">
    <div class="col">
        <form method="POST" action="/presensi/storecuti" id="formcutiPage2" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="hidden_periode" name="periode">
            <input type="hidden" id="hidden_sisa_cuti" name="sisa_cuti">
            <input type="hidden" id="hidden_tgl_cuti" name="tgl_cuti">
            <input type="hidden" id="hidden_tgl_cuti_sampai" name="tgl_cuti_sampai">
            <input type="hidden" id="hidden_jml_hari" name="jml_hari">
            <input type="hidden" id="hidden_sisa_cuti_setelah" name="sisa_cuti_setelah">

            <div class="form-group">
                <label for="kar_ganti" class="col-form-label">Karyawan Yang Akan Menggantikan</label>
                <select name="kar_ganti" id="kar_ganti" class="form-control">
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->nama_lengkap }}">{{ $employee->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="note" class="col-form-label">Note (Optional)</label>
                <textarea name="note" id="note" rows="4" class="form-control"></textarea>
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

        function calculateDays() {
            var tgl_cuti = $("#tgl_cuti").val();
            var tgl_cuti_sampai = $("#tgl_cuti_sampai").val();

            if (tgl_cuti && tgl_cuti_sampai) {
                var start = new Date(tgl_cuti);
                var end = new Date(tgl_cuti_sampai);
                var diffTime = Math.abs(end - start);
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Including start day
                $("#jml_hari").val(diffDays);
            } else if (tgl_cuti) {
                $("#jml_hari").val(1);
            } else {
                $("#jml_hari").val(0);
            }
            calculateSisaCutiSetelah();
        }

        function calculateSisaCutiSetelah() {
            var sisa_cuti = parseFloat($("#sisa_cuti").val());
            var jml_hari = parseFloat($("#jml_hari").val());
            if (!isNaN(sisa_cuti) && !isNaN(jml_hari)) {
                var sisa_cuti_setelah = sisa_cuti - jml_hari;
                $("#sisa_cuti_setelah").val(sisa_cuti_setelah);
            }
        }

        $("#tgl_cuti, #tgl_cuti_sampai").change(function() {
            calculateDays();
        });

        $("#sisaButton").click(function() {
            var periode = $("#periode").val();
            if (!periode) {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Periode Harus Diisi',
                    icon: 'warning',
                });
                return;
            }

            $.ajax({
                url: '/presensi/cek-sisa-cuti',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    periode: periode
                },
                success: function(response) {
                    $("#sisa_cuti").val(response.sisa_cuti);
                    calculateSisaCutiSetelah();
                },
                error: function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal mengambil sisa cuti',
                        icon: 'error',
                    });
                }
            });
        });

        $("#nextButton").click(function() {
            var periode = $("#periode").val();
            var sisa_cuti = $("#sisa_cuti").val();
            var tgl_cuti = $("#tgl_cuti").val();
            var tgl_cuti_sampai = $("#tgl_cuti_sampai").val();
            var jml_hari = $("#jml_hari").val();
            var sisa_cuti_setelah = $("#sisa_cuti_setelah").val();

            if (tgl_cuti == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Tanggal Harus Diisi',
                    icon: 'warning',
                });
            } else {
                // Hide Page 1 and Show Page 2
                $("#page1").hide();
                $("#page2").show();

                // Populate hidden inputs for the second form
                $("#hidden_periode").val(periode);
                $("#hidden_sisa_cuti").val(sisa_cuti);
                $("#hidden_tgl_cuti").val(tgl_cuti);
                $("#hidden_tgl_cuti_sampai").val(tgl_cuti_sampai);
                $("#hidden_jml_hari").val(jml_hari);
                $("#hidden_sisa_cuti_setelah").val(sisa_cuti_setelah);
            }
        });

        $("#backButton").click(function() {
            // Hide Page 2 and Show Page 1
            $("#page2").hide();
            $("#page1").show();
        });

        $("#formcutiPage2").submit(function(event) {
            var kar_ganti = $("#kar_ganti").val();
            var note = $("#note").val();

            if (kar_ganti == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Karyawan Ganti Harus Diisi',
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

