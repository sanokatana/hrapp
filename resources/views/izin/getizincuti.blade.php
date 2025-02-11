@if ($historicuti->isEmpty())
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
@foreach ($historicuti as $d)
@php
// Format the date for each izin entry
$izinFormattedDate = DateHelper::formatIndonesianDate($d->tgl_cuti);
$izinFormattedDateAkhir = DateHelper::formatIndonesianDate($d->tgl_cuti_sampai);
@endphp
<ul class="listview image-listview rounded-custom">
    <li>
        <div class="item">
            <div class="in">
                <div style="width:65%">
                    <b>{{ $izinFormattedDate }}</b><br>
                    <b class="text-muted">Sampai</b><br>
                    @if ($d->tgl_cuti_sampai)
                    <b>{{ $izinFormattedDateAkhir }}</b><br>
                    @endif
                    <b style="color: red;">{{ $d->jenis }}</b><br>
                    @if ($d->tipe_cuti)
                    <b>{{ $d->tipe_cuti }}</b><br>
                    @endif
                    Keterangan - <b class="text-info">{{ $d->note }}</b>
                    @if ($d->keputusan)
                    Keputusan - <b class="text-info">{{ $d->keputusan }}</b>
                    @endif
                </div>

                <div class="status-row" style="text-align: right">
                    <div class="mb-1">
                        @if ($d->status_approved_hrd == 0)
                        <span class="badge bg-warning" style="width:120px">HRD</span>
                        @elseif ($d->status_approved_hrd == 1)
                        <span class="badge bg-success" style="width:120px">HRD</span>
                        @elseif ($d->status_approved_hrd == 2)
                        <span class="badge bg-danger" style="width:120px">HRD</span>
                        @else
                        <span class="badge bg-danger" style="width:120px">Pembatalan</span>
                        @endif
                    </div>
                    <div class="mb-1">
                        @if ($d->status_approved == 0)
                        <span class="badge bg-warning" style="width:120px">Atasan</span>
                        @elseif ($d->status_approved == 1)
                        <span class="badge bg-success" style="width:120px">Atasan</span>
                        @elseif ($d->status_approved == 1)
                        <span class="badge bg-danger" style="width:120px">Atasan</span>
                        @else
                        <span class="badge bg-danger" style="width:120px">Pembatalan</span>
                        @endif
                    </div>
                    <div>
                        @if ($d->status_management == 0)
                        <span class="badge bg-warning" style="width:120px">Management</span>
                        @elseif ($d->status_management == 1)
                        <span class="badge bg-success" style="width:120px">Management</span>
                        @elseif ($d->status_management == 2)
                        <span class="badge bg-danger" style="width:120px">Management</span>
                        @else
                        <span class="badge bg-danger" style="width:120px">Pembatalan</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
@endforeach
