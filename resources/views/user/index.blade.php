@extends('layouts.admin.tabler')@extends('layouts.admin.tabler')@extends('layouts.admin.tabler')



@section('content')@section('content')@section('content')

<div class="page-header d-print-none">

    <div class="container-xl"><div class="page-header d-print-none">

        <div class="row g-2 align-items-center">    <div class="page-header d-print-none">

            <div class="col">

                <div class="page-pretitle">Master Data</div>        <div class="container-xl">

                <h2 class="page-title">User</h2>            <div class="container-xl">

            </div>

        </div>                <div class="row g-2 align-items-center">

    </div>                    <div class="row g-2 align-items-center">

</div>

                        <div class="col">

@if(session('success'))                            <div class="col">

<script>

    document.addEventListener('DOMContentLoaded', function() {                                <div class="page-pretitle">Master Data</div>

        Swal.fire({                                <div class="page-pretitle">Master Data</div>

            title: 'Berhasil!',

            text: "{{ session('success') }}",                                <h2 class="page-title">User</h2>

            icon: 'success',                                <h2 class="page-title">User</h2>

            confirmButtonText: 'Ok'

        });                            </div>

    });                        </div>

</script>

@elseif(session('danger'))                    </div>

<script>                    <div class="col-auto ms-auto d-print-none">

    document.addEventListener('DOMContentLoaded', function() {

        Swal.fire({                    </div> <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-user-create">

            title: 'Danger!',

            text: "{{ session('danger') }}",                </div> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">

            icon: 'error',

            confirmButtonText: 'Ok'                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />

        });

    });                    @if(session('success'))

</script>                    <path d="M12 5l0 14" />

@endif

                    <script>

<div class="page-body">                        < path d = "M5 12l14 0" / >

    <div class="container-xl">

        <div class="row">                            document.addEventListener('DOMContentLoaded', function() {

            <div class="col-12">                                < /svg>

                <div class="card">

                    <div class="card-body">                                Swal.fire({

                        <div class="row">                                    Add Data

                            <div class="col-12">

                                <a href="#" class="btn btn-primary" id="btnTambahUser">                                    title: 'Berhasil!',

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">                                    < /a>

                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                                        <path d="M12 5l0 14" />                                    text: "{{ session('success') }}",

                                        <path d="M5 12l14 0" />                                    < /div>

                                    </svg>

                                    Add User                                    icon: 'success',

                                </a>                                    < /div>

                            </div>

                        </div>                                    confirmButtonText: 'Ok' < /div>

                        <div class="row mt-4">

                            <div class="col-12 table-responsive">                                }); < /div>

                                <table class="table table-vcenter card-table table-striped">

                                    <thead>                            });

                                        <tr>                    </script>@if(session('success'))

                                            <th>No</th>

                                            <th>NIK</th>                    @elseif(session('danger'))<script>

                                            <th>Nama</th>                        < script > document.addEventListener('DOMContentLoaded', function() {

                                            <th>Email</th>

                                            <th>Level</th>                            document.addEventListener('DOMContentLoaded', function() {

                                            <th>Aksi</th>                                Swal.fire({

                                        </tr>

                                    </thead>                                    Swal.fire({

                                    <tbody>                                        title: 'Berhasil!',

                                        @foreach ($user as $d)

                                        <tr>                                        title: 'Danger!',

                                            <td>{{ $loop->iteration }}</td>                                        text: "{{ session('success') }}",

                                            <td>{{ $d->nik }}</td>

                                            <td>{{ $d->name }}</td>                                        text: "{{ session('danger') }}",

                                            <td>{{ $d->email }}</td>                                        icon: 'success',

                                            <td>{{ $d->level }}</td>

                                            <td>                                        icon: 'error',

                                                <div class="form-group">                                        confirmButtonText: 'Ok'

                                                    <a href="#" class="edit btn btn-info btn-sm" id_user="{{ $d->id }}">

                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">                                        confirmButtonText: 'Ok'

                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />                                    });

                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />

                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />                                });

                                                            <path d="M16 5l3 3" />                            });

                                                        </svg>

                                                    </a>                        });

                                                    <form action="{{ route('user.delete', $d) }}" method="POST" style="display:inline;">                    </script>

                                                        @csrf

                                                        @method('DELETE')                    </script>@elseif(session('danger'))

                                                        <a class="btn btn-danger btn-sm delete-confirm">

                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">                    @endif<script>

                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />                        document.addEventListener('DOMContentLoaded', function() {

                                                                <path d="M4 7l16 0" />

                                                                <path d="M10 11l0 6" />                            <

                                                                <path d="M14 11l0 6" />                            div class = "page-body" > Swal.fire({

                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />

                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />                                <

                                                            </svg>                                div class = "container-xl" > title: 'Danger!',

                                                        </a>

                                                    </form>                                <

                                                </div>                                div class = "row" > text: "{{ session('danger') }}",

                                            </td>

                                        </tr>                                <

                                        @endforeach                                div class = "col-12" > icon: 'error',

                                    </tbody>

                                </table>                                <

                                {{ $user->links('vendor.pagination.bootstrap-5') }}                                div class = "card" > confirmButtonText: 'Ok'

                            </div>

                        </div>                                    <

                    </div>                                    div class = "card-body" >

                </div>                            });

            </div>

        </div>                            <

    </div>                            div class = "row" >

</div>                        });



<!-- Modal Create -->                        <

<div class="modal modal-blur fade" id="modal-inputuser" tabindex="-1" role="dialog" aria-hidden="true">                        div class = "col-12" >

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">                    </script>

        <div class="modal-content">

            <div class="modal-header">                    <a href="#" class="btn btn-primary" id="btnTambahUser">@endif

                <h5 class="modal-title">Tambah User</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">

            </div>

            <div class="modal-body">                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                <form action="{{ url('/data/user/store') }}" method="POST" id="formUser">                            <div class="page-body">

                    @csrf

                    <div class="row">                                <path d="M12 5l0 14" />

                        <div class="col-12">                                <div class="container-xl">

                            <div class="form-label">NIK</div>

                            <div class="input-icon mb-3">                                    <path d="M5 12l14 0" />

                                <span class="input-icon-addon">                                    <div class="row">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id">

                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />                        </svg>

                                        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />                        <div class="col-12">

                                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />

                                        <path d="M15 8l2 0" />                            Add User <div class="card">

                                        <path d="M15 12l2 0" />

                                        <path d="M7 16l10 0" />                    </a>

                                    </svg>                    <div class="card-body">

                                </span>

                                <input type="text" value="" class="form-control" name="nik" id="nik" placeholder="NIK">                    </div>

                            </div>                    <form action="{{ url('/data/user') }}" method="GET" class="row g-2 align-items-end">

                        </div>

                    </div>            </div>

                    <div class="row">            <div class="col-lg-10 col-md-9">

                        <div class="col-12">

                            <div class="form-label">Nama User</div>                <div class="row mt-4"> <label class="form-label">Nama User</label>

                            <div class="input-icon mb-3">

                                <span class="input-icon-addon">                    <div class="col-12 table-responsive"> <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama User" value="{{ request('nama') }}">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">

                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />                        <table class="table table-vcenter card-table table-striped">

                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />                    </div>

                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />

                                    </svg>                    <thead>

                                </span>                        <div class="col-lg-2 col-md-3">

                                <input type="text" value="" class="form-control" name="name" id="name" placeholder="Nama">

                            </div>                            <tr> <button class="btn btn-primary w-100" type="submit">

                        </div>

                    </div>                                    <th>NIK</th> Cari

                    <div class="row">

                        <div class="col-12">                                    <th>Nama</th>

                            <div class="form-label">Email</div>                                </button>

                            <div class="input-icon mb-3">

                                <span class="input-icon-addon">                                <th>Email</th>

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail">                        </div>

                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                                        <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />                        <th>Level</th>

                                        <path d="M3 7l9 6l9 -6" />                        </form>

                                    </svg>

                                </span>                        <th>Aksi</th>

                                <input type="email" value="" class="form-control" name="email" id="email" placeholder="Email">

                            </div>                        </tr>

                        </div>                        <div class="table-responsive mt-3">

                    </div>

                    <div class="row">                    </thead>

                        <div class="col-12">                    <table class="table table-striped table-vcenter">

                            <div class="form-label">Level</div>

                            <select name="level" id="level" class="form-select">                        <tbody>

                                <option value="">Pilih Level</option>                            <thead>

                                <option value="Management">Management</option>

                                <option value="HRD">HRD</option>                                @foreach ($user as $d) <tr class="text-center">

                                <option value="Superadmin">Superadmin</option>

                            </select>                                <tr>

                        </div>                                    <th>No</th>

                    </div>

                    <div class="row mt-2">                                    <td>{{ $d->nik}}</td>

                        <div class="col-12">                                    <th>NIK</th>

                            <div class="form-label">Password</div>

                            <div class="input-icon mb-3">                                    <td>{{ $d->name}}</td>

                                <span class="input-icon-addon">                                    <th>Nama</th>

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-key">

                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />                                    <td>{{ $d->email}}</td>

                                        <path d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0z" />                                    <th>Email</th>

                                        <path d="M15 9h.01" />

                                    </svg>                                    <td>{{ $d->level}}</td>

                                </span>                                    <th>Level</th>

                                <input type="password" value="" class="form-control" name="password" id="password" placeholder="Password">

                            </div>                                    <td>

                        </div>                                    <th>Aksi</th>

                    </div>

                    <div class="row mt-3">                                    <div class="form-group">

                        <div class="col-12">                                </tr>

                            <div class="form-group">

                                <button class="btn btn-primary w-100">                                <a href="#" class="edit btn btn-info btn-sm" id_user="{{ $d->id }}">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">                            </thead>

                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>

                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/>                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">

                                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>                        <tbody>

                                        <path d="M14 4l0 4l-6 0l0 -4"/>

                                    </svg>                            <path stroke="none" d="M0 0h24v24H0z" fill="none" /> @forelse ($user as $item)

                                    Simpan

                                </button>                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />

                            </div>                            <tr class="text-center">

                        </div>

                    </div>                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />

                </form>                                <td>{{ $loop->iteration + $user->firstItem() - 1 }}</td>

            </div>

        </div>                                <path d="M16 5l3 3" />

    </div>                                <td>{{ $item->nik }}</td>

</div>

                                </svg>

<!-- Modal Edit -->                                <td>{{ $item->name }}</td>

<div class="modal modal-blur fade" id="modal-edituser" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">                                </a>

        <div class="modal-content">                                <td>{{ $item->email }}</td>

            <div class="modal-header">

                <h5 class="modal-title">Edit User</h5>                                <form action="{{ route('data.user.delete', $d) }}" method="POST">

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                                    <td>{{ $item->level }}</td>

            </div>

            <div class="modal-body" id="loadeduser">                                    @csrf <td>



            </div>                                        @method('DELETE') <div class="btn-list justify-content-center">

        </div>

    </div>                                            <a class="btn btn-danger btn-sm delete-confirm"> <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-user"

</div>

@endsection                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash"> data-id="{{ $item->id }}"



@push('myscript')                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" /> data-nik="{{ $item->nik }}"

<script>

    $(function() {                                                    <path d="M4 7l16 0" /> data-name="{{ $item->name }}"

        $('#btnTambahUser').click(function() {

            $('#modal-inputuser').modal("show");                                                    <path d="M10 11l0 6" /> data-email="{{ $item->email }}"

        });

                                                    <path d="M14 11l0 6" /> data-level="{{ $item->level }}"

        $('.edit').click(function() {

            var id = $(this).attr('id_user');                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /> data-update-url="{{ route('data.user.update', $item) }}"

            $.ajax({

                type: 'POST',                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /> data-delete-url="{{ route('data.user.delete', $item) }}">

                url: '/data/user/edit',

                cache: false,                                                    </svg> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-edit">

                data: {

                    _token: "{{ csrf_token() }}",                                            </a>

                    id: id                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                },

                success: function(respond) {                                </form>

                    $('#loadeduser').html(respond);                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />

                }

            });                </div>

            $('#modal-edituser').modal("show");                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />

        });

                </td>

        $(".delete-confirm").click(function(e) {                <path d="M16 5l3 3" />

            var form = $(this).closest('form');

            e.preventDefault();                </tr> </svg>

            Swal.fire({

                title: "Apakah Yakin?",                @endforeach </button>

                text: "Data User Akan Di Delete!",

                icon: "warning",                </tbody> <button type="button" class="btn btn-sm btn-outline-danger btn-delete-user" data-delete-url="{{ route('data.user.delete', $item) }}">

                showCancelButton: true,

                confirmButtonColor: "#3085d6",                    </table> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-trash">

                cancelButtonColor: "#d33",

                confirmButtonText: "Delete"            </div>

            }).then((result) => {            <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                if (result.isConfirmed) {

                    form.submit();        </div>

                }        <path d="M4 7l16 0" />

            });

        });    </div>

    <path d="M10 11l0 6" />

        $('#formUser').submit(function() {

            var nik = $('#nik').val();</div>

            var name = $('#name').val();<path d="M14 11l0 6" />

            var email = $('#email').val();

            var level = $('#level').val();</div>

            var password = $('#password').val();<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />

            if (nik == "") {

                Swal.fire({</div>

                    title: 'Warning!',<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />

                    text: 'NIK Harus Diisi',

                    icon: 'warning',</div> </svg>

                    confirmButtonText: 'Ok'

                }).then(() => {</div> </button>

                    $('#nik').focus();

                });</div>

                return false;

            } else if (name == "") {<!-- Modal Create --> </td>

                Swal.fire({

                    title: 'Warning!',<div class="modal modal-blur fade" id="modal-inputuser" tabindex="-1" role="dialog" aria-hidden="true">

                    text: 'Nama Harus Diisi',    </tr>

                    icon: 'warning',

                    confirmButtonText: 'Ok'    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"> @empty

                }).then(() => {

                    $('#name').focus();        <div class="modal-content">

                });            <tr>

                return false;

            } else if (email == "") {                <div class="modal-header">

                Swal.fire({                    <td colspan="6" class="text-center">Belum ada data user.</td>

                    title: 'Warning!',

                    text: 'Email Harus Diisi',                    <h5 class="modal-title">Tambah User</h5>

                    icon: 'warning',            </tr>

                    confirmButtonText: 'Ok'

                }).then(() => {            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> @endforelse

                    $('#email').focus();

                });        </div>

                return false;        </tbody>

            } else if (level == "") {

                Swal.fire({        <div class="modal-body">

                    title: 'Warning!',            </table>

                    text: 'Level Harus Dipilih',

                    icon: 'warning',            <form action="{{ url('/data/user/store') }}" method="POST" id="formUser">

                    confirmButtonText: 'Ok'        </div>

                }).then(() => {

                    $('#level').focus();        @csrf

                });

                return false;        <div class="row"> {{ $user->links('vendor.pagination.bootstrap-5') }}

            } else if (password == "") {

                Swal.fire({            <div class="col-12"> </div>

                    title: 'Warning!',

                    text: 'Password Harus Diisi',            <div class="form-label">NIK</div>

                    icon: 'warning',        </div>

                    confirmButtonText: 'Ok'

                }).then(() => {        <div class="input-icon mb-3"> </div>

                    $('#password').focus();

                });        <span class="input-icon-addon">

                return false;    </div>

            }

        });    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id">

    });</div>

</script>

@endpush<path stroke="none" d="M0 0h24v24H0z" fill="none" />

</div>

<path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />

<path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
<div class="modal modal-blur fade" id="modal-user-create" tabindex="-1" aria-hidden="true">

    <path d="M15 8l2 0" />
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

        <path d="M15 12l2 0" />
        <div class="modal-content">

            <path d="M7 16l10 0" />
            <div class="modal-header">

                </svg>
                <h5 class="modal-title">Tambah Data User</h5>

                </span> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                <input type="text" value="" class="form-control" name="nik" id="nik" placeholder="NIK">
            </div>

        </div>
        <div class="modal-body">

        </div> @if ($errors->any() && old('form_action') === 'create-user')

    </div>
    <div class="alert alert-danger">

        <div class="row">
            <ul class="mb-0">

                <div class="col-12"> @foreach ($errors->all() as $error)

                    <div class="form-label">Nama</div>
                    <li>{{ $error }}</li>

                    <div class="input-icon mb-3"> @endforeach

                        <span class="input-icon-addon">
            </ul>

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
        </div>

        <path stroke="none" d="M0 0h24v24H0z" fill="none" /> @endif

        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />

        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
        <form action="{{ url('/data/user/store') }}" method="POST" id="formUserCreate" enctype="multipart/form-data" class="row g-3">

            </svg> @csrf

            </span> <input type="hidden" name="form_action" value="create-user">

            <input type="text" value="" class="form-control" name="name" id="name" placeholder="Nama">
            <div class="col-12">

            </div> <label class="form-label">NIK</label>

    </div>
    <div class="input-icon">

    </div> <span class="input-icon-addon">

        <div class="row"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-id">

                <div class="col-12">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                    <div class="form-label">Email</div>
                    <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />

                    <div class="input-icon mb-3">
                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />

                        <span class="input-icon-addon">
                            <path d="M15 8l2 0" />

                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                                <path d="M15 12l2 0" />

                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 16l10 0" />

                                <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                            </svg>

                            <path d="M3 7l9 6l9 -6" />
                        </span>

            </svg> <input type="text" class="form-control" name="nik" id="nik" value="{{ old('nik') }}" placeholder="10101" autocomplete="off" required>

    </span>
</div>

<input type="email" value="" class="form-control" name="email" id="email" placeholder="Email"> </div>

</div>
<div class="col-12">

</div> <label class="form-label">Nama User</label>

</div>
<div class="input-icon mb-2">

    <div class="row"> <span class="input-icon-addon">

            <div class="col-12"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-user">

                    <label class="form-label">Level</label>
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                    <select name="level" id="level" class="form-select">
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />

                        <option value="">Pilih Level</option>
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />

                        <option value="Management">Management</option>
                </svg>

                <option value="Admin">Admin</option>
        </span>

        <option value="HRD">HRD</option> <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="John Doe" autocomplete="off" required>

        <option value="Superadmin">Superadmin</option>
    </div>

    </select>
    <div id="employeeList" class="dropdown-menu" style="display:none; margin-top: -12px"></div>

</div>
</div>

</div>
<div class="col-12">

    <div class="row mt-2"> <label class="form-label">Email</label>

        <div class="col-12">
            <div class="input-icon">

                <div class="form-label">Password</div> <span class="input-icon-addon">

                    <div class="input-icon mb-3"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-mail">

                            <span class="input-icon-addon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-lock">
                                    <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />

                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 7l9 6l9 -6" />

                                    <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" />
                                </svg>

                                <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                            </span>

                            <path d="M8 11v-4a4 4 0 1 1 8 0v4" /> <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="nama@perusahaan.com" required>

                        </svg> </div>

                </span>
            </div>

            <input type="password" value="" class="form-control" name="password" id="password" placeholder="Password">
            <div class="col-12">

            </div> <label class="form-label">Level</label>

        </div> <select name="level" id="level" class="form-select" required>

    </div>
    <option value="">Pilih Level</option>

    <div class="row mt-3">
        <option value="Management" @selected(old('level')=='Management' )>Management</option>

        <div class="col-12">
            <option value="HRD" @selected(old('level')=='HRD' )>HRD</option>

            <div class="form-group">
                <option value="Admin" @selected(old('level')=='Admin' )>Admin</option>

                <button class="btn btn-primary w-100">
                    <option value="Superadmin" @selected(old('level')=='Superadmin' )>Superadmin</option>

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus"> </select>

                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            </div>

            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
            <div class="col-12">

                <path d="M16 19h6" /> <label class="form-label">Password</label>

                <path d="M19 16v6" />
                <div class="input-group input-group-flat">

                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4" /> <input type="password" class="form-control" name="password" id="password" placeholder="Minimal 6 karakter" required>

                    </svg> <span class="input-group-text">

                        Simpan <a href="#" class="input-group-link" id="togglePassword">Show password</a>

                        </button> </span>

                </div>
            </div>

        </div>
    </div>

</div>
<div class="col-12 d-flex justify-content-end">

    </form> <button class="btn btn-primary" type="submit">Simpan</button>

</div>
</div>

</div>
</form>

</div>
</div>

</div>
</div>

</div>

<!-- Modal Edit --></div>

<div class="modal modal-blur fade" id="modal-edituser" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">@php

        <div class="modal-content"> $userUpdateErrors = $errors->hasBag('userUpdate') ? $errors->userUpdate : null;

            <div class="modal-header"> $shouldOpenCreateUser = $errors->any() && old('form_action') === 'create-user';

                <h5 class="modal-title">Edit User</h5> $hasUserUpdateErrors = $userUpdateErrors && $userUpdateErrors->any();

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> $editUserId = session('edit_user_id');

            </div> $oldUserValues = [

            <div class="modal-body" id="loadeduser"> 'level' => old('level'),

                ];

            </div>@endphp

        </div>

    </div>
    <div id="user-page-flags" class="d-none"

        </div> data-create="{{ $shouldOpenCreateUser ? '1' : '0' }}"

        @endsection data-edit="{{ $hasUserUpdateErrors ? '1' : '0' }}"

        data-edit-id="{{ $editUserId ?? '' }}"

        @push('myscript') data-old="{{ htmlspecialchars(json_encode($oldUserValues), ENT_QUOTES, 'UTF-8') }}">

        <script>
            < /div>

            $(function() {

                        $('#btnTambahUser').click(function() {
                            < div class = "modal modal-blur fade"
                            id = "modal-user-edit"
                            tabindex = "-1"
                            aria - hidden = "true" >

                                $('#modal-inputuser').modal("show"); < div class = "modal-dialog modal-lg modal-dialog-centered"
                            role = "document" >

                        }); < div class = "modal-content" >

                        <
                        div class = "modal-header" >

                        $('.edit').click(function() {
                            < h5 class = "modal-title" > Edit Data User < /h5>

                            var id = $(this).attr('id_user'); < button type = "button"
                            class = "btn-close"
                            data - bs - dismiss = "modal"
                            aria - label = "Close" > < /button>

                            $.ajax({
                                < /div>

                                type: 'POST',
                                < div class = "modal-body" >

                                url: '/data/user/edit',
                                @if($userUpdateErrors && $userUpdateErrors - > any())

                                cache: false,
                                < div class = "alert alert-danger" >

                                data: {
                                    < ul class = "mb-0" >

                                    _token: "{{ csrf_token() }}",
                                    @foreach($userUpdateErrors - > all() as $error)

                                    id: id < li > {
                                        {
                                            $error
                                        }
                                    } < /li>

                                },
                                @endforeach

                                success: function(respond) {
                                    < /ul>

                                    $('#loadeduser').html(respond); < /div>

                                }
                                @endif

                            });

                            $('#modal-edituser').modal("show"); < form method = "POST"
                            class = "row g-3"
                            id = "userEditForm" >

                        });
                        @csrf

                        @method('PUT')

                        $(".delete-confirm").click(function(e) {
                            < input type = "hidden"
                            name = "form_action"
                            value = "edit-user" >

                                var form = $(this).closest('form'); < div class = "col-md-6" >

                            e.preventDefault(); < label class = "form-label" > NIK < /label>

                            Swal.fire({
                                < input type = "text"
                                name = "nik_display"
                                class = "form-control"
                                disabled >

                                title: "Apakah Yakin?",
                                < /div>

                                text: "Data User Akan Di Delete!",
                                < div class = "col-md-6" >

                                icon: "warning",
                                < label class = "form-label" > Nama < /label>

                                showCancelButton: true,
                                < input type = "text"
                                name = "name_display"
                                class = "form-control"
                                disabled >

                                confirmButtonColor: "#3085d6",
                                < /div>

                                cancelButtonColor: "#d33",
                                < div class = "col-md-6" >

                                confirmButtonText: "Delete" < label class = "form-label" > Email < /label>

                            }).then((result) => {
                                < input type = "text"
                                name = "email_display"
                                class = "form-control"
                                disabled >

                                    if (result.isConfirmed) {
                                        < /div>

                                        form.submit(); < div class = "col-md-6" >

                                    } < label class = "form-label" > Level < /label>

                            }); < select name = "level"
                            class = "form-select"
                            required >

                        }); < option value = "" > Pilih Level < /option>

                            <
                            option value = "Management" > Management < /option>

                        $('#formUser').submit(function() {
                                    < option value = "HRD" > HRD < /option>

                                    var nik = $('#nik').val(); < option value = "Admin" > Admin < /option>

                                    var name = $('#name').val(); < option value = "Superadmin" > Superadmin < /option>

                                    var email = $('#email').val(); < /select>

                                    var level = $('#level').val(); < /div>

                                    var password = $('#password').val(); < div class = "col-md-6" >

                                    <
                                    label class = "form-label" > Password Baru < /label>

                                    if (nik == "") {
                                        < input type = "password"
                                        name = "new_password"
                                        class = "form-control"
                                        placeholder = "Kosongkan jika tidak diganti" >

                                            Swal.fire({
                                                < /div>

                                                title: 'Warning!',
                                                < div class = "col-md-6" >

                                                text: 'NIK Harus Diisi',
                                                < label class = "form-label" > Konfirmasi Password Baru < /label>

                                                icon: 'warning',
                                                < input type = "password"
                                                name = "new_password_confirmation"
                                                class = "form-control"
                                                placeholder = "Ulangi password baru" >

                                                confirmButtonText: 'Ok' < /div>

                                            }).then(() => {
                                                < div class = "col-12 d-flex justify-content-between" >

                                                $('#nik').focus(); < button type = "button"
                                                class = "btn btn-outline-danger"
                                                id = "userDeleteButton" > Hapus < /button>

                                            }); < button type = "submit"
                                        class = "btn btn-primary" > Update < /button>

                                        return false; < /div>

                                    } else if (name == "") {
                                        < /form>

                                        Swal.fire({
                                            < /div>

                                            title: 'Warning!',
                                            < /div>

                                            text: 'Nama Harus Diisi',
                                            < /div>

                                            icon: 'warning',
                                            < /div>

                                            confirmButtonText: 'Ok'
                                            @endsection

                                        }).then(() => {

                                            $('#name').focus();
                                            @push('myscript')

                                        }); < script >

                                            return false;
                                        document.addEventListener('DOMContentLoaded', () => {

                                        } else if (email == "") {
                                            const flagsEl = document.getElementById('user-page-flags');

                                            Swal.fire({
                                                const createModalShouldOpen = flagsEl?.dataset.create === '1';

                                                title: 'Warning!',
                                                const editModalErrors = flagsEl?.dataset.edit === '1';

                                                text: 'Email Harus Diisi',
                                                const editUserIdRaw = flagsEl?.dataset.editId ?? '';

                                                icon: 'warning',
                                                const editUserId = editUserIdRaw ? Number(editUserIdRaw) : null;

                                                confirmButtonText: 'Ok'
                                                const oldUserValues = flagsEl?.dataset.old ? JSON.parse(flagsEl.dataset.old) : {};

                                            }).then(() => {

                                                $('#email').focus();
                                                const createModalEl = document.getElementById('modal-user-create');

                                            });
                                            const editModalEl = document.getElementById('modal-user-edit');

                                            return false;
                                            const editForm = document.getElementById('userEditForm');

                                        } else if (level == "") {
                                            const deleteButton = document.getElementById('userDeleteButton');

                                            Swal.fire({

                                                title: 'Warning!',
                                                if (createModalShouldOpen && createModalEl) {

                                                    text: 'Level Harus Dipilih',
                                                    bootstrap.Modal.getOrCreateInstance(createModalEl).show();

                                                    icon: 'warning',
                                                }

                                                confirmButtonText: 'Ok'

                                            }).then(() => {
                                                const nikDisplay = editForm?.querySelector('input[name="nik_display"]');

                                                $('#level').focus();
                                                const nameDisplay = editForm?.querySelector('input[name="name_display"]');

                                            });
                                            const emailDisplay = editForm?.querySelector('input[name="email_display"]');

                                            return false;
                                            const levelSelect = editForm?.querySelector('select[name="level"]');

                                        } else if (password == "") {
                                            const newPasswordInput = editForm?.querySelector('input[name="new_password"]');

                                            Swal.fire({
                                                    const confirmPasswordInput = editForm?.querySelector('input[name="new_password_confirmation"]');

                                                    title: 'Warning!',

                                                    text: 'Password Harus Diisi',
                                                    const openEditModal = (button, overrideValues = null) => {

                                                        icon: 'warning',
                                                        if (!button || !editModalEl || !editForm || !deleteButton) {

                                                            confirmButtonText: 'Ok'
                                                            return;

                                                        }).then(() => {}

                                                        $('#password').focus();

                                                    });
                                                const dataset = button.dataset;

                                                return false; editForm.action = dataset.updateUrl;

                                            }

                                        });
                                        const values = {

                                        });
                                    nik: dataset.nik ?? '',
        </script> name: dataset.name ?? '',

        @endpush email: dataset.email ?? '',

        level: dataset.level ?? '',
        };

        if (overrideValues) {
        Object.keys(overrideValues).forEach((key) => {
        if (Object.prototype.hasOwnProperty.call(values, key)) {
        values[key] = overrideValues[key] ?? values[key];
        }
        });
        }

        if (nikDisplay) nikDisplay.value = values.nik;
        if (nameDisplay) nameDisplay.value = values.name;
        if (emailDisplay) emailDisplay.value = values.email;
        if (levelSelect) levelSelect.value = values.level ?? '';
        if (newPasswordInput) newPasswordInput.value = '';
        if (confirmPasswordInput) confirmPasswordInput.value = '';

        deleteButton.onclick = () => {
        if (!dataset.deleteUrl) {
        return;
        }

        Swal.fire({
        title: 'Apakah yakin?',
        text: 'User akan dihapus secara permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        }).then((result) => {
        if (result.isConfirmed) {
        const deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = dataset.deleteUrl;
        deleteForm.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(deleteForm);
        deleteForm.submit();
        }
        });
        };

        bootstrap.Modal.getOrCreateInstance(editModalEl).show();
        };

        document.querySelectorAll('.btn-edit-user').forEach((button) => {
        button.addEventListener('click', () => openEditModal(button));
        });

        document.querySelectorAll('.btn-delete-user').forEach((button) => {
        button.addEventListener('click', (event) => {
        event.preventDefault();
        const deleteUrl = button.dataset.deleteUrl;
        if (!deleteUrl) {
        return;
        }

        Swal.fire({
        title: 'Apakah yakin?',
        text: 'User akan dihapus secara permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        }).then((result) => {
        if (result.isConfirmed) {
        const deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = deleteUrl;
        deleteForm.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(deleteForm);
        deleteForm.submit();
        }
        });
        });
        });

        if (editModalErrors && editUserId) {
        const targetButton = document.querySelector(`.btn-edit-user[data-id="${editUserId}"]`);
        if (targetButton) {
        openEditModal(targetButton, {
        level: oldUserValues.level,
        });
        }
        }

        const passwordToggle = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        if (passwordToggle && passwordField) {
        passwordToggle.addEventListener('click', (event) => {
        event.preventDefault();
        if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordToggle.textContent = 'Hide password';
        } else {
        passwordField.type = 'password';
        passwordToggle.textContent = 'Show password';
        }
        });
        }

        const nikInput = document.getElementById('nik');
        const nameInput = document.getElementById('nama_lengkap');
        const emailInput = document.getElementById('email');
        const employeeDropdown = document.getElementById('employeeList');

        const hideDropdown = () => {
        if (employeeDropdown) {
        employeeDropdown.style.display = 'none';
        employeeDropdown.innerHTML = '';
        }
        };

        if (nikInput) {
        nikInput.addEventListener('blur', () => {
        const nikValue = nikInput.value.trim();
        if (!nikValue) {
        return;
        }

        fetch(`/data/user/getEmployeeByNik?nik=${encodeURIComponent(nikValue)}`)
        .then((response) => response.json())
        .then((data) => {
        if (data) {
        if (nameInput) nameInput.value = data.nama_lengkap ?? '';
        if (emailInput) emailInput.value = data.email ?? '';
        }
        })
        .catch(() => {});
        });
        }

        if (nameInput && employeeDropdown) {
        nameInput.addEventListener('input', () => {
        const searchTerm = nameInput.value.trim();
        if (searchTerm.length < 2) {
            hideDropdown();
            return;
            }

            fetch(`/data/user/getEmployeeNameUser?nama_lengkap=${encodeURIComponent(searchTerm)}`)
            .then((response)=> response.json())
            .then((data) => {
            employeeDropdown.innerHTML = '';
            if (Array.isArray(data) && data.length > 0) {
            data.forEach((employee) => {
            const item = document.createElement('a');
            item.className = 'dropdown-item';
            item.href = '#';
            item.dataset.nik = employee.nik ?? '';
            item.dataset.email = employee.email ?? '';
            item.textContent = employee.nama_lengkap ?? '';
            item.addEventListener('click', (event) => {
            event.preventDefault();
            if (nikInput) nikInput.value = item.dataset.nik ?? '';
            if (nameInput) nameInput.value = item.textContent ?? '';
            if (emailInput) emailInput.value = item.dataset.email ?? '';
            hideDropdown();
            });
            employeeDropdown.appendChild(item);
            });
            employeeDropdown.style.display = 'block';
            } else {
            hideDropdown();
            }
            })
            .catch(() => hideDropdown());
            });
            }

            document.addEventListener('click', (event) => {
            if (employeeDropdown && !employeeDropdown.contains(event.target) && event.target !== nameInput) {
            hideDropdown();
            }
            });
            });
            </script>
            @endpush