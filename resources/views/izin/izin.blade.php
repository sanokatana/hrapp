@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Izin / Sakit / Cuti</div>
    <div class="right"></div>
</div>
<style>
    .rounded-custom {
        border-radius: 15px;
        /* Customize the radius as needed */
    }

    .scrollable-container {
        max-height: 60vh;
        /* Adjust the max-height as needed */
        overflow-y: auto;
    }
</style>
<!-- * App Header -->
@endsection
@section('content')
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
<div class="row" style="margin-top:70px">
    <div class="col">
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <select name="bulan" id="bulan" class="form-control" style="text-align:center">
                        <option value="">Bulan</option>
                        @for ($i=1; $i<=12; $i++) <option value="{{ $i }}" {{ date("m") == $i ? 'selected' : ''}}>{{ $namabulan[$i]}}</option>
                            @endfor
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control" style="text-align:center">
                        <option value="">Tahun</option>
                        @php
                        $tahunmulai = 2015;
                        $tahunskrng = date("Y");
                        @endphp
                        @for ($tahun=$tahunmulai; $tahun<= $tahunskrng; $tahun++) <option value="{{ $tahun }}" {{ date("Y") == $tahun ? 'selected' : ''}}>{{ $tahun }}</option>
                            @endfor
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <select name="tipe" id="tipe" class="form-control" style="text-align:center">
                        <option value="">Tipe</option>
                        <option value="Izin">Izin</option>
                        <option value="Cuti">Cuti</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <button class="btn btn-primary btn-block" id="getData">
                        <ion-icon name="search-outline"></ion-icon>Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col container" id="showIzin" class="scrollable-container" style="max-height: 65vh; overflow-y: auto;">
    </div>
</div>

<div class="fab-button animate bottom-right dropdown draggable" style="margin-top: 20px;">
    <a href="#" class="fab bg-primary" data-toggle="dropdown">
        <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
    </a>
    <div class="dropdown-menu">
        <a href="/presensi/buatizin" class="dropdown-item bg-primary">
            <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
            <p style="margin-bottom: 0px;">Pengajuan Absen</p>
        </a>
        <a href="/presensi/cuti" class="dropdown-item bg-primary">
            <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
            <p style="margin-bottom: 0px;">Pengajuan Cuti</p>
        </a>
    </div>
</div>
@endsection
@push('myscript')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script>
    $(function() {
        var isDragging = false;

        $(".draggable").draggable({
            containment: "window", // Restricts dragging to the body element
            zIndex: 9999, // Set a high zIndex value to ensure it stays above other elements
            scroll: false, // Disable scrolling while dragging
            start: function() {
                isDragging = true;
            },
            stop: function() {
                setTimeout(function() {
                    isDragging = false;
                }, 100);
            }
        });

        $(".fab").click(function(e) {
            if (isDragging) {
                e.preventDefault();
                e.stopImmediatePropagation();
            }
        });

        $("#getData").click(function(e) {
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            var tipe = $("#tipe").val();
            if (tipe == "Izin") {
                $.ajax({
                    type: 'POST',
                    url: '/presensi/getizin',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun
                    },
                    cache: false,
                    success: function(respond) {
                        $("#showIzin").html(respond);
                    }
                });
            } else if (tipe == "Cuti") {
                $.ajax({
                    type: 'POST',
                    url: '/presensi/getizincuti',
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun
                    },
                    cache: false,
                    success: function(respond) {
                        $("#showIzin").html(respond);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: "Mohon Pilih Tipe",
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        });
    });
</script>
@endpush
