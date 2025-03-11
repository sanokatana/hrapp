<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2>A. IDENTITAS</h2>
                        <input type="hidden" id="candidate_id" name="candidate_id" value="{{$candidateId}}">

                        <!-- Nama Lengkap -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Nama Lengkap</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->nama_lengkap : '' }}"
                                    class="form-control" name="nama_lengkap" id="nama_lengkap"
                                    placeholder="Nama Lengkap" readonly>
                            </div>
                        </div>

                        <!-- Nama Panggilan -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Nama Kecil/Panggilan</label>
                            <div class="col-md-9">
                                <input type="text"
                                    value="{{ $candidateData ? $candidateData->nama_panggilan : '' }}"
                                    class="form-control" name="nama_panggilan" id="nama_panggilan"
                                    placeholder="Nama Panggilan" readonly>
                            </div>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Jenis Kelamin</label>
                            <div class="col-md-9">
                                <select class="form-select" name="jenis" disabled>
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki" {{ (isset($candidateData) && $candidateData->jenis == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ (isset($candidateData) && $candidateData->jenis == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>


                        <!-- Golongan Darah -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Golongan Darah</label>
                            <div class="col-md-9">
                                <select class="form-select" name="gol_darah" disabled>
                                    <option value="">Pilih</option>
                                    <option value="A" {{ (isset($candidateData) && $candidateData->gol_darah == 'A') ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ (isset($candidateData) && $candidateData->gol_darah == 'B') ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ (isset($candidateData) && $candidateData->gol_darah == 'AB') ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ (isset($candidateData) && $candidateData->gol_darah == 'O') ? 'selected' : '' }}>O</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tempat/Tgl Lahir -->

                        <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Tempat Lahir</label>
                                <div class="col-md-9">
                                    <input type="text" value="{{ $candidateData ? $candidateData->tempat_lahir : '' }}" class="form-control" name="tempat_lahir">
                                </div>
                            </div>


                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Tgl Lahir</label>
                            <div class="col-md-9">
                                <input type="date" value="{{ $candidateData ? $candidateData->tgl_lahir : '' }}"
                                    class="form-control" name="tgl_lahir" readonly>
                            </div>
                        </div>

                        <!-- Warga Negara -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Warga Negara</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->warga_negara : '' }}"
                                    class="form-control" name="warga_negara" placeholder="Warga Negara" readonly>
                            </div>
                        </div>

                        <!-- Alamat Rumah -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Alamat Rumah</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="alamat_rumah" rows="2"
                                    placeholder="Alamat Rumah"
                                    readonly>{{ $candidateData ? $candidateData->alamat_rumah : '' }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Alamat RT</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->alamat_rt : '' }}"
                                    class="form-control" name="alamat_rt" placeholder="Warga alamat_rt" readonly>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Alamat RW</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->alamat_rw : '' }}"
                                    class="form-control" name="alamat_rw" placeholder="Warga Negara" readonly>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Kelurahan</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->alamat_kel : '' }}"
                                    class="form-control" name="alamat_kel" placeholder="Warga Negara" readonly>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Kecamatan</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->alamat_kec : '' }}"
                                    class="form-control" name="alamat_kec" placeholder="Warga Negara" readonly>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Kota</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->alamat_kota : '' }}"
                                    class="form-control" name="alamat_kota" placeholder="Warga Negara" readonly>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Provinsi</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->alamat_prov : '' }}"
                                    class="form-control" name="alamat_prov" placeholder="Warga Negara" readonly>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">POS</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->alamat_pos : '' }}"
                                    class="form-control" name="alamat_pos" placeholder="Warga Negara" readonly>
                            </div>
                        </div>

                        <!-- Telpon Rumah/HP -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Telpon Rumah/HP</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->telp_rumah_hp : '' }}"
                                    class="form-control" name="telp_rumah_hp" placeholder="Telpon Rumah/HP"
                                    readonly>
                            </div>
                        </div>

                        <!-- No. KTP/SIM -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">No. KTP/SIM</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->no_ktp_sim : '' }}"
                                    class="form-control" name="no_ktp_sim" placeholder="No. KTP/SIM" readonly>
                            </div>
                        </div>

                        <!-- Tgl Berlaku KTP/SIM -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Tgl Berlaku KTP/SIM</label>
                            <div class="col-md-9">
                                <input type="date" value="{{ $candidateData ? $candidateData->tgl_ktp_sim : '' }}"
                                    class="form-control" name="tgl_ktp_sim" readonly>
                            </div>
                        </div>

                        <!-- Alamat Email -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Alamat Email Pribadi</label>
                            <div class="col-md-9">
                                <input type="email" value="{{ $candidateData ? $candidateData->alamat_email : '' }}"
                                    class="form-control" name="alamat_email" placeholder="Alamat Email" readonly>
                            </div>
                        </div>

                        <!-- Add more form sections as needed -->

                        <h2 class="mt-4">B. KELUARGA & LINGKUNGAN</h2>

                        <h5 class="mt-4">1. Susunan Keluarga (Ayah, Ibu dan Saudara Kandung termasuk Saudara)</h5>
                        <div class="row mb-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>Uraian Keluarga</th>
                                            <th>Nama Lengkap</th>
                                            <th>L/P</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Pendidikan</th>
                                            <th>Pekerjaan</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($candidateFamilyDataSendiri as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $d->uraian}}</td>
                                            <td>{{ $d->nama_lengkap}}</td>
                                            <td>{{ $d->jenis}}</td>
                                            <td>{{ $d->tgl_lahir}}</td>
                                            <td>{{ $d->pendidikan}}</td>
                                            <td>{{ $d->pekerjaan}}</td>
                                            <td>{{ $d->keterangan}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <h2 class="mt-5">C. PENDIDIKAN</h2>

                        <div class="row mb-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>Tingkat Besar</th>
                                            <th>Nama Sekolah</th>
                                            <th>Tempat Sekolah</th>
                                            <th>Jurusan Studi</th>
                                            <th>Dari</th>
                                            <th>Sampai</th>
                                            <th>Berijazah (Thn)</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($candidatePendidikan as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $d->tingkat_besar}}</td>
                                            <td>{{ $d->nama_sekolah}}</td>
                                            <td>{{ $d->tempat_sekolah}}</td>
                                            <td>{{ $d->jurusan_studi}}</td>
                                            <td>{{ $d->dari}}</td>
                                            <td>{{ $d->sampai}}</td>
                                            <td>{{ $d->berijazah}}</td>
                                            <td>{{ $d->keterangan}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-5 col-form-label">Apakah Saudara masih melanjutkan pendidikan ?
                            </label>
                            <div class="col-md-7">
                                <select class="form-select" name="melanjut_pendidikan" id="melanjut_pendidikan"
                                    disabled>
                                    <option value="">Pilih</option>
                                    <option value="Ya" {{ (isset($candidateData) && $candidateData->melanjut_pendidikan == 'Ya') ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ (isset($candidateData) && $candidateData->melanjut_pendidikan == 'Tidak') ? 'selected' : '' }}>Tidak
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Siapa dan Berapa Tanggungan Fields -->
                        <div id="additional_pendidikan">
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-5 col-form-label">Sebutkan pendidikan apa dan kapan waktunya (
                                    hari / jam ) </label>
                                <div class="col-md-7">
                                    <input type="text"
                                        value="{{ $candidateData ? $candidateData->penjelasan_pendidikan : '' }}"
                                        class="form-control" name="penjelasan_pendidikan" placeholder="" readonly>
                                </div>
                            </div>
                        </div>

                        <h2 class="mt-5">D. KURSUS / TRAINING (isikan dari urutan yang terbaru)</h2>
                        <div class="row mb-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>Nama</th>
                                            <th>Diadakan Oleh</th>
                                            <th>Tempat</th>
                                            <th>Lama</th>
                                            <th>Tahun</th>
                                            <th>Dibiayai Oleh</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="kursus_table_body">
                                        @foreach ($candidateKursus as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $d->nama}}</td>
                                            <td>{{ $d->diadakan_oleh}}</td>
                                            <td>{{ $d->tempat}}</td>
                                            <td>{{ $d->lama}}</td>
                                            <td>{{ $d->tahun}}</td>
                                            <td>{{ $d->dibiayai_oleh}}</td>
                                            <td>{{ $d->keterangan}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <!-- Bahasa dan Keterampilan Section -->
                        <h2 class="mt-4">E. PENGUASAAN BAHASA DAN KETERAMPILAN</h2>
                        <h5>Pilih Baik, Cukup, Kurang</h5>

                        <div class="row mb-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>Bahasa</th>
                                            <th>Bicara</th>
                                            <th>Baca</th>
                                            <th>Tulis</th>
                                            <th>Mengetik Steno WPM</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bahasa_table_body">
                                        @foreach ($candidateBahasa as $d)
                                        <tr style="text-align: center;">
                                            <td>{{ $d->bahasa}}</td>
                                            <td>{{ $d->bicara}}</td>
                                            <td>{{ $d->baca}}</td>
                                            <td>{{ $d->tulis}}</td>
                                            <td>{{ $d->steno}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <h2 class="mt-4">G. MINAT DAN KONSEP PRIBADI</h2>
                        <h5>HAL-HAL LAIN YANG BERHUBUNGAN DENGAN LAMARAN SAUDARA.
                            Berikan nomor secara berurutan bagian/jenis macam pekerjaan yang anda senangi :
                        </h5>

                        <div class="row mb-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Jenis Pekerjaan</th>
                                            <th>Nomor</th>
                                            <th>Jenis Pekerjaan</th>
                                            <th>Nomor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Engineering</td>
                                            <td><input class="form-control"
                                                    value="{{ $candidateData ? $candidateData->engineering_no : '' }}"
                                                    type="number" name="engineering_no" readonly>
                                            </td>
                                            <td>Accounting</td>
                                            <td><input class="form-control"
                                                    value="{{ $candidateData ? $candidateData->accounting_no : '' }}"
                                                    type="number" name="accounting_no" readonly></td>
                                        </tr>
                                        <tr>
                                            <td>Geologist</td>
                                            <td><input class="form-control"
                                                    value="{{ $candidateData ? $candidateData->geologist_no : '' }}"
                                                    type="number" name="geologist_no" readonly></td>
                                            <td>Administration</td>
                                            <td><input class="form-control"
                                                    value="{{ $candidateData ? $candidateData->administration_no : '' }}"
                                                    type="number" name="administration_no" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Agronomist</td>
                                            <td><input class="form-control"
                                                    value="{{ $candidateData ? $candidateData->agronomist_no : '' }}"
                                                    type="number" name="agronomist_no" readonly></td>
                                            <td>General Affair</td>
                                            <td><input class="form-control"
                                                    value="{{ $candidateData ? $candidateData->ga_no : '' }}"
                                                    type="number" name="ga_no" readonly></td>
                                        </tr>
                                        <tr>
                                            <td>Consultant/Riset</td>
                                            <td><input class="form-control"
                                                    value="{{ $candidateData ? $candidateData->consultant_no : '' }}"
                                                    type="number" name="consultant_no" readonly></td>
                                            <td>Personnel</td>
                                            <td><input class="form-control"
                                                    value="{{ $candidateData ? $candidateData->personnel_no : '' }}"
                                                    type="number" name="personnel_no" readonly></td>
                                        </tr>
                                        <tr>
                                            <td>Cashier</td>
                                            <td><input class="form-control"
                                                    value="{{ $candidateData ? $candidateData->cashier_no : '' }}"
                                                    type="number" name="cashier_no" readonly></td>
                                            <td>Finance</td>
                                            <td><input class="form-control"
                                                    value="{{ $candidateData ? $candidateData->finance_no : '' }}"
                                                    type="number" name="finance_no" readonly></td>
                                        </tr>
                                        <tr>
                                            <td>Humas</td>
                                            <td><input class="form-control"
                                                    value="{{ $candidateData ? $candidateData->humas_no : '' }}"
                                                    type="number" name="humas_no" readonly></td>
                                            <td>Driver</td>
                                            <td><input class="form-control"
                                                    value="{{ $candidateData ? $candidateData->driver_no : '' }}"
                                                    type="number" name="driver_no" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-12 col-form-label">1. Pernahkah Saudara melamar pekerjaan di
                                Perusahaan kami ? </label>
                            <div class="col-md-12">
                                <textarea class="form-control" name="saudara_pekerjaan" rows="2" placeholder=""
                                    readonly>{{ $candidateData ? $candidateData->saudara_pekerjaan : '' }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-12 col-form-label">2. Organisasi – organisasi apakah yang pernah
                                Saudara masuki ? Sebutkan jabatan – jabatan yang pernah Anda pegang </label>
                            <div class="col-md-12">
                                <textarea class="form-control" name="organisasi" rows="2" placeholder=""
                                    readonly>{{ $candidateData ? $candidateData->organisasi : '' }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-12 col-form-label">3. Dalam keadaan darurat, siapakah yang dapat
                                dihubungi ? Sebutkan nama, alamat, telpon serta apa hubungannya Saudara dengan nama
                                tersebut ?</label>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Nama</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->em_nama : '' }}"
                                    class="form-control" name="em_nama" readonly>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Alamat</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->em_alamat : '' }}"
                                    class="form-control" name="em_alamat" readonly>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Telpon</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->em_telp : '' }}"
                                    class="form-control" name="em_telp" readonly>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Hubungan</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $candidateData ? $candidateData->em_status : '' }}"
                                    class="form-control" name="em_status" readonly>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-12 col-form-label">4. Sebutkan dua nama sebagai referensi Saudara
                                dalam hal ini (yang mengetahui tentang Anda)</label>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-4 col-form-label">Referensi 1</label>
                            <div class="col-md-8">
                                <input type="text"
                                    value="{{ $candidateData ? $candidateData->nama_referensi1 : '' }}"
                                    class="form-control" name="nama_referensi1" readonly>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-4 col-form-label">Referensi 2</label>
                            <div class="col-md-8">
                                <input type="text"
                                    value="{{ $candidateData ? $candidateData->nama_referensi2 : '' }}"
                                    class="form-control" name="nama_referensi2" readonly>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-12 col-form-label">5. Apakah Saudara pernah menderita sakit yang
                                lama ? </label>
                            <div class="col-md-12">
                                <textarea class="form-control" name="sakit_lama" rows="2" placeholder=""
                                    readonly>{{ $candidateData ? $candidateData->sakit_lama : '' }}</textarea>
                            </div>
                        </div>

                        <h2 class="mt-4">I. LAIN – LAIN</h2>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-5 col-form-label">3. Kapankah Saudara dapat mulai bekerja di
                                perusahaan kami ? </label>
                            <div class="col-md-7">
                                <input class="form-control"
                                    value="{{ $candidateData ? $candidateData->mulai_kerja : '' }}" type="DATE"
                                    id="mulai_kerja" name="mulai_kerja" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        <!-- File Upload Section -->
                        <h2 class="mt-4">PERLENGKAPAN DATA</h2>
                        <div class="row mb-4">
                            @if($candidateDataLengkap && $candidateDataLengkap->photo_ktp && $candidateDataLengkap->photo_ktp !== 'No_Document' )
                            <!-- If there's a file uploaded, show it as a clickable link -->
                            <div class="col-4">
                                <h3>Photo KTP</h3>
                                <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_ktp) }}" target="_blank" class="btn btn-info btn-block w-100">
                                    Lihat File
                                </a>
                            </div>
                            @else
                            <!-- If there's no file uploaded, show the upload input -->
                            <div class="col-4">
                                <h3>KTP</h3>
                                <input class="form-control" type="file" id="photo_ktp" name="photo_ktp">
                            </div>
                            @endif
                            @if($candidateDataLengkap && $candidateDataLengkap->photo_kk && $candidateDataLengkap->photo_kk !== 'No_Document')
                            <!-- If there's a file uploaded, show it as a clickable link -->
                            <div class="col-4">
                                <h3>Photo Kartu Keluarga</h3>
                                <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_kk) }}" target="_blank" class="btn btn-info btn-block w-100">
                                    Lihat File
                                </a>
                            </div>
                            @else
                            <!-- If there's no file uploaded, show the upload input -->
                            <div class="col-4">
                                <h3>Kartu Keluarga</h3>
                                <input class="form-control" type="file" id="photo_kk" name="photo_kk">
                            </div>
                            @endif
                            @if($candidateDataLengkap && $candidateDataLengkap->photo_sim && $candidateDataLengkap->photo_sim !== 'No_Document')
                            <!-- If there's a file uploaded, show it as a clickable link -->
                            <div class="col-4">
                                <h3>Photo SIM</h3>
                                <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_sim) }}" target="_blank" class="btn btn-info btn-block w-100">
                                    Lihat File
                                </a>
                            </div>
                            @else
                            <!-- If there's no file uploaded, show the upload input -->
                            <div class="col-4">
                                <h3>SIM</h3>
                                <input class="form-control" type="file" id="photo_sim" name="photo_sim">
                            </div>
                            @endif
                        </div>
                        <div class="row mb-4">
                            @if($candidateDataLengkap && $candidateDataLengkap->photo_npwp && $candidateDataLengkap->photo_npwp !== 'No_Document')
                            <!-- If there's a file uploaded, show it as a clickable link -->
                            <div class="col-4">
                                <h3>Photo NPWP</h3>
                                <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_npwp) }}" target="_blank" class="btn btn-info btn-block w-100">
                                    Lihat File
                                </a>
                            </div>
                            @else
                            <!-- If there's no file uploaded, show the upload input -->
                            <div class="col-4">
                                <h3>NPWP</h3>
                                <input class="form-control" type="file" id="photo_npwp" name="photo_npwp">
                            </div>
                            @endif
                            @if($candidateDataLengkap && $candidateDataLengkap->photo_ijazah && $candidateDataLengkap->photo_ijazah !== 'No_Document')
                            <!-- If there's a file uploaded, show it as a clickable link -->
                            <div class="col-4">
                                <h3>Photo Ijazah</h3>
                                <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_ijazah) }}" target="_blank" class="btn btn-info btn-block w-100">
                                    Lihat File
                                </a>
                            </div>
                            @else
                            <!-- If there's no file uploaded, show the upload input -->
                            <div class="col-4">
                                <h3>Ijazah</h3>
                                <input class="form-control" type="file" id="photo_ijazah" name="photo_ijazah">
                            </div>
                            @endif
                            @if($candidateDataLengkap && $candidateDataLengkap->photo_anda && $candidateDataLengkap->photo_anda !== 'No_Document')
                            <!-- If there's a file uploaded, show it as a clickable link -->
                            <div class="col-4">
                                <h3>Photo Anda</h3>
                                <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_anda) }}" target="_blank" class="btn btn-info btn-block w-100">
                                    Lihat File
                                </a>
                            </div>
                            @else
                            <!-- If there's no file uploaded, show the upload input -->
                            <div class="col-4">
                                <h3>Photo Anda</h3>
                                <input class="form-control" type="file" id="photo_anda" name="photo_anda">
                            </div>
                            @endif
                        </div>

                        @if($keluargaData->isNotEmpty())
                        <div class="card mt-4">
                            <div class="card-body">
                                <div class="col-12 table-responsive">
                                    <table class="table table-vcenter card-table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Uraian</th>
                                                <th>Nama</th>
                                                <th>Jenis</th>
                                                <th>NIK</th>
                                                <th>Tempat Lahir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($keluargaData as $d)
                                            <tr>
                                                <td>{{ $d->uraian }}</td>
                                                <td>{{ $d->nama_lengkap }}</td>
                                                <td>{{ $d->jenis }}</td>
                                                <td>
                                                    <input type="text" name="nik[]" class="form-control" value="{{ $d->nik }}">
                                                    <input type="hidden" name="keluarga_id[]" value="{{ $d->id }}">
                                                </td>
                                                <td><input type="text" name="tempat_lahir[]" class="form-control" value="{{ $d->tempat_lahir }}"></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
