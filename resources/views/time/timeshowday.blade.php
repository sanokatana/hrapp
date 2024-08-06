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
        {{ $d['total_jam_kerja'] }}
    </td>
</tr>
@endforeach
