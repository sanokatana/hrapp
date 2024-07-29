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
@section('content')
@if ($processedHistoribulanini->isEmpty())
<script>
    Swal.fire({
        title: 'Gagal!',
        text: "Tidak Ada Data",
        icon: 'error',
        confirmButtonText: 'Ok'
    });
</script>
@endif
@php
use App\Helpers\DateHelper;
@endphp
@foreach ($processedHistoribulanini as $d)
<ul class="listview image-listview rounded-custom">
    @php
        $jam_masuk_time = strtotime($d->jam_masuk);
        $threshold_time = strtotime("08:00:00");
        $lateness_threshold = strtotime("08:01:00"); // Lateness threshold at 08:00:30

        // Calculate lateness
        if ($jam_masuk_time <= $lateness_threshold) {
            $lateness = "Tepat Waktu";
        } else {
            $hours_diff = floor(($jam_masuk_time - $threshold_time) / 3600);
            $minutes_diff = floor((($jam_masuk_time - $threshold_time) % 3600) / 60);
            $lateness = ($hours_diff > 0 ? $hours_diff . " Jam " : "") . ($minutes_diff > 0 ? $minutes_diff . " Menit" : "");
        }

        // Determine status based on lateness
        $status = ($lateness == "Tepat Waktu") ? "On Time" : "Terlambat";
    @endphp
    <li>
        <div class="item">
            <div class="icon-box bg-info">
                <ion-icon name="finger-print-outline"></ion-icon>
            </div>

            <div class="in">
                <div class="jam-row">
                    <div><b>{{ DateHelper::formatIndonesianDate($d->tanggal) }}</b></div>
                    <div class="status {{ $status == 'Terlambat' ? 'text-danger' : 'text-success' }}">
                        <b>{{ $status }}</b>
                    </div>
                    <div class="lateness {{ $status == 'Terlambat' ? 'text-warning' : 'text-info' }}">
                        ({{ $lateness }})
                    </div>
                </div>
                <div class="jam-row">
                    <div class="jam-in mb-1">
                        <span class="badge badge-success" style="width: 70px;">{{ $d->jam_masuk }}</span>
                    </div>
                    <div class="jam-out">
                        <span class="badge badge-danger" style="width: 70px;">{{ $d->jam_pulang != null ? $d->jam_pulang : "No Scan" }}</span>
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
@endforeach

@endsection
@push('myscript')
<script>
</script>
@endpush
