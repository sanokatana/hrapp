<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class CandidateController extends Controller
{
    public function candidate_data()
    {
        return view("recruitment.form.index");
    }

    public function candidate_store(StoreCandidateRequest $request)
    {

        $data = $request->validated();

        // Add candidate_id manually
        $candidateId = Auth::guard('candidate')->user()->id;
        $data['candidate_id'] = $candidateId;

        DB::beginTransaction();

        try {
            // Save the candidate data to the database and get the ID
            $candidateDataId = DB::table('candidate_data')->insertGetId($data);

            // Prepare and insert family data into candidate_data_keluarga_sendiri
            // Fixed family data for Ayah and Ibu
            $fixedFamilyDataSendiri = [
                ['uraian' => 'Ayah', 'key' => 'ayah'],
                ['uraian' => 'Ibu', 'key' => 'ibu'],
            ];

            $familyDataSendiri = []; // Initialize array for storing data

            foreach ($fixedFamilyDataSendiri as $entry) {
                $familyDataSendiri[] = [
                    'candidate_data_id' => $candidateDataId,
                    'uraian' => $entry['uraian'],
                    'nama_lengkap' => $request->input('family1_nama_lengkap_' . $entry['key']),
                    'jenis' => $request->input('family1_jenis_' . $entry['key']),
                    'tgl_lahir' => $request->input('family1_tgl_lahir_' . $entry['key']),
                    'pendidikan' => $request->input('family1_pendidikan_' . $entry['key']),
                    'pekerjaan' => $request->input('family1_pekerjaan_' . $entry['key']),
                    'keterangan' => $request->input('family1_keterangan_' . $entry['key']),
                ];
            }

            // Process dynamically added siblings
            $siblingCount = $request->input('sibling_count'); // Assuming you keep track of sibling count
            for ($i = 1; $i <= $siblingCount; $i++) {
                $familyDataSendiri[] = [
                    'candidate_data_id' => $candidateDataId,
                    'uraian' => 'Anak ke ' . $i,
                    'nama_lengkap' => $request->input('family1_nama_lengkap_anak' . $i),
                    'jenis' => $request->input('family1_jenis_anak' . $i),
                    'tgl_lahir' => $request->input('family1_tgl_lahir_anak' . $i),
                    'pendidikan' => $request->input('family1_pendidikan_anak' . $i),
                    'pekerjaan' => $request->input('family1_pekerjaan_anak' . $i),
                    'keterangan' => $request->input('family1_keterangan_anak' . $i),
                ];
            }

            // Insert family data into candidate_data_keluarga_sendiri
            if (!empty($familyDataSendiri)) {
                DB::table('candidate_data_keluarga_sendiri')->insert($familyDataSendiri);
            }

            // Prepare and insert family data into candidate_data_keluarga

            $fixedFamilyData = [
                'TK' => [],
                'TK1' => [
                    ['uraian' => 'Anak ke 1', 'key' => 'anak1']
                ],
                'TK2' => [
                    ['uraian' => 'Anak ke 1', 'key' => 'anak1'],
                    ['uraian' => 'Anak ke 2', 'key' => 'anak2']
                ],
                'TK3' => [
                    ['uraian' => 'Anak ke 1', 'key' => 'anak1'],
                    ['uraian' => 'Anak ke 2', 'key' => 'anak2'],
                    ['uraian' => 'Anak ke 3', 'key' => 'anak3']
                ],
                'M' => [
                    ['uraian' => 'Istri/Suami', 'key' => 'istri_suami']
                ],
                'M1' => [
                    ['uraian' => 'Istri/Suami', 'key' => 'istri_suami'],
                    ['uraian' => 'Anak ke 1', 'key' => 'anak1']
                ],
                'M2' => [
                    ['uraian' => 'Istri/Suami', 'key' => 'istri_suami'],
                    ['uraian' => 'Anak ke 1', 'key' => 'anak1'],
                    ['uraian' => 'Anak ke 2', 'key' => 'anak2']
                ],
                'M3' => [
                    ['uraian' => 'Istri/Suami', 'key' => 'istri_suami'],
                    ['uraian' => 'Anak ke 1', 'key' => 'anak1'],
                    ['uraian' => 'Anak ke 2', 'key' => 'anak2'],
                    ['uraian' => 'Anak ke 3', 'key' => 'anak3']
                ],
            ];

            $familyDataKeluarga = [];
            $familyType = $request->input('status_keluarga');
            $familyConfig = $fixedFamilyData[$familyType] ?? [];

            foreach ($familyConfig as $entry) {
                $familyDataKeluarga[] = [
                    'candidate_data_id' => $candidateDataId,
                    'uraian' => $entry['uraian'],
                    'nama_lengkap' => $request->input('family_nama_lengkap_' . $entry['key']),
                    'jenis' => $request->input('family_jenis_' . $entry['key']),
                    'tgl_lahir' => $request->input('family_tgl_lahir_' . $entry['key']),
                    'pendidikan' => $request->input('family_pendidikan_' . $entry['key']),
                    'pekerjaan' => $request->input('family_pekerjaan_' . $entry['key']),
                    'keterangan' => $request->input('family_keterangan_' . $entry['key']),
                ];
            }

            // Insert family data into candidate_data_keluarga
            if (!empty($familyDataKeluarga)) {
                DB::table('candidate_data_keluarga')->insert($familyDataKeluarga);
            }

            foreach(['Dasar', 'SLTP', 'SLTA', 'Diploma', 'Strata I', 'Strata II', 'Lain-Lain'] as $index => $level) {
                // Retrieve input data for the current index
                $namaSekolah = $request->input('nama_sekolah_' . $index);
                $tempatSekolah = $request->input('tempat_sekolah_' . $index);
                $jurusanStudi = $request->input('jurusan_studi_' . $index);
                $dari = $request->input('dari_' . $index);
                $sampai = $request->input('sampai_' . $index);
                $berijazah = $request->input('berijazah_' . $index);
                $keterangan = $request->input('keterangan_' . $index);

                // Check if any of the fields are filled
                if (!empty($namaSekolah) || !empty($tempatSekolah) || !empty($jurusanStudi) || !empty($dari) || !empty($sampai) || !empty($berijazah) || !empty($keterangan)) {
                    // Insert data into the database
                    DB::table('candidate_data_pendidikan')->insert([
                        'candidate_data_id' => $candidateDataId,
                        'tingkat_besar' => $level,
                        'nama_sekolah' => $namaSekolah,
                        'tempat_sekolah' => $tempatSekolah,
                        'jurusan_studi' => $jurusanStudi,
                        'dari' => $dari,
                        'sampai' => $sampai,
                        'berijazah' => $berijazah,
                        'keterangan' => $keterangan,
                    ]);
                }
            }

            $kursusData = [];

            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'kursus_') === 0) {
                    $parts = explode('_', $key);
                    $index = $parts[1];
                    $field = $parts[2];

                    if (!isset($kursusData[$index])) {
                        $kursusData[$index] = [];
                    }

                    $kursusData[$index][$field] = $value;
                }
            }

            foreach ($kursusData as $index => $kursus) {
                DB::table('candidate_data_kursus')->insert([
                    'candidate_data_id' => $candidateDataId,
                    'nama' => $kursus['nama'] ?? null,
                    'diadakan_oleh' => $kursus['diadakan'] ?? null,
                    'tempat' => $kursus['tempat'] ?? null,
                    'lama' => $kursus['lama'] ?? null,
                    'tahun' => $kursus['tahun'] ?? null,
                    'dibiayai_oleh' => $kursus['dibiayai'] ?? null,
                    'keterangan' => $kursus['keterangan'] ?? null,
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();

            // Log the error and show a danger message
            Log::error($e->getMessage());
            return redirect()->back()->with(['danger' => 'Data Gagal Disimpan']);
        }
    }
}
