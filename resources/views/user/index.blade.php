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
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">

                    {{-- Card Header / Toolbar --}}
                    <div class="card-header d-flex flex-column flex-md-row gap-2 align-items-md-center">
                        <div>
                            <h3 class="card-title mb-1">Daftar User</h3>
                            <div class="text-muted small">
                                Kelola akses user aplikasi (level & perusahaan).
                            </div>
                        </div>

                        <div class="ms-md-auto d-flex gap-2 align-items-center flex-wrap">
                            {{-- Search sederhana --}}
                            <form class="d-flex" method="GET" action="{{ url()->current() }}">
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20"
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="10" cy="10" r="7"/>
                                            <line x1="21" y1="21" x2="15" y2="15"/>
                                        </svg>
                                    </span>
                                    <input type="text" name="q" class="form-control" placeholder="Cari nama / NIK / email"
                                           value="{{ request('q') }}">
                                </div>
                            </form>

                            <button type="button" class="btn btn-primary" id="btnTambahUser">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="icon icon-tabler icons-tabler-outline icon-tabler-plus me-1">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" />
                                </svg>
                                Tambah User
                            </button>
                        </div>
                    </div>

                    {{-- Tabel --}}
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Level</th>
                                        <th>Companies</th>
                                        <th style="width: 10%;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $user->firstItem() - 1 }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $d->nik }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $d->name }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $d->email }}</div>
                                            </td>
                                            <td>
                                                @if ($d->level == 'Superadmin')
                                                    <span class="badge bg-red-lt">{{ $d->level }}</span>
                                                @elseif ($d->level == 'Admin')
                                                    <span class="badge bg-blue-lt">{{ $d->level }}</span>
                                                @elseif ($d->level == 'HRD')
                                                    <span class="badge bg-green-lt">{{ $d->level }}</span>
                                                @else
                                                    <span class="badge bg-yellow-lt">{{ $d->level }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($d->companies->count() > 0)
                                                    @foreach($d->companies as $company)
                                                        <span class="badge bg-azure-lt me-1 mb-1">{{ $company->short_name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted small">No companies</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-list justify-content-center">
                                                    <button type="button"
                                                            class="btn btn-icon btn-outline-info btn-sm edit"
                                                            title="Edit"
                                                            id_user="{{ $d->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                             stroke-width="2" stroke-linecap="round"
                                                             stroke-linejoin="round"
                                                             class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                            <path d="M16 5l3 3" />
                                                        </svg>
                                                    </button>

                                                    <form action="/data/user/{{ $d->id }}/delete"
                                                          method="POST"
                                                          style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                                class="btn btn-icon btn-outline-danger btn-sm delete-confirm"
                                                                title="Hapus">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                 stroke-width="2" stroke-linecap="round"
                                                                 stroke-linejoin="round"
                                                                 class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
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
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Belum ada data user.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <div class="text-muted small">
                            Menampilkan
                            <span class="fw-semibold">{{ $user->firstItem() ?? 0 }}</span>
                            -
                            <span class="fw-semibold">{{ $user->lastItem() ?? 0 }}</span>
                            dari
                            <span class="fw-semibold">{{ $user->total() }}</span>
                            user
                        </div>
                        <div>
                            {{ $user->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>

                </div>{{-- /card --}}
            </div>
        </div>
    </div>
</div>

{{-- Modal Create User --}}
<div class="modal modal-blur fade" id="modal-inputuser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title">Tambah User</h5>
                    <div class="text-muted small">
                        Buat akun user baru untuk mengakses aplikasi.
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="/data/user/store" method="POST" id="formUser">
                    @csrf

                    {{-- Section: Data User --}}
                    <div class="mb-3">
                        <h3 class="card-title mb-0 fs-5">Data User</h3>
                        <div class="text-muted small mb-2">
                            Informasi identitas user.
                        </div>
                        <hr class="mt-1 mb-3">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">NIK <span class="text-danger">*</span></label>
                                <div class="input-icon mb-1">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                            <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                            <path d="M15 8l2 0" />
                                            <path d="M15 12l2 0" />
                                            <path d="M7 16l10 0" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="nik" id="nik"
                                           placeholder="NIK">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Nama User <span class="text-danger">*</span></label>
                                <div class="input-icon mb-1">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="name" id="name"
                                           placeholder="Nama">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="input-icon mb-1">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                                            <path d="M3 7l9 6l9 -6" />
                                        </svg>
                                    </span>
                                    <input type="email" value="" class="form-control" name="email" id="email"
                                           placeholder="email@domain.com">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Akses --}}
                    <div class="mt-4 mb-3">
                        <h3 class="card-title mb-0 fs-5">Akses</h3>
                        <div class="text-muted small mb-2">
                            Tentukan level akses user.
                        </div>
                        <hr class="mt-1 mb-3">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Level <span class="text-danger">*</span></label>
                                <select name="level" id="level" class="form-select">
                                    <option value="">Pilih Level</option>
                                    <option value="Management">Management</option>
                                    <option value="HRD">HRD</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Superadmin">Superadmin</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Password --}}
                    <div class="mt-4 mb-3">
                        <h3 class="card-title mb-0 fs-5">Password Awal</h3>
                        <div class="text-muted small mb-2">
                            Password untuk login pertama kali.
                        </div>
                        <hr class="mt-1 mb-3">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" value="" class="form-control" name="password"
                                       id="password" placeholder="Password">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary w-100">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit User --}}
<div class="modal modal-blur fade" id="modal-edituser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title">Edit User</h5>
                    <div class="text-muted small">
                        Perbarui level, password, dan perusahaan user.
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeduser">
                {{-- Partial form edit user akan di-load via Ajax --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
$(function() {
    // Buka modal tambah user
    $('#btnTambahUser').on('click', function() {
        $('#modal-inputuser').modal('show');
    });

    // Buka modal edit user
    $('.edit').on('click', function() {
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

                // Jalankan init jika partial menyediakan
                if (typeof window.__initEditUser === 'function') {
                    window.__initEditUser();
                }
            }
        });

        $('#modal-edituser').modal('show');
    });

    // Konfirmasi hapus
    $(document).on('click', '.delete-confirm', function(e) {
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

    // Validasi form create
    $('#formUser').on('submit', function() {
        var nik      = $('#nik').val();
        var name     = $('#name').val();
        var email    = $('#email').val();
        var level    = $('#level').val();
        var password = $('#password').val();

        if (nik === "") {
            Swal.fire({
                title: 'Warning!',
                text: 'NIK Harus Diisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then(() => { $('#nik').focus(); });
            return false;
        } else if (name === "") {
            Swal.fire({
                title: 'Warning!',
                text: 'Nama Harus Diisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then(() => { $('#name').focus(); });
            return false;
        } else if (email === "") {
            Swal.fire({
                title: 'Warning!',
                text: 'Email Harus Diisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then(() => { $('#email').focus(); });
            return false;
        } else if (level === "") {
            Swal.fire({
                title: 'Warning!',
                text: 'Level Harus Dipilih',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then(() => { $('#level').focus(); });
            return false;
        } else if (password === "") {
            Swal.fire({
                title: 'Warning!',
                text: 'Password Harus Diisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then(() => { $('#password').focus(); });
            return false;
        }
    });
});
</script>
@endpush
