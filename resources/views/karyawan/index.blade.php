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

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ title: 'Berhasil!', text: "{{ session('success') }}", icon: 'success', confirmButtonText: 'Ok' });
    });
</script>
@elseif(session('danger'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ title: 'Danger!', text: "{{ session('danger') }}", icon: 'error', confirmButtonText: 'Ok' });
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
                                <a href="#" class="btn btn-primary" id="btnTambahKaryawan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Karyawan
                                </a>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Perusahaan</th>
                                            <th>Cabang</th>
                                            <th>Department</th>
                                            <th>Jabatan</th>
                                            <th>Email</th>
                                            <th>No. HP</th>
                                            <th>Masuk</th>
                                            <th>Resign</th>
                                            <th>Status</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($employees as $employee)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $employee->nik }}</td>
                                            <td>{{ $employee->nama_lengkap }}</td>
                                            <td>{{ $employee->company?->short_name }}</td>
                                            <td>{{ $employee->cabang?->nama ?? '-' }}</td>
                                            <td>{{ $employee->department?->nama ?? '-' }}</td>
                                            <td>{{ $employee->jabatan?->nama ?? '-' }}</td>
                                            <td>{{ $employee->email ?? '-' }}</td>
                                            <td>{{ $employee->no_hp ?? '-' }}</td>
                                            <td>{{ optional($employee->tgl_masuk)->format('d/m/Y') }}</td>
                                            <td>{{ optional($employee->tgl_resign)->format('d/m/Y') }}</td>
                                            <td>{{ $employee->status_kar }}</td>
                                            <td>
                                                <div class="form-group">
                                                    <a href="#" class="edit btn btn-info btn-sm" id_karyawan="{{ $employee->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                            <path d="M16 5l3 3" />
                                                        </svg>
                                                    </a>
                                                    <form action="/karyawan/{{ $employee->id }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a class="btn btn-danger btn-sm delete-confirm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M4 7l16 0" />
                                                                <path d="M10 11l0 6" />
                                                                <path d="M14 11l0 6" />
                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                            </svg>
                                                        </a>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="13" class="text-center">Belum ada data karyawan.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div> <!-- /card-body -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    <div class="modal modal-blur fade" id="modal-inputkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/karyawan" method="POST" id="formKaryawan">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-label">NIK</div>
                                <input type="text" name="nik" id="nik" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <div class="form-label">Nama Lengkap</div>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <div class="form-label">Email</div>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <div class="form-label">No. HP</div>
                                <input type="text" name="no_hp" id="no_hp" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-3">
                                <div class="form-label">Tanggal Masuk</div>
                                <input type="date" name="tgl_masuk" id="tgl_masuk" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <div class="form-label">Perusahaan</div>
                                <select name="company_id" id="company_id" class="form-select" required>
                                    <option value="">Pilih Perusahaan</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->short_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="form-label">Cabang</div>
                                <select name="cabang_id" id="cabang_id" class="form-select" disabled>
                                    <option value="">Pilih perusahaan dulu</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="form-label">Department</div>
                                <select name="department_id" id="department_id" class="form-select">
                                    <option value="">Pilih Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-3">
                                <div class="form-label">Jabatan</div>
                                <select name="jabatan_id" id="jabatan_id" class="form-select">
                                    <option value="">Pilih Jabatan</option>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}">{{ $position->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="form-label">Status</div>
                                <select name="status_kar" id="status_kar" class="form-select" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Non-Aktif">Non-Aktif</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class="form-label">Password</div>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button class="btn btn-primary w-100">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div> <!-- /modal-body -->
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal modal-blur fade" id="modal-editkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadedkaryawan"></div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
<script>
$(function () {
    $('#btnTambahKaryawan').on('click', function () {
        $('#modal-inputkaryawan').modal('show');
    });

    // Create modal: company -> cabang
    $('#company_id').on('change', function () {
        const companyId = $(this).val();
        const $cabang   = $('#cabang_id');

        $cabang.prop('disabled', true).empty()
               .append('<option value="">Memuat cabang...</option>');

        if (!companyId) {
            $cabang.empty().append('<option value="">Pilih perusahaan dulu</option>');
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
    });

    // Open edit modal and load partial
    $('.edit').on('click', function () {
        const id = $(this).attr('id_karyawan');

        $.ajax({
            type: 'POST',
            url: '/karyawan/edit',
            cache: false,
            data: { _token: "{{ csrf_token() }}", id },
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
            if (result.isConfirmed) form.submit();
        });
    });

    // Create validation
    $('#formKaryawan').on('submit', function () {
        const nik          = $('#nik').val();
        const nama_lengkap = $('#nama_lengkap').val();
        const company_id   = $('#company_id').val();
        const status_kar   = $('#status_kar').val();
        const password     = $('#password').val();

        if (!nik) {
            Swal.fire({ title: 'Warning!', text: 'NIK Harus Diisi', icon: 'warning', confirmButtonText: 'Ok' })
                .then(() => $('#nik').focus());
            return false;
        } else if (!nama_lengkap) {
            Swal.fire({ title: 'Warning!', text: 'Nama Lengkap Harus Diisi', icon: 'warning', confirmButtonText: 'Ok' })
                .then(() => $('#nama_lengkap').focus());
            return false;
        } else if (!company_id) {
            Swal.fire({ title: 'Warning!', text: 'Perusahaan Harus Dipilih', icon: 'warning', confirmButtonText: 'Ok' })
                .then(() => $('#company_id').focus());
            return false;
        } else if (!status_kar) {
            Swal.fire({ title: 'Warning!', text: 'Status Harus Dipilih', icon: 'warning', confirmButtonText: 'Ok' })
                .then(() => $('#status_kar').focus());
            return false;
        } else if (!password) {
            Swal.fire({ title: 'Warning!', text: 'Password Harus Diisi', icon: 'warning', confirmButtonText: 'Ok' })
                .then(() => $('#password').focus());
            return false;
        }
    });
});
</script>
@endpush
