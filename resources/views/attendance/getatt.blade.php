@php
use App\Helpers\DateHelper;



@endphp
@foreach ($presensi as $d)
<tr style="text-align: center;">
    <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
    <td style="vertical-align: middle;">{{ $d['nip'] }}</td>
    <td style="vertical-align: middle;">{{ $d['nama_lengkap'] }}</td>
    <td style="vertical-align: middle;">{{ $d['nama_dept'] }}</td>
    <td style="vertical-align: middle;">{{ DateHelper::formatIndonesianDate($d['tanggal']) }}</td>
    <td style="vertical-align: middle;">
        {!! $d['jam_masuk'] !== null ? $d['jam_masuk'] : '<span class="badge bg-danger" style="color: white;">No Data</span>' !!}
    </td>
    <td style="vertical-align: middle;">
        {!! $d['jam_pulang'] !== null ? $d['jam_pulang'] : '<span class="badge bg-danger" style="color: white;">No Data</span>' !!}
    </td>
    <td style="vertical-align: middle;">
        @if ($d['jam_masuk'] === null)
        <div class="row">
            <span class="badge bg-danger text-yellow-fg" style="color: white;">No Data</span>
            <span class="badge bg-danger-lt" style="color: white;">
                0
            </span>
        </div>
        @elseif (strtotime($d['jam_masuk']) > strtotime('08:00:00'))
        @php
        $delayInSeconds = strtotime($d['jam_masuk']) - strtotime('08:00:00');
        $delayHours = floor($delayInSeconds / 3600);
        $delayMinutes = floor(($delayInSeconds % 3600) / 60);
        @endphp
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
