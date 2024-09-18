@extends('layouts.candidate.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Dashboard
                </div>
                <h2 class="page-title">
                    Candidate Data
                </h2>
                <br>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2>A. IDENTITAS</h2>

                        <!-- Nama Lengkap -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Nama Lengkap</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama_lengkap" placeholder="Nama Lengkap">
                            </div>
                        </div>

                        <!-- Nama Panggilan -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Nama Kecil/Panggilan</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama_panggilan" placeholder="Nama Panggilan">
                            </div>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Jenis Kelamin</label>
                            <div class="col-md-9">
                                <select class="form-select" name="jenis">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Golongan Darah -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Golongan Darah</label>
                            <div class="col-md-9">
                                <select class="form-select" name="gol_darah">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tempat/Tgl Lahir -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Tempat/Tgl Lahir</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="tgl_lahir">
                            </div>
                        </div>

                        <!-- Warga Negara -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Warga Negara</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="warga_negara" placeholder="Warga Negara">
                            </div>
                        </div>

                        <!-- Alamat Rumah -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Alamat Rumah</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="alamat_rumah" rows="2" placeholder="Alamat Rumah"></textarea>
                            </div>
                        </div>

                        <!-- Telpon Rumah/HP -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Telpon Rumah/HP</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="telp_rumah_hp" placeholder="Telpon Rumah/HP">
                            </div>
                        </div>

                        <!-- No. KTP/SIM -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">No. KTP/SIM</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="no_ktp_sim" placeholder="No. KTP/SIM">
                            </div>
                        </div>

                        <!-- Tgl Berlaku KTP/SIM -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Tgl Berlaku KTP/SIM</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="tgl_ktp_sim">
                            </div>
                        </div>

                        <!-- No. NPWP -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">No. NPWP</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="no_npwp" placeholder="No. NPWP">
                            </div>
                        </div>

                        <!-- Alamat NPWP -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Alamat NPWP</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="alamat_npwp" rows="2" placeholder="Alamat NPWP"></textarea>
                            </div>
                        </div>

                        <!-- Status Keluarga -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Status Keluarga</label>
                            <div class="col-md-9">
                                <select class="form-select" name="status_keluarga" id="status_keluarga">
                                    <option value="TK">TK (Tidak Kawin)</option>
                                    <option value="TK1">TK1</option>
                                    <option value="TK2">TK2</option>
                                    <option value="TK3">TK3</option>
                                    <option value="M">M (Menikah)</option>
                                    <option value="M1">M1</option>
                                    <option value="M2">M2</option>
                                    <option value="M3">M3</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal Menikah -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Tanggal Menikah</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="tgl_menikah">
                            </div>
                        </div>

                        <!-- Jabatan Saat Ini -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Jabatan saat ini</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="jabatan" placeholder="Jabatan saat ini">
                            </div>
                        </div>

                        <!-- Nama Perusahaan -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Nama Perusahaan</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama_perusahaan" placeholder="Nama Perusahaan">
                            </div>
                        </div>

                        <!-- Alamat Perusahaan -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Alamat Perusahaan</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="alamat_perusahaan" rows="2" placeholder="Alamat Perusahaan"></textarea>
                            </div>
                        </div>

                        <!-- Alamat Email -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-3 col-form-label">Alamat Email</label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" placeholder="Alamat Email">
                            </div>
                        </div>

                        <!-- Add more form sections as needed -->

                        <h2 class="mt-4">B. KELUARGA & LINGKUNGAN</h2>


                        <h5 id="table_keluarga">1. Susunan Keluarga (Suami/Istri dan anak – anak)</h5>
                        <div class="row" id="table_keluarga">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Uraian Keluarga</th>
                                            <th>Nama Lengkap</th>
                                            <th>L/P</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Pendidikan</th>
                                            <th>Pekerjaan</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="family_table_body">
                                        <tr id="row_istri">
                                            <td>Istri/Suami</td>
                                            <td><input class="form-control" type="text" name="nama_istri_suami"></td>
                                            <td><input class="form-control" type="text" name="lp_istri_suami"></td>
                                            <td><input class="form-control" type="date" name="dob_istri_suami"></td>
                                            <td><input class="form-control" type="text" name="pendidikan_istri_suami"></td>
                                            <td><input class="form-control" type="text" name="pekerjaan_istri_suami"></td>
                                            <td><input class="form-control" type="text" name="keterangan_istri_suami"></td>
                                        </tr>
                                        <tr id="row_anak1">
                                            <td>Anak ke 1</td>
                                            <td><input class="form-control" type="text" name="nama_anak1"></td>
                                            <td><input class="form-control" type="text" name="lp_anak1"></td>
                                            <td><input class="form-control" type="date" name="dob_anak1"></td>
                                            <td><input class="form-control" type="text" name="pendidikan_anak1"></td>
                                            <td><input class="form-control" type="text" name="pekerjaan_anak1"></td>
                                            <td><input class="form-control" type="text" name="keterangan_anak1"></td>
                                        </tr>
                                        <tr id="row_anak2">
                                            <td>Anak ke 2</td>
                                            <td><input class="form-control" type="text" name="nama_anak2"></td>
                                            <td><input class="form-control" type="text" name="lp_anak2"></td>
                                            <td><input class="form-control" type="date" name="dob_anak2"></td>
                                            <td><input class="form-control" type="text" name="pendidikan_anak2"></td>
                                            <td><input class="form-control" type="text" name="pekerjaan_anak2"></td>
                                            <td><input class="form-control" type="text" name="keterangan_anak2"></td>
                                        </tr>
                                        <tr id="row_anak3">
                                            <td>Anak ke 3</td>
                                            <td><input class="form-control" type="text" name="nama_anak3"></td>
                                            <td><input class="form-control" type="text" name="lp_anak3"></td>
                                            <td><input class="form-control" type="date" name="dob_anak3"></td>
                                            <td><input class="form-control" type="text" name="pendidikan_anak3"></td>
                                            <td><input class="form-control" type="text" name="pekerjaan_anak3"></td>
                                            <td><input class="form-control" type="text" name="keterangan_anak3"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <h5 class="mt-4">2. Susunan Keluarga (Ayah, Ibu dan Saudara Kandung termasuk Saudara)</h5>
                        <div class="row mb-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Uraian Keluarga</th>
                                            <th>Nama Lengkap</th>
                                            <th>L/P</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Pendidikan</th>
                                            <th>Pekerjaan</th>
                                            <th>Keterangan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="family_table_body_2">
                                        <tr>
                                            <td>Ayah</td>
                                            <td><input class="form-control" type="text" name="nama_ayah"></td>
                                            <td><input class="form-control" type="text" name="lp_ayah"></td>
                                            <td><input class="form-control" type="date" name="dob_ayah"></td>
                                            <td><input class="form-control" type="text" name="pendidikan_ayah"></td>
                                            <td><input class="form-control" type="text" name="pekerjaan_ayah"></td>
                                            <td><input class="form-control" type="text" name="keterangan_ayah"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Ibu</td>
                                            <td><input class="form-control" type="text" name="nama_ibu"></td>
                                            <td><input class="form-control" type="text" name="lp_ibu"></td>
                                            <td><input class="form-control" type="date" name="dob_ibu"></td>
                                            <td><input class="form-control" type="text" name="pendidikan_ibu"></td>
                                            <td><input class="form-control" type="text" name="pekerjaan_ibu"></td>
                                            <td><input class="form-control" type="text" name="keterangan_ibu"></td>
                                            <td></td>
                                        </tr>
                                        <tr id="row_anak1">
                                            <td>Anak ke 1</td>
                                            <td><input class="form-control" type="text" name="nama_anak1"></td>
                                            <td><input class="form-control" type="text" name="lp_anak1"></td>
                                            <td><input class="form-control" type="date" name="dob_anak1"></td>
                                            <td><input class="form-control" type="text" name="pendidikan_anak1"></td>
                                            <td><input class="form-control" type="text" name="pekerjaan_anak1"></td>
                                            <td><input class="form-control" type="text" name="keterangan_anak1"></td>
                                            <td><button type="button" id="add_sibling_btn" class="btn btn-sm btn-success">+</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Apakah Saudara mempunyai tanggung jawab lain selain anak & istri ? -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-5 col-form-label">Apakah Saudara mempunyai tanggung jawab lain selain anak & istri ?</label>
                            <div class="col-md-7">
                                <select class="form-select" name="tanggung_jawab" id="tanggung_jawab">
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Siapa dan Berapa Tanggungan Fields -->
                        <div id="additional_tanggungan" style="display: none;">
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-5 col-form-label">Siapa dan berapa besar tanggungan?</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" name="siapa_tanggungan" placeholder="Siapa">
                                    <input type="number" class="form-control mt-2" name="besar_tanggungan" placeholder="Rp. ....... / bulan">
                                </div>
                            </div>
                        </div>

                        <!-- Status Rumah -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-5 col-form-label">Apakah rumah status yang Saudara tempati saat ini:</label>
                            <div class="col-md-7">
                                <select class="form-select" name="status_rumah">
                                    <option value="Rumah Pribadi">Rumah Pribadi</option>
                                    <option value="Orang Tua">Orang Tua</option>
                                    <option value="Kontrak">Kontrak</option>
                                    <option value="Lain-lain">Lain-lain</option>
                                </select>
                            </div>
                        </div>

                        <h2 class="mt-5">C. PENDIDIKAN</h2>

                        <div class="row mb-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
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
                                    <tbody id="family_table_body_2">
                                        <tr>
                                            <td name="Dasar">Dasar</td>
                                            <td><input class="form-control" type="text" name="nama_sekolah"></td>
                                            <td><input class="form-control" type="text" name="tempat_sekolah"></td>
                                            <td><input class="form-control" type="text" name="jurusan_studi"></td>
                                            <td><input class="form-control" type="date" name="dari"></td>
                                            <td><input class="form-control" type="date" name="sampai"></td>
                                            <td><input class="form-control" type="text" name="berijazah"></td>
                                            <td><input class="form-control" type="text" name="keterangan"></td>
                                        </tr>
                                        <tr>
                                            <td name="SLTP">SLTP</td>
                                            <td><input class="form-control" type="text" name="nama_sekolah"></td>
                                            <td><input class="form-control" type="text" name="tempat_sekolah"></td>
                                            <td><input class="form-control" type="text" name="jurusan_studi"></td>
                                            <td><input class="form-control" type="date" name="dari"></td>
                                            <td><input class="form-control" type="date" name="sampai"></td>
                                            <td><input class="form-control" type="text" name="berijazah"></td>
                                            <td><input class="form-control" type="text" name="keterangan"></td>
                                        </tr>
                                        <tr>
                                            <td name="SLTA">SLTA</td>
                                            <td><input class="form-control" type="text" name="nama_sekolah"></td>
                                            <td><input class="form-control" type="text" name="tempat_sekolah"></td>
                                            <td><input class="form-control" type="text" name="jurusan_studi"></td>
                                            <td><input class="form-control" type="date" name="dari"></td>
                                            <td><input class="form-control" type="date" name="sampai"></td>
                                            <td><input class="form-control" type="text" name="berijazah"></td>
                                            <td><input class="form-control" type="text" name="keterangan"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td name="Diploma">Diploma</td>
                                            <td><input class="form-control" type="text" name="nama_sekolah"></td>
                                            <td><input class="form-control" type="text" name="tempat_sekolah"></td>
                                            <td><input class="form-control" type="text" name="jurusan_studi"></td>
                                            <td><input class="form-control" type="date" name="dari"></td>
                                            <td><input class="form-control" type="date" name="sampai"></td>
                                            <td><input class="form-control" type="text" name="berijazah"></td>
                                            <td><input class="form-control" type="text" name="keterangan"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td name="Strata I">Strata I</td>
                                            <td><input class="form-control" type="text" name="nama_sekolah"></td>
                                            <td><input class="form-control" type="text" name="tempat_sekolah"></td>
                                            <td><input class="form-control" type="text" name="jurusan_studi"></td>
                                            <td><input class="form-control" type="date" name="dari"></td>
                                            <td><input class="form-control" type="date" name="sampai"></td>
                                            <td><input class="form-control" type="text" name="berijazah"></td>
                                            <td><input class="form-control" type="text" name="keterangan"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td name="Strata II">Strata II</td>
                                            <td><input class="form-control" type="text" name="nama_sekolah"></td>
                                            <td><input class="form-control" type="text" name="tempat_sekolah"></td>
                                            <td><input class="form-control" type="text" name="jurusan_studi"></td>
                                            <td><input class="form-control" type="date" name="dari"></td>
                                            <td><input class="form-control" type="date" name="sampai"></td>
                                            <td><input class="form-control" type="text" name="berijazah"></td>
                                            <td><input class="form-control" type="text" name="keterangan"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td name="Lain-Lain">Lain-Lain</td>
                                            <td><input class="form-control" type="text" name="nama_sekolah"></td>
                                            <td><input class="form-control" type="text" name="tempat_sekolah"></td>
                                            <td><input class="form-control" type="text" name="jurusan_studi"></td>
                                            <td><input class="form-control" type="date" name="dari"></td>
                                            <td><input class="form-control" type="date" name="sampai"></td>
                                            <td><input class="form-control" type="text" name="berijazah"></td>
                                            <td><input class="form-control" type="text" name="keterangan"></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-md-5 col-form-label">Apakah Saudara masih melanjutkan pendidikan ? </label>
                            <div class="col-md-7">
                                <select class="form-select" name="melanjut_pendidikan" id="melanjut_pendidikan">
                                    <option value="">Pilih</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                        </div>

                        <!-- Siapa dan Berapa Tanggungan Fields -->
                        <div id="additional_pendidikan" style="display: none;">
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-5 col-form-label">Sebutkan pendidikan apa dan kapan waktunya ( hari / jam ) </label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" name="penjelasan_pendidikan" placeholder="Penjelasan">
                                </div>
                            </div>
                        </div>

                        <h2 class="mt-5">D. KURSUS / TRAINING (isikan dari urutan yang terbaru)</h2>
                        <div class="row mb-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Diadakan Oleh</th>
                                            <th>Tempat</th>
                                            <th>Lama</th>
                                            <th>Tahun</th>
                                            <th>Dibiayai Oleh</th>
                                            <th>Keterangan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="kursus_table_body">
                                        <tr>
                                            <td><input class="form-control" type="text" name="nama[]"></td>
                                            <td><input class="form-control" type="text" name="diadakan_oleh[]"></td>
                                            <td><input class="form-control" type="text" name="tempat[]"></td>
                                            <td><input class="form-control" type="text" name="lama[]"></td>
                                            <td><input class="form-control" type="text" name="tahun[]"></td>
                                            <td><input class="form-control" type="text" name="dibiayai_oleh[]"></td>
                                            <td><input class="form-control" type="text" name="keterangan[]"></td>
                                            <td><button type="button" class="btn btn-sm btn-success" id="add_kursus_btn">+</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Bahasa dan Keterampilan Section -->
                        <h2 class="mt-4">E. PENGUASAAN BAHASA DAN KETERAMPILAN</h2>
                        <h5>Pilih Baik, Cukup, Kurang</h5>

                        <div class="row mb-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped" >
                                    <thead>
                                        <tr>
                                            <th>Bahasa</th>
                                            <th>Bicara</th>
                                            <th>Baca</th>
                                            <th>Tulis</th>
                                            <th>Mengetik Steno WPM</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bahasa_table_body">
                                        <tr>
                                            <td><input class="form-control" type="text" name="bahasa[]"></td>
                                            <td>
                                                <select class="form-select" name="bicara[]">
                                                    <option value="Baik">Baik</option>
                                                    <option value="Cukup">Cukup</option>
                                                    <option value="Kurang">Kurang</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" name="baca[]">
                                                    <option value="Baik">Baik</option>
                                                    <option value="Cukup">Cukup</option>
                                                    <option value="Kurang">Kurang</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select" name="tulis[]">
                                                    <option value="Baik">Baik</option>
                                                    <option value="Cukup">Cukup</option>
                                                    <option value="Kurang">Kurang</option>
                                                </select>
                                            </td>
                                            <td><input class="form-control" type="text" name="steno_wpm[]"></td>
                                            <td><button type="button" class="btn btn-sm btn-success" id="add_bahasa_btn">+</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tanggungJawabSelect = document.getElementById('tanggung_jawab');
        const additionalTanggungan = document.getElementById('additional_tanggungan');

        tanggungJawabSelect.addEventListener('change', function() {
            if (this.value === 'Ya') {
                additionalTanggungan.style.display = 'block';
            } else {
                additionalTanggungan.style.display = 'none';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const tanggungJawabSelect = document.getElementById('melanjut_pendidikan');
        const additionalTanggungan = document.getElementById('additional_pendidikan');

        tanggungJawabSelect.addEventListener('change', function() {
            if (this.value === 'Ya') {
                additionalTanggungan.style.display = 'block';
            } else {
                additionalTanggungan.style.display = 'none';
            }
        });
    });

    document.getElementById('status_keluarga').addEventListener('change', function() {
        const status = this.value;

        // Hide all rows by default
        document.getElementById('table_keluarga').style.display = 'none';
        document.getElementById('row_istri').style.display = 'none';
        document.getElementById('row_anak1').style.display = 'none';
        document.getElementById('row_anak2').style.display = 'none';
        document.getElementById('row_anak3').style.display = 'none';

        // Show rows based on selected status
        if (status.startsWith('M')) {
            document.getElementById('row_istri').style.display = 'table-row'; // show spouse
            document.getElementById('table_keluarga').style.display = 'table-row'; // show spouse
        }
        if (status.includes('1')) {
            document.getElementById('row_anak1').style.display = 'table-row'; // show first child
            document.getElementById('table_keluarga').style.display = 'table-row'; // show spouse
        }
        if (status.includes('2')) {
            document.getElementById('row_anak1').style.display = 'table-row'; // ensure first child is shown
            document.getElementById('row_anak2').style.display = 'table-row'; // show second child
            document.getElementById('table_keluarga').style.display = 'table-row'; // show spouse
        }
        if (status.includes('3')) {
            document.getElementById('row_anak1').style.display = 'table-row'; // ensure first child is shown
            document.getElementById('row_anak2').style.display = 'table-row'; // ensure second child is shown
            document.getElementById('row_anak3').style.display = 'table-row'; // show third child
            document.getElementById('table_keluarga').style.display = 'table-row'; // show spouse
        }
    });

    // Trigger change event on page load to set initial table visibility
    document.getElementById('status_keluarga').dispatchEvent(new Event('change'));

    let siblingCount = 1;

    document.getElementById('add_sibling_btn').addEventListener('click', function() {
        siblingCount++;
        const tableBody = document.getElementById('family_table_body_2');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
      <td>Anak ke ${siblingCount}</td>
      <td><input class="form-control" type="text" name="nama_anak${siblingCount}"></td>
      <td><input class="form-control" type="text" name="lp_anak${siblingCount}"></td>
      <td><input class="form-control" type="date" name="dob_anak${siblingCount}"></td>
      <td><input class="form-control" type="text" name="pendidikan_anak${siblingCount}"></td>
      <td><input class="form-control" type="text" name="pekerjaan_anak${siblingCount}"></td>
      <td><input class="form-control" type="text" name="keterangan_anak${siblingCount}"></td>
      <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">-</button></td>
    `;

        tableBody.appendChild(newRow);
    });

    function removeRow(button) {
        // Remove the row when the "-" button is clicked
        button.closest('tr').remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        let kursusCount = 1;

        document.getElementById('add_kursus_btn').addEventListener('click', function() {
            kursusCount++;
            const tableBody = document.getElementById('kursus_table_body');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
            <td><input class="form-control" type="text" name="nama[]"></td>
            <td><input class="form-control" type="text" name="diadakan_oleh[]"></td>
            <td><input class="form-control" type="text" name="tempat[]"></td>
            <td><input class="form-control" type="text" name="lama[]"></td>
            <td><input class="form-control" type="text" name="tahun[]"></td>
            <td><input class="form-control" type="text" name="dibiayai_oleh[]"></td>
            <td><input class="form-control" type="text" name="keterangan[]"></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">-</button></td>
        `;

            tableBody.appendChild(newRow);
        });

        function removeRow(button) {
            // Remove the row when the "-" button is clicked
            button.closest('tr').remove();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        let bahasaCount = 1;

        document.getElementById('add_bahasa_btn').addEventListener('click', function() {
            bahasaCount++;
            const tableBody = document.getElementById('bahasa_table_body');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
            <td><input class="form-control" type="text" name="bahasa[]"></td>
            <td>
                <select class="form-select" name="bicara[]">
                    <option value="Baik">Baik</option>
                    <option value="Cukup">Cukup</option>
                    <option value="Kurang">Kurang</option>
                </select>
            </td>
            <td>
                <select class="form-select" name="baca[]">
                    <option value="Baik">Baik</option>
                    <option value="Cukup">Cukup</option>
                    <option value="Kurang">Kurang</option>
                </select>
            </td>
            <td>
                <select class="form-select" name="tulis[]">
                    <option value="Baik">Baik</option>
                    <option value="Cukup">Cukup</option>
                    <option value="Kurang">Kurang</option>
                </select>
            </td>
            <td><input class="form-control" type="text" name="steno_wpm[]"></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">-</button></td>
        `;

            tableBody.appendChild(newRow);
        });

        function removeRow(button) {
            // Remove the row when the "-" button is clicked
            button.closest('tr').remove();
        }
    });
</script>
@endpush
