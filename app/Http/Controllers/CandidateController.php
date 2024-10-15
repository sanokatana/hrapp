<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidateRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function candidate_data()
    {
        // Get the currently authenticated candidate
        $candidate = Auth::guard('candidate')->user();
        $candidateId = $candidate->id;

        // Check if candidate data exists
        $candidateData = DB::table('candidate_data')->where('candidate_id', $candidateId)->first();

        // If candidateData exists, return 'recruitment.form.view' view
        if ($candidateData) {


            $candidate = DB::table('candidates')->where('id', $candidateId)->first();
            // Get all records from candidate_data_keluarga related to the candidateData's id
            $candidateFamilyData = DB::table('candidate_data_keluarga')
                ->where('candidate_data_id', $candidateData->id)
                ->get();

            $candidateFamilyDataSendiri = DB::table('candidate_data_keluarga_sendiri')
                ->where('candidate_data_id', $candidateData->id)
                ->get();

            $candidatePendidikan = DB::table('candidate_data_pendidikan')
                ->where('candidate_data_id', $candidateData->id)
                ->get();

            $candidateKursus = DB::table('candidate_data_kursus')
                ->where('candidate_data_id', $candidateData->id)
                ->get();

            $candidateBahasa = DB::table('candidate_data_bahasa')
                ->where('candidate_data_id', $candidateData->id)
                ->get();

            $candidatePekerjaan = DB::table('candidate_data_pekerjaan')
                ->where('candidate_data_id', $candidateData->id)
                ->get();
            // Return the 'recruitment.form.view' view along with candidate data and family data
            return view('recruitment.form.view', compact('candidateData', 'candidate', 'candidateFamilyData', 'candidatePekerjaan', 'candidateId', 'candidateFamilyDataSendiri', 'candidateBahasa', 'candidatePendidikan', 'candidateKursus'));
        }

        // Otherwise, return 'recruitment.form.index' view
        return view('recruitment.form.index', compact('candidateId'));
    }


    public function candidate_store_form(StoreCandidateRequest $request)
    {
        $data = $request->validated();

        // Add candidate_id manually
        $candidate = Auth::guard('candidate')->user();
        $candidateId = $candidate->id;
        $jobOpeningId = $candidate->job_opening_id;
        $jobOpening = DB::table('job_openings')->where('id', $jobOpeningId)->first();
        $recruitmentTypeId = $jobOpening->recruitment_type_id;
        $candidateUser = $candidate->nama_candidate;
        $data['candidate_id'] = $candidateId;

        $currentDate = Carbon::now();

        $manager = new ImageManager();

        // Create the folder path
        $folderPath = public_path('storage/uploads/candidate/' . $candidateId . '.' . Str::slug($candidateUser) . '/');

        // Create the directory if it doesn't exist
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // Handle the file upload for 'gambaran_posisi'
        if ($request->hasFile('gambaran_posisi')) {
            $file = $request->file('gambaran_posisi');
            $extension = $file->getClientOriginalExtension();

            // Generate a unique file name
            $fileName = $candidateId . "_" . "gambaran_posisi" . "_" . uniqid() . "." . $extension;

            // Create an Intervention Image instance
            $image = $manager->make($file);

            // Resize and compress the image to keep it under 1 MB
            $image->resize(null, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // Prevent upsizing
            });

            // Set the initial quality to 75% and save the image until it's under 1 MB
            $quality = 75;
            do {
                $image->save($folderPath . $fileName, $quality);
                if (filesize($folderPath . $fileName) > 1048576) { // 1 MB in bytes
                    $quality -= 5; // Reduce quality by 5%
                } else {
                    break; // Break if the file is under 1 MB
                }
            } while ($quality > 0);

            // Assign the file name to the gambaran_posisi field
            $data['gambaran_posisi'] = $fileName;
        } else {
            $data['gambaran_posisi'] = "No_Document"; // Handle the case where no file is uploaded
        }

        // Function to handle slip uploads
        $slips = ['slip1', 'slip2', 'slip3'];
        foreach ($slips as $slip) {
            if ($request->hasFile($slip)) {
                $file = $request->file($slip);
                $extension = $file->getClientOriginalExtension();

                // Generate a unique file name for each slip
                $fileName = ucfirst($slip) . "_" . $candidateId . "_" . uniqid() . "." . $extension;

                // Check if the file is an image
                if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    // Create an Intervention Image instance
                    $image = $manager->make($file);

                    // Resize and compress the image to keep it under 1 MB
                    $image->resize(null, 800, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize(); // Prevent upsizing
                    });

                    // Set the initial quality to 75% and save the image until it's under 1 MB
                    $quality = 75;
                    do {
                        $image->save($folderPath . $fileName, $quality);
                        if (filesize($folderPath . $fileName) > 1048576) { // 1 MB in bytes
                            $quality -= 5; // Reduce quality by 5%
                        } else {
                            break; // Break if the file is under 1 MB
                        }
                    } while ($quality > 0);
                } else {
                    // Store other file types (like PDF) without modification
                    $file->move($folderPath, $fileName);
                }

                // Assign the file name to the respective slip field
                $data[$slip] = $fileName;
            } else {
                $data[$slip] = "No_Document"; // Handle the case where no file is uploaded
            }
        }

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
                'K' => [
                    ['uraian' => 'Istri/Suami', 'key' => 'istri_suami']
                ],
                'K1' => [
                    ['uraian' => 'Istri/Suami', 'key' => 'istri_suami'],
                    ['uraian' => 'Anak ke 1', 'key' => 'anak1']
                ],
                'K2' => [
                    ['uraian' => 'Istri/Suami', 'key' => 'istri_suami'],
                    ['uraian' => 'Anak ke 1', 'key' => 'anak1'],
                    ['uraian' => 'Anak ke 2', 'key' => 'anak2']
                ],
                'K3' => [
                    ['uraian' => 'Istri/Suami', 'key' => 'istri_suami'],
                    ['uraian' => 'Anak ke 1', 'key' => 'anak1'],
                    ['uraian' => 'Anak ke 2', 'key' => 'anak2'],
                    ['uraian' => 'Anak ke 3', 'key' => 'anak3']
                ],
            ];

            $familyDataKeluarga = [];
            $familyType = $request->input('status_pajak');
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

            foreach (['Dasar', 'SLTP', 'SLTA', 'Diploma', 'Strata I', 'Strata II', 'Lain-Lain'] as $index => $level) {
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
                // Check if at least one field is not empty
                if (!empty($kursus['nama']) || !empty($kursus['diadakan']) || !empty($kursus['tempat']) ||
                    !empty($kursus['lama']) || !empty($kursus['tahun']) || !empty($kursus['dibiayai']) ||
                    !empty($kursus['keterangan'])) {

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
            }

            $languageData = [];

            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'language_') === 0) {
                    $parts = explode('_', $key);
                    $index = $parts[1];
                    $field = $parts[2];

                    if (!isset($languageData[$index])) {
                        $languageData[$index] = [];
                    }

                    $languageData[$index][$field] = $value;
                }
            }

            foreach ($languageData as $index => $language) {
                DB::table('candidate_data_bahasa')->insert([
                    'candidate_data_id' => $candidateDataId,
                    'bahasa' => $language['bahasa'] ?? null,
                    'bicara' => $language['bicara'] ?? null,
                    'baca' => $language['baca'] ?? null,
                    'tulis' => $language['tulis'] ?? null,
                    'steno' => $language['steno'] ?? null,
                ]);
            }

            $kerjaData = [];

            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'pekerjaan_') === 0) {
                    $parts = explode('_', $key);
                    $index = $parts[1];
                    $field = $parts[2];

                    if (!isset($kerjaData[$index])) {
                        $kerjaData[$index] = [];
                    }

                    $kerjaData[$index][$field] = $value;
                }
            }

            foreach ($kerjaData as $index => $kerja) {
                // Check if at least one of the fields is not empty
                if (!empty($kerja['perusahaan']) || !empty($kerja['alamat']) || !empty($kerja['jabatan']) ||
                    !empty($kerja['dari']) || !empty($kerja['sampai']) || !empty($kerja['keterangan']) ||
                    !empty($kerja['alasan'])) {

                    DB::table('candidate_data_pekerjaan')->insert([
                        'candidate_data_id' => $candidateDataId,
                        'perusahaan' => $kerja['perusahaan'] ?? null,
                        'alamat' => $kerja['alamat'] ?? null,
                        'jabatan' => $kerja['jabatan'] ?? null,
                        'dari' => $kerja['dari'] ?? null,
                        'sampai' => $kerja['sampai'] ?? null,
                        'keterangan' => $kerja['keterangan'] ?? null,
                        'alasan' => $kerja['alasan'] ?? null,
                    ]);
                }
            }

            $stage = DB::table('hiring_stages')
                ->where('recruitment_type_id', $recruitmentTypeId)
                ->where('type', 'Form Filled')
                ->first();

            // If the stage is found, update the candidate's current_stage_id
            if ($stage) {
                DB::table('candidates')
                    ->where('id', $candidateId)
                    ->update(['current_stage_id' => $stage->id]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Error inserting candidate data', ['message' => $e->getMessage()]);
            return redirect()->back()->with(['danger' => 'Data Gagal Disimpan']);
        }
    }

    public function files()
    {
        return view('recruitment.files.index');
    }

    public function candidate_data_perlengkapan()
    {

        $candidate = Auth::guard('candidate')->user();
        $candidateId = $candidate->id;

        $candidate = DB::table('candidates')->where('id', $candidateId)->first();
        // Check if candidate data exists
        $candidateData = DB::table('candidate_data')->where('candidate_id', $candidateId)->first();

        $candidateDataLengkap = DB::table('candidate_data_perlengkapan')->where('candidate_data_id', $candidateData->id)->first();

        if ($candidateData) {
            $keluargaData = DB::table('candidate_data_keluarga')->where('candidate_data_id', $candidateData->id)->get();
        } else {
            $keluargaData = collect(); // Empty collection if no candidate data
        }

        // Otherwise, return 'recruitment.form.index' view
        return view('recruitment.perlengkapan.index', compact('keluargaData', 'candidateDataLengkap', 'candidate'));
    }

    public function storePerlengkapan(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();
        $candidateId = $candidate->id;

        // Get candidate data
        $candidateData = DB::table('candidate_data')->where('candidate_id', $candidateId)->first();

        // Fetch existing candidate data for perlengkapan
        $candidateDataPerlengkapan = DB::table('candidate_data_perlengkapan')
            ->where('candidate_data_id', $candidateData->id)
            ->first();

        $candidateUser = $candidate->nama_candidate;
        $manager = new ImageManager();
        $folderPath = public_path('storage/uploads/candidate/' . $candidateId . '.' . Str::slug($candidateUser) . '/');

        // Create directory if not exists
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $data = [];

        // Handle multiple file uploads
        $fields = ['photo_ktp', 'photo_kk', 'photo_sim', 'photo_npwp', 'photo_ijazah', 'photo_anda'];
        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $extension = $file->getClientOriginalExtension();

                // Generate unique file name
                $fileName = $candidateId . "_" . str_replace('photo_', '', $field) . "_" . uniqid() . "." . $extension;

                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                    // Compress and resize the image
                    $image = $manager->make($file)->resize(null, 800, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    // Save the image with a quality under 1 MB
                    $quality = 75;
                    do {
                        $image->save($folderPath . $fileName, $quality);
                        if (filesize($folderPath . $fileName) > 1048576) {
                            $quality -= 5;
                        } else {
                            break;
                        }
                    } while ($quality > 0);
                } elseif ($extension == 'pdf') {
                    // For PDF files, just move the file to the designated folder
                    $file->move($folderPath, $fileName);
                } else {
                    continue; // Skip unsupported file types
                }

                // Save file name in the data array
                $data[$field] = $fileName;
            } else {
                // If no file is uploaded, retain the existing value from the database
                $data[$field] = $candidateDataPerlengkapan ? $candidateDataPerlengkapan->{$field} : "No_Document";
            }
        }

        // Check if record exists, then update or insert accordingly
        if ($candidateDataPerlengkapan) {
            // Update the record
            DB::table('candidate_data_perlengkapan')->where('candidate_data_id', $candidateData->id)->update([
                'photo_ktp' => $data['photo_ktp'],
                'photo_kk' => $data['photo_kk'],
                'photo_sim' => $data['photo_sim'],
                'photo_npwp' => $data['photo_npwp'],
                'photo_ijazah' => $data['photo_ijazah'],
                'photo_anda' => $data['photo_anda'],
                'updated_at' => now(),
            ]);
        } else {
            // Insert new record
            DB::table('candidate_data_perlengkapan')->insert([
                'candidate_data_id' => $candidateData->id,
                'photo_ktp' => $data['photo_ktp'],
                'photo_kk' => $data['photo_kk'],
                'photo_sim' => $data['photo_sim'],
                'photo_npwp' => $data['photo_npwp'],
                'photo_ijazah' => $data['photo_ijazah'],
                'photo_anda' => $data['photo_anda'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update nik and tempat_lahir in candidate_data_keluarga
        if ($request->has('nik') && $request->has('tempat_lahir') && $request->has('keluarga_id')) {
            foreach ($request->nik as $index => $nik) {
                // Use the actual keluarga_id from the form
                $keluargaId = $request->keluarga_id[$index];
                DB::table('candidate_data_keluarga')
                    ->where('id', $keluargaId)
                    ->update([
                        'nik' => $nik,
                        'tempat_lahir' => $request->tempat_lahir[$index],
                        'updated_at' => now()
                    ]);
            }
        }

        return redirect()->back()->with('success', 'Data successfully saved!');
    }
}
