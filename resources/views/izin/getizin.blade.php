@if ($historiizin->isEmpty())
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
@foreach ($historiizin as $d)
@php
// Format the date for each izin entry
$izinFormattedDate = DateHelper::formatIndonesianDate($d->tgl_izin);
$izinFormattedDateAkhir = DateHelper::formatIndonesianDate($d->tgl_izin_akhir);
@endphp
<ul class="listview image-listview rounded-custom">
    <li>
        <div class="item">
            <div class="in">
                <div>
                    <b>{{ $izinFormattedDate }}</b><br>
                    <b class="text-muted">Sampai</b><br>
                    @if ($d->tgl_izin_akhir)
                    <b>{{ $izinFormattedDateAkhir }}</b><br>
                    @endif
                    <b style="color: red;">{{ DateHelper::getStatusText($d->status) }}</b><br>
                    Note - <b class="text-info">{{ $d->keterangan }}</b>
                </div>
                <div class="status-row" style="text-align: right">
                    <div class="mb-1">
                        @if ($d->status_approved == 0)
                        <span class="badge bg-warning">Waiting Approval</span>
                        @elseif ($d->status_approved == 1)
                        <span class="badge bg-success">Form Approved</span>
                        @else
                        <span class="badge bg-danger">Form Declined</span>
                        @endif
                    </div>
                    <div>
                        @if ($d->status_approved_hrd == 0)
                        <span class="badge bg-warning">Waiting Approval</span>
                        @elseif ($d->status_approved_hrd == 1)
                        <span class="badge bg-success">Form Approved</span>
                        @else
                        <span class="badge bg-danger">Form Declined</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
@endforeach
