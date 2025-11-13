@extends('layouts.presensi')

@section('content')
@php
    $nameParts = explode(' ', trim($karyawan->nama_lengkap));
    $firstName = $nameParts[0] ?? $karyawan->nama_lengkap;
    $jobTitle = optional($karyawan->jabatan)->nama ?? 'Karyawan';
    $department = optional($karyawan->department)->nama;
    $todayIn = $todayRecord && $todayRecord->jam_masuk ? \Carbon\Carbon::createFromFormat('H:i:s', $todayRecord->jam_masuk)->format('H:i') : null;
    $todayOut = $todayRecord && $todayRecord->jam_keluar ? \Carbon\Carbon::createFromFormat('H:i:s', $todayRecord->jam_keluar)->format('H:i') : null;
@endphp

<style>
    .rounded-custom {
        border-radius: 10px;
        border: 1px solid #092c9f;
        margin-bottom: 4px;
    }

    .jam-row {
        display: flex;
        flex-direction: column;
    }

    .list-menu-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
    }

    .list-menu {
        display: flex;
        flex-wrap: nowrap;
    }

    .item-menu {
        flex: 0 0 auto;
        width: 33.3333%;
    }

    #user-detail {
    display: flex;
    align-items: center;
    gap: 12px;
}

#user-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

#user-name {
    font-weight: 700;
    font-size: 1.4rem;
    margin: 0;
    color: #ffffff;
    line-height: 1.1;
}

.user-meta {
    display: flex;
    flex-direction: column;
    margin-top: 2px;
}

.user-role {
    color: #e5e5e5;
    font-size: 0.95rem;
    font-weight: 500;
    line-height: 1.2;
}

.user-dept {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.8rem;
    font-weight: 400;
}

</style>

<div class="section" id="user-section">
    <div id="user-detail">
    <div class="avatar">
        <img src="{{ asset('assets/img/sample/avatar/male_avatar.jpg') }}" alt="avatar" class="imaged w64 rounded">
    </div>

    <div id="user-info">
        <h2 id="user-name">{{ $firstName }}</h2>

        <div class="user-meta">
            <span class="user-role">{{ $jobTitle }}</span>
            @if ($department)
                <span class="user-dept" style="margin-top: 10px;">{{ $department }}</span>
            @endif
        </div>
    </div>
</div>

</div>

<div class="section" id="menu-section">
    <div class="card">
        <div class="card-body text-center">
            <div class="list-menu-wrapper">
                <div class="list-menu">
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/presensi/editprofile" class="green" style="font-size: 40px;">
                                <ion-icon name="person-sharp"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Profil</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/presensi/create" class="green" style="font-size: 40px;">
                                <ion-icon name="camera-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Absen</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/presensi/histori" class="warning" style="font-size: 40px;">
                                <ion-icon name="document-text"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Histori</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section mt-2" id="presence-section">
    <div class="todaypresence">
        <div class="row">
            <div class="col-6">
                <div class="card gradasigreen">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                <ion-icon name="finger-print-outline"></ion-icon>
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Masuk</h4>
                                <span>{{ $todayIn ?? 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                <ion-icon name="finger-print-outline"></ion-icon>
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Pulang</h4>
                                <span>{{ $todayOut ?? 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="rekappresensi">
        <h3 style="text-align:center">Absensi {{ $monthName }} {{ $year }}</h3>
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-primary"
                            style="position:absolute; top:3px; right:5px; font-size:0.6rem; z-index:999">{{ $summary->presentDays }}</span>
                        <ion-icon name="accessibility-outline" style="font-size: 1.6rem;"
                            class="text-primary mb-1"></ion-icon>
                        <br>
                        <span style="font-size:0.8rem; font-weight:500">Hadir</span>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-danger"
                            style="position:absolute; top:3px; right:5px; font-size:0.6rem; z-index:999">{{ $summary->lateDays }}</span>
                        <ion-icon name="hourglass-outline" style="font-size: 1.6rem;"
                            class="text-danger mb-1"></ion-icon>
                        <br>
                        <span style="font-size:0.8rem; font-weight:500">Terlambat</span>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-warning"
                            style="position:absolute; top:3px; right:5px; font-size:0.6rem; z-index:999">{{ $summary->withoutCheckout }}</span>
                        <ion-icon name="walk-outline" style="font-size: 1.6rem;"
                            class="text-warning mb-1"></ion-icon>
                        <br>
                        <span style="font-size:0.8rem; font-weight:500">Belum Pulang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="presencetab mt-2" style="margin-bottom:100px;">
        <h3 class="mb-2">Histori Presensi</h3>
        @forelse ($history as $item)
        <ul class="listview image-listview rounded-custom">
            <li>
                <div class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="finger-print-outline"></ion-icon>
                    </div>
                    <div class="in">
                        <div class="jam-row">
                            <div class="fw-bold">{{ $item->tanggal_label }}</div>
                            @if ($item->lokasi)
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
        @empty
        <div class="card">
            <div class="card-body text-center">
                <p class="mb-0">Belum ada data presensi bulan ini.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('myscript')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const listMenuWrapper = document.querySelector('.list-menu-wrapper');
        if (!listMenuWrapper) {
            return;
        }

        let isDown = false;
        let startX;
        let scrollLeft;

        listMenuWrapper.addEventListener('mousedown', (e) => {
            isDown = true;
            listMenuWrapper.classList.add('active');
            startX = e.pageX - listMenuWrapper.offsetLeft;
            scrollLeft = listMenuWrapper.scrollLeft;
        });
        listMenuWrapper.addEventListener('mouseleave', () => {
            isDown = false;
            listMenuWrapper.classList.remove('active');
        });
        listMenuWrapper.addEventListener('mouseup', () => {
            isDown = false;
            listMenuWrapper.classList.remove('active');
        });
        listMenuWrapper.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - listMenuWrapper.offsetLeft;
            const walk = (x - startX) * 3;
            listMenuWrapper.scrollLeft = scrollLeft - walk;
        });
    });
</script>
@endpush
