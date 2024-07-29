<!-- App Bottom Menu -->
<div class="appBottomMenu">
    <a href="/dashboard" class="item {{ request()->is('dashboard') ? 'active' : ''}}">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>
    <a href="/presensi/histori" class="item {{ request()->is('presensi/histori') ? 'active' : ''}}">
        <div class="col">
            <ion-icon name="document-text-outline" role="img" class="md hydrated"
                aria-label="document text outline"></ion-icon>
            <strong>History</strong>
        </div>
    </a>
    <!-- <a href="/presensi/create" class="item {{ request()->is('presensi/create') ? 'active' : ''}}">
        <div class="col">
            <ion-icon name="camera-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            <strong>Absen</strong>
        </div>
    </a> -->
    <a href="/presensi/izin" class="item {{ request()->is('presensi/izin') ? 'active' : ''}}">
        <div class="col">
            <ion-icon name="calendar-outline"></ion-icon>
            <strong>Izin</strong>
        </div>
    </a>
    <a href="/notifikasi" class="item {{ request()->is('notifikasi') ? 'active' : ''}}">
        <div class="col">
        <ion-icon name="notifications-outline"  role="img" class="md hydrated"></ion-icon>
            <strong>Notifikasi</strong>
        </div>
    </a>
    <a href="/editprofile" class="item {{ request()->is('editprofile') ? 'active' : ''}}">
        <div class="col">
            <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="people outline"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div>
