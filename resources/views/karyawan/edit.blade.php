<form action="/karyawan/{{ $karyawan->id }}/update" method="POST" id="formEditKaryawan" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-3">
            <div class="form-label">Nomer Mesin</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M15 8l2 0" />
                        <path d="M15 12l2 0" />
                        <path d="M7 16l10 0" />
                    </svg>
                </span>
                <input type="text" value="{{ $karyawan->nip }}" class="form-control" name="nip" id="nip" placeholder="10101">
            </div>
        </div>
        <div class="col-3">
            <div class="form-label">NIK</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M15 8l2 0" />
                        <path d="M15 12l2 0" />
                        <path d="M7 16l10 0" />
                    </svg>
                </span>
                <input type="text" value="{{ $karyawan->nik }}" class="form-control" name="nik" id="nik" placeholder="10101">
            </div>
        </div>
        <div class="col-3">
            <div class="form-label">Nama Karyawan</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                </span>
                <input type="text" value="{{ $karyawan->nama_lengkap }}" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="John Doe">
            </div>
        </div>
        <div class="col-3">
            <div class="form-label">Tanggal Masuk</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M16 3l0 4" />
                        <path d="M8 3l0 4" />
                        <path d="M4 11l16 0" />
                        <path d="M8 15h2v2h-2z" />
                    </svg>
                </span>
                <input type="date" value="{{ $karyawan->tgl_masuk }}" class="form-control" name="tgl_masuk" id="tgl_masuk" placeholder="Tanggal Masuk">
            </div>
        </div>
        <div class="col-3">
            <div class="form-label">Email Perusahaan</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                        <path d="M3 7l9 6l9 -6" />
                    </svg>
                </span>
                <input type="text" value="{{ $karyawan->email }}" class="form-control" name="email" id="email" placeholder="@ciptaharmoni.com">
            </div>
        </div>
        <div class="col-3">
            <div class="form-label">Nomer HP</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-phone">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                    </svg>
                </span>
                <input type="text" value="{{ $karyawan->no_hp }}" class="form-control" name="no_hp" id="no_hp" placeholder="No HP">
            </div>
        </div>
        <div class="col-3">
            <div class="form-label">Date Of Birth</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M16 3l0 4" />
                        <path d="M8 3l0 4" />
                        <path d="M4 11l16 0" />
                        <path d="M8 15h2v2h-2z" />
                    </svg>
                </span>
                <input type="date" value="{{ $karyawan->DOB }}" class="form-control" name="DOB" id="DOB" placeholder="">
            </div>
        </div>
        <div class="col-3">
            <div class="form-label">Foto Karyawan</div>
            <input type="file" class="form-control" name="foto" id="foto" accept=".png, .jpg, .jpeg">
        </div>
        <!-- Additional Fields Start Here -->
        <div class="col-3 ">
            <div class="form-label">Grade</div>
            <input type="text" value="{{ $karyawan->grade }}" class="form-control" name="grade" id="grade" placeholder="Grade">
        </div>
        <div class="col-3">
            <div class="form-label">Nomer Kontrak</div>
            <select name="no_kontrak_edit" id="no_kontrak_edit" class="form-select">
                @foreach ($contract as $d)
                    <option {{ $karyawan->no_kontrak == $d->no_kontrak ? 'selected' : '' }} value="{{ $d->no_kontrak }}">{{ $d->no_kontrak }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <div class="form-label">Employee Status</div>
            <select name="employee_status_edit" id="employee_status_edit" class="form-select">
            <option {{ $karyawan->employee_status == '' ? 'selected' : '' }} value="">Choose</option>
                <option {{ $karyawan->employee_status == 'PKWT' ? 'selected' : '' }} value="PKWT">PKWT</option>
                <option {{ $karyawan->employee_status == 'PKWTT' ? 'selected' : '' }} value="PKWTT">PKWTT</option>
            </select>
        </div>
        <div class="col-3">
            <div class="form-label">Base POH</div>
            <select name="base_poh" id="base_poh" class="form-select">
                <option value="">Pilih</option>
                @foreach ($location as $d)
                    <option {{ $karyawan->base_poh == $d->nama_kantor ? 'selected' : '' }} value="{{ $d->nama_kantor }}">{{ $d->nama_kantor }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <div class="form-label">Nama PT</div>
            <input type="text" value="{{ $karyawan->nama_pt }}" class="form-control" name="nama_pt" id="nama_pt" placeholder="Nama PT">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Sex</div>
            <input type="text" value="{{ $karyawan->sex }}" class="form-control" name="sex" id="sex" placeholder="Sex">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Marital Status</div>
            <input type="text" value="{{ $karyawan->marital_status }}" class="form-control" name="marital_status" id="marital_status" placeholder="Marital Status">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Birthplace</div>
            <input type="text" value="{{ $karyawan->birthplace }}" class="form-control" name="birthplace" id="birthplace" placeholder="Birthplace">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Religion</div>
            <input type="text" value="{{ $karyawan->religion }}" class="form-control" name="religion" id="religion" placeholder="Religion">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Status Karyawan</div>
            <select name="status_kar" id="status_kar" class="form-select">
                <option value="">Pilih Status</option>
                <option value="Aktif" {{ $karyawan->status_kar == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Non-Aktif" {{ $karyawan->status_kar == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
        </div>
    </div>
    <div class="row mt-3">
        <h5 class="modal-title"><u>Department Details</u></h5>
        <div class="col-3">
            <div class="form-label">Department</div>
            <select name="kode_dept" id="kode_dept" class="form-select">
                <option value="">Pilih</option>
                @foreach ($department as $d)
                <option {{ $karyawan->kode_dept == $d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <div class="form-label">Jabatan</div>
            <select name="jabatan" id="jabatan" class="form-select">
                <option value="">Pilih</option>
                @foreach ($jabatan as $d)
                <option {{ $karyawan->jabatan == $d->id ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->nama_jabatan }} - {{ $d->site }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mt-3">
        <h5 class="modal-title"><u>Address Karyawan</u></h5>
        <div class="col-3">
            <div class="form-label">Address</div>
            <input type="text" value="{{ $karyawan->address }}" class="form-control" name="address" id="address" placeholder="Address">
        </div>
        <div class="col-3">
            <div class="form-label">Address RT</div>
            <input type="text" value="{{ $karyawan->address_rt }}" class="form-control" name="address_rt" id="address_rt" placeholder="Address RT">
        </div>
        <div class="col-3">
            <div class="form-label">Address RW</div>
            <input type="text" value="{{ $karyawan->address_rw }}" class="form-control" name="address_rw" id="address_rw" placeholder="Address RW">
        </div>
        <div class="col-3">
            <div class="form-label">Address Kel</div>
            <input type="text" value="{{ $karyawan->address_kel }}" class="form-control" name="address_kel" id="address_kel" placeholder="Address Kel">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Address Kec</div>
            <input type="text" value="{{ $karyawan->address_kec }}" class="form-control" name="address_kec" id="address_kec" placeholder="Address Kec">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Address Kota</div>
            <input type="text" value="{{ $karyawan->address_kota }}" class="form-control" name="address_kota" id="address_kota" placeholder="Address Kota">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Address Prov</div>
            <input type="text" value="{{ $karyawan->address_prov }}" class="form-control" name="address_prov" id="address_prov" placeholder="Address Prov">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Kode Pos</div>
            <input type="text" value="{{ $karyawan->kode_pos }}" class="form-control" name="kode_pos" id="kode_pos" placeholder="Kode Pos">
        </div>
    </div>
    <div class="row mt-3">
        <h5 class="modal-title"><u>Education & Experience</u></h5>
        <div class="col-3">
            <div class="form-label">Gelar</div>
            <input type="text" value="{{ $karyawan->gelar }}" class="form-control" name="gelar" id="gelar" placeholder="Gelar">
        </div>
        <div class="col-3">
            <div class="form-label">Major</div>
            <input type="text" value="{{ $karyawan->major }}" class="form-control" name="major" id="major" placeholder="Major">
        </div>
        <div class="col-3">
            <div class="form-label">Kampus</div>
            <input type="text" value="{{ $karyawan->kampus }}" class="form-control" name="kampus" id="kampus" placeholder="Kampus">
        </div>
        <div class="col-3">
            <div class="form-label">Job Experience</div>
            <input type="text" value="{{ $karyawan->job_exp }}" class="form-control" name="job_exp" id="job_exp" placeholder="Job Experience">
        </div>
    </div>
    <div class="row mt-3">
        <h5 class="modal-title"><u>Data Pribadi</u></h5>
        <div class="col-3 mt-2">
            <div class="form-label">NIK KTP</div>
            <input type="text" value="{{ $karyawan->nik_ktp }}" class="form-control" name="nik_ktp" id="nik_ktp" placeholder="NIK KTP">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Blood Type</div>
            <input type="text" value="{{ $karyawan->blood_type }}" class="form-control" name="blood_type" id="blood_type" placeholder="Blood Type">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Email Personal</div>
            <input type="text" value="{{ $karyawan->email_personal }}" class="form-control" name="email_personal" id="email_personal" placeholder="Email Personal">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Family Card</div>
            <input type="text" value="{{ $karyawan->family_card }}" class="form-control" name="family_card" id="family_card" placeholder="Family Card">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">No NPWP</div>
            <input type="text" value="{{ $karyawan->no_npwp }}" class="form-control" name="no_npwp" id="no_npwp" placeholder="No NPWP">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Alamat NPWP</div>
            <input type="text" value="{{ $karyawan->alamat_npwp }}" class="form-control" name="alamat_npwp" id="alamat_npwp" placeholder="Alamat NPWP">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">BPJS TK</div>
            <input type="text" value="{{ $karyawan->bpjstk }}" class="form-control" name="bpjstk" id="bpjstk" placeholder="BPJS TK">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">BPJS Kes</div>
            <input type="text" value="{{ $karyawan->bpjskes }}" class="form-control" name="bpjskes" id="bpjskes" placeholder="BPJS Kes">
        </div>
    </div>
    <div class="row mt-3">
        <h5 class="modal-title"><u>Bank Detail</u></h5>
        <div class="col-3 mt-2">
            <div class="form-label">Rekening No</div>
            <input type="text" value="{{ $karyawan->rek_no }}" class="form-control" name="rek_no" id="rek_no" placeholder="Rekening No">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Bank Name</div>
            <input type="text" value="{{ $karyawan->bank_name }}" class="form-control" name="bank_name" id="bank_name" placeholder="Bank Name">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Rekening Name</div>
            <input type="text" value="{{ $karyawan->rek_name }}" class="form-control" name="rek_name" id="rek_name" placeholder="Rekening Name">
        </div>
    </div>
    <div class="row mt-3">
        <h5 class="modal-title"><u>Family Data</u></h5>
        <div class="col-3 mt-2">
            <div class="form-label">Father's Name</div>
            <input type="text" value="{{ $karyawan->father_name }}" class="form-control" name="father_name" id="father_name" placeholder="Father's Name">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Mother's Name</div>
            <input type="text" value="{{ $karyawan->mother_name }}" class="form-control" name="mother_name" id="mother_name" placeholder="Mother's Name">
        </div>
    </div>
    <div class="row">
        <div class="col-3 mt-2">
            <div class="form-label">Nama Pasangan</div>
            <input type="text" value="{{ $karyawan->fd_si_name }}" class="form-control" name="fd_si_name" id="fd_si_name" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">NIK Pasangan</div>
            <input type="text" value="{{ $karyawan->fd_si_nik }}" class="form-control" name="fd_si_nik" id="fd_si_nik" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Kota Lahir Pasangan</div>
            <input type="text" value="{{ $karyawan->fd_si_kota }}" class="form-control" name="fd_si_kota" id="fd_si_kota" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">DOB Pasangan</div>
            <input type="date" value="{{ $karyawan->fd_si_dob }}" class="form-control" name="fd_si_dob" id="fd_si_dob" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Nama Anak Pertama</div>
            <input type="text" value="{{ $karyawan->fd_anak1_name }}" class="form-control" name="fd_anak1_name" id="fd_anak1_name" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">NIK Anak Pertama</div>
            <input type="text" value="{{ $karyawan->fd_anak1_nik }}" class="form-control" name="fd_anak1_nik" id="fd_anak1_nik" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Kota Lahir Anak Pertama</div>
            <input type="text" value="{{ $karyawan->fd_anak1_kota }}" class="form-control" name="fd_anak1_kota" id="fd_anak1_kota" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">DOB Anak Pertama</div>
            <input type="date" value="{{ $karyawan->fd_anak1_dob }}" class="form-control" name="fd_anak1_dob" id="fd_anak1_dob" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Nama Anak Kedua</div>
            <input type="text" value="{{ $karyawan->fd_anak2_name }}" class="form-control" name="fd_anak2_name" id="fd_anak2_name" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">NIK Anak Kedua</div>
            <input type="text" value="{{ $karyawan->fd_anak2_nik }}" class="form-control" name="fd_anak2_nik" id="fd_anak2_nik" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Kota Lahir Anak Kedua</div>
            <input type="text" value="{{ $karyawan->fd_anak2_kota }}" class="form-control" name="fd_anak2_kota" id="fd_anak2_kota" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">DOB Anak Kedua</div>
            <input type="date" value="{{ $karyawan->fd_anak2_dob }}" class="form-control" name="fd_anak2_dob" id="fd_anak2_dob" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Nama Anak Ketiga</div>
            <input type="text" value="{{ $karyawan->fd_anak3_name }}" class="form-control" name="fd_anak3_name" id="fd_anak3_name" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">NIK Anak Kedua</div>
            <input type="text" value="{{ $karyawan->fd_anak3_nik }}" class="form-control" name="fd_anak3_nik" id="fd_anak3_nik" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Kota Lahir Anak Ketiga</div>
            <input type="text" value="{{ $karyawan->fd_anak3_nik }}" class="form-control" name="fd_anak3_kota" id="fd_anak3_kota" placeholder="Family Data Anak">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">DOB Anak Ketiga</div>
            <input type="date" value="{{ $karyawan->fd_anak3_dob }}" class="form-control" name="fd_anak3_dob" id="fd_anak3_dob" placeholder="Family Data Anak">
        </div>
    </div>
    <div class="row mt-3">
        <h5 class="modal-title"><u>Emergency Contact</u></h5>
        <div class="col-3 mt-2">
            <div class="form-label">Emergency Contact Name</div>
            <input type="text" value="{{ $karyawan->em_name }}" class="form-control" name="em_name" id="em_name" placeholder="Emergency Contact Name">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Emergency Contact Phone</div>
            <input type="text" value="{{ $karyawan->em_telp }}" class="form-control" name="em_telp" id="em_telp" placeholder="Emergency Contact Phone">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Emergency Contact Relation</div>
            <input type="text" value="{{ $karyawan->em_relation }}" class="form-control" name="em_relation" id="em_relation" placeholder="Emergency Contact Relation">
        </div>
        <div class="col-3 mt-2">
            <div class="form-label">Emergency Contact Address</div>
            <input type="text" value="{{ $karyawan->em_alamat }}" class="form-control" name="em_alamat" id="em_alamat" placeholder="Emergency Contact Address">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
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
