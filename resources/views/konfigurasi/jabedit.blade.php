<form action="/konfigurasi/jabatan/{{ $jabatan->id }}/update" method="POST" id="formJabatan">
    @csrf
    @method('POST')
    <div class="row">
        <div class="col-12">
            <div class="form-label">ID Jabatan</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-community">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 9l5 5v7h-5v-4m0 4h-5v-7l5 -5m1 1v-6a1 1 0 0 1 1 -1h10a1 1 0 0 1 1 1v17h-8" />
                        <path d="M13 7l0 .01" />
                        <path d="M17 7l0 .01" />
                        <path d="M17 11l0 .01" />
                        <path d="M17 15l0 .01" />
                    </svg>
                </span>
                <input type="hidden" value="{{ $jabatan->id }}" name="id">
                <input type="text" value="{{ $jabatan->id }}" class="form-control" name="id_display" id="id_display" placeholder="0" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Nama Jabatan</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-letter-case">
                        <path stroke="none" d="M0 0h24V0H0z" fill="none" />
                        <path d="M17.5 15.5m-3.5 0a3.5 3.5 0 1 0 7 0a3.5 3.5 0 1 0 -7 0" />
                        <path d="M3 19v-10.5a3.5 3.5 0 0 1 7 0v10.5" />
                        <path d="M3 13h7" />
                        <path d="M21 12v7" />
                    </svg>
                </span>
                <input type="text" value="{{ $jabatan->nama_jabatan }}" class="form-control" name="nama_jabatan" id="nama_jabatan" placeholder="Nama Jabatan">
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-label">Department</div>
        <select name="kode_dept" id="kode_dept" class="form-select">
            <option value="">Pilih</option>
            @foreach ($department as $d)
            <option {{ $jabatan->kode_dept == $d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 mt-3">
        <div class="form-label">Jabatan Atasan</div>
        <select name="jabatan_atasan" id="jabatan_atasan" class="form-select">
            <option value="">Pilih</option>
            @foreach ($jabat as $d)
            <option {{ $jabatan->jabatan_atasan == $d->id ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->nama_jabatan }} || {{ $d->kode_dept }} || {{ $d->site }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 mt-3">
        <div class="form-label">Site</div>
        <select name="site" id="site" class="form-select">
            <option value="">Pilih</option>
            @foreach ($location as $d)
            <option {{ $jabatan->site == $d->nama_kantor ? 'selected' : '' }} value="{{ $d->nama_kantor }}">{{ $d->nama_kantor }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 mt-3">
        <div class="form-label">Posisi Jabatan</div>
        <select name="jabatan_posisi" id="jabatan_posisi" class="form-select">
            <option {{ $jabatan->jabatan == 'Management' ? 'selected' : '' }} value="Management">Management</option>
            <option {{ $jabatan->jabatan == 'Head Of Department' ? 'selected' : '' }} value="Head Of Department">Head Of Department</option>
            <option {{ $jabatan->jabatan == 'Section Head' ? 'selected' : '' }} value="Section Head">Section Head</option>
            <option {{ $jabatan->jabatan == 'Officer' ? 'selected' : '' }} value="Officer">Officer</option>
            <option {{ $jabatan->jabatan == 'Internship' ? 'selected' : '' }} value="Internship">Officer</option>
        </select>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 12l5 5l10 -10" />
                        <path d="M2 12l5 5m5 -5l5 -5" />
                    </svg>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
