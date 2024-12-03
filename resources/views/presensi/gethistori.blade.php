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
            $jam_masuk_time = $d->jam_masuk ? strtotime($d->jam_masuk) : PHP_INT_MAX; // Use PHP_INT_MAX for null values
            $threshold_time = $d->jam_kerja;
            $lateness_threshold = $d->jam_kerja;

            // Calculate lateness and determine status
            if ($d->jam_masuk === null) {
                $status = "Tidak Absen Masuk";
                $lateness = ""; // No lateness if jam_masuk is null
            } elseif ($jam_masuk_time <= $lateness_threshold) {
                $status = "On Time";
                $lateness = "Tepat Waktu";
            } else {
                $hours_diff = floor(($jam_masuk_time - $threshold_time) / 3600);
                $minutes_diff = floor((($jam_masuk_time - $threshold_time) % 3600) / 60);
                $lateness = ($hours_diff > 0 ? $hours_diff . " Jam " : "") . ($minutes_diff > 0 ? $minutes_diff . " Menit" : "");
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
                            class="status {{ $status == 'Terlambat' ? 'text-danger' : ($status == 'Tidak Absen Masuk' ? 'text-danger' : 'text-success') }}">
                            <b>{{ $status }}</b>
                        </div>
                        @if ($status != 'Tidak Absen Masuk')
                            <div class="lateness {{ $status == 'Terlambat' ? 'text-warning' : 'text-success' }}">
                                ({{ $lateness }})
                            </div>
                        @endif
                    </div>
                    <div class="jam-row">
                        <div class="jam-in mb-1">
                            <span
                                class="badge {{ $status == 'Tidak Absen Masuk' ? 'badge-danger' : ($status == 'Terlambat' ? 'badge-danger' : 'badge-success') }}"
                                style="width: 70px;">
                                {{ $d->jam_masuk !== null ? $d->jam_masuk : "No Scan" }}
                            </span>
                        </div>
                        <div class="jam-out">
                            <span class="badge {{ $d->jam_pulang !== null ? 'badge-success' : 'badge-danger' }}"
                                style="width: 70px;">
                                {{ $d->jam_pulang !== null ? $d->jam_pulang : "No Scan" }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
@endforeach
