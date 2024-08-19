@extends('layouts.presensi')
@section('content')
@php
use App\Helpers\DateHelper;
@endphp
@if ($totalNotif >= 1)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'info',
            title: 'Anda ada notifikasi',
            text: 'Anda memiliki {{ $totalNotif }} notifikasi',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif
<style>
    .rounded-custom {
        border-radius: 10px;
        border: 1px solid #092c9f;
        margin-bottom: 4px;

        /* Customize the radius as needed */
    }

    .jam-row {
        display: flex;
        flex-direction: column;
    }

    .status-row {
        display: flex;
        flex-direction: column;
        align-items: end;
    }

    .jam-in {
        width: 100%;
        /* Make each badge occupy full width */
    }

    .jam-out {
        width: 100%;
        /* Make each badge occupy full width */
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
        /* Adjust this width as necessary to show 3 items */
    }
</style>
<div class="section" id="user-section">
    <div id="user-detail">
        <div class="avatar">
            @if (!empty($namaUser->foto))
            @php
            $path = Storage::url('uploads/karyawan/' . $namaUser->foto)
            @endphp
            <img src="{{ url($path) }}" alt="avatar" class="imaged w64" style="height:64px">
            @else
            <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w64 rounded">
            @endif
        </div>
        <div id="user-info">
            <h2 id="user-name">{{ $namaUser->first_name }}</h2>
            <span id="user-role">{{ $namaUser->nama_jabatan }}</span>
        </div>
        <div class="avatar">
            <span class="badge bg-danger" style="position:absolute; top:45px; right:29px; font-size:0.6rem; z-index:999">{{ $totalNotif }}</span>
            <ion-icon name="notifications" style="position:absolute; z-index:999; left: 280px; height:64px; width:40px ; margin-left: 20px; margin-top: 5px ; color: white; vertical-align: middle;"></ion-icon>
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
                            <a href="/editprofile" class="green" style="font-size: 40px;">
                                <ion-icon name="person-sharp"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Profil</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/presensi/izin" class="danger" style="font-size: 40px;">
                                <ion-icon name="calendar-number"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Cuti</span>
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
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="" class="orange" style="font-size: 40px;">
                                <ion-icon name="folder-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            Files
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="" class="blue" style="font-size: 40px;">
                                <ion-icon name="briefcase-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            Reimbursement
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="" class="purple" style="font-size: 40px;">
                                <ion-icon name="school-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            Training
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="" class="green" style="font-size: 40px;">
                                <ion-icon name="camera-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            Absen
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
                                @if ($presensihariini != null && $presensihariini->foto_in != null)
                                @php
                                $path = Storage::url('/uploads/absensi/'.$presensihariini->foto_in);
                                @endphp
                                <img src="{{ url($path) }}" alt="" class="imaged w48">
                                @else
                                <ion-icon name="finger-print-outline"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Masuk</h4>
                                <span>{{ $presensihariini != null ? $presensihariini->jam_in : 'Belum Absen'}}</span>
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
                                @if ($presensihariini != null && $presensihariini->foto_out != null)
                                @php
                                $path = Storage::url('/uploads/absensi/'.$presensihariini->foto_out);
                                @endphp
                                <img src="{{ url($path) }}" alt="" class="imaged w48">
                                @else
                                <ion-icon name="finger-print-outline"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Pulang</h4>
                                <span>{{ $presensihariini != null && $presensihariini->jam_out != null ? $presensihariini->jam_out : 'Belum Absen'}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="rekappresensi">
        <h3>Rekap Presensi Bulan {{ $namabulan[$bulanini]}} Tahun {{$tahunini}}</h3>
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-danger" style="position:absolute; top:3px; right:5px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlhadir}}</span>
                        <ion-icon name="accessibility-outline" style="font-size: 1.6rem;" class="text-primary mb-1"></ion-icon>
                        <br>
                        <span style="font-size:0.8rem; font-weight:500">Hadir</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-danger" style="position:absolute; top:3px; right:5px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlterlambat}}</span>
                        <ion-icon name="hourglass-outline" style="font-size: 1.6rem;" class="text-danger mb-1"></ion-icon>
                        <br>
                        <span style="font-size:0.8rem; font-weight:500">Telat</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-danger" style="position:absolute; top:3px; right:5px; font-size:0.6rem; z-index:999">{{ $rekapizin->jmlizin}}</span>
                        <ion-icon name="newspaper-outline" style="font-size: 1.6rem;" class="text-success mb-1"></ion-icon>
                        <br>
                        <span style="font-size:0.8rem; font-weight:500">Izin</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.8rem">
                        <span class="badge bg-danger" style="position:absolute; top:3px; right:5px; font-size:0.6rem; z-index:999">{{ $rekapcuti->jmlcuti }}</span>
                        <ion-icon name="document-attach-outline" style="font-size: 1.6rem;" class="text-warning mb-1"></ion-icon>
                        <br>
                        <span style="font-size:0.8rem; font-weight:500">Cuti</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="presencetab mt-2">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                        Hadir
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#formView" role="tab">
                        Form
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#cutiView" role="tab">
                        Cuti
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                @foreach ($processedHistoribulanini as $d)
                <ul class="listview image-listview rounded-custom">
                    @php
                    $jam_masuk_time = strtotime($d->jam_masuk);
                    $threshold_time = $d->jam_kerja;
                    $lateness_threshold = $d->jam_kerja;

                    // Calculate lateness
                    if ($jam_masuk_time <= $lateness_threshold) {
                        $lateness="Tepat Waktu" ;
                        } else {
                        $hours_diff=floor(($jam_masuk_time - $threshold_time) / 3600);
                        $minutes_diff=floor((($jam_masuk_time - $threshold_time) % 3600) / 60);
                        $lateness=($hours_diff> 0 ? $hours_diff . " Jam " : "") . ($minutes_diff > 0 ? $minutes_diff . " Menit" : "");
                        }

                        // Determine status based on lateness
                        $status = ($lateness == "Tepat Waktu") ? "On Time" : "Terlambat";
                        @endphp

                        <li>
                            <div class="item">
                                <div class="icon-box bg-info">
                                    <ion-icon name="finger-print-outline"></ion-icon>
                                </div>

                                <div class="in">
                                    <div class="jam-row">
                                        <div><b>{{ DateHelper::formatIndonesianDate($d->tanggal) }}</b></div>
                                        <div class="status {{ $status == 'Terlambat' ? 'text-danger' : 'text-success' }}">
                                            <b>{{ $status }}</b>
                                        </div>
                                        <div class="lateness {{ $status == 'Terlambat' ? 'text-warning' : 'text-success' }}">
                                            ({{ $lateness }})
                                        </div>
                                    </div>
                                    <div class="jam-row">
                                        <div class="jam-in mb-1">
                                            <span class="badge {{ $status == 'Terlambat' ? 'badge-danger' : 'badge-success' }}" style="width: 70px;">{{ $d->jam_masuk }}</span>
                                        </div>
                                        <div class="jam-out">
                                            <span class="badge {{ $d->jam_pulang != null ? 'badge-success' : "badge-danger" }}" style="width: 70px;">{{ $d->jam_pulang != null ? $d->jam_pulang : "No Scan" }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                </ul>
                @endforeach

            </div>

            <div class="tab-pane fade" id="formView" role="tabpanel">
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
                                    @if ($d->tgl_izin_akhir)
                                    <b class="text-muted">Sampai</b><br>
                                    <b>{{ $izinFormattedDateAkhir }}</b><br>
                                    @endif
                                    <b style="color: red;">{{ DateHelper::getStatusText($d->status) }}</b><br>
                                    <b class="text-info">{{ $d->keterangan }}</b>
                                </div>
                                <div class="status-row">
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
            </div>
            <div class="tab-pane fade" id="cutiView" role="tabpanel">
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
                                <div>
                                    <b>{{ $izinFormattedDate }}</b><br>
                                    <b class="text-muted">Sampai</b><br>
                                    @if ($d->tgl_cuti_sampai)
                                    <b>{{ $izinFormattedDateAkhir }}</b><br>
                                    @endif
                                    <b style="color: red;">Cuti</b><br>
                                    @if ($d->tipe_cuti)
                                    <b class="text-info">{{ $d->tipe_cuti }}</b><br>
                                    @else
                                    <b class="text-info">Cuti Tahunan</b><br>
                                    @endif
                                    <b class="text-success">{{ $d->note }}</b>
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
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const listMenuWrapper = document.querySelector('.list-menu-wrapper');
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
            const walk = (x - startX) * 3; //scroll-fast
            listMenuWrapper.scrollLeft = scrollLeft - walk;
        });
    });
</script>
@endpush
