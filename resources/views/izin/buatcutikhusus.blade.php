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
    <div class="pageTitle">Pengajuan Cuti</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
<div class="row" style="margin-top: 70px;" id="page1">
    <div class="col">
        <form method="POST" action="/presensi/storecutikhusus" id="formcutiPage" enctype="multipart/form-data">
            @csrf
            <div class="form-group basic">
                <select name="id_tipe_cuti" id="id_tipe_cuti" class="custom-select">
                    <option value="">-- Select Tipe Cuti--</option>
                    @foreach ($tipecuti as $d)
                    <option data-jumlah_hari="{{ $d->jumlah_hari }}" value="{{ $d->id_tipe_cuti }}">{{ $d->tipe_cuti }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="text" id="tgl_cuti" name="tgl_cuti" class="datepicker form-control" placeholder="Tanggal Pengajuan Cuti" disabled autocomplete="off">
            </div>
            <div class="form-group">
                <input type="text" id="tgl_cuti_sampai" name="tgl_cuti_sampai" class="datepicker form-control" placeholder="Sampai Tanggal" disabled autocomplete="off">
            </div>
            <div class="form-group">
                <input type="number" id="jml_hari" name="jml_hari" class="form-control" placeholder="Berapa Hari" disabled autocomplete="off">
            </div>
            <div class="form-group">
                <input type="text" id="periode" name="periode" class="form-control" placeholder="Periode" value="{{ $periode }}" disabled>
            </div>
            <div class="form-group">
                <label for="note" class="col-form-label">Note</label>
                <textarea name="note" id="note" rows="4" class="form-control" autocomplete="off"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
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
        function getQueryParam(param) {
            var urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        var idTipeCuti = getQueryParam('id_tipe_cuti');
        if (idTipeCuti) {
            $('#id_tipe_cuti').val(idTipeCuti);
            // Trigger the change event manually
            $('#id_tipe_cuti').trigger('change');
        }

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
        }

        function restrictDateRange() {
            var selectedOption = $("#id_tipe_cuti").find(':selected');
            var maxDays = parseInt(selectedOption.data('jumlah_hari'), 10);

            if (!isNaN(maxDays)) {
                $("#tgl_cuti").datepicker('destroy').datepicker({
                    format: "yyyy-mm-dd",
                    onSelect: function(selectedDate) {
                        var startDate = new Date(selectedDate);
                        var endDate = new Date(startDate);
                        endDate.setDate(startDate.getDate() + maxDays - 1);

                        $("#tgl_cuti_sampai").datepicker('destroy').datepicker({
                            format: "yyyy-mm-dd",
                            minDate: startDate,
                            maxDate: endDate
                        });
                    }
                });
            }
        }

        $("#id_tipe_cuti").change(function() {
            var selectedOption = $(this).find(':selected');
            if (selectedOption.val() !== "") {
                $("#tgl_cuti, #tgl_cuti_sampai").prop('disabled', false);
                restrictDateRange();
            } else {
                $("#tgl_cuti, #tgl_cuti_sampai").prop('disabled', true);
                $("#tgl_cuti, #tgl_cuti_sampai").val('');
                $("#jml_hari").val('');
            }
        });

        $("#tgl_cuti, #tgl_cuti_sampai").change(function() {
            calculateDays();
        });

        $("#formcutiPage").submit(function(event) {
            var note = $("#note").val();

            if (note === "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Note Keterangan Harus Diisi',
                    icon: 'warning',
                });
                event.preventDefault(); // Prevent form submission
            } else {
                // Show the full-screen loading overlay
                $("#loadingOverlay").css("display", "flex");

                // Enable the disabled fields before submitting
                $("#tgl_cuti, #tgl_cuti_sampai, #jml_hari").prop('disabled', false);

                // Allow the form to proceed
                return true;
            }
        });

        // Ensure date pickers are initialized properly
        if (idTipeCuti) {
            restrictDateRange();
            $("#tgl_cuti, #tgl_cuti_sampai").prop('disabled', false);
        }
    });
</script>
@endpush

