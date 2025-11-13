@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Organisasi</div>
                <h2 class="page-title">Jabatan</h2>
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
                                <a href="#" class="btn btn-primary" id="btnTambahJabatan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 5v14"/>
                                        <path d="M5 12h14"/>
                                    </svg>
                                    Add Jabatan
                                </a>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Perusahaan</th>
                                            <th>Cabang</th>
                                            <th>Department</th>
                                            <th>Nama Jabatan</th>
                                            <th>Level</th>
                                            <th>Tarif / Hari</th>
                                            <th width="14%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($positions as $i => $p)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $p->company?->short_name ?? '-' }}</td>
                                            <td>{{ $p->cabang?->nama ?? '-' }}</td>
                                            <td>{{ $p->department?->nama ?? '-' }}</td>
                                            <td>{{ $p->nama }}</td>
                                            <td>{{ $p->level ?? '-' }}</td>
                                            <td>Rp {{ number_format($p->daily_rate ?? 0, 0, ',', '.') }}</td>
                                            <td>
                                                <div class="form-group">
                                                    <a href="#" class="edit btn btn-info btn-sm" data-id="{{ $p->id }}" title="Edit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                            <path d="M16 5l3 3" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('jabatans.destroy', $p->id) }}" method="POST" style="display:inline;">
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
                                            <td colspan="7" class="text-center">Belum ada data jabatan.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Create Modal -->
                        <div class="modal modal-blur fade" id="modal-inputjabatan" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Jabatan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('jabatans.store') }}" method="POST" id="formJabatanCreate">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Perusahaan</label>
                                                    <select name="company_id" id="company_id_create" class="form-select" required>
                                                        <option value="">Pilih Perusahaan</option>
                                                        @foreach($companies as $c)
                                                            <option value="{{ $c->id }}">{{ $c->short_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Cabang</label>
                                                    <select name="cabang_id" id="cabang_id_create" class="form-select" disabled>
                                                        <option value="">Pilih perusahaan dulu</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label">Department</label>
                                                    <select name="department_id" id="department_id_create" class="form-select" disabled required>
                                                        <option value="">Pilih perusahaan dulu</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Nama Jabatan</label>
                                                    <input type="text" name="nama" id="nama_create" class="form-control" placeholder="Nama Jabatan" required>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label">Level</label>
                                                    <input type="text" name="level" id="level_create" class="form-control" placeholder="Level">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Tarif Per Hari</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" name="daily_rate" id="daily_rate_create" class="form-control" placeholder="0" step="0.01" min="0" required>
                                                    </div>
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

                        <!-- Edit Modal -->
                        <div class="modal modal-blur fade" id="modal-editjabatan" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Jabatan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="loadedjabatan"></div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- /card-body -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
$(function() {
    // Open create modal
    $('#btnTambahJabatan').on('click', function() {
        $('#modal-inputjabatan').modal('show');
    });

    // Dependent selects in CREATE modal
    $('#company_id_create').on('change', function() {
        const companyId = $(this).val();
        const $cabang   = $('#cabang_id_create');
        const $dept     = $('#department_id_create');

        // Reset
        $cabang.prop('disabled', true).empty().append('<option value="">Memuat cabang...</option>');
        $dept.prop('disabled', true).empty().append('<option value="">Memuat department...</option>');

        if (!companyId) {
            $cabang.empty().append('<option value="">Pilih perusahaan dulu</option>');
            $dept.empty().append('<option value="">Pilih perusahaan dulu</option>');
            return;
        }

        $.getJSON(`/companies/${companyId}/branches`, function(rows) {
            $cabang.empty().append('<option value="">Semua Cabang</option>');
            rows.forEach(r => $cabang.append(new Option(r.nama, r.id)));
            $cabang.prop('disabled', false);
        });

        $.getJSON(`/companies/${companyId}/departments`, function(rows) {
            $dept.empty().append('<option value="">Pilih Department</option>');
            rows.forEach(r => $dept.append(new Option(r.nama, r.id)));
            $dept.prop('disabled', false);
        });
    });

    // Create validation (simple front-end checks)
    $('#formJabatanCreate').on('submit', function() {
        const company   = $('#company_id_create').val();
        const dept      = $('#department_id_create').val();
        const nama      = $('#nama_create').val();
        const dailyRate = $('#daily_rate_create').val();

        if (!company) {
            Swal.fire({ title:'Warning!', text:'Perusahaan Harus Dipilih', icon:'warning', confirmButtonText:'Ok' });
            return false;
        }
        if (!dept) {
            Swal.fire({ title:'Warning!', text:'Department Harus Dipilih', icon:'warning', confirmButtonText:'Ok' });
            return false;
        }
        if (!nama) {
            Swal.fire({ title:'Warning!', text:'Nama Jabatan Harus Diisi', icon:'warning', confirmButtonText:'Ok' });
            return false;
        }
        if (dailyRate === '' || Number(dailyRate) < 0) {
            Swal.fire({ title:'Warning!', text:'Tarif Per Hari harus diisi dengan angka >= 0', icon:'warning', confirmButtonText:'Ok' });
            return false;
        }
    });

    // Open edit modal (AJAX)
    $('.edit').on('click', function() {
        const id = $(this).data('id');

        $.ajax({
            type: 'POST',
            url: 'jabatans/edit',
            data: { _token: "{{ csrf_token() }}", id },
            success: function(html) {
                $('#loadedjabatan').html(html);

                // Ensure partial initializer runs
                if (typeof window.__initEditJabatan === 'function') {
                    window.__initEditJabatan();
                }

                $('#modal-editjabatan').modal('show');
            },
            error: function() {
                Swal.fire({ title:'Error', text:'Gagal memuat data.', icon:'error' });
            }
        });
    });

    // Delete confirm
    $(document).on('click', '.delete-confirm', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: "Apakah Yakin?",
            text: "Data Jabatan Akan Dihapus!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Delete"
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
