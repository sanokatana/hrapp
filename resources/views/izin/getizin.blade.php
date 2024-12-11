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
                <div style="width:65%">
                    <b>{{ $izinFormattedDate }}</b><br>
                    @if ($d->tgl_izin_akhir)
                    <b class="text-muted">Sampai</b><br>
                    <b>{{ $izinFormattedDateAkhir }}</b><br>
                    @endif
                    <b style="color: red;">{{ DateHelper::getStatusText($d->status) }}</b><br>
                    <b class="text-info">Keterangan - {{ $d->keterangan }}</b>
                </div>
                <div class="status-row">
                    <div class="mb-1 text-center">
                        @if ($d->status_approved_hrd == 0)
                        <span class="badge bg-warning" style="width:110px">Approval HR</span>
                        @elseif ($d->status_approved_hrd == 1)
                        <span class="badge bg-success" style="width:110px">Approval HR</span>
                        @elseif ($d->status_approved_hrd == 2)
                        <span class="badge bg-danger" style="width:110px">Approval HR</span>
                        @else
                        <span class="badge bg-danger">Pembatalan</span>
                        @endif
                    </div>
                    <div class="text-center">
                        @if ($d->status_approved == 0)
                        <span class="badge bg-warning" style="width:110px">Approval Atasan</span>
                        @elseif ($d->status_approved == 1)
                        <span class="badge bg-success" style="width:110px">Approval Atasan</span>
                        @elseif ($d->status_approved == 2)
                        <span class="badge bg-danger" style="width:110px">Approval Atasan</span>
                        @else
                        <span class="badge bg-danger" style="width:110px">Pembatalan</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
@endforeach
