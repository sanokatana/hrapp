@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Master Data</div>
                <h2 class="page-title">User</h2>
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
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    });
</script>
@endif

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <a href="#" class="btn btn-primary" id="btnTambahUser">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add User
                                </a>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Level</th>
                                            <th>Companies</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                                                        <tbody>
                                        @foreach ($user as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $user->firstItem() - 1 }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ $d->name }}</td>
                                            <td>{{ $d->email }}</td>
                                            <td>
                                                @if ($d->level == 'Superadmin')
                                                    <span class="badge bg-red">{{ $d->level }}</span>
                                                @elseif ($d->level == 'Admin')
                                                    <span class="badge bg-blue">{{ $d->level }}</span>
                                                @elseif ($d->level == 'HRD')
                                                    <span class="badge bg-green">{{ $d->level }}</span>
                                                @else
                                                    <span class="badge bg-yellow">{{ $d->level }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($d->companies->count() > 0)
                                                    @foreach($d->companies as $company)
                                                        <span class="badge bg-azure me-1">{{ $company->short_name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No companies</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="#" class="btn btn-sm btn-primary edit" id_user="{{ $d->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                            <path d="M16 5l3 3" />
                                                        </svg>
                                                    </a>
                                                    <form action="/data/user/{{ $d->id }}/delete" method="POST" style="margin-left: 5px;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger delete-confirm" type="submit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M4 7l16 0" />
                                                                <path d="M10 11l0 6" />
                                                                <path d="M14 11l0 6" />
                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $user->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-inputuser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/data/user/store" method="POST" id="formUser">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">NIK</div>
                            <div class="input-icon mb-3">
                                <input type="text" value="" class="form-control" name="nik" id="nik" placeholder="NIK">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">Nama User</div>
                            <div class="input-icon mb-3">
                                <input type="text" value="" class="form-control" name="name" id="name" placeholder="Nama">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">Email</div>
                            <div class="input-icon mb-3">
                                <input type="email" value="" class="form-control" name="email" id="email" placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-label">Level</div>
                            <select name="level" id="level" class="form-select">
                                <option value="">Pilih Level</option>
                                <option value="Management">Management</option>
                                <option value="HRD">HRD</option>
                                <option value="Superadmin">Superadmin</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-label">Password</div>
                            <div class="input-icon mb-3">
                                <input type="password" value="" class="form-control" name="password" id="password" placeholder="Password">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-edituser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeduser">
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
$(function() {
    $('#btnTambahUser').click(function() {
        $('#modal-inputuser').modal("show");
    });

    $('.edit').click(function() {
        var id = $(this).attr('id_user');
        $.ajax({
            type: 'POST',
            url: '/data/user/edit',
            cache: false,
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function(respond) {
                $('#loadeduser').html(respond);
            }
        });
        $('#modal-edituser').modal("show");
    });

    $(".delete-confirm").click(function(e) {
        var form = $(this).closest('form');
        e.preventDefault();
        Swal.fire({
            title: "Apakah Yakin?",
            text: "Data User Akan Di Delete!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Delete"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    $('#formUser').submit(function() {
        var nik = $('#nik').val();
        var name = $('#name').val();
        var email = $('#email').val();
        var level = $('#level').val();
        var password = $('#password').val();
        if (nik == "") {
            Swal.fire({ title: 'Warning!', text: 'NIK Harus Diisi', icon: 'warning', confirmButtonText: 'Ok' }).then(() => { $('#nik').focus(); });
            return false;
        } else if (name == "") {
            Swal.fire({ title: 'Warning!', text: 'Nama Harus Diisi', icon: 'warning', confirmButtonText: 'Ok' }).then(() => { $('#name').focus(); });
            return false;
        } else if (email == "") {
            Swal.fire({ title: 'Warning!', text: 'Email Harus Diisi', icon: 'warning', confirmButtonText: 'Ok' }).then(() => { $('#email').focus(); });
            return false;
        } else if (level == "") {
            Swal.fire({ title: 'Warning!', text: 'Level Harus Dipilih', icon: 'warning', confirmButtonText: 'Ok' }).then(() => { $('#level').focus(); });
            return false;
        } else if (password == "") {
            Swal.fire({ title: 'Warning!', text: 'Password Harus Diisi', icon: 'warning', confirmButtonText: 'Ok' }).then(() => { $('#password').focus(); });
            return false;
        }
    });
});
</script>
@endpush
