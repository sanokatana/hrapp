@php
use App\Helpers\DateHelper;
@endphp

@foreach ($presensi->sortByDesc('tanggal') as $d)
<tr style="text-align: center;">
    <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
    <td style="vertical-align: middle;">{{ $d['nip'] }}</td>
    <td style="vertical-align: middle;">{{ $d['nama_lengkap'] }}</td>
    <td style="vertical-align: middle;">{{ $d['nama_dept'] }}</td>
    <td style="vertical-align: middle;">{{ DateHelper::formatIndonesianDate($d['tanggal']) }}</td>
    <td style="vertical-align: middle;">
        {!! $d['jam_masuk'] !== '' ? $d['jam_masuk'] : '<span class="badge bg-danger" style="color: white;">No Data</span>' !!}
    </td>
    <td style="vertical-align: middle;">
        {!! $d['jam_pulang'] !== '' ? $d['jam_pulang'] : '<span class="badge bg-danger" style="color: white;">No Data</span>' !!}
    </td>
    <td style="vertical-align: middle;">
        @if ($d['jam_masuk'] === '')
            <div class="row">
                <span class="badge bg-danger text-yellow-fg" style="color: white;">No Data</span>
                <span class="badge bg-danger-lt" style="color: white;">0</span>
            </div>
        @else
            @php
                $jam_masuk = strtotime($d['jam_masuk']);
                $shift_start = strtotime($d['shift_start_time']);
            @endphp

            @if ($jam_masuk > $shift_start)
                <div class="row">
                    <span class="badge bg-yellow text-yellow-fg" style="color: white;">Terlambat</span>
                    <span class="badge bg-yellow-lt" style="color: white;">
                        {{ floor(($jam_masuk - $shift_start) / 3600) }} Jam
                        {{ floor((($jam_masuk - $shift_start) % 3600) / 60) }} Menit
                    </span>
                </div>
            @else
                <div class="row">
                    <span class="badge bg-green text-yellow-fg" style="color: white;">Tepat Waktu</span>
                    <span class="badge bg-green-lt" style="color: white;">On Time</span>
                </div>
            @endif
        @endif
    </td>
</tr>
@endforeach
