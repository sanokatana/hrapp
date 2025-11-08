@extends('layouts.admin.tabler')@extends('layouts.admin.tabler')



@section('content')@section('content')

<div class="page-header d-print-none">
    <div class="page-header d-print-none">

        <div class="container-xl">
            <div class="container-xl">

                <div class="row g-2 align-items-center">
                    <div class="row g-2 align-items-center">

                        <div class="col">
                            <div class="col">

                                <div class="page-pretitle">Organisasi</div>
                                <div class="page-pretitle">Organisasi</div>

                                <h2 class="page-title">Cabang</h2>
                                <h2 class="page-title">Cabang</h2>

                            </div>
                        </div>

                    </div>
                    <div class="col-auto ms-auto d-print-none">

                    </div> <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-cabang-create">

                </div> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">

                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                    @if(session('success'))
                    <path d="M12 5l0 14" />

                    <script>
                        < path d = "M5 12l14 0" / >

                            document.addEventListener('DOMContentLoaded', function() {
                                < /svg>

                                Swal.fire({
                                    Add Cabang

                                    title: 'Berhasil!',
                                    < /a>

                                    text: "{{ session('success') }}",
                                    < /div>

                                    icon: 'success',
                                    < /div>

                                    confirmButtonText: 'Ok' < /div>

                                }); < /div>

                            });
                    </script>
                    <div class="page-body">

                        @elseif(session('danger')) <div class="container-xl">

                            <script>
                                < div class = "row" >

                                document.addEventListener('DOMContentLoaded', function() {
                                    < div class = "col-12" >

                                    Swal.fire({
                                        @if(session('success'))

                                        title: 'Danger!',
                                        < div class = "alert alert-success" > {
                                            {
                                                session('success')
                                            }
                                        } < /div>

                                        text: "{{ session('danger') }}",
                                        @endif

                                        icon: 'error',

                                        confirmButtonText: 'Ok' < div class = "card" >

                                    }); < div class = "card-header" > Daftar Cabang < /div>

                                }); < div class = "table-responsive" >
                            </script>
                            <table class="table table-striped">

                                @endif <thead>

                                    <tr>

                                        <div class="page-body">
                                            <th width="5%">#</th>

                                            <div class="container-xl">
                                                <th>Perusahaan</th>

                                                <div class="row">
                                                    <th>Kode</th>

                                                    <div class="col-12">
                                                        <th>Nama</th>

                                                        <div class="card">
                                                            <th>Alamat</th>

                                                            <div class="card-body">
                                                                <th>Kota</th>

                                                                <div class="row">
                                                                    <th width="12%">Aksi</th>

                                                                    <div class="col-12">
                                    </tr>

                                    <a href="#" class="btn btn-primary" id="btnTambahCabang">
                                </thead>

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                    <tbody>

                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" /> @forelse ($branches as $branch)

                                        <path d="M12 5l0 14" />
                                        <tr>

                                            <path d="M5 12l14 0" />
                                            <td>{{ $loop->iteration }}</td>

                                </svg>
                                <td>{{ $branch->company?->short_name }}</td>

                                Add Cabang <td>{{ $branch->kode }}</td>

                                </a>
                                <td>{{ $branch->nama }}</td>

                        </div>
                        <td>{{ $branch->alamat ?? '-' }}</td>

                    </div>
                    <td>{{ $branch->kota ?? '-' }}</td>

                    <div class="row mt-4">
                        <td>

                            <div class="col-12 table-responsive"> <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-cabang"

                                    <table class="table table-vcenter card-table table-striped"> data-id="{{ $branch->id }}"

                                    <thead> data-company-id="{{ $branch->company_id }}"

                                        <tr> data-kode="{{ $branch->kode }}"

                                            <th>No</th> data-nama="{{ $branch->nama }}"

                                            <th>Perusahaan</th> data-alamat="{{ $branch->alamat }}"

                                            <th>Kode</th> data-kota="{{ $branch->kota }}"

                                            <th>Nama</th> data-update-url="{{ route('cabang.update', $branch) }}"

                                            <th>Kota</th> data-delete-url="{{ route('cabang.destroy', $branch) }}">

                                            <th>Aksi</th> Edit

                                        </tr>
                                </button>

                                </thead>
                        </td>

                        <tbody>
                            </tr>

                            @foreach ($branches as $d) @empty

                            <tr>
                            <tr>

                                <td>{{ $loop->iteration }}</td>
                                <td colspan="7" class="text-center">Belum ada data cabang.</td>

                                <td>{{ $d->company?->short_name }}</td>
                            </tr>

                            <td>{{ $d->kode }}</td> @endforelse

                            <td>{{ $d->nama }}</td>
                        </tbody>

                        <td>{{ $d->kota }}</td>
                        </table>

                        <td>
                    </div>

                    <div class="form-group"> </div>

                    <a href="#" class="edit btn btn-info btn-sm" id_cabang="{{ $d->id }}">
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
        </div>

        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    </div>

    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
</div>

<path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />

<path d="M16 5l3 3" />
<div class="modal modal-blur fade" id="modal-cabang-create" tabindex="-1" aria-hidden="true">

    </svg>
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

        </a>
        <div class="modal-content">

            <form action="{{ route('cabang.destroy', $d) }}" method="POST" style="display:inline;">
                <div class="modal-header">

                    @csrf <h5 class="modal-title">Tambah Cabang</h5>

                    @method('DELETE') <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <a class="btn btn-danger btn-sm delete-confirm">
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                    <div class="modal-body">

                        <path stroke="none" d="M0 0h24v24H0z" fill="none" /> @if ($errors->any() && old('form_action') === 'create-cabang')

                        <path d="M4 7l16 0" />
                        <div class="alert alert-danger">

                            <path d="M10 11l0 6" />
                            <ul class="mb-0">

                                <path d="M14 11l0 6" /> @foreach ($errors->all() as $error)

                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <li>{{ $error }}</li>

                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /> @endforeach

                </svg> </ul>

                </a>
        </div>

        </form> @endif

    </div>

    </td>
    <form action="{{ route('cabang.store') }}" method="POST" class="row g-3">

        </tr> @csrf

        @endforeach <input type="hidden" name="form_action" value="create-cabang">

        </tbody>
        <div class="col-md-6">

            </table> <label class="form-label">Perusahaan</label>

        </div> <select name="company_id" class="form-select" required>

</div>
<option value="">Pilih Perusahaan</option>

</div> @foreach ($companies as $company)

</div>
<option value="{{ $company->id }}" @selected(old('company_id')==$company->id)>{{ $company->short_name }}</option>

</div> @endforeach

</div> </select>

</div>
</div>

</div>
<div class="col-md-6">

    <label class="form-label">Kode</label>

    <!-- Modal Create --> <input type="text" name="kode" class="form-control" placeholder="CBG" value="{{ old('kode') }}" required>

    <div class="modal modal-blur fade" id="modal-inputcabang" tabindex="-1" role="dialog" aria-hidden="true"> </div>

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="col-md-6">

            <div class="modal-content"> <label class="form-label">Nama Cabang</label>

                <div class="modal-header"> <input type="text" name="nama" class="form-control" placeholder="Cabang Utama" value="{{ old('nama') }}" required>

                    <h5 class="modal-title">Tambah Cabang</h5>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="col-md-6">

                </div> <label class="form-label">Kota</label>

                <div class="modal-body"> <input type="text" name="kota" class="form-control" placeholder="Jakarta" value="{{ old('kota') }}">

                    <form action="{{ route('cabang.store') }}" method="POST" id="formCabang">
                </div>

                @csrf <div class="col-12">

                    <div class="row"> <label class="form-label">Alamat</label>

                        <div class="col-12"> <input type="text" name="alamat" class="form-control" placeholder="Jl. Contoh 123" value="{{ old('alamat') }}">

                            <div class="form-label">Perusahaan</div>
                        </div>

                        <div class="input-icon mb-3">
                            <div class="col-12 d-flex justify-content-end">

                                <select name="company_id" class="form-select" id="company_id" required> <button type="submit" class="btn btn-primary">Simpan</button>

                                    <option value="">Pilih Perusahaan</option>
                            </div>

                            @foreach ($companies as $company) </form>

                            <option value="{{ $company->id }}">{{ $company->short_name }}</option>
                        </div>

                        @endforeach
                    </div>

                    </select>
                </div>

            </div>
        </div>

    </div>

</div>
<div class="modal modal-blur fade" id="modal-cabang-edit" tabindex="-1" aria-hidden="true">

    <div class="row">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

            <div class="col-12">
                <div class="modal-content">

                    <div class="form-label">Kode Cabang</div>
                    <div class="modal-header">

                        <div class="input-icon mb-3">
                            <h5 class="modal-title">Edit Cabang</h5>

                            <input type="text" class="form-control" name="kode" id="kode" placeholder="CBG" required> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                        </div>
                    </div>

                </div>
                <div class="modal-body">

                </div> @php

                <div class="row"> $cabangUpdateErrors = $errors->hasBag('cabangUpdate') ? $errors->cabangUpdate : null;

                    <div class="col-12"> @endphp

                        <div class="form-label">Nama Cabang</div>

                        <div class="input-icon mb-3"> @if ($cabangUpdateErrors && $cabangUpdateErrors->any())

                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Cabang Utama" required>
                            <div class="alert alert-danger">

                            </div>
                            <ul class="mb-0">

                        </div> @foreach ($cabangUpdateErrors->all() as $error)

                    </div>
                    <li>{{ $error }}</li>

                    <div class="row"> @endforeach

                        <div class="col-12">
                            </ul>

                            <div class="form-label">Alamat</div>
                        </div>

                        <div class="input-icon mb-3"> @endif

                            <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Jl. Contoh No. 123">

                        </div>
                        <form method="POST" class="row g-3" id="cabangEditForm">

                    </div> @csrf

                </div> @method('PUT')

                <div class="row"> <input type="hidden" name="form_action" value="edit-cabang">

                    <div class="col-12">
                        <div class="col-md-6">

                            <div class="form-label">Kota</div> <label class="form-label">Perusahaan</label>

                            <div class="input-icon mb-3"> <select name="company_id" class="form-select" required>

                                    <input type="text" class="form-control" name="kota" id="kota" placeholder="Jakarta">
                                    <option value="">Pilih Perusahaan</option>

                            </div> @foreach ($companies as $company)

                        </div>
                        <option value="{{ $company->id }}">{{ $company->short_name }}</option>

                    </div> @endforeach

                    <div class="row mt-3"> </select>

                        <div class="col-12"> </div>

                        <div class="form-group">
                            <div class="col-md-6">

                                <button class="btn btn-primary w-100"> <label class="form-label">Kode</label>

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy"> <input type="text" name="kode" class="form-control" required>

                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            </div>

                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <div class="col-md-6">

                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /> <label class="form-label">Nama Cabang</label>

                                <path d="M14 4l0 4l-6 0l0 -4" /> <input type="text" name="nama" class="form-control" required>

                                </svg>
                            </div>

                            Simpan <div class="col-md-6">

                                </button> <label class="form-label">Kota</label>

                            </div> <input type="text" name="kota" class="form-control">

                        </div>
                    </div>

                </div>
                <div class="col-12">

                    </form> <label class="form-label">Alamat</label>

                </div> <input type="text" name="alamat" class="form-control">

            </div>
        </div>

    </div>
    <div class="col-12 d-flex justify-content-between">

    </div> <button type="button" class="btn btn-outline-danger" id="cabangDeleteButton">Hapus</button>

    <button type="submit" class="btn btn-primary">Update</button>

    <!-- Modal Edit -->
</div>

<div class="modal modal-blur fade" id="modal-editcabang" tabindex="-1" role="dialog" aria-hidden="true">
    </form>

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"> </div>

    <div class="modal-content"> </div>

    <div class="modal-header"> </div>

    <h5 class="modal-title">Edit Cabang</h5>
</div>

<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>@php

<div class="modal-body" id="loadedcabang"> $shouldOpenCreateCabang = $errors->any() && old('form_action') === 'create-cabang';

    $hasCabangUpdateErrors = $errors->hasBag('cabangUpdate') && $errors->cabangUpdate->any();

</div> $editCabangId = session('edit_cabang_id');

</div> $oldCabangValues = [

</div> 'company_id' => old('company_id'),

</div> 'kode' => old('kode'),

@endsection 'nama' => old('nama'),

'alamat' => old('alamat'),

@push('myscript') 'kota' => old('kota'),

<script>
    ];

    $(function() {
                @endphp

                $('#btnTambahCabang').click(function() {

                    $('#modal-inputcabang').modal("show"); < div id = "cabang-page-flags"
                    class = "d-none"

                });
                data - create = "{{ $shouldOpenCreateCabang ? '1' : '0' }}"

                data - edit = "{{ $hasCabangUpdateErrors ? '1' : '0' }}"

                $('.edit').click(function() {
                            data - edit - id = "{{ $editCabangId ?? '' }}"

                            var id = $(this).attr('id_cabang');
                            data - old = "{{ htmlspecialchars(json_encode($oldCabangValues), ENT_QUOTES, 'UTF-8') }}" >

                                $.ajax({
                                    < /div>

                                    type: 'POST',
                                    @endsection

                                    url: '/cabang/edit',

                                    cache: false,
                                    @push('myscript')

                                    data: {
                                        < script >

                                        _token: "{{ csrf_token() }}",
                                        document.addEventListener('DOMContentLoaded', () => {

                                                id: id
                                                const flagsEl = document.getElementById('cabang-page-flags');

                                            },
                                            const createModalShouldOpen = flagsEl?.dataset.create === '1';

                                            success: function(respond) {
                                                const editModalErrors = flagsEl?.dataset.edit === '1';

                                                $('#loadedcabang').html(respond);
                                                const editCabangIdRaw = flagsEl?.dataset.editId ?? '';

                                            }
                                            const editCabangId = editCabangIdRaw ? Number(editCabangIdRaw) : null;

                                        });
                                    const oldCabangValues = flagsEl?.dataset.old ? JSON.parse(flagsEl.dataset.old) : {};

                                    $('#modal-editcabang').modal("show");

                                });
                            const createModalEl = document.getElementById('modal-cabang-create');

                            if (createModalShouldOpen && createModalEl) {

                                $(".delete-confirm").click(function(e) {
                                        bootstrap.Modal.getOrCreateInstance(createModalEl).show();

                                        var form = $(this).closest('form');
                                    }

                                    e.preventDefault();

                                    Swal.fire({
                                        const editModalEl = document.getElementById('modal-cabang-edit');

                                        title: "Apakah Yakin?",
                                        const editForm = document.getElementById('cabangEditForm');

                                        text: "Data Cabang Akan Di Delete!",
                                        const deleteButton = document.getElementById('cabangDeleteButton');

                                        icon: "warning",

                                        showCancelButton: true,
                                        if (!editModalEl || !editForm || !deleteButton) {

                                            confirmButtonColor: "#3085d6",
                                            return;

                                            cancelButtonColor: "#d33",
                                        }

                                        confirmButtonText: "Delete"

                                    }).then((result) => {
                                        const companySelect = editForm.querySelector('select[name="company_id"]');

                                        if (result.isConfirmed) {
                                            const kodeInput = editForm.querySelector('input[name="kode"]');

                                            form.submit();
                                            const namaInput = editForm.querySelector('input[name="nama"]');

                                        }
                                        const alamatInput = editForm.querySelector('input[name="alamat"]');

                                    });
                                    const kotaInput = editForm.querySelector('input[name="kota"]');

                                });

                            const openEditModal = (button, overrideValues = null) => {

                                $('#formCabang').submit(function() {
                                        if (!button) {

                                            var company_id = $('#company_id').val();
                                            return;

                                            var kode = $('#kode').val();
                                        }

                                        var nama = $('#nama').val();

                                        if (company_id == "") {
                                            const dataset = button.dataset;

                                            Swal.fire({
                                                    editForm.action = dataset.updateUrl;

                                                    title: 'Warning!',

                                                    text: 'Perusahaan Harus Dipilih',
                                                    const values = overrideValues ?? {

                                                        icon: 'warning',
                                                        company_id: dataset.companyId ?? '',

                                                        confirmButtonText: 'Ok'
                                                        kode: dataset.kode ?? '',

                                                    }).then(() => {
                                                    nama: dataset.nama ?? '',

                                                    $('#company_id').focus();alamat: dataset.alamat ?? '',

                                                }); kota: dataset.kota ?? '',

                                                return false;
                                            };

                                        } else if (kode == "") {

                                            Swal.fire({
                                                companySelect.value = values.company_id ?? '';

                                                title: 'Warning!',
                                                kodeInput.value = values.kode ?? '';

                                                text: 'Kode Cabang Harus Diisi',
                                                namaInput.value = values.nama ?? '';

                                                icon: 'warning',
                                                alamatInput.value = values.alamat ?? '';

                                                confirmButtonText: 'Ok'
                                                kotaInput.value = values.kota ?? '';

                                            }).then(() => {

                                                    $('#kode').focus();
                                                    deleteButton.onclick = () => {

                                                    });
                                                if (confirm('Hapus cabang ini?')) {

                                                    return false;
                                                    const deleteForm = document.createElement('form');

                                                } else if (nama == "") {
                                                    deleteForm.method = 'POST';

                                                    Swal.fire({
                                                            deleteForm.action = dataset.deleteUrl;

                                                            title: 'Warning!',
                                                            deleteForm.innerHTML = '@csrf<input type="hidden" name="_method" value="DELETE">';

                                                            text: 'Nama Cabang Harus Diisi',
                                                            document.body.appendChild(deleteForm);

                                                            icon: 'warning',
                                                            deleteForm.submit();

                                                            confirmButtonText: 'Ok'
                                                        }

                                                    }).then(() => {};

                                                    $('#nama').focus();

                                                });
                                            bootstrap.Modal.getOrCreateInstance(editModalEl).show();

                                            return false;
                                        };

                                    }

                                });
                            document.querySelectorAll('.btn-edit-cabang').forEach((button) => {

                            });
                            button.addEventListener('click', () => openEditModal(button));
</script> });

@endpush

if (editModalErrors && editCabangId) {
const targetButton = document.querySelector(`.btn-edit-cabang[data-id="${editCabangId}"]`);
openEditModal(targetButton, oldCabangValues);
}
});
</script>
@endpush