@php
    $entries = $history instanceof \Illuminate\Support\Collection ? $history : collect($history);
@endphp

@if ($entries->isEmpty())
<div class="card">
    <div class="card-body text-center">
        <p class="mb-0">Belum ada data presensi pada periode ini.</p>
    </div>
</div>
@else
    @foreach ($entries as $item)
    <ul class="listview image-listview rounded-custom">
        <li>
            <div class="item">
                <div class="icon-box bg-info">
                    <ion-icon name="finger-print-outline"></ion-icon>
                </div>
                <div class="in">
                    <div class="jam-row">
                        <div class="fw-bold">{{ $item->tanggal_label ?? '-' }}</div>
                        @if (!empty($item->lokasi))
                        <div class="text-muted">{{ $item->lokasi }}</div>
                        @endif
                    </div>
                    <div class="jam-row">
                        <div class="jam-in mb-1">
                            <span class="badge bg-success text-white" style="width: 80px;">
                                {{ $item->jam_masuk_label ?? '--:--' }}
                            </span>
                        </div>
                        <div class="jam-out">
                            <span class="badge bg-warning text-dark" style="width: 80px;">
                                {{ $item->jam_keluar_label ?? '--:--' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    @endforeach
@endif
