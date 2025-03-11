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
                    Candidate Data Intern
                </h2>
                <br>
            </div>
        </div>
    </div>
</div>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    });
</script>
@elseif(session('danger'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Sudah!',
            text: "{{ session('danger') }}",
            icon: 'warning',
            confirmButtonText: 'Ok'
        });
    });
</script>
@endif
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action='/candidate/data/store' method="POST" id="formCandidate" enctype="multipart/form-data">
                            @csrf
                            <h2>A. IDENTITAS</h2>
                            <input type="hidden" id="candidate_id" name="candidate_id" value="{{$candidateId}}">

                            <!-- Nama Lengkap -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Nama Lengkap <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Nama Lengkap" maxlength="255">
                                </div>
                            </div>

                            <!-- Nama Panggilan -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Nama Kecil/Panggilan <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="nama_panggilan" id="nama_panggilan" placeholder="Nama Panggilan" maxlength="255">
                                </div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Jenis Kelamin <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <select class="form-select" name="jenis" id="jenis">
                                        <option value="">Pilih</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>


                            <!-- Golongan Darah -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Golongan Darah <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <select class="form-select" name="gol_darah" id="gol_darah">
                                        <option value="">Pilih</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="AB">AB</option>
                                        <option value="O">O</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tempat/Tgl Lahir -->

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Tempat Lahir <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" maxlength="255">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Tgl Lahir <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir">
                                </div>
                            </div>

                            <!-- Warga Negara -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Warga Negara<span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="warga_negara" id="warga_negara" placeholder="Warga Negara" maxlength="255">
                                </div>
                            </div>

                            <!-- Alamat Rumah -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Alamat Rumah (Lengkap)<span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="alamat_rumah" id="alamat_rumah" rows="2" placeholder="Alamat Rumah" maxlength="500"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Alamat RT <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="alamat_rt" id="alamat_rt" rows="1" placeholder="Alamat RT" maxlength="20"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Alamat RW <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="alamat_rw" id="alamat_rw" rows="1" placeholder="Alamat RW" maxlength="20"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Alamat Kelurahan <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="alamat_kel" id="alamat_kel" rows="1" placeholder="Alamat Kelurahan" maxlength="50"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Alamat Kecamatan <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="alamat_kec" id="alamat_kec" rows="1" placeholder="Alamat Kecamatan" maxlength="50"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Alamat Kota <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="alamat_kota" id="alamat_kota" rows="1" placeholder="Alamat Kota" maxlength="50"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Alamat Provinsi <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="alamat_prov" id="alamat_prov" rows="1" placeholder="Alamat Provinsi" maxlength="50"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Alamat POS <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="alamat_pos" id="alamat_pos" rows="1" placeholder="Alamat POS" maxlength="20"></textarea>
                                </div>
                            </div>

                            <!-- Telpon Rumah/HP -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Telpon Rumah/HP <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="telp_rumah_hp" id="telp_rumah_hp" placeholder="Telpon Rumah/HP" maxlength="50">
                                </div>
                            </div>

                            <!-- No. KTP/SIM -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">No. KTP/SIM <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="no_ktp_sim" id="no_ktp_sim" placeholder="No. KTP/SIM" maxlength="100">
                                </div>
                            </div>

                            <!-- Tgl Berlaku KTP/SIM -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Tgl Berlaku KTP/SIM <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" name="tgl_ktp_sim" id="tgl_ktp_sim">
                                </div>
                            </div>

                            <!-- Alamat Email -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Alamat Email Pribadi<span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" name="alamat_email" id="alamat_email" placeholder="Alamat Email" maxlength="255">
                                </div>
                            </div>

                            <!-- Add more form sections as needed -->

                            <h2 class="mt-4">B. KELUARGA & LINGKUNGAN</h2>

                            <h5 class="mt-4">1. Susunan Keluarga (Ayah, Ibu dan Saudara Kandung termasuk Saudara) <span style="color: red;">*</span></h5>
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
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="family_table_body_2">
                                            <tr>
                                                <td>Ayah</td>
                                                <td><input class="form-control" type="text" name="family1_nama_lengkap_ayah" id="family1_nama_lengkap_ayah"></td>
                                                <td>
                                                    <input type="hidden" name="family1_jenis_ayah" id="family1_jenis_ayah" value="L">
                                                    Laki-laki
                                                </td>
                                                <td><input class="form-control" type="date" name="family1_tgl_lahir_ayah" id="family1_tgl_lahir_ayah"></td>
                                                <td><input class="form-control" type="text" name="family1_pendidikan_ayah" id="family1_pendidikan_ayah"></td>
                                                <td><input class="form-control" type="text" name="family1_pekerjaan_ayah" id="family1_pekerjaan_ayah"></td>
                                                <td><input class="form-control" type="text" name="family1_keterangan_ayah" id="family1_keterangan_ayah"></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Ibu</td>
                                                <td><input class="form-control" type="text" name="family1_nama_lengkap_ibu" id="family1_nama_lengkap_ibu"></td>
                                                <td>
                                                    <input type="hidden" name="family1_jenis_ibu" id="family1_jenis_ibu" value="P">
                                                    Perempuan
                                                </td>
                                                <td><input class="form-control" type="date" name="family1_tgl_lahir_ibu" id="family1_tgl_lahir_ibu"></td>
                                                <td><input class="form-control" type="text" name="family1_pendidikan_ibu" id="family1_pendidikan_ibu"></td>
                                                <td><input class="form-control" type="text" name="family1_pekerjaan_ibu" id="family1_pekerjaan_ibu"></td>
                                                <td><input class="form-control" type="text" name="family1_keterangan_ibu" id="family1_keterangan_ibu"></td>
                                                <td></td>
                                            </tr>
                                            <tr id="row_anak1">
                                                <td>Anak ke 1</td>
                                                <td><input class="form-control" type="text" name="family1_nama_lengkap_anak1" id="family1_nama_lengkap_anak1"></td>
                                                <td><select class="form-control" name="family1_jenis_anak1" id="family1_jenis_anak1">
                                                        <option value="">Pilih</option>
                                                        <option value="L">Laki-laki</option>
                                                        <option value="P">Perempuan</option>
                                                    </select></td>
                                                <td><input class="form-control" type="date" name="family1_tgl_lahir_anak1" id="family1_tgl_lahir_anak1"></td>
                                                <td><input class="form-control" type="text" name="family1_pendidikan_anak1" id="family1_pendidikan_anak1"></td>
                                                <td><input class="form-control" type="text" name="family1_pekerjaan_anak1" id="family1_pekerjaan_anak1"></td>
                                                <td><input class="form-control" type="text" name="family1_keterangan_anak1" id="family1_keterangan_anak1"></td>
                                                <td><button type="button" id="add_sibling_btn" class="btn btn-sm btn-success">+</button></td>
                                            </tr>
                                            <input type="hidden" id="sibling_count" name="sibling_count" value="1">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <h2 class="mt-5">C. PENDIDIKAN <span style="color: red;">*</span></h2>

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
                                        <tbody>
                                            @foreach(['Dasar', 'SLTP', 'SLTA', 'Diploma', 'Strata I', 'Strata II', 'Lain-Lain'] as $index => $level)
                                            <tr>
                                                <td>{{ $level }}</td>
                                                <td><input class="form-control" type="text" name="nama_sekolah_{{ $index }}"></td>
                                                <td><input class="form-control" type="text" name="tempat_sekolah_{{ $index }}"></td>
                                                <td><input class="form-control" type="text" name="jurusan_studi_{{ $index }}"></td>
                                                <td><input class="form-control" type="date" name="dari_{{ $index }}"></td>
                                                <td><input class="form-control" type="date" name="sampai_{{ $index }}"></td>
                                                <td><input class="form-control" type="text" name="berijazah_{{ $index }}"></td>
                                                <td><input class="form-control" type="text" name="keterangan_{{ $index }}"></td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-5 col-form-label">Apakah Saudara masih melanjutkan pendidikan ? <span style="color: red;">*</span></label>
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

                            <h2 class="mt-5">D. KURSUS / TRAINING (isikan dari urutan yang terbaru) </h2>
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
                                            <tr id="row_1">
                                                <td><input class="form-control" type="text" name="kursus_1_nama"></td>
                                                <td><input class="form-control" type="text" name="kursus_1_diadakan"></td>
                                                <td><input class="form-control" type="text" name="kursus_1_tempat"></td>
                                                <td><input class="form-control" type="text" name="kursus_1_lama"></td>
                                                <td><input class="form-control" type="text" name="kursus_1_tahun"></td>
                                                <td><input class="form-control" type="text" name="kursus_1_dibiayai"></td>
                                                <td><input class="form-control" type="text" name="kursus_1_keterangan"></td>
                                                <td><button type="button" class="btn btn-sm btn-success" id="add_kursus_btn">+</button></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            <!-- Bahasa dan Keterampilan Section -->
                            <h2 class="mt-4">E. PENGUASAAN BAHASA DAN KETERAMPILAN <span style="color: red;">*</span></h2>
                            <h5>Pilih Baik, Cukup, Kurang</h5>

                            <div class="row mb-4">
                                <div class="col-12 table-responsive">
                                    <table class="table table-vcenter card-table table-striped">
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
                                                <td><input class="form-control" type="text" name="language_1_bahasa" value="English"></td>
                                                <td>
                                                    <select class="form-select" name="language_1_bicara" id="language_1_bicara">
                                                        <option value="">Pilih</option>
                                                        <option value="Baik">Baik</option>
                                                        <option value="Cukup">Cukup</option>
                                                        <option value="Kurang">Kurang</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" name="language_1_baca" id="language_1_baca">
                                                        <option value="">Pilih</option>
                                                        <option value="Baik">Baik</option>
                                                        <option value="Cukup">Cukup</option>
                                                        <option value="Kurang">Kurang</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" name="language_1_tulis" id="language_1_tulis">
                                                        <option value="">Pilih</option>
                                                        <option value="Baik">Baik</option>
                                                        <option value="Cukup">Cukup</option>
                                                        <option value="Kurang">Kurang</option>
                                                    </select>
                                                </td>
                                                <td><input class="form-control" type="text" name="language_1_steno" id="language_1_steno"></td>
                                                <td><button type="button" class="btn btn-sm btn-success" id="add_bahasa_btn">+</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <h2 class="mt-4">F. MINAT DAN KONSEP PRIBADI <span style="color: red;">*</span></h2>
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
                                                <td>
                                                    <select class="form-control" name="engineering_no">
                                                        <option value="">Select</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </td>
                                                <td>Accounting</td>
                                                <td>
                                                    <select class="form-control" name="accounting_no">
                                                        <option value="">Select</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Geologist</td>
                                                <td>
                                                    <select class="form-control" name="geologist_no">
                                                        <option value="">Select</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </td>
                                                <td>Administration</td>
                                                <td>
                                                    <select class="form-control" name="administration_no">
                                                        <option value="">Select</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Agronomist</td>
                                                <td>
                                                    <select class="form-control" name="agronomist_no">
                                                        <option value="">Select</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </td>
                                                <td>General Affair</td>
                                                <td>
                                                    <select class="form-control" name="ga_no">
                                                        <option value="">Select</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Consultant/Riset</td>
                                                <td>
                                                    <select class="form-control" name="consultant_no">
                                                        <option value="">Select</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </td>
                                                <td>Personnel</td>
                                                <td>
                                                    <select class="form-control" name="personnel_no">
                                                        <option value="">Select</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Cashier</td>
                                                <td>
                                                    <select class="form-control" name="cashier_no">
                                                        <option value="">Select</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </td>
                                                <td>Finance</td>
                                                <td>
                                                    <select class="form-control" name="finance_no">
                                                        <option value="">Select</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Humas</td>
                                                <td>
                                                    <select class="form-control" name="humas_no">
                                                        <option value="">Select</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </td>
                                                <td>Driver</td>
                                                <td>
                                                    <select class="form-control" name="driver_no">
                                                        <option value="">Select</option>
                                                        @for($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-12 col-form-label">1. Organisasi – organisasi apakah yang pernah Saudara masuki ? Sebutkan jabatan – jabatan yang pernah Anda pegang <span style="color: red;">*</span></label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="organisasi" id="organisasi" rows="2" placeholder="Alasan" maxlength="1000"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-12 col-form-label">2. Dalam keadaan darurat, siapakah yang dapat dihubungi ? Sebutkan nama, alamat, telpon serta apa hubungannya Saudara dengan nama tersebut ? <span style="color: red;">*</span></label>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Nama <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="em_nama" id="em_nama" maxlength="255">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Alamat <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="em_alamat" id="em_alamat" maxlength="255">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Telpon <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="em_telp" id="em_telp" maxlength="255">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Hubungan <span style="color: red;">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="em_status" id="em_status" maxlength="255">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-12 col-form-label">3. Sebutkan dua nama sebagai referensi Saudara dalam hal ini (yang mengetahui tentang Anda)</label>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-4 col-form-label">Referensi 1</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="nama_referensi1" maxlength="255">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-4 col-form-label">Referensi 2</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="nama_referensi2" maxlength="255">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-12 col-form-label">4. Apakah Saudara pernah menderita sakit yang lama ? <span style="color: red;">*</span></label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="sakit_lama" id="sakit_lama" rows="2" placeholder="Alasan" maxlength="255"></textarea>
                                </div>
                            </div>

                            <h2 class="mt-4">I. LAIN – LAIN</h2>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-5 col-form-label">1. Kapankah Saudara dapat mulai bekerja di perusahaan kami ? <span style="color: red;">*</span></label>
                                <div class="col-md-7">
                                    <input class="form-control" type="DATE" id="mulai_kerja" name="mulai_kerja">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    function formatCurrency(input) {
        let value = input.value.replace(/\D/g, ''); // Remove non-digit characters
        if (value) {
            input.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
        } else {
            input.value = '';
        }
    }

    function saveNumericValue(input) {
        // Remove 'Rp' and format to number
        let value = input.value.replace(/[^\d]/g, ''); // Remove non-digit characters
        input.value = value ? parseFloat(value).toFixed(0) : '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Informasi Mengisi Form',
            html: 'Form di tandain <span style="color: red;">*</span> WAJIB di isi <br> Click tombol <span style="color: green;">+</span> untuk menambah baris',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    });

    let siblingCount = 1;

    // Add event listener for the Add Sibling button
    document.getElementById('add_sibling_btn').addEventListener('click', function() {
        siblingCount++;
        const tableBody = document.getElementById('family_table_body_2');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
        <td>Anak ke ${siblingCount}</td>
        <td><input class="form-control" type="text" name="family1_nama_lengkap_anak${siblingCount}"></td>
        <td><select class="form-control" name="family1_jenis_anak${siblingCount}">
            <option value="">Pilih</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select></td>
        <td><input class="form-control" type="date" name="family1_tgl_lahir_anak${siblingCount}"></td>
        <td><input class="form-control" type="text" name="family1_pendidikan_anak${siblingCount}"></td>
        <td><input class="form-control" type="text" name="family1_pekerjaan_anak${siblingCount}"></td>
        <td><input class="form-control" type="text" name="family1_keterangan_anak${siblingCount}"></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">-</button></td>
    `;

        tableBody.appendChild(newRow);

        // Update sibling count in hidden field
        document.getElementById('sibling_count').value = siblingCount;
    });

    // Function to remove a row
    function removeRow(button) {
        const row = button.closest('tr');
        row.remove();

        // Adjust sibling count
        siblingCount--;
        document.getElementById('sibling_count').value = siblingCount;
    }

    function removeRow(button) {
        button.closest('tr').remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        let kursusCount = 1;

        document.getElementById('add_kursus_btn').addEventListener('click', function() {
            kursusCount++;
            const tableBody = document.getElementById('kursus_table_body');
            const newRow = document.createElement('tr');

            newRow.id = 'row_' + kursusCount;
            newRow.innerHTML = `
            <td><input class="form-control" type="text" name="kursus_${kursusCount}_nama"></td>
            <td><input class="form-control" type="text" name="kursus_${kursusCount}_diadakan"></td>
            <td><input class="form-control" type="text" name="kursus_${kursusCount}_tempat"></td>
            <td><input class="form-control" type="text" name="kursus_${kursusCount}_lama"></td>
            <td><input class="form-control" type="text" name="kursus_${kursusCount}_tahun"></td>
            <td><input class="form-control" type="text" name="kursus_${kursusCount}_dibiayai"></td>
            <td><input class="form-control" type="text" name="kursus_${kursusCount}_keterangan"></td>
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

    document.addEventListener('DOMContentLoaded', function() {
        let bahasaCount = 1;

        document.getElementById('add_bahasa_btn').addEventListener('click', function() {
            bahasaCount++;
            const tableBody = document.getElementById('bahasa_table_body');
            const newRow = document.createElement('tr');

            newRow.id = 'row_' + bahasaCount;
            newRow.innerHTML = `
            <td><input class="form-control" type="text" name="language_${bahasaCount}_bahasa"></td>
            <td>
                <select class="form-select" name="language_${bahasaCount}_bicara">
                    <option value="">Pilih</option>
                    <option value="Baik">Baik</option>
                    <option value="Cukup">Cukup</option>
                    <option value="Kurang">Kurang</option>
                </select>
            </td>
            <td>
                <select class="form-select" name="language_${bahasaCount}_baca">
                    <option value="">Pilih</option>
                    <option value="Baik">Baik</option>
                    <option value="Cukup">Cukup</option>
                    <option value="Kurang">Kurang</option>
                </select>
            </td>
            <td>
                <select class="form-select" name="language_${bahasaCount}_tulis">
                    <option value="">Pilih</option>
                    <option value="Baik">Baik</option>
                    <option value="Cukup">Cukup</option>
                    <option value="Kurang">Kurang</option>
                </select>
            </td>
            <td><input class="form-control" type="text" name="language_${bahasaCount}_steno"></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">-</button></td>
        `;

            tableBody.appendChild(newRow);
        });

        function removeRow(button) {
            // Remove the row when the "-" button is clicked
            button.closest('tr').remove();
        }
    });

    $(document).ready(function() {
        $('#formCandidate').on('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting by default

            var fields = [
                // Existing fields
                {
                    id: '#nama_lengkap',
                    message: 'Nama Lengkap Harus Diisi'
                },
                {
                    id: '#nama_panggilan',
                    message: 'Nama Panggilan Harus Diisi'
                },
                {
                    id: '#jenis',
                    message: 'Jenis Kelamin Harus Diisi'
                },
                {
                    id: '#gol_darah',
                    message: 'Golongan Darah Harus Diisi'
                },
                {
                    id: '#tempat_lahir',
                    message: 'Tempat Lahir Harus Diisi'
                },
                {
                    id: '#tgl_lahir',
                    message: 'Tanggal Lahir Harus Diisi'
                },
                {
                    id: '#warga_negara',
                    message: 'Warga Negara Harus Diisi'
                },
                {
                    id: '#alamat_rumah',
                    message: 'Alamat Rumah Harus Diisi'
                },
                {
                    id: '#telp_rumah_hp',
                    message: 'Telpon Rumah/HP Harus Diisi'
                },
                {
                    id: '#no_ktp_sim',
                    message: 'No. KTP/SIM Harus Diisi'
                },
                {
                    id: '#tgl_ktp_sim',
                    message: 'Tanggal Berlaku KTP/SIM Harus Diisi'
                },
                {
                    id: '#no_npwp',
                    message: 'No. NPWP Harus Diisi'
                },
                {
                    id: '#alamat_npwp',
                    message: 'Alamat NPWP Harus Diisi'
                },
                {
                    id: '#marriage_status',
                    message: 'Status Menikah Harus Diisi'
                },
                {
                    id: '#jabatan',
                    message: 'Jabatan Saat Ini Harus Diisi'
                },
                {
                    id: '#alamat_email',
                    message: 'Alamat Email Harus Diisi'
                },

                // New family fields
                {
                    id: '#family1_nama_lengkap_ayah',
                    message: 'Nama Lengkap Ayah Harus Diisi'
                },
                {
                    id: '#family1_jenis_ayah',
                    message: 'Jenis Kelamin Ayah Harus Diisi'
                },
                {
                    id: '#family1_tgl_lahir_ayah',
                    message: 'Tanggal Lahir Ayah Harus Diisi'
                },
                {
                    id: '#family1_pendidikan_ayah',
                    message: 'Pendidikan Ayah Harus Diisi'
                },
                {
                    id: '#family1_pekerjaan_ayah',
                    message: 'Pekerjaan Ayah Harus Diisi'
                },
                {
                    id: '#family1_keterangan_ayah',
                    message: 'Keterangan Ayah Harus Diisi'
                },
                {
                    id: '#family1_nama_lengkap_ibu',
                    message: 'Nama Lengkap Ibu Harus Diisi'
                },
                {
                    id: '#family1_jenis_ibu',
                    message: 'Jenis Kelamin Ibu Harus Diisi'
                },
                {
                    id: '#family1_tgl_lahir_ibu',
                    message: 'Tanggal Lahir Ibu Harus Diisi'
                },
                {
                    id: '#family1_pendidikan_ibus',
                    message: 'Pendidikan Ibu Harus Diisi'
                },
                {
                    id: '#family1_pekerjaan_ibu',
                    message: 'Pekerjaan Ibu Harus Diisi'
                },
                {
                    id: '#family1_keterangan_ibu',
                    message: 'Keterangan Ibu Harus Diisi'
                },
                {
                    id: '#family1_nama_lengkap_anak1',
                    message: 'Nama Lengkap Anak ke-1 Harus Diisi'
                },
                {
                    id: '#family1_jenis_anak1',
                    message: 'Jenis Kelamin Anak ke-1 Harus Diisi'
                },
                {
                    id: '#family1_tgl_lahir_anak1',
                    message: 'Tanggal Lahir Anak ke-1 Harus Diisi'
                },
                {
                    id: '#family1_pendidikan_anak1',
                    message: 'Pendidikan Anak ke-1 Harus Diisi'
                },
                {
                    id: '#family1_pekerjaan_anak1',
                    message: 'Pekerjaan Anak ke-1 Harus Diisi'
                },
                {
                    id: '#family1_keterangan_anak1',
                    message: 'Keterangan Anak ke-1 Harus Diisi'
                },
                {
                    id: '#melanjut_pendidikan',
                    message: 'Perlanjutan Pendidikan Field Harus Diisi'
                },
                {
                    id: '#language_1_bicara',
                    message: 'Field Bicara Bahasa Inggris Harus Diisi'
                },
                {
                    id: '#language_1_baca',
                    message: 'Field Baca Bahasa Inggris Harus Diisi'
                },
                {
                    id: '#language_1_tulis',
                    message: 'Field Tulis Bahasa Inggris Harus Diisi'
                },
                {
                    id: '#language_1_steno',
                    message: 'Field Steno Inggris Harus Diisi'
                },
                {
                    id: '#saudara_pekerjaan',
                    message: 'Saudara Melamar Harus Diisi'
                },
                {
                    id: '#organisasi',
                    message: 'Organisasi Harus Diisi'
                },
                {
                    id: '#em_nama',
                    message: 'Nama Emergency Contact Harus Diisi'
                },
                {
                    id: '#em_alamat',
                    message: 'Alamat Emergency Contact Harus Diisi'
                },
                {
                    id: '#em_telp',
                    message: 'Telpon Emergency Contact Harus Diisi'
                },
                {
                    id: '#em_status',
                    message: 'Hubungan Emergency Contact Harus Diisi'
                },
                {
                    id: '#sakit_lama',
                    message: 'Saudara Pernah Menderita Sakit Yang Lama Harus Diisi'
                },
                {
                    id: '#masa_percobaan',
                    message: 'Masa Percobaan Harus Diisi'
                },
                {
                    id: '#proses_bi',
                    message: 'Proses BI Harus Diisi'
                },
                {
                    id: '#mulai_kerja',
                    message: 'Mulai Kerja Harus Diisi'
                },
            ];
            for (let i = 0; i < fields.length; i++) {
                var field = fields[i];
                if ($(field.id).val() === "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: field.message,
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then(() => {
                        // Smooth scroll to the field
                        $('html, body').animate({
                            scrollTop: $(field.id).offset().top - 100 // Adjust for fixed headers
                        }, 500, function() {
                            // Set focus after scrolling is complete
                            $(field.id).focus();
                        });
                    });
                    return false; // Stop further execution and show the alert
                }
            }
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Pastikan semua data sudah benar sebelum mengirim.",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Simpan!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: $('#formCandidate').attr('action'),
                        data: $('#formCandidate').serialize(),
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Data berhasil disimpan.",
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    location.reload(); // Reload to clear form
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal!",
                                    text: response.message || "Terjadi kesalahan. Silakan coba lagi.",
                                    icon: "error",
                                    confirmButtonText: "Coba Lagi"
                                });
                            }
                        },
                        error: function(xhr) {
                            let message = "Terjadi kesalahan. Silakan coba lagi.";

                            if (xhr.status === 422) {
                                // Handle Laravel validation errors
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = "";
                                $.each(errors, function(key, value) {
                                    errorMessages += value[0] + "\n";
                                });

                                message = errorMessages;
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                // Show cleaned error message from backend
                                message = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                title: "Error!",
                                text: message,
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush

