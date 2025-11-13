@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Edit Profile</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
<div class="section">
    <div class="container pt-3" style="padding-left: 0px; padding-right: 0px;">
        @php
        $messagesuccess = Session::get('success');
        $messageerror = Session::get('error');
        @endphp
        @if (Session::get('success'))
        <div class="alert alert-success mb-2">
            <div class="d-flex">
                <div>
                    <ion-icon name="checkmark-circle" class="me-2" style="font-size: 24px;"></ion-icon>
                </div>
                <div>{{ $messagesuccess }}</div>
            </div>
        </div>
        @endif
        @if (Session::get('error'))
        <div class="alert alert-danger mb-2">
            <div class="d-flex">
                <div>
                    <ion-icon name="alert-circle" class="me-2" style="font-size: 24px;"></ion-icon>
                </div>
                <div>{{ $messageerror }}</div>
            </div>
        </div>
        @endif

        <div class="card mb-2">
            <div class="card-body p-3 pt-4">
                <form action="/presensi/{{ $karyawan->nik }}/updateprofile" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Profile Photo -->
                    <div class="text-center">
                        <div class="avatar-upload mb-3">
                            <div class="avatar-preview mx-auto">
                                @if (!empty($karyawan->foto))
                                <img src="{{ asset('storage/uploads/karyawan/'. $karyawan->nik . '.' . $karyawan->nama_lengkap . '/' . $karyawan->foto) }}" alt="avatar" id="profileImage" class="profile-image">
                                @else
                                @if($karyawan->sex == 'M')
                                <img src="{{ asset('assets/img/sample/avatar/male_avatar.jpg') }}" alt="avatar" id="profileImage" class="profile-image">
                                @else
                                <img src="{{ asset('assets/img/sample/avatar/female_avatar.jpg') }}" alt="avatar" id="profileImage" class="profile-image">
                                @endif
                                @endif
                                <!-- Camera button overlay -->
                                <div class="camera-button">
                                    <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg" style="display: none;">
                                    <label for="fileuploadInput" class="camera-icon-wrapper">
                                        <ion-icon name="camera"></ion-icon>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="form-group mb-3">
                        <div class="form-label">Full Name</div>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <ion-icon name="person-outline"></ion-icon>
                            </span>
                            <input type="text" class="form-control" value="{{ $karyawan->nama_lengkap }}" name="nama_lengkap" placeholder="Full Name" autocomplete="off" readonly>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-label">Phone Number</div>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <ion-icon name="call-outline"></ion-icon>
                            </span>
                            <input type="text" class="form-control" value="{{ $karyawan->no_hp }}" name="no_hp" placeholder="Phone Number" autocomplete="off" readonly>
                        </div>
                    </div>

                    <div class="row mt-4 g-2">
                        <div class="col-12">
                            <button type="button" class="btn btn-danger btn-block" id="logoutButton">
                                <ion-icon name="log-out-outline" class="me-1"></ion-icon>
                                Logout
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Image preview functionality
        $("#fileuploadInput").change(function() {
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#profileImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Cek Sisa Cuti functionality
        var currYear = (new Date()).getFullYear();
        $("#sisaButton").click(function() {
            // Show loading state
            Swal.fire({
                title: 'Checking...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '/presensi/cek-sisa-cuti-profile',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    currYear: currYear
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Leave Balance',
                        html: `
                            <div class="text-start">
                                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                    <span class="fw-bold">Remaining:</span>
                                    <span class="badge bg-primary">${response.sisa_cuti} days</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold">Year:</span>
                                    <span>${response.cutiYear}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold">Period Start:</span>
                                    <span>${response.awal}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Period End:</span>
                                    <span>${response.akhir}</span>
                                </div>
                            </div>
                        `,
                        icon: 'info',
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        Swal.fire({
                            title: 'No Leave Period Found',
                            text: 'You don\'t have an active leave period. Please contact HR.',
                            icon: 'error',
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to retrieve leave balance',
                            icon: 'error',
                        });
                    }
                }
            });
        });

        // Logout confirmation
        $("#logoutButton").click(function() {
            Swal.fire({
                title: 'Confirm Logout',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/proseslogout';
                }
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    .section {
        padding: 15px;
        margin-top: 40px;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border: none;
    }

    /* Profile image styling */
    .avatar-upload {
        position: relative;
        display: inline-block;
    }

    .avatar-preview {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        background-color: #f8f9fa;
        border: 3px solid #e0e0e0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .camera-button {
        position: absolute;
        right: 0;
        bottom: 0;
        background: white;
        border-radius: 50%;
        padding: 2px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .camera-icon-wrapper {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
    }

    /* Form styling */
    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #495057;
        font-size: 14px;
    }

    .form-hint {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }

    .input-icon {
        position: relative;
    }

    .input-icon-addon {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        background-color: transparent;
        border-right: 1px solid #dee2e6;
        z-index: 1;
    }

    .input-icon .form-control {
        padding-left: 45px;
        height: 45px;
        border-radius: 10px;
    }

    .btn {
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        font-size: 14px;
    }

    .btn-block {
        width: 100%;
    }

    .btn ion-icon {
        font-size: 18px;
    }

    /* Alert styling */
    .alert {
        border-radius: 10px;
        border-left: 4px solid;
        padding: 12px;
        margin-bottom: 15px;
    }

    .alert-success {
        border-left-color: #10b981;
        background-color: #d1fae5;
        color: #047857;
    }

    .alert-danger {
        border-left-color: #ef4444;
        background-color: #fee2e2;
        color: #b91c1c;
    }
</style>
@endpush
