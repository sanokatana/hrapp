@extends('layouts.presensi')

@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Notifikasi</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

<style>
    .listview {
        border-radius: 10px;
    }
</style>

@section('content')
@php
use App\Helpers\DateHelper;
@endphp
<div class="row" style="margin-top:70px">
    <div class="col" style="margin-bottom:80px">
        @foreach ($notif as $d)
        <ul class="listview image-listview rounded-custom scrollable-container" style="margin-bottom: 10px; border: 1px solid #962E2E;">
            @foreach ($d['details'] as $detail)
            <li>
                <div class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="information-outline"></ion-icon>
                    </div>
                    <div class="in">
                        <div class="jam-row">
                            <div><b>{{ DateHelper::formatIndonesianDate($d['tanggal']) }}</b></div>
                            <div class="status {{ $detail['status_class'] }}">
                                <b>{{ $detail['status'] }}</b>
                            </div>
                        </div>
                        <div class="jam-row">
                            @if (in_array($detail['status'], ['Terlambat', 'Tidak Absen Masuk', 'Tidak Masuk Kerja']))
                                <div class="jam-in mb-1">
                                    <span class="badge badge-warning" style="width: 70px;">{{ $detail['jam_masuk'] }}</span>
                                </div>
                            @endif
                            @if (in_array($detail['status'], ['Pulang Awal', 'Tidak Absen Pulang', 'Tidak Masuk Kerja']))
                                <div class="jam-out">
                                    <span class="badge badge-warning" style="width: 70px;">{{ $detail['jam_pulang'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        @endforeach
    </div>
</div>
@endsection

@push('myscript')
<script>
// Add any specific scripts if necessary
</script>
@endpush
