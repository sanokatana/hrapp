<form action="{{ route('jabatans.update', $jabatan) }}" method="POST" id="formJabatan">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <label class="form-label">Company</label>
            <select name="company_id" class="form-select" required>
                <option value="">Pilih Company</option>
                @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ $jabatan->company_id == $company->id ? 'selected' : '' }}>
                    {{ $company->short_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Cabang</label>
            <select name="cabang_id" class="form-select">
                <option value="">Pilih Cabang</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ $jabatan->cabang_id == $branch->id ? 'selected' : '' }}>
                    {{ $branch->nama }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-6">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select" required>
                <option value="">Pilih Department</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ $jabatan->department_id == $dept->id ? 'selected' : '' }}>
                    {{ $dept->nama }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Nama Jabatan</label>
            <input type="text" name="nama" class="form-control" value="{{ $jabatan->nama }}" placeholder="Nama Jabatan" required>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-6">
            <label class="form-label">Level</label>
            <input type="text" name="level" class="form-control" value="{{ $jabatan->level }}" placeholder="Level">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/>
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                        <path d="M14 4l0 4l-6 0l0 -4"/>
                    </svg>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
