@php
use App\Helpers\DateHelper;
@endphp
@foreach ($presensi as $d)
    @php
        // Standard office start time
        $startTime = strtotime('08:00:00');
        $jamInTime = strtotime($d->jam_masuk);

        // Calculate delay in hours and minutes
        $delayHours = 0;
        $delayMinutes = 0;
        if ($jamInTime > $startTime) {
            $delayInSeconds = $jamInTime - $startTime;
            $delayHours = floor($delayInSeconds / 3600);
            $delayMinutes = floor(($delayInSeconds % 3600) / 60);
        }
    @endphp
    <tr style="text-align: center;">
        <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
        <td style="vertical-align: middle;">{{ $d->nip }}</td>
        <td style="vertical-align: middle;">{{ $d->nama_lengkap }}</td>
        <td style="vertical-align: middle;">{{ $d->nama_dept }}</td>
        <td style="vertical-align: middle;">{{ DateHelper::formatIndonesianDate($d->tanggal) }}</td>
        <td style="vertical-align: middle;">{{ $d->jam_masuk }}</td>
        <td style="vertical-align: middle;">
            {!! $d->jam_pulang != null ? $d->jam_pulang : '<span class="badge bg-danger" style="color: white;">Belum Absen</span>' !!}
        </td>
        <td style="vertical-align: middle;">
            @if ($jamInTime > $startTime)
                <div class="row">
                    <span class="badge bg-yellow text-yellow-fg" style="color: white;">Terlambat</span>
                    <span class="badge bg-yellow-lt" style="color: white;">
                        {{ $delayHours > 0 ? $delayHours . ' Jam ' : '' }}{{ $delayMinutes > 0 ? $delayMinutes . ' Menit' : '' }}
                    </span>
                </div>
            @else
                <div class="row">
                    <span class="badge bg-green text-yellow-fg" style="color: white;">Tepat Waktu</span>
                    <span class="badge bg-green-lt" style="color: white;">
                        On Time
                    </span>
                </div>
            @endif
        </td>
    </tr>
@endforeach
