<form action="{{ route('jabatans.update', $jabatan->id) }}" method="POST" id="formJabatanEdit">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6">
            <label class="form-label">Perusahaan</label>
            <select name="company_id" id="company_id_edit" class="form-select" required>
                <option value="">Pilih Perusahaan</option>
                @foreach($companies as $c)
                    <option value="{{ $c->id }}" {{ $jabatan->company_id == $c->id ? 'selected' : '' }}>
                        {{ $c->short_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Cabang</label>
            <select name="cabang_id" id="cabang_id_edit" class="form-select">
                <option value="">{{ $branches->count() ? 'Pilih Cabang' : 'Pilih perusahaan dulu' }}</option>
                @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ $jabatan->cabang_id == $b->id ? 'selected' : '' }}>
                        {{ $b->nama }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6">
            <label class="form-label">Department</label>
            <select name="department_id" id="department_id_edit" class="form-select" required>
                <option value="">{{ $departments->count() ? 'Pilih Department' : 'Pilih perusahaan dulu' }}</option>
                @foreach($departments as $d)
                    <option value="{{ $d->id }}" {{ $jabatan->department_id == $d->id ? 'selected' : '' }}>
                        {{ $d->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Nama Jabatan</label>
            <input type="text" name="nama" id="nama_edit" class="form-control" value="{{ $jabatan->nama }}" placeholder="Nama Jabatan" required>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6">
            <label class="form-label">Level</label>
            <input type="text" name="level" id="level_edit" class="form-control" value="{{ $jabatan->level }}" placeholder="Level">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <button class="btn btn-primary w-100">Update</button>
        </div>
    </div>
</form>

<script>
window.__initEditJabatan = function () {
    const $company = $('#company_id_edit');
    const $cabang  = $('#cabang_id_edit');
    const $dept    = $('#department_id_edit');

    function loadBranches(companyId, preselectId = null) {
        $cabang.prop('disabled', true).empty().append('<option value="">Memuat cabang...</option>');
        if (!companyId) {
            $cabang.empty().append('<option value="">Pilih perusahaan dulu</option>');
            return;
        }
        $.getJSON(`/companies/${companyId}/branches`, function(rows) {
            $cabang.empty().append('<option value="">Semua Cabang</option>');
            rows.forEach(r => $cabang.append(new Option(r.nama, r.id)));
            $cabang.prop('disabled', false);
            if (preselectId && rows.some(r => String(r.id) === String(preselectId))) {
                $cabang.val(preselectId);
            }
        });
    }

    function loadDepartments(companyId, preselectId = null) {
        $dept.prop('disabled', true).empty().append('<option value="">Memuat department...</option>');
        if (!companyId) {
            $dept.empty().append('<option value="">Pilih perusahaan dulu</option>');
            return;
        }
        $.getJSON(`/companies/${companyId}/departments`, function(rows) {
            $dept.empty().append('<option value="">Pilih Department</option>');
            rows.forEach(r => $dept.append(new Option(r.nama, r.id)));
            $dept.prop('disabled', false);
            if (preselectId && rows.some(r => String(r.id) === String(preselectId))) {
                $dept.val(preselectId);
            }
        });
    }

    const currentCompanyId = $company.val();
    const currentCabangId  = "{{ $jabatan->cabang_id }}";
    const currentDeptId    = "{{ $jabatan->department_id }}";

    // Initialize lists for current company
    if (currentCompanyId) {
        loadBranches(currentCompanyId, currentCabangId);
        loadDepartments(currentCompanyId, currentDeptId);
    }

    // Change handlers
    $(document).off('change.jabCompanyEdit', '#company_id_edit').on('change.jabCompanyEdit', '#company_id_edit', function () {
        const companyId = $(this).val();
        loadBranches(companyId, null);
        loadDepartments(companyId, null);
    });

    // Front-end validation
    $('#formJabatanEdit').off('submit.jabEdit').on('submit.jabEdit', function () {
        const company = $company.val();
        const dept    = $dept.val();
        const nama    = $('#nama_edit').val();

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
    });
};
</script>
