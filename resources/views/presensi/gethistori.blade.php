@if ($histori->isEmpty())
<div id="alert-div" class="alert alert-warning">
    <p style="text-align: center; height: 10px">Data Belum Ada</p>
</div>
@endif
@php
use App\Helpers\DateHelper;
@endphp
@foreach ($histori as $d)
<ul class="listview image-listview rounded-custom">
    @php
    $path = $d->foto_in ? Storage::url('uploads/absensi/'.$d->foto_in) : null;
    $jam_masuk_time = strtotime($d->jam_masuk);
    $hours_diff = floor(($jam_masuk_time - strtotime("08:05")) / 3600);
    $minutes_diff = floor((($jam_masuk_time - strtotime("08:05")) % 3600) / 60);

    // Calculate lateness
    if ($hours_diff > 0) {
        $lateness = $hours_diff . " Jam " . $minutes_diff . " Menit";
    } elseif ($minutes_diff > 0) {
        $lateness = $minutes_diff . " Menit";
    } else {
        $lateness = "On Time";
    }

    // Determine status based on lateness
    $status = ($lateness != "On Time") ? "Terlambat" : "On Time";
    @endphp
    <li>
        <div class="item">
            @if ($path)
            <img src="{{ url($path) }}" alt="" class="imaged w48 circular-image">
            @else
            <div class="icon-box bg-info">
                <ion-icon name="finger-print-outline"></ion-icon>
            </div>
            @endif

            <div class="in">
                <div class="jam-row">
                    <div><b>{{ DateHelper::formatIndonesianDate($d->tanggal) }}</b></div>
                    <div class="status {{ $status == 'Terlambat' ? 'text-danger' : 'text-success' }}">
                        {{ $status }}
                    </div>
                    <div class="lateness {{ $status == 'Terlambat' ? 'text-warning' : 'text-success' }}">
                        ({{ $lateness }})
                    </div>
                </div>
                <div class="jam-row">
                    <div class="jam-in mb-1">
                        <span class="badge badge-success">{{ $d->jam_masuk }}</span>
                    </div>
                    <div class="jam-out">
                        <span class="badge badge-danger">{{ $d->jam_pulang != null ? $d->jam_pulang : "No Scan" }}</span>
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
@endforeach
