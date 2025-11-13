@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Histori Presensi</div>
    <div class="right"></div>
</div>
<style>
    :root {
        --viewport-height: 100vh; /* Default value */
    }

    .rounded-custom {
        border-radius: 10px;
        border: 1px solid #092c9f;
        margin-bottom: 4px;
        /* Customize the radius as needed */
    }

    #showHistori {
        max-height: calc(var(--viewport-height) - 232px); /* Adjust 70px according to the size of your bottom nav bar */
        overflow-y: auto; /* Enable vertical scrolling */
        padding: 10px; /* Optional: Add padding if needed */
        box-sizing: border-box; /* Ensure padding doesn't affect height calculation */
        -webkit-overflow-scrolling: touch; /* Smooth scrolling */
    }
</style>
<!-- * App Header -->
@endsection
@section('content')
@php
    $selectedMonth = isset($selectedMonth) ? (int) $selectedMonth : (int) date('n');
    $selectedYear = isset($selectedYear) ? (int) $selectedYear : (int) date('Y');
    $monthNames = $namabulan ?? [];
    $currentYear = (int) date('Y');
@endphp
<div class="row" style="margin-top:70px">
    <div class="col">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <select name="bulan" id="bulan" class="form-control" style="text-align:center">
                        <option value="">Bulan</option>
                        @for ($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" {{ $selectedMonth === $i ? 'selected' : '' }}>{{ $monthNames[$i] ?? $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control" style="text-align:center">
                        <option value="">Tahun</option>
                        @php
                        $tahunmulai = 2015;
                        $tahunskrng = max($currentYear, $selectedYear);
                        @endphp
                        @for ($tahun=$tahunmulai; $tahun<= $tahunskrng; $tahun++)
                            <option value="{{ $tahun }}" {{ $selectedYear === (int) $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <button class="btn btn-primary btn-block" id="getData">
                        <ion-icon name="search-outline"></ion-icon> Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col" id="showHistori">
        {!! $initialHistory ?? '' !!}
    </div>
</div>
@endsection

@push('myscript')
<script>
    function updateViewportHeight() {
        document.documentElement.style.setProperty('--viewport-height', `${window.innerHeight}px`);
    }

    // Update viewport height on initial load
    updateViewportHeight();

    // Update viewport height on resize
    window.addEventListener('resize', updateViewportHeight);

    $(function(){
        $("#getData").click(function(e){
            e.preventDefault();
            var bulan = parseInt($("#bulan").val(), 10) || '';
            var tahun = parseInt($("#tahun").val(), 10) || '';

            if (!bulan || !tahun) {
                Swal.fire({
                    title: 'Periode Tidak Lengkap',
                    text: 'Silakan pilih bulan dan tahun terlebih dahulu.',
                    icon: 'warning'
                });
                return;
            }

            $.ajax({
                type: 'GET',
                url: '/presensi/histori/data',
                data: {
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond){
                    $("#showHistori").html(respond);
                }
            });
        });
    });
</script>
@endpush
