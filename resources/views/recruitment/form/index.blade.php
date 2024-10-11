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
                                <label class="col-md-3 col-form-label">Nama Lengkap</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Nama Lengkap">
                                </div>
                            </div>

                            <!-- Nama Panggilan -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Nama Kecil/Panggilan</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="nama_panggilan" id="nama_panggilan" placeholder="Nama Panggilan">
                                </div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Jenis Kelamin</label>
                                <div class="col-md-9">
                                    <select class="form-select" name="jenis">
                                        <option value="">Pilih</option>
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
                                <label class="col-md-3 col-form-label">Status Pajak</label>
                                <div class="col-md-9">
                                    <select class="form-select" name="status_pajak" id="status_pajak">
                                        <option value="">Pilih</option>
                                        <option value="TK">TK (Tidak Kawin)</option>
                                        <option value="TK1">TK1</option>
                                        <option value="TK2">TK2</option>
                                        <option value="TK3">TK3</option>
                                        <option value="K">K (Menikah)</option>
                                        <option value="K1">K1</option>
                                        <option value="K2">K2</option>
                                        <option value="K3">K3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Status Menikah</label>
                                <div class="col-md-9">
                                    <select class="form-select" name="marriage_status" id="marriage_status">
                                        <option value="">Pilih</option>
                                        <option value="Menikah">Menikah</option>
                                        <option value="Tidak Menikah">Tidak Menikah</option>
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
                                    <input type="email" class="form-control" name="alamat_email" placeholder="Alamat Email">
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
                                                <td><input class="form-control" type="text" name="family_nama_lengkap_istri_suami"></td>
                                                <td><input class="form-control" type="text" name="family_jenis_istri_suami"></td>
                                                <td><input class="form-control" type="date" name="family_tgl_lahir_istri_suami"></td>
                                                <td><input class="form-control" type="text" name="family_pendidikan_istri_suami"></td>
                                                <td><input class="form-control" type="text" name="family_pekerjaan_istri_suami"></td>
                                                <td><input class="form-control" type="text" name="family_keterangan_istri_suami"></td>
                                            </tr>
                                            <tr id="row_anak1">
                                                <td>Anak ke 1</td>
                                                <td><input class="form-control" type="text" name="family_nama_lengkap_anak1"></td>
                                                <td><input class="form-control" type="text" name="family_jenis_anak1"></td>
                                                <td><input class="form-control" type="date" name="family_tgl_lahir_anak1"></td>
                                                <td><input class="form-control" type="text" name="family_pendidikan_anak1"></td>
                                                <td><input class="form-control" type="text" name="family_pekerjaan_anak1"></td>
                                                <td><input class="form-control" type="text" name="family_keterangan_anak1"></td>
                                            </tr>
                                            <tr id="row_anak2">
                                                <td>Anak ke 2</td>
                                                <td><input class="form-control" type="text" name="family_nama_lengkap_anak2"></td>
                                                <td><input class="form-control" type="text" name="family_jenis_anak2"></td>
                                                <td><input class="form-control" type="date" name="family_tgl_lahir_anak2"></td>
                                                <td><input class="form-control" type="text" name="family_pendidikan_anak2"></td>
                                                <td><input class="form-control" type="text" name="family_pekerjaan_anak2"></td>
                                                <td><input class="form-control" type="text" name="family_keterangan_anak2"></td>
                                            </tr>
                                            <tr id="row_anak3">
                                                <td>Anak ke 3</td>
                                                <td><input class="form-control" type="text" name="family_nama_lengkap_anak3"></td>
                                                <td><input class="form-control" type="text" name="family_jenis_anak3"></td>
                                                <td><input class="form-control" type="date" name="family_tgl_lahir_anak3"></td>
                                                <td><input class="form-control" type="text" name="family_pendidikan_anak3"></td>
                                                <td><input class="form-control" type="text" name="family_pekerjaan_anak3"></td>
                                                <td><input class="form-control" type="text" name="family_keterangan_anak3"></td>
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
                                                <td><input class="form-control" type="text" name="family1_nama_lengkap_ayah"></td>
                                                <td><input class="form-control" type="text" name="family1_jenis_ayah"></td>
                                                <td><input class="form-control" type="date" name="family1_tgl_lahir_ayah"></td>
                                                <td><input class="form-control" type="text" name="family1_pendidikan_ayah"></td>
                                                <td><input class="form-control" type="text" name="family1_pekerjaan_ayah"></td>
                                                <td><input class="form-control" type="text" name="family1_keterangan_ayah"></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Ibu</td>
                                                <td><input class="form-control" type="text" name="family1_nama_lengkap_ibu"></td>
                                                <td><input class="form-control" type="text" name="family1_jenis_ibu"></td>
                                                <td><input class="form-control" type="date" name="family1_tgl_lahir_ibu"></td>
                                                <td><input class="form-control" type="text" name="family1_pendidikan_ibu"></td>
                                                <td><input class="form-control" type="text" name="family1_pekerjaan_ibu"></td>
                                                <td><input class="form-control" type="text" name="family1_keterangan_ibu"></td>
                                                <td></td>
                                            </tr>
                                            <tr id="row_anak1">
                                                <td>Anak ke 1</td>
                                                <td><input class="form-control" type="text" name="family1_nama_lengkap_anak1"></td>
                                                <td><input class="form-control" type="text" name="family1_jenis_anak1"></td>
                                                <td><input class="form-control" type="date" name="family1_tgl_lahir_anak1"></td>
                                                <td><input class="form-control" type="text" name="family1_pendidikan_anak1"></td>
                                                <td><input class="form-control" type="text" name="family1_pekerjaan_anak1"></td>
                                                <td><input class="form-control" type="text" name="family1_keterangan_anak1"></td>
                                                <td><button type="button" id="add_sibling_btn" class="btn btn-sm btn-success">+</button></td>
                                            </tr>
                                            <input type="hidden" id="sibling_count" name="sibling_count" value="1">
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
                                        <input type="text" class="form-control" name="siapa_tanggungan" id="siapa_tanggungan" placeholder="Siapa">
                                        <input type="number" class="form-control mt-2" name="nilai_tanggungan" id="nilai_tanggungan" placeholder="Rp. ....... / bulan">
                                    </div>
                                </div>
                            </div>

                            <!-- Status Rumah -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-5 col-form-label">Apakah rumah status yang Saudara tempati saat ini:</label>
                                <div class="col-md-7">
                                    <select class="form-select" name="rumah_status">
                                        <option value="">Pilih</option>
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
                            <h2 class="mt-4">E. PENGUASAAN BAHASA DAN KETERAMPILAN</h2>
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
                                                    <select class="form-select" name="language_1_bicara">
                                                        <option value="">Pilih</option>
                                                        <option value="Baik">Baik</option>
                                                        <option value="Cukup">Cukup</option>
                                                        <option value="Kurang">Kurang</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" name="language_1_baca">
                                                        <option value="">Pilih</option>
                                                        <option value="Baik">Baik</option>
                                                        <option value="Cukup">Cukup</option>
                                                        <option value="Kurang">Kurang</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" name="language_1_tulis">
                                                        <option value="">Pilih</option>
                                                        <option value="Baik">Baik</option>
                                                        <option value="Cukup">Cukup</option>
                                                        <option value="Kurang">Kurang</option>
                                                    </select>
                                                </td>
                                                <td><input class="form-control" type="text" name="language_1_steno"></td>
                                                <td><button type="button" class="btn btn-sm btn-success" id="add_bahasa_btn">+</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <h2 class="mt-4">F. RIWAYAT PEKERJAAN</h2>
                            <h5>(Isikan urutan dari pekerjaan saat ini)</h5>
                            <div class="row mb-4">
                                <div class="col-12 table-responsive">
                                    <table class="table table-vcenter card-table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Perusahaan</th>
                                                <th>Alamat</th>
                                                <th>Jabatan</th>
                                                <th>Dari</th>
                                                <th>Sampai</th>
                                                <th>Keterangan</th>
                                                <th>Alasan Keluar</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pekerjaan_table_body">
                                            <tr>
                                                <td><input class="form-control" type="text" name="pekerjaan_1_perusahaan"></td>
                                                <td><input class="form-control" type="text" name="pekerjaan_1_alamat"></td>
                                                <td><input class="form-control" type="text" name="pekerjaan_1_jabatan"></td>
                                                <td><input class="form-control" type="date" name="pekerjaan_1_dari"></td>
                                                <td><input class="form-control" type="date" name="pekerjaan_1_sampai"></td>
                                                <td><input class="form-control" type="text" name="pekerjaan_1_keterangan"></td>
                                                <td><input class="form-control" type="text" name="pekerjaan_1_alasan"></td>
                                                <td><button type="button" class="btn btn-sm btn-success" id="add_pekerjaan_btn">+</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Alasan Pekerjaan -->
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-12 col-form-label">Selain untuk meningkatkan karir dan pendapatan, sebutkan alasan saudara meninggalkan pekerjaan terakhir:</label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="alasan_pekerjaan_terakhir" rows="2" placeholder="Alasan"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-12 col-form-label">Berilah uraian pekerjaan dari jabatan terakhir</label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="uraian_pekerjaan_terakhir" rows="2" placeholder="Alasan"></textarea>
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
                                <label class="col-md-12 col-form-label">1. Pernahkah Saudara melamar pekerjaan di Perusahaan kami ? </label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="saudara_pekerjaan" rows="2" placeholder="Alasan"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-12 col-form-label">2. Organisasi – organisasi apakah yang pernah Saudara masuki ? Sebutkan jabatan – jabatan yang pernah Anda pegang </label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="organisasi" rows="2" placeholder="Alasan"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-12 col-form-label">3. Dalam keadaan darurat, siapakah yang dapat dihubungi ? Sebutkan nama, alamat, telpon serta apa hubungannya Saudara dengan nama tersebut ?</label>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Nama</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="em_nama">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Alamat</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="em_alamat">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Telpon</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="em_telp">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-3 col-form-label">Hubungan</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="em_status">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-12 col-form-label">4. Sebutkan dua nama sebagai referensi Saudara dalam hal ini (yang mengetahui tentang Anda)</label>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-4 col-form-label">Referensi 1</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="nama_referensi1">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-4 col-form-label">Referensi 2</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="nama_referensi2">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-12 col-form-label">5. Apakah Saudara pernah menderita sakit yang lama ? </label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="sakit_lama" rows="2" placeholder="Alasan"></textarea>
                                </div>
                            </div>

                            <h2 class="mt-4">H. GAMBARAN POSISI SAAT INI</h2>
                            <h5>Gambarkan Posisi Anda saat ini dalam Struktur Organisasi</h5>
                            <!-- File Upload Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="gambaran_posisi" class="form-label">Upload Gambaran Posisi</label>
                                    <input class="form-control" type="file" id="gambaran_posisi" name="gambaran_posisi">
                                </div>
                            </div>

                            <h2 class="mt-4">I. LAIN – LAIN</h2>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-5 col-form-label">1. Apakah Saudara bersedia menjalani masa percobaan</label>
                                <div class="col-md-7">
                                    <select class="form-select" name="masa_percobaan">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-5 col-form-label">2. Bersediakan saudara untuk mengikuti proses BI Checking bersama CHL Group?</label>
                                <div class="col-md-7">
                                    <select class="form-select" name="proses_bi">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <label class="col-md-5 col-form-label">3. Kapankah Saudara dapat mulai bekerja di perusahaan kami ? </label>
                                <div class="col-md-7">
                                    <input class="form-control" type="DATE" id="mulai_kerja" name="mulai_kerja">
                                </div>
                            </div>

                            <h2 class="mt-4">KETERANGAN PENGHASILAN (HARAP LENGKAPI DENGAN SLIP GAJI 3 BULAN TERAKHIR)</h2>
                            <h5>Upload 3</h5>
                            <!-- File Upload Section -->
                            <div class="row mb-4">
                                <div class="col-4">
                                    <input class="form-control" type="file" id="slip_gaji1" name="slip1">
                                </div>
                                <div class="col-4">
                                    <input class="form-control" type="file" id="slip_gaji2" name="slip2">
                                </div>
                                <div class="col-4">
                                    <input class="form-control" type="file" id="slip_gaji3" name="slip3">
                                </div>
                            </div>

                            <h2 class="mt-4">Pendapatan Terakhir</h2>
                            <div class="row mb-4">
                                <div class="col-12 table-responsive">
                                    <table class="table table-vcenter card-table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Detail</th>
                                                <th>Amount (Rp)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Gaji Pokok -->
                                            <tr>
                                                <td>Gaji Pokok</td>
                                                <td><input class="form-control" type="number" name="gaji_pokok"></td>
                                            </tr>
                                            <!-- Tunjangan -->
                                            <tr>
                                                <td colspan="2"><strong>Tunjangan</strong></td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" class="form-control" name="tunjangan1" value=""></td>
                                                <td><input class="form-control" type="number" name="nilai_tunjangan1"></td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" class="form-control" name="tunjangan2" value=""></td>
                                                <td><input class="form-control" type="number" name="nilai_tunjangan2"></td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" class="form-control" name="tunjangan3" value=""></td>
                                                <td><input class="form-control" type="number" name="nilai_tunjangan3"></td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" class="form-control" name="tunjangan4" value=""></td>
                                                <td><input class="form-control" type="number" name="nilai_tunjangan4"></td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" class="form-control" name="tunjangan5" value=""></td>
                                                <td><input class="form-control" type="number" name="nilai_tunjangan5"></td>
                                            </tr>
                                            <!-- Insentif -->
                                            <tr>
                                                <td>Insentif</td>
                                                <td><input class="form-control" type="number" name="nilai_insentif"></td>
                                            </tr>
                                            <!-- Lain-lain -->
                                            <tr>
                                                <td>Lain-lain</td>
                                                <td><input class="form-control" type="number" name="nilai_lain_lain"></td>
                                            </tr>
                                            <!-- Total Take Home Pay -->
                                            <tr>
                                                <td>Total Take Home Pay / Bulan</td>
                                                <td><input class="form-control" type="number" name="take_home_bulan"></td>
                                            </tr>
                                            <!-- Pendapatan per Tahun -->
                                            <tr>
                                                <td>Pendapatan per Tahun (Termasuk THR)</td>
                                                <td><input class="form-control" type="number" name="take_home_tahun"></td>
                                            </tr>
                                            <!-- Gaji per Tahun Bulan -->
                                            <tr>
                                                <td>Gaji per Tahun Bulan</td>
                                                <td><input class="form-control" type="number" name="bulan_gaji"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <h2 class="mt-4">Pendapatan Yang Diharapkan</h2>
                            <div class="row mb-4">
                                <div class="col-12 table-responsive">
                                    <table class="table table-vcenter card-table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Detail</th>
                                                <th>Amount (Rp)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Take Home Pay / Bulan -->
                                            <tr>
                                                <td>Take Home Pay / Bulan</td>
                                                <td><input class="form-control" type="number" name="harap_take_home_bulan"></td>
                                            </tr>
                                            <!-- Pendapatan per Tahun -->
                                            <tr>
                                                <td>Pendapatan per Tahun (Bersih/Kotor)</td>
                                                <td><input class="form-control" type="number" name="harap_take_home_tahun"></td>
                                            </tr>
                                        </tbody>
                                    </table>
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

    document.addEventListener('DOMContentLoaded', function() {
        const tanggungJawabSelectTang = document.getElementById('tanggung_jawab');
        const additionalTanggunganTang = document.getElementById('additional_tanggungan');
        const siapaTanggungan = document.getElementById('siapa_tanggungan');
        const nilaiTanggungan = document.getElementById('nilai_tanggungan');

        tanggungJawabSelectTang.addEventListener('change', function() {
            if (this.value === 'Ya') {
                additionalTanggunganTang.style.display = 'block';
            } else {
                additionalTanggunganTang.style.display = 'none';
                // Reset the values of hidden fields to ensure they are submitted as empty
                siapaTanggungan.value = '';
                nilaiTanggungan.value = '';
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

    document.getElementById('status_pajak').addEventListener('change', function() {
        const status = this.value;

        // Hide all rows by default
        document.getElementById('table_keluarga').style.display = 'none';
        document.getElementById('row_istri').style.display = 'none';
        document.getElementById('row_anak1').style.display = 'none';
        document.getElementById('row_anak2').style.display = 'none';
        document.getElementById('row_anak3').style.display = 'none';

        // Reset all required attributes
        document.querySelectorAll('#row_istri input, #row_anak1 input, #row_anak2 input, #row_anak3 input').forEach(function(input) {
            input.required = false;
        });

        // Show the table if any family members are required
        let shouldShowTable = false;

        // Handle spouse visibility and required attribute
        if (status.startsWith('K')) {
            document.getElementById('row_istri').style.display = 'table-row'; // show spouse
            shouldShowTable = true;

            // Make spouse fields required
            document.querySelectorAll('#row_istri input').forEach(function(input) {
                input.required = true;
            });
        }

        // Handle anak1 visibility and required attribute
        if (status.includes('1')) {
            document.getElementById('row_anak1').style.display = 'table-row'; // show first child
            shouldShowTable = true;

            // Make anak1 fields required
            document.querySelectorAll('#row_anak1 input').forEach(function(input) {
                input.required = true;
            });
        }

        // Handle anak2 visibility and required attributex
        if (status.includes('2')) {
            document.getElementById('row_anak1').style.display = 'table-row'; // ensure first child is shown
            document.getElementById('row_anak2').style.display = 'table-row'; // show second child
            shouldShowTable = true;

            // Make anak1 and anak2 fields required
            document.querySelectorAll('#row_anak1 input, #row_anak2 input').forEach(function(input) {
                input.required = true;
            });
        }

        // Handle anak3 visibility and required attribute
        if (status.includes('3')) {
            document.getElementById('row_anak1').style.display = 'table-row'; // ensure first child is shown
            document.getElementById('row_anak2').style.display = 'table-row'; // ensure second child is shown
            document.getElementById('row_anak3').style.display = 'table-row'; // show third child
            shouldShowTable = true;

            // Make anak1, anak2, and anak3 fields required
            document.querySelectorAll('#row_anak1 input, #row_anak2 input, #row_anak3 input').forEach(function(input) {
                input.required = true;
            });
        }

        // If any row is shown, display the table
        if (shouldShowTable) {
            document.getElementById('table_keluarga').style.display = 'block';
        }
    });


    // Trigger change event on page load to set initial table visibility
    document.getElementById('status_pajak').dispatchEvent(new Event('change'));

    let siblingCount = 1;

    // Add event listener for the Add Sibling button
    document.getElementById('add_sibling_btn').addEventListener('click', function() {
        siblingCount++;
        const tableBody = document.getElementById('family_table_body_2');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
        <td>Anak ke ${siblingCount}</td>
        <td><input class="form-control" type="text" name="family1_nama_lengkap_anak${siblingCount}"></td>
        <td><input class="form-control" type="text" name="family1_jenis_anak${siblingCount}"></td>
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
            <td><input class="form-control" type="text" name="_${bahasaCount}_steno"></td>
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
        let pekerjaanCount = 1;

        document.getElementById('add_pekerjaan_btn').addEventListener('click', function() {
            pekerjaanCount++;
            const tableBody = document.getElementById('pekerjaan_table_body');
            const newRow = document.createElement('tr');

            newRow.id = 'row_' + pekerjaanCount;
            newRow.innerHTML = `
            <td><input class="form-control" type="text" name="pekerjaan_${pekerjaanCount}_perusahaan"></td>
            <td><input class="form-control" type="text" name="pekerjaan_${pekerjaanCount}_alamat"></td>
            <td><input class="form-control" type="text" name="pekerjaan_${pekerjaanCount}_jabatan"></td>
            <td><input class="form-control" type="date" name="pekerjaan_${pekerjaanCount}_dari"></td>
            <td><input class="form-control" type="date" name="pekerjaan_${pekerjaanCount}_sampai"></td>
            <td><input class="form-control" type="text" name="pekerjaan_${pekerjaanCount}_keterangan"></td>
            <td><input class="form-control" type="text" name="pekerjaan_${pekerjaanCount}_alasan"></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">-</button></td>
        `;

            tableBody.appendChild(newRow);
        });

        function removeRow(button) {
            // Remove the row when the "-" button is clicked
            button.closest('tr').remove();
        }
    });

    $(function() {
        $('#formCandidate').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting by default

            // var fields = [
            //     // Existing fields
            //     {
            //         id: '#nama_lengkap',
            //         message: 'Nama Lengkap Harus Diisi'
            //     },
            //     {
            //         id: '#nama_panggilan',
            //         message: 'Nama Panggilan Harus Diisi'
            //     },
            //     {
            //         id: 'select[name="jenis"]',
            //         message: 'Jenis Kelamin Harus Diisi'
            //     },
            //     {
            //         id: 'select[name="gol_darah"]',
            //         message: 'Golongan Darah Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="tgl_lahir"]',
            //         message: 'Tanggal Lahir Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="warga_negara"]',
            //         message: 'Warga Negara Harus Diisi'
            //     },
            //     {
            //         id: 'textarea[name="alamat_rumah"]',
            //         message: 'Alamat Rumah Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="telp_rumah_hp"]',
            //         message: 'Telpon Rumah/HP Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="no_ktp_sim"]',
            //         message: 'No. KTP/SIM Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="tgl_ktp_sim"]',
            //         message: 'Tanggal Berlaku KTP/SIM Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="no_npwp"]',
            //         message: 'No. NPWP Harus Diisi'
            //     },
            //     {
            //         id: 'textarea[name="alamat_npwp"]',
            //         message: 'Alamat NPWP Harus Diisi'
            //     },
            //     {
            //         id: 'select[name="status_pajak"]',
            //         message: 'Status Pajak Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="tgl_menikah"]',
            //         message: 'Tanggal Menikah Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="jabatan"]',
            //         message: 'Jabatan Saat Ini Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="nama_perusahaan"]',
            //         message: 'Nama Perusahaan Harus Diisi'
            //     },
            //     {
            //         id: 'textarea[name="alamat_perusahaan"]',
            //         message: 'Alamat Perusahaan Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="alamat_email"]',
            //         message: 'Alamat Email Harus Diisi'
            //     },

            //     // New family fields
            //     {
            //         id: 'input[name="family1_nama_lengkap_ayah"]',
            //         message: 'Nama Lengkap Ayah Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_jenis_ayah"]',
            //         message: 'Jenis Kelamin Ayah Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_tgl_lahir_ayah"]',
            //         message: 'Tanggal Lahir Ayah Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_pendidikan_ayah"]',
            //         message: 'Pendidikan Ayah Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_pekerjaan_ayah"]',
            //         message: 'Pekerjaan Ayah Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_keterangan_ayah"]',
            //         message: 'Keterangan Ayah Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_nama_lengkap_ibu"]',
            //         message: 'Nama Lengkap Ibu Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_jenis_ibu"]',
            //         message: 'Jenis Kelamin Ibu Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_tgl_lahir_ibu"]',
            //         message: 'Tanggal Lahir Ibu Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_pendidikan_ibu"]',
            //         message: 'Pendidikan Ibu Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_pekerjaan_ibu"]',
            //         message: 'Pekerjaan Ibu Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_keterangan_ibu"]',
            //         message: 'Keterangan Ibu Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_nama_lengkap_anak1"]',
            //         message: 'Nama Lengkap Anak ke-1 Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_jenis_anak1"]',
            //         message: 'Jenis Kelamin Anak ke-1 Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_tgl_lahir_anak1"]',
            //         message: 'Tanggal Lahir Anak ke-1 Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_pendidikan_anak1"]',
            //         message: 'Pendidikan Anak ke-1 Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_pekerjaan_anak1"]',
            //         message: 'Pekerjaan Anak ke-1 Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="family1_keterangan_anak1"]',
            //         message: 'Keterangan Anak ke-1 Harus Diisi'
            //     },
            //     {
            //         id: 'select[name="tanggung_jawab"]',
            //         message: 'Tanggung Jawab Harus Diisi'
            //     },
            //     {
            //         id: 'select[name="rumah_status"]',
            //         message: 'Status Rumah Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="melanjut_pendidikan"]',
            //         message: 'Perlanjutan Pendidikan Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="language_1_bahasa"]',
            //         message: 'Bahasa Inggris Harus Diisi'
            //     },
            //     {
            //         id: 'select[name="language_1_bicara"]',
            //         message: 'Bicara Bahasa Harus Diisi'
            //     },
            //     {
            //         id: 'select[name="language_1_baca"]',
            //         message: 'Bicara Bahasa Harus Diisi'
            //     },
            //     {
            //         id: 'select[name="language_1_tulis"]',
            //         message: 'Tulis Bahasa Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="language_1_steno"]',
            //         message: 'Steno Inggris Harus Diisi'
            //     },
            //     {
            //         id: 'textarea[name="alasan_pekerjaan_terakhir"]',
            //         message: 'Alasan Meninggalkan Pekerjaan Terakhir Harus Diisi'
            //     },
            //     {
            //         id: 'textarea[name="uraian_pekerjaan_terakhir"]',
            //         message: 'Uraian Pekejeraan Jabatan Terakhir Harus Diisi'
            //     },
            //     {
            //         id: 'textarea[name="saudara_pekerjaan"]',
            //         message: 'Saudara Melamar Harus Diisi'
            //     },
            //     {
            //         id: 'textarea[name="organisasi"]',
            //         message: 'Organisasi Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="em_nama"]',
            //         message: 'Nama Emergency Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="em_alamat"]',
            //         message: 'Alamat Emergency Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="em_telp"]',
            //         message: 'Telpon Emergency Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="em_status"]',
            //         message: 'Hubungan Emergency Harus Diisi'
            //     },
            //     {
            //         id: 'textarea[name="sakit_lama"]',
            //         message: 'Saudara Pernah Menderita Sakit Yang Lama Harus Diisi'
            //     },
            //     {
            //         id: '#gambaran_posisi',
            //         message: 'Upload Gambaran Posisi Harus Diisi'
            //     },
            //     {
            //         id: 'select[name="masa_percobaan"]',
            //         message: 'Masa Percobaan Harus Diisi'
            //     },
            //     {
            //         id: 'select[name="proses_bi"]',
            //         message: 'Proses BI Harus Diisi'
            //     },
            //     {
            //         id: 'input[name="mulai_kerja"]',
            //         message: 'Mulai Kerja Harus Diisi'
            //     },
            // ];

            // // Validate all fields
            // for (let i = 0; i < fields.length; i++) {
            //     var field = fields[i];
            //     if ($(field.id).val() === "") {
            //         Swal.fire({
            //             title: 'Warning!',
            //             text: field.message,
            //             icon: 'warning',
            //             confirmButtonText: 'Ok'
            //         }).then(() => {
            //             $(field.id).focus();
            //         });
            //         return false; // Stop the form submission and show the alert
            //     }
            // }
            this.submit();
        });

    });
</script>
@endpush
