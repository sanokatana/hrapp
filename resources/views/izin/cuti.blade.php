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
        <div class="form-group basic" style="margin-bottom: 20px; text-align: center; padding: 0px">
            <select name="id_tipe_cuti" id="id_tipe_cuti" class="custom-select" onchange="handleTipeCutiChange()" style="text-align: center; height: 40px; font-size: 16px; width: 100%;">
                <option value="">-- Select Tipe Cuti--</option>
                <option value="Cuti Tahunan">Cuti Tahunan</option>
                @foreach ($tipecuti as $d)
                <option data-jumlah_hari="{{ $d->jumlah_hari }}" value="{{ $d->id_tipe_cuti }}">{{ $d->tipe_cuti }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    function handleTipeCutiChange() {
    var select = document.getElementById("id_tipe_cuti");
    var value = select.options[select.selectedIndex].value;

    if (value === "Cuti Tahunan") {
        // AJAX call to check for active cuti
        $.ajax({
            url: '/presensi/buatcuti',
            type: 'GET',
            success: function(response) {
                // Redirect to buatcuti if the cuti is valid
                window.location.href = '/presensi/buatcuti';
            },
            error: function(xhr) {
                // Show SweetAlert message if no active cuti record
                if (xhr.status === 403) {
                    Swal.fire({
                        title: 'Tidak Ada Periode Cuti',
                        text: xhr.responseJSON.error,
                        icon: 'error',
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal memuat data cuti. Mohon coba lagi.',
                        icon: 'error',
                    });
                }
            }
        });
    } else if (value) {
        // Redirect to cuti khusus
        window.location.href = '/presensi/buatcutikhusus?id_tipe_cuti=' + value;
    }
}

</script>
@endpush
