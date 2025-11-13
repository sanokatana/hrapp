@extends('layouts.presensi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map {
        height: 230px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(13, 110, 253, 0.15);
    }

    .camera-frame {
        position: relative;
        width: 100%;
        background: #000;
        border-radius: 16px;
        overflow: hidden;
    }

    .camera-frame video {
        width: 100%;
        height: auto;
        display: block;
    }

    .camera-indicator {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .camera-indicator .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ff3b30;
        animation: blink 1.2s infinite;
    }

    .camera-indicator.offline {
        background: rgba(0, 0, 0, 0.45);
    }

    .camera-indicator.offline .dot {
        background: #6c757d;
        animation: none;
    }

    @keyframes blink {
        0%, 100% { opacity: 0.2; }
        50% { opacity: 1; }
    }

    #distanceInfo {
        min-height: 20px;
    }
</style>
@endpush

@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">E-Attendance</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
@php
    $cabangConfigured = $cabang && $cabang->latitude !== null && $cabang->longitude !== null && $cabang->radius_meter !== null;
@endphp

<input type="hidden" id="lokasi">
<input type="hidden" id="selfie">
<input type="hidden" id="attendanceMode" value="{{ $hasCheckedIn ? 'out' : 'in' }}">
<input type="hidden" id="cabangLat" value="{{ $cabangConfigured ? $cabang->latitude : '' }}">
<input type="hidden" id="cabangLng" value="{{ $cabangConfigured ? $cabang->longitude : '' }}">
<input type="hidden" id="cabangRadius" value="{{ $cabangConfigured ? $cabang->radius_meter : '' }}">

<div class="section" style="margin-top: 70px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small text-uppercase">Cabang</div>
                    <div class="fw-bold fs-5">{{ $cabang->nama ?? 'Belum ditetapkan' }}</div>
                    <div class="text-muted small">{{ $cabang->alamat ?? 'Alamat cabang belum diisi.' }}</div>
                </div>
                <div class="text-end">
                    <div class="text-muted small text-uppercase">Radius</div>
                    <div class="fw-bold fs-5">{{ $cabang->radius_meter ? $cabang->radius_meter . ' m' : '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!$cabangConfigured)
<div class="section pt-0">
    <div class="alert alert-warning shadow-sm mb-0">
        Lokasi cabang belum dikonfigurasi. Silakan hubungi administrator sebelum melakukan presensi.
    </div>
</div>
@endif

<div class="section mt-3">
    <div id="map"></div>
    <p class="small text-muted mt-2" id="distanceInfo">Menunggu lokasi Anda...</p>
</div>

<div class="section mt-3" style="margin-bottom: 50px;">
    <div class="camera-frame">
        <video id="selfieVideo" autoplay playsinline muted></video>
        <canvas id="selfieCanvas" class="d-none"></canvas>
        <div class="camera-indicator">
            <span class="dot"></span>
            <span class="label">Kamera aktif</span>
        </div>
    </div>
    <p class="small text-muted mt-2 mb-0">Pastikan wajah terlihat jelas sebelum menekan tombol presensi.</p>
</div>

<div class="section mt-4">
    <div class="form-button-group2">
        @if ($hasCheckedOut)
            <div class="alert alert-success mb-0">
                Anda sudah melakukan presensi masuk dan pulang hari ini. Terima kasih!
            </div>
        @else
            <button id="takeabsen"
                    class="btn btn-{{ $hasCheckedIn ? 'danger' : 'primary' }} btn-block"
                    data-mode="{{ $hasCheckedIn ? 'out' : 'in' }}"
                    {{ $cabangConfigured ? '' : 'disabled' }}>
                <ion-icon name="{{ $hasCheckedIn ? 'log-out-outline' : 'log-in-outline' }}"></ion-icon>
                {{ $hasCheckedIn ? 'Absen Pulang' : 'Absen Masuk' }}
            </button>
        @endif
    </div>
</div>

<audio id="notifikasi_in">
    <source src="{{ asset('assets/sound/notifikasi_in.mp3') }}" type="audio/mpeg">
</audio>
<audio id="notifikasi_out">
    <source src="{{ asset('assets/sound/notifikasi_out.mp3') }}" type="audio/mpeg">
</audio>
<audio id="radius_sound">
    <source src="{{ asset('assets/sound/radius.mp3') }}" type="audio/mpeg">
</audio>
@endsection

@push('myscript')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function () {
    const lokasiInput = document.getElementById('lokasi');
    const selfieInput = document.getElementById('selfie');
    const modeInput = document.getElementById('attendanceMode');
    const takeAbsenBtn = document.getElementById('takeabsen');
    const videoEl = document.getElementById('selfieVideo');
    const canvasEl = document.getElementById('selfieCanvas');
    const distanceInfo = document.getElementById('distanceInfo');
    const notifikasiIn = document.getElementById('notifikasi_in');
    const notifikasiOut = document.getElementById('notifikasi_out');
    const radiusSound = document.getElementById('radius_sound');
    const cameraIndicator = document.querySelector('.camera-indicator');

    const cabangLatValue = document.getElementById('cabangLat')?.value || '';
    const cabangLngValue = document.getElementById('cabangLng')?.value || '';
    const cabangRadiusValue = document.getElementById('cabangRadius')?.value || '';

    const cabangLat = cabangLatValue ? parseFloat(cabangLatValue) : null;
    const cabangLng = cabangLngValue ? parseFloat(cabangLngValue) : null;
    const cabangRadius = cabangRadiusValue ? parseFloat(cabangRadiusValue) : null;

    let mapInstance = null;
    let branchMarker = null;
    let branchCircle = null;
    let userMarker = null;
    let cameraReady = false;
    let cameraAlertShown = false;

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const toRad = x => x * Math.PI / 180;
        const R = 6371000; // meters
        const dLat = toRad(lat2 - lat1);
        const dLon = toRad(lon2 - lon1);
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function updateCameraIndicator(isActive, labelText) {
        if (!cameraIndicator) {
            return;
        }

        cameraIndicator.classList.toggle('offline', !isActive);
        const labelEl = cameraIndicator.querySelector('.label');
        if (labelEl) {
            labelEl.textContent = labelText;
        }
    }

    function notifyCameraOnce(title, text, icon) {
        if (cameraAlertShown) {
            return;
        }
        cameraAlertShown = true;
        Swal.fire({ title, text, icon });
    }

    function initMap(initialLat, initialLng) {
        if (mapInstance) {
            return;
        }

        const defaultLat = cabangLat ?? initialLat;
        const defaultLng = cabangLng ?? initialLng;

        mapInstance = L.map('map').setView([defaultLat, defaultLng], 17);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
        }).addTo(mapInstance);

        if (cabangLat !== null && cabangLng !== null) {
            branchMarker = L.marker([cabangLat, cabangLng]).addTo(mapInstance)
                .bindPopup('Lokasi Cabang');
            branchCircle = L.circle([cabangLat, cabangLng], {
                radius: cabangRadius ?? 0,
                color: '#0d6efd',
                fillColor: '#0d6efd',
                fillOpacity: 0.12,
            }).addTo(mapInstance);
        }
    }

    function updateUserMarker(lat, lng) {
        if (!mapInstance) {
            initMap(lat, lng);
        }

        if (userMarker) {
            userMarker.setLatLng([lat, lng]);
        } else {
            userMarker = L.marker([lat, lng], { icon: L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
            }) }).addTo(mapInstance).bindPopup('Lokasi Anda');
        }

        if (cabangLat !== null && cabangLng !== null && cabangRadius !== null) {
            const distance = calculateDistance(lat, lng, cabangLat, cabangLng);
            if (distanceInfo) {
                distanceInfo.textContent = `Jarak Anda dari cabang: ${distance.toFixed(1)} meter`;
            }
            if (takeAbsenBtn) {
                takeAbsenBtn.dataset.distance = distance;
            }
        } else if (distanceInfo) {
            distanceInfo.textContent = 'Lokasi cabang belum dikonfigurasi. Presensi akan ditolak.';
        }
    }

    function initGeolocation() {
        if (!('geolocation' in navigator)) {
            Swal.fire({
                title: 'Lokasi Tidak Didukung',
                text: 'Perangkat Anda tidak mendukung fitur geolokasi.',
                icon: 'error'
            });
            takeAbsenBtn?.setAttribute('disabled', true);
            return;
        }

        const handlePosition = position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            lokasiInput.value = `${lat},${lng}`;
            updateUserMarker(lat, lng);
        };

        const handleError = error => {
            console.error('Geolocation error:', error);
            Swal.fire({
                title: 'Lokasi Tidak Aktif',
                text: 'Aktifkan GPS dan izinkan akses lokasi untuk presensi.',
                icon: 'warning'
            });
        };

        navigator.geolocation.getCurrentPosition(handlePosition, handleError, {
            enableHighAccuracy: true,
            maximumAge: 0,
            timeout: 10000,
        });

        navigator.geolocation.watchPosition(handlePosition, handleError, {
            enableHighAccuracy: true,
            maximumAge: 0,
            timeout: 10000,
        });
    }

    function initCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            cameraReady = false;
            updateCameraIndicator(false, 'Kamera tidak tersedia');
            notifyCameraOnce('Kamera Tidak Tersedia', 'Perangkat tidak mendukung kamera atau akses tidak tersedia. Presensi tetap dapat dilanjutkan tanpa selfie.', 'info');
            return;
        }

        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
            .then(stream => {
                cameraReady = true;
                updateCameraIndicator(true, 'Kamera aktif');
                if (videoEl) {
                    videoEl.srcObject = stream;
                }
            })
            .catch(() => {
                cameraReady = false;
                updateCameraIndicator(false, 'Kamera tidak aktif');
                notifyCameraOnce('Akses Kamera Ditolak', 'Izinkan akses kamera untuk mengambil selfie. Presensi tetap dapat dilanjutkan tanpa selfie.', 'warning');
            });
    }

    function captureSelfie() {
        if (!videoEl.videoWidth || !videoEl.videoHeight) {
            return null;
        }
        canvasEl.width = videoEl.videoWidth;
        canvasEl.height = videoEl.videoHeight;
        const context = canvasEl.getContext('2d');
        context.drawImage(videoEl, 0, 0, canvasEl.width, canvasEl.height);
        return canvasEl.toDataURL('image/jpeg', 0.92);
    }

    function handleSubmit(event) {
        event.preventDefault();
        if (!takeAbsenBtn || takeAbsenBtn.disabled) {
            return;
        }

        const lokasi = lokasiInput.value;
        if (!lokasi) {
            Swal.fire({
                title: 'Lokasi Belum Terdeteksi',
                text: 'Pastikan GPS aktif dan coba lagi.',
                icon: 'warning'
            });
            return;
        }

        if (cabangLat !== null && cabangLng !== null && cabangRadius !== null) {
            const [userLat, userLng] = lokasi.split(',').map(parseFloat);
            const distance = calculateDistance(userLat, userLng, cabangLat, cabangLng);
            if (distance > cabangRadius) {
                radiusSound?.play();
                Swal.fire({
                    title: 'Di Luar Radius',
                    text: `Jarak Anda ${Math.round(distance)} meter dari lokasi cabang.`,
                    icon: 'error'
                });
                return;
            }
        }

        const originalHtml = takeAbsenBtn.innerHTML;
        const originalDisabled = takeAbsenBtn.disabled;

        let selfieData = '';
        if (cameraReady) {
            const captured = captureSelfie();
            if (!captured) {
                Swal.fire({
                    title: 'Selfie Gagal',
                    text: 'Kamera belum siap. Tunggu beberapa detik kemudian coba lagi.',
                    icon: 'warning'
                });
                return;
            }
            selfieData = captured;
        }

        selfieInput.value = selfieData;

        takeAbsenBtn.disabled = true;
        takeAbsenBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

        $.ajax({
            type: 'POST',
            url: '/presensi/store',
            data: {
                _token: '{{ csrf_token() }}',
                lokasi: lokasi,
                selfie: selfieData,
            },
            success: function (response) {
                const parts = response.split('|');
                const status = parts[0] || 'error';
                const message = parts[1] || 'Terjadi kesalahan.';
                const mode = parts[2] || (modeInput ? modeInput.value : 'in');

                if (status === 'success') {
                    if (mode === 'in') {
                        notifikasiIn?.play();
                    } else {
                        notifikasiOut?.play();
                    }

                    Swal.fire({
                        title: 'Berhasil!',
                        text: message,
                        icon: 'success'
                    });

                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 2500);
                } else {
                    if (message.toLowerCase().includes('radius')) {
                        radiusSound?.play();
                    }
                    Swal.fire({
                        title: 'Error!',
                        text: message,
                        icon: 'error'
                    });
                    takeAbsenBtn.disabled = originalDisabled;
                    takeAbsenBtn.innerHTML = originalHtml;
                }
            },
            error: function () {
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal menghubungi server. Silakan coba kembali.',
                    icon: 'error'
                });
                takeAbsenBtn.disabled = originalDisabled;
                takeAbsenBtn.innerHTML = originalHtml;
            }
        });
    }

    initCamera();
    initGeolocation();

    if (takeAbsenBtn) {
        takeAbsenBtn.addEventListener('click', handleSubmit);
    }
})();
</script>
@endpush
