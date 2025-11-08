@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Manajemen Karyawan</div>
                <h2 class="page-title">Data Karyawan</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-karyawan-create">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Add Karyawan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="modal modal-blur fade" id="modal-karyawan-create" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Karyawan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if ($errors->any() && old('form_action') === 'create-karyawan')
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <form action="{{ route('karyawan.store') }}" method="POST" class="row g-3">
                                    @csrf
                                    <input type="hidden" name="form_action" value="create-karyawan">
                                    <div class="col-md-3">
                                        <label class="form-label">NIK</label>
                                        <input type="text" name="nik" value="{{ old('nik') }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Email Perusahaan</label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="nama@perusahaan.com">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">No. HP</label>
                                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-control" placeholder="08xxxxxxxxxx">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Masuk</label>
                                        <input type="date" name="tgl_masuk" value="{{ old('tgl_masuk') }}" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Perusahaan</label>
                                        <select name="company_id" class="form-select" required>
                                            <option value="">Pilih Perusahaan</option>
                                            @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->short_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Cabang</label>
                                        <select name="cabang_id" class="form-select">
                                            <option value="">Semua Cabang</option>
                                            @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}" @selected(old('cabang_id') == $branch->id)>{{ $branch->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Department</label>
                                        <select name="department_id" class="form-select">
                                            <option value="">Pilih Department</option>
                                            @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>{{ $department->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Jabatan</label>
                                        <select name="jabatan_id" class="form-select">
                                            <option value="">Pilih Jabatan</option>
                                            @foreach ($positions as $position)
                                            <option value="{{ $position->id }}" @selected(old('jabatan_id') == $position->id)>{{ $position->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Site / Lokasi</label>
                                        <select name="lokasi_id" class="form-select">
                                            <option value="">Pilih Lokasi</option>
                                            @foreach ($locations as $location)
                                            <option value="{{ $location->id }}" @selected(old('lokasi_id') == $location->id)>{{ $location->nama_kantor }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Status Karyawan</label>
                                        <select name="status_kar" class="form-select" required>
                                            <option value="">Pilih Status</option>
                                            <option value="Aktif" @selected(old('status_kar') == 'Aktif')>Aktif</option>
                                            <option value="Non-Aktif" @selected(old('status_kar') == 'Non-Aktif')>Non-Aktif</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Password Awal</label>
                                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Daftar Karyawan</div>
                    <div class="table-responsive">
                        <table class="table table-striped">
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
                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-karyawan"
                                            data-id="{{ $employee->id }}"
                                            data-nik="{{ $employee->nik }}"
                                            data-nama="{{ $employee->nama_lengkap }}"
                                            data-email="{{ $employee->email }}"
                                            data-nohp="{{ $employee->no_hp }}"
                                            data-tgl-masuk="{{ optional($employee->tgl_masuk)->format('Y-m-d') }}"
                                            data-tgl-resign="{{ optional($employee->tgl_resign)->format('Y-m-d') }}"
                                            data-company-id="{{ $employee->company_id }}"
                                            data-cabang-id="{{ $employee->cabang_id }}"
                                            data-department-id="{{ $employee->department_id }}"
                                            data-jabatan-id="{{ $employee->jabatan_id }}"
                                            data-lokasi-id="{{ $employee->lokasi_id }}"
                                            data-status="{{ $employee->status_kar }}"
                                            data-update-url="{{ route('karyawan.update', $employee) }}"
                                            data-delete-url="{{ route('karyawan.destroy', $employee) }}">
                                            Edit
                                        </button>
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
            </div>
        </div>
    </div>
</div>

@php
    $shouldOpenCreateKaryawan = $errors->any() && old('form_action') === 'create-karyawan';
    $hasKaryawanUpdateErrors = $errors->hasBag('karyawanUpdate') && $errors->karyawanUpdate->any();
    $editKaryawanId = session('edit_karyawan_id');
    $oldKaryawanValues = [
        'nama_lengkap' => old('nama_lengkap'),
        'email' => old('email'),
        'no_hp' => old('no_hp'),
        'tgl_masuk' => old('tgl_masuk'),
        'tgl_resign' => old('tgl_resign'),
        'company_id' => old('company_id'),
        'cabang_id' => old('cabang_id'),
        'department_id' => old('department_id'),
        'jabatan_id' => old('jabatan_id'),
        'lokasi_id' => old('lokasi_id'),
        'status_kar' => old('status_kar'),
    ];
@endphp

<div id="karyawan-page-flags" class="d-none"
    data-create="{{ $shouldOpenCreateKaryawan ? '1' : '0' }}"
    data-edit="{{ $hasKaryawanUpdateErrors ? '1' : '0' }}"
    data-edit-id="{{ $editKaryawanId ?? '' }}"
    data-old="{{ htmlspecialchars(json_encode($oldKaryawanValues), ENT_QUOTES, 'UTF-8') }}">
</div>

<div class="modal modal-blur fade" id="modal-karyawan-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $karyawanUpdateErrors = $errors->hasBag('karyawanUpdate') ? $errors->karyawanUpdate : null;
                @endphp

                @if ($karyawanUpdateErrors && $karyawanUpdateErrors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($karyawanUpdateErrors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" class="row g-3" id="karyawanEditForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_action" value="edit-karyawan">
                    <div class="col-md-3">
                        <label class="form-label">NIK</label>
                        <input type="text" class="form-control" name="_nik_display" disabled>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email Perusahaan</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Masuk</label>
                        <input type="date" name="tgl_masuk" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Resign</label>
                        <input type="date" name="tgl_resign" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Perusahaan</label>
                        <select name="company_id" class="form-select" required>
                            <option value="">Pilih Perusahaan</option>
                            @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->short_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cabang</label>
                        <select name="cabang_id" class="form-select">
                            <option value="">Semua Cabang</option>
                            @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select">
                            <option value="">Pilih Department</option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jabatan</label>
                        <select name="jabatan_id" class="form-select">
                            <option value="">Pilih Jabatan</option>
                            @foreach ($positions as $position)
                            <option value="{{ $position->id }}">{{ $position->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Site / Lokasi</label>
                        <select name="lokasi_id" class="form-select">
                            <option value="">Pilih Lokasi</option>
                            @foreach ($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->nama_kantor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status Karyawan</label>
                        <select name="status_kar" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Isi untuk mengganti password">
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-danger" id="karyawanDeleteButton">Hapus</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const flagsEl = document.getElementById('karyawan-page-flags');
        const createModalShouldOpen = flagsEl?.dataset.create === '1';
        const editModalErrors = flagsEl?.dataset.edit === '1';
        const editKaryawanIdRaw = flagsEl?.dataset.editId ?? '';
        const editKaryawanId = editKaryawanIdRaw ? Number(editKaryawanIdRaw) : null;
        const oldKaryawanValues = flagsEl?.dataset.old ? JSON.parse(flagsEl.dataset.old) : {};

        const createModalEl = document.getElementById('modal-karyawan-create');
        if (createModalShouldOpen && createModalEl) {
            bootstrap.Modal.getOrCreateInstance(createModalEl).show();
        }

        const editModalEl = document.getElementById('modal-karyawan-edit');
        const editForm = document.getElementById('karyawanEditForm');
        const deleteButton = document.getElementById('karyawanDeleteButton');

        if (!editModalEl || !editForm || !deleteButton) {
            return;
        }

        const nikDisplay = editForm.querySelector('input[name="_nik_display"]');
        const namaInput = editForm.querySelector('input[name="nama_lengkap"]');
        const emailInput = editForm.querySelector('input[name="email"]');
        const hpInput = editForm.querySelector('input[name="no_hp"]');
        const masukInput = editForm.querySelector('input[name="tgl_masuk"]');
        const resignInput = editForm.querySelector('input[name="tgl_resign"]');
        const companySelect = editForm.querySelector('select[name="company_id"]');
        const cabangSelect = editForm.querySelector('select[name="cabang_id"]');
        const departmentSelect = editForm.querySelector('select[name="department_id"]');
        const jabatanSelect = editForm.querySelector('select[name="jabatan_id"]');
        const lokasiSelect = editForm.querySelector('select[name="lokasi_id"]');
        const statusSelect = editForm.querySelector('select[name="status_kar"]');
        const passwordInput = editForm.querySelector('input[name="password"]');

        const openEditModal = (button, overrideValues = null) => {
            if (!button) {
                return;
            }

            const dataset = button.dataset;
            editForm.action = dataset.updateUrl;

            const values = {
                nik: dataset.nik ?? '',
                nama_lengkap: dataset.nama ?? '',
                email: dataset.email ?? '',
                no_hp: dataset.nohp ?? '',
                tgl_masuk: dataset.tglMasuk ?? '',
                tgl_resign: dataset.tglResign ?? '',
                company_id: dataset.companyId ?? '',
                cabang_id: dataset.cabangId ?? '',
                department_id: dataset.departmentId ?? '',
                jabatan_id: dataset.jabatanId ?? '',
                lokasi_id: dataset.lokasiId ?? '',
                status_kar: dataset.status ?? '',
            };

            if (overrideValues) {
                Object.keys(overrideValues).forEach((key) => {
                    if (Object.prototype.hasOwnProperty.call(values, key)) {
                        values[key] = overrideValues[key] ?? '';
                    }
                });
            }

            nikDisplay.value = values.nik ?? '';
            namaInput.value = values.nama_lengkap ?? '';
            emailInput.value = values.email ?? '';
            hpInput.value = values.no_hp ?? '';
            masukInput.value = values.tgl_masuk ?? '';
            resignInput.value = values.tgl_resign ?? '';
            companySelect.value = values.company_id ?? '';
            cabangSelect.value = values.cabang_id ?? '';
            departmentSelect.value = values.department_id ?? '';
            jabatanSelect.value = values.jabatan_id ?? '';
            lokasiSelect.value = values.lokasi_id ?? '';
            statusSelect.value = values.status_kar ?? '';
            passwordInput.value = '';

            deleteButton.onclick = () => {
                if (confirm('Hapus karyawan ini?')) {
                    const deleteForm = document.createElement('form');
                    deleteForm.method = 'POST';
                    deleteForm.action = dataset.deleteUrl;
                    deleteForm.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
                    document.body.appendChild(deleteForm);
                    deleteForm.submit();
                }
            };

            bootstrap.Modal.getOrCreateInstance(editModalEl).show();
        };

        document.querySelectorAll('.btn-edit-karyawan').forEach((button) => {
            button.addEventListener('click', () => openEditModal(button));
        });

        if (editModalErrors && editKaryawanId) {
            const targetButton = document.querySelector(`.btn-edit-karyawan[data-id="${editKaryawanId}"]`);
            openEditModal(targetButton, oldKaryawanValues);
        }
    });
</script>
@endpush
