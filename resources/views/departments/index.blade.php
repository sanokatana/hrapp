@extends('layouts.admin.tabler')@extends('layouts.admin.tabler')



@section('content')@section('content')

<div class="page-header d-print-none"><div class="page-header d-print-none">

    <div class="container-xl">    <div class="container-xl">

        <div class="row g-2 align-items-center">        <div class="row g-2 align-items-center">

            <div class="col">            <div class="col">

                <div class="page-pretitle">Organisasi</div>                <div class="page-pretitle">Organisasi</div>

                <h2 class="page-title">Department</h2>                <h2 class="page-title">Department</h2>

            </div>            </div>

        </div>            <div class="col-auto ms-auto d-print-none">

    </div>                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-department-create">

</div>                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">

                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />

@if(session('success'))                        <path d="M12 5l0 14" />

<script>                        <path d="M5 12l14 0" />

    document.addEventListener('DOMContentLoaded', function() {                    </svg>

        Swal.fire({                    Add Department

            title: 'Berhasil!',                </a>

            text: "{{ session('success') }}",            </div>

            icon: 'success',        </div>

            confirmButtonText: 'Ok'    </div>

        });</div>

    });

</script><div class="page-body">

@elseif(session('danger'))    <div class="container-xl">

<script>        <div class="row">

    document.addEventListener('DOMContentLoaded', function() {            <div class="col-12">

        Swal.fire({                @if (session('success'))

            title: 'Danger!',                <div class="alert alert-success">{{ session('success') }}</div>

            text: "{{ session('danger') }}",                @endif

            icon: 'error',

            confirmButtonText: 'Ok'                <div class="card">

        });                    <div class="card-header">Daftar Department</div>

    });                    <div class="table-responsive">

</script>                        <table class="table table-striped">

@endif                            <thead>

                                <tr>

<div class="page-body">                                    <th width="5%">#</th>

    <div class="container-xl">                                    <th>Perusahaan</th>

        <div class="row">                                    <th>Cabang</th>

            <div class="col-12">                                    <th>Kode</th>

                <div class="card">                                    <th>Nama</th>

                    <div class="card-body">                                    <th width="12%">Aksi</th>

                        <div class="row">                                </tr>

                            <div class="col-12">                            </thead>

                                <a href="#" class="btn btn-primary" id="btnTambahDepartment">                            <tbody>

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">                                @forelse ($departments as $department)

                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />                                <tr>

                                        <path d="M12 5l0 14" />                                    <td>{{ $loop->iteration }}</td>

                                        <path d="M5 12l14 0" />                                    <td>{{ $department->company?->short_name }}</td>

                                    </svg>                                    <td>{{ $department->cabang?->nama ?? '-' }}</td>

                                    Add Department                                    <td>{{ $department->kode }}</td>

                                </a>                                    <td>{{ $department->nama }}</td>

                            </div>                                    <td>

                        </div>                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-department"

                        <div class="row mt-4">                                            data-id="{{ $department->id }}"

                            <div class="col-12 table-responsive">                                            data-company-id="{{ $department->company_id }}"

                                <table class="table table-vcenter card-table table-striped">                                            data-cabang-id="{{ $department->cabang_id }}"

                                    <thead>                                            data-kode="{{ $department->kode }}"

                                        <tr>                                            data-nama="{{ $department->nama }}"

                                            <th>No</th>                                            data-update-url="{{ route('departments.update', $department) }}"

                                            <th>Perusahaan</th>                                            data-delete-url="{{ route('departments.destroy', $department) }}">

                                            <th>Cabang</th>                                            Edit

                                            <th>Kode</th>                                        </button>

                                            <th>Nama</th>                                    </td>

                                            <th>Aksi</th>                                </tr>

                                        </tr>                                @empty

                                    </thead>                                <tr>

                                    <tbody>                                    <td colspan="6" class="text-center">Belum ada data department.</td>

                                        @foreach ($departments as $d)                                </tr>

                                        <tr>                                @endforelse

                                            <td>{{ $loop->iteration }}</td>                            </tbody>

                                            <td>{{ $d->company?->short_name }}</td>                        </table>

                                            <td>{{ $d->cabang?->nama ?? '-' }}</td>                    </div>

                                            <td>{{ $d->kode }}</td>                </div>

                                            <td>{{ $d->nama }}</td>            </div>

                                            <td>        </div>

                                                <div class="form-group">    </div>

                                                    <a href="#" class="edit btn btn-info btn-sm" id_department="{{ $d->id }}"></div>

                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">

                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" /><div class="modal modal-blur fade" id="modal-department-create" tabindex="-1" aria-hidden="true">

                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />        <div class="modal-content">

                                                            <path d="M16 5l3 3" />            <div class="modal-header">

                                                        </svg>                <h5 class="modal-title">Tambah Department</h5>

                                                    </a>                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                                    <form action="{{ route('departments.destroy', $d) }}" method="POST" style="display:inline;">            </div>

                                                        @csrf            <div class="modal-body">

                                                        @method('DELETE')                @if ($errors->any() && old('form_action') === 'create-department')

                                                        <a class="btn btn-danger btn-sm delete-confirm">                <div class="alert alert-danger">

                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">                    <ul class="mb-0">

                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />                        @foreach ($errors->all() as $error)

                                                                <path d="M4 7l16 0" />                        <li>{{ $error }}</li>

                                                                <path d="M10 11l0 6" />                        @endforeach

                                                                <path d="M14 11l0 6" />                    </ul>

                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />                </div>

                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />                @endif

                                                            </svg>

                                                        </a>                <form action="{{ route('departments.store') }}" method="POST" class="row g-3">

                                                    </form>                    @csrf

                                                </div>                    <input type="hidden" name="form_action" value="create-department">

                                            </td>                    <div class="col-md-6">

                                        </tr>                        <label class="form-label">Perusahaan</label>

                                        @endforeach                        <select name="company_id" class="form-select" required>

                                    </tbody>                            <option value="">Pilih Perusahaan</option>

                                </table>                            @foreach ($companies as $company)

                            </div>                            <option value="{{ $company->id }}" @selected(old('company_id')==$company->id)>{{ $company->short_name }}</option>

                        </div>                            @endforeach

                    </div>                        </select>

                </div>                    </div>

            </div>                    <div class="col-md-6">

        </div>                        <label class="form-label">Cabang</label>

    </div>                        <select name="cabang_id" class="form-select">

</div>                            <option value="">Semua Cabang</option>

                            @foreach ($branches as $branch)

<!-- Modal Create -->                            <option value="{{ $branch->id }}" @selected(old('cabang_id')==$branch->id)>{{ $branch->nama }}</option>

<div class="modal modal-blur fade" id="modal-inputdepartment" tabindex="-1" role="dialog" aria-hidden="true">                            @endforeach

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">                        </select>

        <div class="modal-content">                    </div>

            <div class="modal-header">                    <div class="col-md-4">

                <h5 class="modal-title">Tambah Department</h5>                        <label class="form-label">Kode</label>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                        <input type="text" name="kode" class="form-control" placeholder="DEPT" value="{{ old('kode') }}" required>

            </div>                    </div>

            <div class="modal-body">                    <div class="col-md-8">

                <form action="{{ route('departments.store') }}" method="POST" id="formDepartment">                        <label class="form-label">Nama Department</label>

                    @csrf                        <input type="text" name="nama" class="form-control" placeholder="Nama Department" value="{{ old('nama') }}" required>

                    <div class="row">                    </div>

                        <div class="col-12">                    <div class="col-12 d-flex justify-content-end">

                            <div class="form-label">Perusahaan</div>                        <button type="submit" class="btn btn-primary">Simpan</button>

                            <div class="input-icon mb-3">                    </div>

                                <select name="company_id" class="form-select" id="company_id" required>                </form>

                                    <option value="">Pilih Perusahaan</option>            </div>

                                    @foreach ($companies as $company)        </div>

                                    <option value="{{ $company->id }}">{{ $company->short_name }}</option>    </div>

                                    @endforeach</div>

                                </select>

                            </div><div class="modal modal-blur fade" id="modal-department-edit" tabindex="-1" aria-hidden="true">

                        </div>    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

                    </div>        <div class="modal-content">

                    <div class="row">            <div class="modal-header">

                        <div class="col-12">                <h5 class="modal-title">Edit Department</h5>

                            <div class="form-label">Cabang</div>                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            <div class="input-icon mb-3">            </div>

                                <select name="cabang_id" class="form-select" id="cabang_id">            <div class="modal-body">

                                    <option value="">Semua Cabang</option>                @php

                                    @foreach ($branches as $branch)                $departmentUpdateErrors = $errors->hasBag('departmentUpdate') ? $errors->departmentUpdate : null;

                                    <option value="{{ $branch->id }}">{{ $branch->nama }}</option>                @endphp

                                    @endforeach

                                </select>                @if ($departmentUpdateErrors && $departmentUpdateErrors->any())

                            </div>                <div class="alert alert-danger">

                        </div>                    <ul class="mb-0">

                    </div>                        @foreach ($departmentUpdateErrors->all() as $error)

                    <div class="row">                        <li>{{ $error }}</li>

                        <div class="col-12">                        @endforeach

                            <div class="form-label">Kode Department</div>                    </ul>

                            <div class="input-icon mb-3">                </div>

                                <input type="text" class="form-control" name="kode" id="kode" placeholder="DEPT" required>                @endif

                            </div>

                        </div>                <form method="POST" class="row g-3" id="departmentEditForm">

                    </div>                    @csrf

                    <div class="row">                    @method('PUT')

                        <div class="col-12">                    <input type="hidden" name="form_action" value="edit-department">

                            <div class="form-label">Nama Department</div>                    <div class="col-md-6">

                            <div class="input-icon mb-3">                        <label class="form-label">Perusahaan</label>

                                <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Department" required>                        <select name="company_id" class="form-select" required>

                            </div>                            <option value="">Pilih Perusahaan</option>

                        </div>                            @foreach ($companies as $company)

                    </div>                            <option value="{{ $company->id }}">{{ $company->short_name }}</option>

                    <div class="row mt-3">                            @endforeach

                        <div class="col-12">                        </select>

                            <div class="form-group">                    </div>

                                <button class="btn btn-primary w-100">                    <div class="col-md-6">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">                        <label class="form-label">Cabang</label>

                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>                        <select name="cabang_id" class="form-select">

                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/>                            <option value="">Semua Cabang</option>

                                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>                            @foreach ($branches as $branch)

                                        <path d="M14 4l0 4l-6 0l0 -4"/>                            <option value="{{ $branch->id }}">{{ $branch->nama }}</option>

                                    </svg>                            @endforeach

                                    Simpan                        </select>

                                </button>                    </div>

                            </div>                    <div class="col-md-4">

                        </div>                        <label class="form-label">Kode</label>

                    </div>                        <input type="text" name="kode" class="form-control" required>

                </form>                    </div>

            </div>                    <div class="col-md-8">

        </div>                        <label class="form-label">Nama Department</label>

    </div>                        <input type="text" name="nama" class="form-control" required>

</div>                    </div>

                    <div class="col-12 d-flex justify-content-between">

<!-- Modal Edit -->                        <button type="button" class="btn btn-outline-danger" id="departmentDeleteButton">Hapus</button>

<div class="modal modal-blur fade" id="modal-editdepartment" tabindex="-1" role="dialog" aria-hidden="true">                        <button type="submit" class="btn btn-primary">Update</button>

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">                    </div>

        <div class="modal-content">                </form>

            <div class="modal-header">            </div>

                <h5 class="modal-title">Edit Department</h5>        </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>    </div>

            </div></div>

            <div class="modal-body" id="loadeddepartment">

@php

            </div>$shouldOpenCreateDepartment = $errors->any() && old('form_action') === 'create-department';

        </div>$hasDepartmentUpdateErrors = $errors->hasBag('departmentUpdate') && $errors->departmentUpdate->any();

    </div>$editDepartmentId = session('edit_department_id');

</div>$oldDepartmentValues = [

@endsection'company_id' => old('company_id'),

'cabang_id' => old('cabang_id'),

@push('myscript')'kode' => old('kode'),

<script>'nama' => old('nama'),

    $(function() {];

        $('#btnTambahDepartment').click(function() {@endphp

            $('#modal-inputdepartment').modal("show");

        });<div id="department-page-flags" class="d-none"

    data-create="{{ $shouldOpenCreateDepartment ? '1' : '0' }}"

        $('.edit').click(function() {    data-edit="{{ $hasDepartmentUpdateErrors ? '1' : '0' }}"

            var id = $(this).attr('id_department');    data-edit-id="{{ $editDepartmentId ?? '' }}"

            $.ajax({    data-old="{{ htmlspecialchars(json_encode($oldDepartmentValues), ENT_QUOTES, 'UTF-8') }}">

                type: 'POST',</div>

                url: '/departments/edit',@endsection

                cache: false,

                data: {@push('myscript')

                    _token: "{{ csrf_token() }}",<script>

                    id: id    document.addEventListener('DOMContentLoaded', () => {

                },        const flagsEl = document.getElementById('department-page-flags');

                success: function(respond) {        const createModalShouldOpen = flagsEl?.dataset.create === '1';

                    $('#loadeddepartment').html(respond);        const editModalErrors = flagsEl?.dataset.edit === '1';

                }        const editDepartmentIdRaw = flagsEl?.dataset.editId ?? '';

            });        const editDepartmentId = editDepartmentIdRaw ? Number(editDepartmentIdRaw) : null;

            $('#modal-editdepartment').modal("show");        const oldDepartmentValues = flagsEl?.dataset.old ? JSON.parse(flagsEl.dataset.old) : {};

        });

        const createModalEl = document.getElementById('modal-department-create');

        $(".delete-confirm").click(function(e) {        if (createModalShouldOpen && createModalEl) {

            var form = $(this).closest('form');            bootstrap.Modal.getOrCreateInstance(createModalEl).show();

            e.preventDefault();        }

            Swal.fire({

                title: "Apakah Yakin?",        const editModalEl = document.getElementById('modal-department-edit');

                text: "Data Department Akan Di Delete!",        const editForm = document.getElementById('departmentEditForm');

                icon: "warning",        const deleteButton = document.getElementById('departmentDeleteButton');

                showCancelButton: true,

                confirmButtonColor: "#3085d6",        if (!editModalEl || !editForm || !deleteButton) {

                cancelButtonColor: "#d33",            return;

                confirmButtonText: "Delete"        }

            }).then((result) => {

                if (result.isConfirmed) {        const companySelect = editForm.querySelector('select[name="company_id"]');

                    form.submit();        const cabangSelect = editForm.querySelector('select[name="cabang_id"]');

                }        const kodeInput = editForm.querySelector('input[name="kode"]');

            });        const namaInput = editForm.querySelector('input[name="nama"]');

        });

        const openEditModal = (button, overrideValues = null) => {

        $('#formDepartment').submit(function() {            if (!button) {

            var company_id = $('#company_id').val();                return;

            var kode = $('#kode').val();            }

            var nama = $('#nama').val();

            if (company_id == "") {            const dataset = button.dataset;

                Swal.fire({            editForm.action = dataset.updateUrl;

                    title: 'Warning!',

                    text: 'Perusahaan Harus Dipilih',            const values = overrideValues ?? {

                    icon: 'warning',                company_id: dataset.companyId ?? '',

                    confirmButtonText: 'Ok'                cabang_id: dataset.cabangId ?? '',

                }).then(() => {                kode: dataset.kode ?? '',

                    $('#company_id').focus();                nama: dataset.nama ?? '',

                });            };

                return false;

            } else if (kode == "") {            companySelect.value = values.company_id ?? '';

                Swal.fire({            cabangSelect.value = values.cabang_id ?? '';

                    title: 'Warning!',            kodeInput.value = values.kode ?? '';

                    text: 'Kode Department Harus Diisi',            namaInput.value = values.nama ?? '';

                    icon: 'warning',

                    confirmButtonText: 'Ok'            deleteButton.onclick = () => {

                }).then(() => {                if (confirm('Hapus department ini?')) {

                    $('#kode').focus();                    const deleteForm = document.createElement('form');

                });                    deleteForm.method = 'POST';

                return false;                    deleteForm.action = dataset.deleteUrl;

            } else if (nama == "") {                    deleteForm.innerHTML = '@csrf<input type="hidden" name="_method" value="DELETE">';

                Swal.fire({                    document.body.appendChild(deleteForm);

                    title: 'Warning!',                    deleteForm.submit();

                    text: 'Nama Department Harus Diisi',                }

                    icon: 'warning',            };

                    confirmButtonText: 'Ok'

                }).then(() => {            bootstrap.Modal.getOrCreateInstance(editModalEl).show();

                    $('#nama').focus();        };

                });

                return false;        document.querySelectorAll('.btn-edit-department').forEach((button) => {

            }            button.addEventListener('click', () => openEditModal(button));

        });        });

    });

</script>        if (editModalErrors && editDepartmentId) {

@endpush            const targetButton = document.querySelector(`.btn-edit-department[data-id="${editDepartmentId}"]`);

            openEditModal(targetButton, oldDepartmentValues);
        }
    });
</script>
@endpush
*** End Patch

</div>
<div class="col-md-2">

    <div class="col-md-4"> <label class="form-label">Kode</label>

        <label class="form-label">Kode</label> <input type="text" name="kode" class="form-control" placeholder="DEPT" required>

        <input type="text" name="kode" class="form-control" placeholder="DEPT" value="{{ old('kode') }}" required>
    </div>

</div>
<div class="col-md-4">

    <div class="col-md-8"> <label class="form-label">Nama Department</label>

        <label class="form-label">Nama Department</label> <input type="text" name="nama" class="form-control" placeholder="Nama Department" required>

        <input type="text" name="nama" class="form-control" placeholder="Nama Department" value="{{ old('nama') }}" required>
    </div>

</div>
<div class="col-12 d-flex justify-content-end">

    <div class="col-12 d-flex justify-content-end"> <button type="submit" class="btn btn-primary">Simpan</button>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>

</div>
</form>

</form>
</div>

</div>
</div>

</div>

</div> @if (!empty($editing))

</div>
<div class="card mb-3">

    <div class="card-header">Edit Department</div>

    @if (!empty($editing)) <div class="card-body">

        <div class="card mb-3">
            <form action="{{ route('departments.update', $editing) }}" method="POST" class="row g-3">

                <div class="card-header">Edit Department</div> @csrf

                <div class="card-body"> @method('PUT')

                    <form action="{{ route('departments.update', $editing) }}" method="POST" class="row g-3">
                        <div class="col-md-3">

                            @csrf <label class="form-label">Perusahaan</label>

                            @method('PUT') <select name="company_id" class="form-select" required>

                                <div class="col-md-6">
                                    <option value="">Pilih Perusahaan</option>

                                    <label class="form-label">Perusahaan</label> @foreach ($companies as $company)

                                    <select name="company_id" class="form-select" required>
                                        <option value="{{ $company->id }}" @selected(old('company_id', $editing->company_id) == $company->id)>{{ $company->short_name }}</option>

                                        <option value="">Pilih Perusahaan</option> @endforeach

                                        @foreach ($companies as $company)
                                    </select>

                                    <option value="{{ $company->id }}" @selected(old('company_id', $editing->company_id) == $company->id)>{{ $company->short_name }}</option>
                                </div>

                                @endforeach <div class="col-md-3">

                            </select> <label class="form-label">Cabang</label>

                        </div> <select name="cabang_id" class="form-select">

                            <div class="col-md-6">
                                <option value="">Semua Cabang</option>

                                <label class="form-label">Cabang</label> @foreach ($branches as $branch)

                                <select name="cabang_id" class="form-select">
                                    <option value="{{ $branch->id }}" @selected(old('cabang_id', $editing->cabang_id) == $branch->id)>{{ $branch->nama }}</option>

                                    <option value="">Semua Cabang</option> @endforeach

                                    @foreach ($branches as $branch)
                                </select>

                                <option value="{{ $branch->id }}" @selected(old('cabang_id', $editing->cabang_id) == $branch->id)>{{ $branch->nama }}</option>
                            </div>

                            @endforeach <div class="col-md-2">

                        </select> <label class="form-label">Kode</label>

                </div> <input type="text" name="kode" class="form-control" value="{{ old('kode', $editing->kode) }}" required>

                <div class="col-md-4"> </div>

                <label class="form-label">Kode</label>
                <div class="col-md-4">

                    <input type="text" name="kode" class="form-control" value="{{ old('kode', $editing->kode) }}" required> <label class="form-label">Nama Department</label>

                </div> <input type="text" name="nama" class="form-control" value="{{ old('nama', $editing->nama) }}" required>

                <div class="col-md-8"> </div>

                <label class="form-label">Nama Department</label>
                <div class="col-12 d-flex gap-2 justify-content-end">

                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $editing->nama) }}" required> <button type="submit" class="btn btn-primary">Update</button>

                </div>
        </div>

        <div class="col-12 d-flex gap-2 justify-content-end">
            </form>

            <button type="submit" class="btn btn-primary">Update</button>
            <form action="{{ route('departments.destroy', $editing) }}" method="POST" class="mt-3">

        </div> @csrf

        </form> @method('DELETE')

        <form action="{{ route('departments.destroy', $editing) }}" method="POST" class="mt-3"> <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Hapus department ini?')">Hapus Department</button>

            @csrf </form>

        @method('DELETE')
    </div>

    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Hapus department ini?')">Hapus Department</button>
</div>

</form> @endif

</div>

</div>
<div class="card">

    @endif <div class="card-header">Daftar Department</div>

    <div class="table-responsive">

        <div class="card">
            <table class="table table-striped">

                <div class="card-header">Daftar Department</div>
                <thead>

                    <div class="table-responsive">
                        <tr>

                            <table class="table table-striped">
                                <th width="5%">#</th>

                                <thead>
                                    <th>Perusahaan</th>

                                    <tr>
                                        <th>Cabang</th>

                                        <th width="5%">#</th>
                                        <th>Kode</th>

                                        <th>Perusahaan</th>
                                        <th>Nama</th>

                                        <th>Cabang</th>
                                        <th width="10%">Aksi</th>

                                        <th>Kode</th>
                                    </tr>

                                    <th>Nama</th>
                                </thead>

                                <th width="10%">Aksi</th>
                                <tbody>

                        </tr> @forelse ($departments as $department)

                </thead>
                <tr>

                    <tbody>
                        <td>{{ $loop->iteration }}</td>

                        @forelse ($departments as $department) <td>{{ $department->company?->short_name }}</td>

                        <tr>
                            <td>{{ $department->cabang?->nama ?? '-' }}</td>

                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $department->kode }}</td>

                            <td>{{ $department->company?->short_name }}</td>
                            <td>{{ $department->nama }}</td>

                            <td>{{ $department->cabang?->nama ?? '-' }}</td>
                            <td>

                            <td>{{ $department->kode }}</td> <a href="{{ route('departments.index', ['edit' => $department->id]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>

                            <td>{{ $department->nama }}</td>
                            </td>

                            <td>
                        </tr>

                        <a href="{{ route('departments.index', ['edit' => $department->id]) }}" class="btn btn-sm btn-outline-secondary">Edit</a> @empty

                        </td>
                        <tr>

                        </tr>
                        <td colspan="6" class="text-center">Belum ada data department.</td>

                        @empty
                </tr>

                <tr> @endforelse

                    <td colspan="6" class="text-center">Belum ada data department.</td>
                    </tbody>

                </tr>
            </table>

            @endforelse
        </div>

        </tbody>
    </div>

    </table>

</div>
</div>

</div>
</div>

</div>

</div>
</div>

</div> @endsection

</div> });

</div> });

@endsection });

</script>

@push('myscript')@endpush

@if ($errors->any() && old('form_action') === 'create-department')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('modal-department-create'));
        modal.show();
    });
</script>
@endif
@endpush