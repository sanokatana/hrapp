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
    $jam_masuk_time = $d->jam_masuk ? strtotime($d->jam_masuk) : PHP_INT_MAX;
    $threshold_time = strtotime($d->jam_kerja);
    $lateness_threshold = strtotime($d->jam_kerja);

    // Calculate lateness and determine status
    if ($d->jam_masuk === null) {
    $status = "Tidak Absen";
    $lateness = "";
    } elseif ($jam_masuk_time <= ($threshold_time + 30)) { // Allow 30 seconds grace period
        $status="On Time" ;
        $lateness="Tepat Waktu" ;
        } else {
        $time_diff=$jam_masuk_time - $threshold_time;
        $hours_diff=floor($time_diff / 3600);
        $minutes_diff=floor(($time_diff % 3600) / 60);
        $seconds_diff=$time_diff % 60;

        $lateness="" ;
        if ($hours_diff> 0) {
        $lateness .= $hours_diff . " Jam ";
        }
        if ($minutes_diff > 0) {
        $lateness .= $minutes_diff . " Menit ";
        }
        if ($seconds_diff > 0 && $hours_diff == 0 && $minutes_diff == 0) {
        $lateness .= $seconds_diff . " Detik";
        }
        $status = "Terlambat";
        }
        @endphp

        <li>
            <div class="item">
                <div class="icon-box bg-info">
                    <ion-icon name="finger-print-outline"></ion-icon>
                </div>

                <div class="in">
                    <div class="jam-row">
                        <div><b>{{ DateHelper::formatIndonesianDate($d->tanggal) }}</b></div>
                        <div
                            class="status {{ $status == 'Terlambat' ? 'text-danger' : ($status == 'Tidak Absen' ? 'text-danger' : 'text-success') }}">
                            <b>{{ $status }}</b>
                        </div>
                        @if ($status != 'Tidak Absen')
                        <div
                            class="lateness {{ $status == 'Terlambat' ? 'text-warning' : 'text-success' }}">
                            ({{ $lateness }})
                        </div>
                        @else
                        <div
                            class="lateness text-danger">
                            Masuk
                        </div>
                        @endif
                        <div class="text-muted">{{ $d->shift_name }}</>
                        </div>
                    </div>
                    <div class="jam-row">
                        <div class="jam-in mb-1">
                            <span class="badge {{ $status == 'Tidak Absen' ? 'badge-danger' : ($status == 'Terlambat' ? 'badge-danger' : 'badge-success') }}" style="width: 70px;">
                                {{ (!empty($d->jam_masuk) && $d->jam_masuk !== null) ? $d->jam_masuk : "No Scan" }}
                            </span>
                        </div>
                        <div class="jam-out">
                            <span class="badge {{ (!empty($d->jam_pulang) && $d->jam_pulang !== null) ? 'badge-success' : 'badge-danger' }}" style="width: 70px;">
                                {{ (!empty($d->jam_pulang) && $d->jam_pulang !== null) ? $d->jam_pulang : "No Scan" }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
</ul>
@endforeach
