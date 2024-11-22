@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Account Setting
                </div>
                <h2 class="page-title">
                    Setting
                </h2>
                <br>
            </div>
        </div>
    </div>
</div>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    });
</script>
@elseif(session('danger'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Danger!',
            text: "{{ session('danger') }}",
            icon: 'danger',
            confirmButtonText: 'Ok'
        });
    });
</script>
@endif
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="row g-0">
                <div class="col-12 col-md-3 border-end">
                    <div class="card-body">
                        <h4 class="subheader">Account Settings</h4>
                        <div class="list-group list-group-transparent">
                            <a href="/panel/accountSetting" class="list-group-item list-group-item-action d-flex align-items-center active">My Account</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-9 d-flex flex-column">
                    <div class="card-body">
                        <h3 class="card-title mt-4">User Profile</h3>
                        <div class="row g-3">
                            <div class="col-md">
                                <div class="form-label">Nama Lengkap</div>
                                <input type="text" class="form-control" value="{{ $karyawan->nama_lengkap ?? '' }}" readonly>
                            </div>
                            <div class="col-md">
                                <div class="form-label">Department</div>
                                <input type="text" class="form-control" value="{{ $karyawan->department_name ?? '' }}" readonly>
                            </div>
                            <div class="col-md">
                                <div class="form-label">Jabatan</div>
                                <input type="text" class="form-control" value="{{ $karyawan->jabatan_name ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md">
                                <div class="form-label">NIK</div>
                                <input type="text" class="form-control" value="{{ $karyawan->nik ?? '' }}" readonly>
                            </div>
                            <div class="col-md">
                                <div class="form-label">Nomer Mesin</div>
                                <input type="text" class="form-control" value="{{ $karyawan->nip ?? '' }}" readonly>
                            </div>
                            <div class="col-md">
                                <div class="form-label">Email</div>
                                <input type="text" class="form-control" value="{{ $karyawan->email ?? '' }}" readonly>
                            </div>
                        </div>
                        <h3 class="card-title mt-4">Password</h3>
                        <p class="card-subtitle">You can set a new password</p>
                        <form method="POST" action="{{ route('update-password') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Re-type New Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Re-type new password" required>
                            </div>
                            <div id="password-match" class="text-danger" style="display: none;">
                                Passwords do not match!
                            </div>
                            <div class="btn-list">
                                <button type="submit" class="btn btn-primary">Save Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const passwordMatchMessage = document.getElementById('password-match');

        passwordConfirmation.addEventListener('input', function() {
            if (password.value !== passwordConfirmation.value) {
                passwordMatchMessage.style.display = 'block';
            } else {
                passwordMatchMessage.style.display = 'none';
            }
        });
    });
</script>
@endpush
