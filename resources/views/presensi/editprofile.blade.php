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
use App\Helpers\DateHelper;
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
