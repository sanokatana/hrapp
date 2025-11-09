<form action="/karyawan/{{ $karyawan->id }}" method="POST" id="formUpdateKaryawan">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-4">
            <div class="form-label">NIK</div>
            <input type="text" value="{{ $karyawan->nik }}" class="form-control" disabled>
        </div>
        <div class="col-md-4">
            <div class="form-label">Nama Lengkap</div>
            <input type="text" name="nama_lengkap" id="nama_lengkap_edit" value="{{ $karyawan->nama_lengkap }}" class="form-control" required>
        </div>
        <div class="col-md-4">
            <div class="form-label">Email</div>
            <input type="email" name="email" id="email_edit" value="{{ $karyawan->email }}" class="form-control">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-4">
            <div class="form-label">No. HP</div>
            <input type="text" name="no_hp" id="no_hp_edit" value="{{ $karyawan->no_hp }}" class="form-control">
        </div>
        <div class="col-md-4">
            <div class="form-label">Tanggal Masuk</div>
            <input type="date" name="tgl_masuk" id="tgl_masuk_edit" value="{{ $karyawan->tgl_masuk }}" class="form-control">
        </div>
        <div class="col-md-4">
            <div class="form-label">Tanggal Resign</div>
            <input type="date" name="tgl_resign" id="tgl_resign_edit" value="{{ $karyawan->tgl_resign }}" class="form-control">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-4">
            <div class="form-label">Perusahaan</div>
            <select name="company_id" id="company_id_edit" class="form-select" required>
                <option value="">Pilih Perusahaan</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ $karyawan->company_id == $company->id ? 'selected' : '' }}>
                        {{ $company->short_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <div class="form-label">Cabang</div>
            <select name="cabang_id" id="cabang_id_edit" class="form-select" {{ $karyawan->company_id ? '' : 'disabled' }}>
                <option value="">{{ $karyawan->company_id ? 'Memuat cabang...' : 'Pilih perusahaan dulu' }}</option>
            </select>
        </div>

        <div class="col-md-4">
            <div class="form-label">Department</div>
            <select name="department_id" id="department_id_edit" class="form-select">
                <option value="">Pilih Department</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" {{ $karyawan->department_id == $department->id ? 'selected' : '' }}>
                        {{ $department->nama }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-4">
            <div class="form-label">Jabatan</div>
            <select name="jabatan_id" id="jabatan_id_edit" class="form-select">
                <option value="">Pilih Jabatan</option>
                @foreach ($positions as $position)
                    <option value="{{ $position->id }}" {{ $karyawan->jabatan_id == $position->id ? 'selected' : '' }}>
                        {{ $position->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <div class="form-label">Status</div>
            <select name="status_kar" id="status_kar_edit" class="form-select" required>
                <option value="">Pilih Status</option>
                <option value="Aktif" {{ $karyawan->status_kar == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Non-Aktif" {{ $karyawan->status_kar == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6">
            <div class="form-label">Password Baru (Opsional)</div>
            <input type="password" name="password" id="password_edit" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti">
        </div>
        <div class="col-md-6">
            <div class="form-label">Konfirmasi Password</div>
            <input type="password" name="password_confirmation" id="password_confirmation_edit" class="form-control" placeholder="Ulangi password baru">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">Update</button>
        </div>
    </div>
</form>

<script>
// Expose an init function the parent page will call after injecting this partial
window.__initEditKaryawan = function () {
    const $company = $('#company_id_edit');
    const $cabang  = $('#cabang_id_edit');

    const currentCompanyId = $company.val();
    const currentCabangId  = @json($karyawan->cabang_id);

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

    $(document).off('change.karCompanyEdit', '#company_id_edit')
        .on('change.karCompanyEdit', '#company_id_edit', function () {
            loadCabang($(this).val(), null);
        });

    if (currentCompanyId) {
        loadCabang(currentCompanyId, currentCabangId);
    } else {
        $cabang.empty().append('<option value="">Pilih perusahaan dulu</option>').prop('disabled', true);
    }

    // Form validation (same rules as create, adjusted)
    $('#formUpdateKaryawan').off('submit.karUpdate').on('submit.karUpdate', function () {
        const nama_lengkap = $('#nama_lengkap_edit').val();
        const company_id   = $('#company_id_edit').val();
        const status_kar   = $('#status_kar_edit').val();
        const password     = $('#password_edit').val();
        const confirmPwd   = $('#password_confirmation_edit').val();

        if (!nama_lengkap) {
            Swal.fire({ title: 'Warning!', text: 'Nama Lengkap Harus Diisi', icon: 'warning', confirmButtonText: 'Ok' })
                .then(() => $('#nama_lengkap_edit').focus());
            return false;
        } else if (!company_id) {
            Swal.fire({ title: 'Warning!', text: 'Perusahaan Harus Dipilih', icon: 'warning', confirmButtonText: 'Ok' })
                .then(() => $('#company_id_edit').focus());
            return false;
        } else if (!status_kar) {
            Swal.fire({ title: 'Warning!', text: 'Status Harus Dipilih', icon: 'warning', confirmButtonText: 'Ok' })
                .then(() => $('#status_kar_edit').focus());
            return false;
        } else if (password && password !== confirmPwd) {
            Swal.fire({ title: 'Warning!', text: 'Password dan Konfirmasi Password Tidak Cocok', icon: 'warning', confirmButtonText: 'Ok' })
                .then(() => $('#password_confirmation_edit').focus());
            return false;
        }
    });
};
</script>
