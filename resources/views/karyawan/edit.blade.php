<form action="/karyawan/{{ $karyawan->id }}" method="POST" id="formUpdateKaryawan">
    @csrf
    @method('PUT')

    {{-- Section: Data Pribadi --}}
    <div class="mb-3">
        <h3 class="card-title mb-0 fs-5">Data Pribadi</h3>
        <div class="text-muted small mb-2">
            Perbarui informasi dasar karyawan.
        </div>
        <hr class="mt-1 mb-3">

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">NIK</label>
                <input type="text" value="{{ $karyawan->nik }}" class="form-control" disabled>
            </div>

            <div class="col-md-4">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama_lengkap" id="nama_lengkap_edit"
                       value="{{ $karyawan->nama_lengkap }}" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" id="no_hp_edit"
                       value="{{ $karyawan->no_hp }}" class="form-control">
            </div>
        </div>

        <div class="row g-3 mt-0">
            <div class="col-md-4">
                <label class="form-label">Tanggal Masuk</label>
                <input type="date" name="tgl_masuk" id="tgl_masuk_edit"
                       value="{{ $karyawan->tgl_masuk }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Resign</label>
                <input type="date" name="tgl_resign" id="tgl_resign_edit"
                       value="{{ $karyawan->tgl_resign }}" class="form-control">
            </div>
        </div>
    </div>

    {{-- Section: Kantor & Jabatan --}}
    <div class="mb-3">
        <h3 class="card-title mb-0 fs-5">Kantor & Jabatan</h3>
        <div class="text-muted small mb-2">
            Pengaturan perusahaan, cabang, department, dan posisi karyawan.
        </div>
        <hr class="mt-1 mb-3">

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Perusahaan <span class="text-danger">*</span></label>
                <select name="company_id" id="company_id_edit" class="form-select" required>
                    <option value="">Pilih Perusahaan</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ $karyawan->company_id == $company->id ? 'selected' : '' }}>
                            {{ $company->short_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Cabang</label>
                <select name="cabang_id" id="cabang_id_edit"
                        class="form-select" {{ $karyawan->company_id ? '' : 'disabled' }}>
                    <option value="">{{ $karyawan->company_id ? 'Memuat cabang...' : 'Pilih perusahaan dulu' }}</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Department</label>
                <select name="department_id" id="department_id_edit" class="form-select">
                    <option value="">Pilih Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ $karyawan->department_id == $department->id ? 'selected' : '' }}>
                            {{ $department->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row g-3 mt-0">
            <div class="col-md-4">
                <label class="form-label">Jabatan</label>
                <select name="jabatan_id" id="jabatan_id_edit" class="form-select">
                    <option value="">Pilih Jabatan</option>
                    @foreach ($positions as $position)
                        <option value="{{ $position->id }}"
                            {{ $karyawan->jabatan_id == $position->id ? 'selected' : '' }}>
                            {{ $position->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status_kar" id="status_kar_edit" class="form-select" required>
                    <option value="">Pilih Status</option>
                    <option value="Aktif" {{ $karyawan->status_kar == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Non-Aktif" {{ $karyawan->status_kar == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Section: Akun Login --}}
    <div class="mb-3">
        <h3 class="card-title mb-0 fs-5">Akun Login</h3>
        <div class="text-muted small mb-2">
            Kosongkan password jika tidak ingin mengganti.
        </div>
        <hr class="mt-1 mb-3">

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Password Baru (Opsional)</label>
                <input type="password" name="password" id="password_edit"
                       class="form-control"
                       placeholder="Kosongkan jika tidak ingin mengganti">
            </div>
            <div class="col-md-6">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation_edit"
                       class="form-control"
                       placeholder="Ulangi password baru">
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary w-100">
            Update
        </button>
    </div>
</form>

<script>
// Expose an init function the parent page will call after injecting this partial
window.__initEditKaryawan = function () {
    const $company = $('#company_id_edit');
    const $cabang  = $('#cabang_id_edit');

    const currentCompanyId = $company.val();
    const currentCabangId  = "{{ $karyawan->cabang_id ?? '' }}";

    function loadCabang(companyId, preselectId = null) {
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

            if (preselectId && rows.some(r => String(r.id) === String(preselectId))) {
                $cabang.val(preselectId);
            } else if (rows.length === 1) {
                $cabang.val(rows[0].id);
            }
        });
    }

    // Bind change handler sekali saja
    $(document).off('change.karCompanyEdit', '#company_id_edit')
        .on('change.karCompanyEdit', '#company_id_edit', function () {
            loadCabang($(this).val(), null);
        });

    // Initial load cabang
    if (currentCompanyId) {
        loadCabang(currentCompanyId, currentCabangId);
    } else {
        $cabang.empty()
               .append('<option value="">Pilih perusahaan dulu</option>')
               .prop('disabled', true);
    }

    // Form validation (mirip create)
    $('#formUpdateKaryawan').off('submit.karUpdate').on('submit.karUpdate', function () {
        const nama_lengkap = $('#nama_lengkap_edit').val();
        const company_id   = $('#company_id_edit').val();
        const status_kar   = $('#status_kar_edit').val();
        const password     = $('#password_edit').val();
        const confirmPwd   = $('#password_confirmation_edit').val();

        if (!nama_lengkap) {
            Swal.fire({
                title: 'Warning!',
                text: 'Nama Lengkap Harus Diisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then(() => $('#nama_lengkap_edit').focus());
            return false;
        } else if (!company_id) {
            Swal.fire({
                title: 'Warning!',
                text: 'Perusahaan Harus Dipilih',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then(() => $('#company_id_edit').focus());
            return false;
        } else if (!status_kar) {
            Swal.fire({
                title: 'Warning!',
                text: 'Status Harus Dipilih',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then(() => $('#status_kar_edit').focus());
            return false;
        } else if (password && password !== confirmPwd) {
            Swal.fire({
                title: 'Warning!',
                text: 'Password dan Konfirmasi Password Tidak Cocok',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then(() => $('#password_confirmation_edit').focus());
            return false;
        }
    });
};
</script>
