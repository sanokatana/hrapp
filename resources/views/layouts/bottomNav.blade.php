<!-- App Bottom Menu -->
<div class="appBottomMenu">
    <a href="/dashboard" class="item {{ request()->is('dashboard') ? 'active' : ''}}">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>
    <a href="/presensi/create" class="item {{ request()->is('presensi/create') ? 'active' : ''}}">
        <div class="col">
            <ion-icon name="camera-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            <strong>Absen</strong>
        </div>
    </a>
    <a href="/presensi/histori" class="item {{ request()->is('presensi/histori') ? 'active' : ''}}">
        <div class="col">
            <ion-icon name="document-text-outline" role="img" class="md hydrated"
                aria-label="document text outline"></ion-icon>
            <strong>Histori</strong>
        </div>
    </a>
    <a href="/presensi/editprofile" class="item {{ request()->is('presensi/editprofile') ? 'active' : ''}}">
        <div class="col">
            <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="people outline"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div>
