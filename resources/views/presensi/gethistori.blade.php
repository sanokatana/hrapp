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
    if ($d->is_libur) {
        // Libur logic - just show times without status
        $status = "Libur";
        $statusClass = "text-muted";
        $showStatus = true;
        $showLateness = false;
    } else if (empty($d->jam_masuk)) {
        $status = "Tidak Absen Masuk";
        $lateness = "";
        $statusClass = "text-danger";
        $showStatus = true;
        $showLateness = false;
    } else {
        $jam_masuk_time = strtotime($d->jam_masuk);
        $threshold_time = strtotime($d->jam_kerja);
        $lateness_threshold = strtotime($d->jam_kerja);

        if ($jam_masuk_time <= ($threshold_time + 30)) {
            $status = "On Time";
            $lateness = "Tepat Waktu";
            $statusClass = "text-success";
        } else {
            $time_diff = $jam_masuk_time - $threshold_time;
            $hours_diff = floor($time_diff / 3600);
            $minutes_diff = floor(($time_diff % 3600) / 60);
            $seconds_diff = $time_diff % 60;

            $lateness = "";
            if ($hours_diff > 0) {
                $lateness .= $hours_diff . " Jam ";
            }
            if ($minutes_diff > 0) {
                $lateness .= $minutes_diff . " Menit ";
            }
            if ($seconds_diff > 0 && $hours_diff == 0 && $minutes_diff == 0) {
                $lateness .= $seconds_diff . " Detik";
            }
            $status = "Terlambat";
            $statusClass = "text-danger";
        }
        $showStatus = true;
        $showLateness = true;
    }

    // Handle pulang status
    if (!$d->is_libur && empty($d->jam_pulang)) {
        $statusPulang = "Tidak Absen Pulang";
        $statusClassPulang = "text-danger";
        $showPulangStatus = true;
    } else {
        $showPulangStatus = false;
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
                    @if($showStatus)
                    <div class="status {{ $statusClass }}">
                        <b>{{ $status }}</b>
                    </div>
                    @if ($showLateness)
                    <div class="lateness {{ $status == 'Terlambat' ? 'text-warning' : 'text-success' }}">
                        ({{ $lateness }})
                    </div>
                    @endif
                    @endif
                    @if ($showPulangStatus)
                    <div class="status {{ $statusClassPulang }}">
                        <b>{{ $statusPulang }}</b>
                    </div>
                    @endif
                    <div class="text-muted">{{ $d->shift_name }}</div>
                </div>
                <div class="jam-row">
                    <div class="jam-in mb-1">
                        <span class="badge {{ $d->is_libur ? 'badge-info' : ($status == 'Tidak Absen Masuk' ? 'badge-danger' : ($status == 'Terlambat' ? 'badge-danger' : 'badge-success')) }}" style="width: 70px;">
                            {{ (!empty($d->jam_masuk) && $d->jam_masuk !== null) ? $d->jam_masuk : "No Scan" }}
                        </span>
                    </div>
                    <div class="jam-out">
                        <span class="badge {{ $d->is_libur ? 'badge-info' : ((!empty($d->jam_pulang) && $d->jam_pulang !== null) ? 'badge-success' : 'badge-danger') }}" style="width: 70px;">
                            {{ (!empty($d->jam_pulang) && $d->jam_pulang !== null) ? $d->jam_pulang : "No Scan" }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
@endforeach
