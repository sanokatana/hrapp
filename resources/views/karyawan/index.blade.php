@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Manajemen Karyawan</div>
                <h2 class="page-title">Data Karyawan</h2>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        });
    </script>
@elseif (session('danger'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                            <h3 class="card-title mb-1">Daftar Karyawan</h3>
                            <div class="text-muted small">
                                Kelola data karyawan aktif dan non-aktif.
                            </div>
                        </div>
                        <div class="ms-md-auto d-flex gap-2 align-items-center flex-wrap">
                            {{-- Optional simple search (GET) --}}
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
                                    <input type="text" name="q" class="form-control" placeholder="Cari nama / NIK"
                                           value="{{ request('q') }}">
                                </div>
                            </form>

                            <button type="button" class="btn btn-primary" id="btnTambahKaryawan">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="icon icon-tabler icons-tabler-outline icon-tabler-plus me-1">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 5l0 14"/>
                                    <path d="M5 12l14 0"/>
                                </svg>
                                Tambah Karyawan
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped mb-0">
                                <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Perusahaan / Cabang</th>
                                    <th>Department</th>
                                    <th>Jabatan</th>
                                    <th>No. HP</th>
                                    <th>Masuk</th>
                                    <th>Resign</th>
                                    <th>Status</th>
                                    <th width="8%" class="text-center">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($employees as $employee)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $employee->nik }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $employee->nama_lengkap }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">
                                                {{ $employee->company?->short_name ?? '-' }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ $employee->cabang?->nama ?? 'Cabang tidak diatur' }}
                                            </div>
                                        </td>
                                        <td>{{ $employee->department?->nama ?? '-' }}</td>
                                        <td>{{ $employee->jabatan?->nama ?? '-' }}</td>
                                        <td>{{ $employee->no_hp ?? '-' }}</td>
                                        <td>{{ optional($employee->tgl_masuk)->format('d/m/Y') }}</td>
                                        <td>{{ optional($employee->tgl_resign)->format('d/m/Y') ?: '-' }}</td>
                                        <td>
                                            @if ($employee->status_kar === 'Aktif')
                                                <span class="badge bg-green-lt">Aktif</span>
                                            @elseif ($employee->status_kar === 'Non-Aktif')
                                                <span class="badge bg-red-lt">Non-Aktif</span>
                                            @else
                                                <span class="badge bg-secondary-lt">{{ $employee->status_kar }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-list justify-content-center">
                                                <button type="button"
                                                        class="btn btn-icon btn-outline-info btn-sm edit"
                                                        title="Edit"
                                                        id_karyawan="{{ $employee->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round"
                                                         class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z"/>
                                                        <path d="M16 5l3 3"/>
                                                    </svg>
                                                </button>

                                                <form action="/karyawan/{{ $employee->id }}" method="POST"
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
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M4 7l16 0"/>
                                                            <path d="M10 11l0 6"/>
                                                            <path d="M14 11l0 6"/>
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
                                            Belum ada data karyawan.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div> {{-- /card --}}
            </div>
        </div>
    </div>

    {{-- Modal Create --}}
    <div class="modal modal-blur fade" id="modal-inputkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title">Tambah Karyawan</h5>
                        <div class="text-muted small">
                            Lengkapi informasi karyawan di bawah ini.
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="/karyawan" method="POST" id="formKaryawan">
                        @csrf

                        {{-- Section: Data Pribadi --}}
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="card-title mb-0 fs-5">Data Pribadi</h3>
                            </div>
                            <div class="text-muted small mb-2">
                                Informasi dasar karyawan.
                            </div>
                            <hr class="mt-1 mb-3">

                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">NIK</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            Auto
                                        </span>
                                        <input type="text" name="nik" id="nik" class="form-control"
                                               readonly placeholder="Pilih perusahaan">
                                    </div>
                                    <div class="form-hint">Otomatis tergenerasi dari perusahaan.</div>
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">No. HP</label>
                                    <input type="text" name="no_hp" id="no_hp" class="form-control"
                                           placeholder="08xxxxxxxxxx">
                                </div>
                            </div>

                            <div class="row g-3 mt-0">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Masuk</label>
                                    <input type="date" name="tgl_masuk" id="tgl_masuk" class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- Section: Kantor & Jabatan --}}
                        <div class="mt-4 mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="card-title mb-0 fs-5">Kantor & Jabatan</h3>
                            </div>
                            <div class="text-muted small mb-2">
                                Pengaturan perusahaan, cabang, dan posisi karyawan.
                            </div>
                            <hr class="mt-1 mb-3">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Perusahaan <span class="text-danger">*</span></label>
                                    <select name="company_id" id="company_id" class="form-select" required>
                                        <option value="">Pilih Perusahaan</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">
                                                {{ $company->short_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Cabang</label>
                                    <select name="cabang_id" id="cabang_id" class="form-select" disabled>
                                        <option value="">Pilih perusahaan dulu</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Department</label>
                                    <select name="department_id" id="department_id" class="form-select">
                                        <option value="">Pilih Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mt-0">
                                <div class="col-md-4">
                                    <label class="form-label">Jabatan</label>
                                    <select name="jabatan_id" id="jabatan_id" class="form-select">
                                        <option value="">Pilih Jabatan</option>
                                        @foreach ($positions as $position)
                                            <option value="{{ $position->id }}">{{ $position->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status_kar" id="status_kar" class="form-select" required>
                                        <option value="">Pilih Status</option>
                                        <option value="Aktif">Aktif</option>
                                        <option value="Non-Aktif">Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Akun Login --}}
                        <div class="mt-4 mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="card-title mb-0 fs-5">Akun Login</h3>
                            </div>
                            <div class="text-muted small mb-2">
                                Password awal yang digunakan untuk login aplikasi.
                            </div>
                            <hr class="mt-1 mb-3">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control"
                                           required>
                                    <div class="form-hint">
                                        Password dapat diganti oleh karyawan setelah login.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary w-100">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div> {{-- /modal-body --}}
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal modal-blur fade" id="modal-editkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title">Edit Karyawan</h5>
                        <div class="text-muted small">
                            Perbarui informasi karyawan.
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadedkaryawan"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function () {
        // Open create modal
        $('#btnTambahKaryawan').on('click', function () {
            $('#modal-inputkaryawan').modal('show');
        });

        // Company -> Cabang + NIK generator
        $('#company_id').on('change', function () {
            const companyId = $(this).val();
            const $cabang   = $('#cabang_id');

            $cabang.prop('disabled', true)
                .empty()
                .append('<option value="">Memuat cabang...</option>');

            if (!companyId) {
                $cabang.empty().append('<option value="">Pilih perusahaan dulu</option>');
                $('#nik').val('').attr('placeholder', 'Pilih perusahaan');
                return;
            }

            $.getJSON(`/companies/${companyId}/branches`, function (rows) {
                $cabang.empty().append('<option value="">Semua Cabang</option>');
                rows.forEach(r => $cabang.append(new Option(r.nama, r.id)));
                $cabang.prop('disabled', false);

                if (rows.length === 1) {
                    $cabang.val(rows[0].id);
                }
            });

            $.getJSON(`/companies/${companyId}/next-nik`, function (payload) {
                $('#nik').val(payload.nik).attr('placeholder', payload.nik);
            });
        });

        // Open edit modal and load partial
        $('.edit').on('click', function () {
            const id = $(this).attr('id_karyawan');

            $.ajax({
                type: 'POST',
                url: '/karyawan/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function (respond) {
                    $('#loadedkaryawan').html(respond);

                    // After partial is injected, run its initializer if present
                    if (typeof window.__initEditKaryawan === 'function') {
                        window.__initEditKaryawan();
                    }
                },
            });

            $('#modal-editkaryawan').modal('show');
        });

        // Delete confirmation
        $(document).on('click', '.delete-confirm', function (e) {
            const form = $(this).closest('form');
            e.preventDefault();

            Swal.fire({
                title: "Apakah Yakin?",
                text: "Data Karyawan Akan Di Delete!",
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

        // Create validation
        $('#formKaryawan').on('submit', function () {
            const nama_lengkap = $('#nama_lengkap').val();
            const company_id   = $('#company_id').val();
            const status_kar   = $('#status_kar').val();
            const password     = $('#password').val();

            if (!company_id) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Perusahaan Harus Dipilih',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => $('#company_id').focus());
                return false;
            } else if (!$('#nik').val()) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'NIK belum tergenerasi. Pilih perusahaan untuk melanjutkan.',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => $('#company_id').focus());
                return false;
            } else if (!nama_lengkap) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Nama Lengkap Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => $('#nama_lengkap').focus());
                return false;
            } else if (!status_kar) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Status Harus Dipilih',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => $('#status_kar').focus());
                return false;
            } else if (!password) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Password Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => $('#password').focus());
                return false;
            }
        });
    });
</script>
@endpush
