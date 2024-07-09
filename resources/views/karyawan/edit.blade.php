<form action="/karyawan/{{ $karyawan->nik }}/update" method="POST" id="formKaryawan" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-6">
            <div class="form-label">NIK</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M15 8l2 0" />
                        <path d="M15 12l2 0" />
                        <path d="M7 16l10 0" />
                    </svg>
                </span>
                <input type="text" value="{{$karyawan->nik}}" class="form-control" name="nik" id="nik" placeholder="10101" disabled>
            </div>
        </div>
        <div class="col-6">
            <div class="form-label">Nama Karyawan</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                </span>
                <input type="text" value="{{$karyawan->nama_lengkap}}" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="John Doe">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-label">Email</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                        <path d="M3 7l9 6l9 -6" />
                    </svg>
                </span>
                <input type="text" value="{{$karyawan->email}}" class="form-control" name="email" id="email" placeholder="@ciptaharmoni.com">
            </div>
        </div>
        <div class="col-6">
            <div class="form-label">Jabatan</div>
            <select name="jabatan" id="jabatan" class="form-select">
                <option value="">Pilih</option>
                @foreach ($jabatan as $d)
                <option {{ $karyawan->jabatan == $d->id ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->nama_jabatan }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-label">Nomer HP</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-phone">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                    </svg>
                </span>
                <input type="text" value="{{$karyawan->no_hp}}" class="form-control" name="no_hp" id="no_hp" placeholder="No HP">
            </div>
        </div>
        <div class="col-6">
            <div class="mb-3">
                <div class="form-label">Foto Karyawan</div>
                <input type="file" class="form-control" name="foto" id="foto" accept=".png, .jpg, .jpeg">
                <input type="hidden" name="old_foto" value="{{ $karyawan->foto}}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-label">Tanggal Masuk</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M16 3l0 4" />
                        <path d="M8 3l0 4" />
                        <path d="M4 11l16 0" />
                        <path d="M8 15h2v2h-2z" />
                    </svg>
                </span>
                <input type="date" value="{{$karyawan->tgl_masuk}}" class="form-control" name="tgl_masuk" id="tgl_masuk" placeholder="">
            </div>
        </div>
        <div class="col-6">
            <div class="form-label">Tanggal Resign</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M16 3l0 4" />
                        <path d="M8 3l0 4" />
                        <path d="M4 11l16 0" />
                        <path d="M8 15h2v2h-2z" />
                    </svg>
                </span>
                <input type="date" value="{{$karyawan->tgl_resign}}" class="form-control" name="tgl_resign" id="tgl_resign" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-label">Date Of Birth</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M16 3l0 4" />
                        <path d="M8 3l0 4" />
                        <path d="M4 11l16 0" />
                        <path d="M8 15h2v2h-2z" />
                    </svg>
                </span>
                <input type="date" value="{{$karyawan->DOB}}" class="form-control" name="DOB" id="DOB" placeholder="">
            </div>
        </div>
        <div class="col-6">
            <div class="form-label">Department</div>
            <select name="kode_dept" id="kode_dept" class="form-select">
                <option value="">Pilih</option>
                @foreach ($department as $d)
                <option {{ $karyawan->kode_dept == $d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-6">
            <div class="form-label">Level</div>
            <select name="level" id="level" class="form-select">
                <option value="">Pilih</option>
                <option {{ $karyawan->level == 'Officer' ? 'selected' : '' }} value="Officer">Officer</option>
                <option {{ $karyawan->level == 'Manager' ? 'selected' : '' }} value="Manager">Manager</option>
                <option {{ $karyawan->level == 'HRD' ? 'selected' : '' }} value="HRD">HRD</option>
                <option {{ $karyawan->level == 'Management' ? 'selected' : '' }} value="Management">Management</option>
            </select>
        </div>
        <div class="col-6">
            <div class="form-label">Atasan</div>
            <select name="nik_atasan" id="nik_atasan" class="form-select">
                <option value="">Pilih</option>
                @foreach ($atasan as $a)
                <option {{ $karyawan->nik_atasan == $a->nik ? 'selected' : '' }} value="{{ $a->nik }}">{{ $a->nama_lengkap }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                    </svg>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
