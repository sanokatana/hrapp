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
@php
@endphp
@section('content')
<div class="row" style="margin-top: 4rem;">
    <div class="col">
        @php
        $messagesuccess = Session::get('success');
        $messageerror = Session::get('error');
        @endphp
        @if (Session::get('success'))
        <div class="alert alert-success">
            {{ $messagesuccess}}
        </div>
        @endif
        @if (Session::get('error'))
        <div class="alert alert-danger">
            {{ $messageerror}}
        </div>
        @endif
    </div>
</div>
<form action="/presensi/{{ $karyawan->nik}}/updateprofile" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="col">
        <div class="form-group boxed text-center mb-1">
            <div class="image-wrapper mb-3 position-relative">
                @if (!empty($karyawan->foto))
                    <img src="{{ asset('storage/uploads/karyawan/'. $karyawan->nip . '.' . $karyawan->nama_lengkap . '/' . $karyawan->foto) }}" alt="avatar" class="imaged w100 rounded" style="height:100px; width:100px; object-fit:cover;">
                @else
                    @if($karyawan->sex == 'M')
                        <img src="{{ asset('assets/img/sample/avatar/male_avatar.jpg') }}" alt="avatar" class="imaged w100 rounded" style="height:100px; width:100px; object-fit:cover;">
                    @else
                        <img src="{{ asset('assets/img/sample/avatar/female_avatar.jpg') }}" alt="avatar" class="imaged w100 rounded" style="height:100px; width:100px; object-fit:cover;">
                    @endif
                @endif

                <!-- Upload Button -->
                <div class="upload-button">
                    <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg" style="display: none;">
                    <label for="fileuploadInput" class="btn btn-primary btn-sm rounded-circle">
                        <ion-icon name="camera" style="margin: 0px;"></ion-icon>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="text" class="form-control" value="{{ $karyawan->nama_lengkap}}" name="nama_lengkap" placeholder="Nama Lengkap" autocomplete="off" readonly>
            </div>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="text" class="form-control" value="{{ $karyawan->no_hp}}" name="no_hp" placeholder="No. HP" autocomplete="off" readonly>
            </div>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off">
            </div>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <button type="submit" class="btn btn-primary btn-block">
                    <ion-icon name="refresh-outline"></ion-icon>
                    Update
                </button>
                <button type="button" class="btn btn-warning btn-block" id="sisaButton">
                    <ion-icon name="calendar-number-outline"></ion-icon>
                    Cek Sisa Cuti
                </button>
                <button type="button" class="btn btn-danger btn-block" onclick="location.href='/proseslogout';" value="Go to Logout">
                    <ion-icon name="log-out-outline"></ion-icon>
                    Logout
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('myscript')
<script>

    $(document).ready(function() {

        $("#fileuploadInput").change(function() {
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('.image-wrapper img').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        var currYear = (new Date()).getFullYear();

        $("#sisaButton").click(function() {
            $.ajax({
                url: '/presensi/cek-sisa-cuti-profile',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    currYear: currYear
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Sisa Cuti',
                        html: response.sisa_cuti + ' hari untuk tahun ' + response.cutiYear + '<br> <b>Periode Awal</b>: ' + response.awal + '<br> <b>Periode Akhir</b>: ' + response.akhir,
                        icon: 'info',
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        Swal.fire({
                            title: 'Periode Cuti Tidak Ditemukan!',
                            text: 'Anda tidak ada Periode Cuti. Mohon hubungi HRD.',
                            icon: 'error',
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal mengambil sisa cuti',
                            icon: 'error',
                        });
                    }
                }
            });
        });
    });

</script>
@endpush

@push('styles')
<style>
    .custom-file-upload {
        border: 2px dashed #ddd;
        border-radius: 15px;
        padding: 8px 15px;
        text-align: center;
        cursor: pointer;
    }
    .custom-file-upload input {
        display: none;
    }
    .custom-file-upload label {
        margin: 0;
        cursor: pointer;
    }
    .image-wrapper {
        display: inline-block;
        position: relative;
    }
    .image-wrapper img {
        border: 3px solid #ddd;
    }
    .label {
        font-size: 0.875rem;
        color: #666;
        margin-bottom: 5px;
        display: block;
    }

    .upload-button {
        position: absolute;
        bottom: 0;
        right: 0;
        margin: 5px;
    }

    .upload-button label {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .upload-button ion-icon {
        font-size: 18px;
    }
</style>
@endpush
