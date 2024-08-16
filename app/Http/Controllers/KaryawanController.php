<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\ShiftPattern;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();
        $query->select('karyawan.*', 'department.nama_dept', 'jabatan.nama_jabatan');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        $query->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id');
        $query->orderBy('karyawan.tgl_masuk', 'asc');


        if (!empty($request->nama_karyawan)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }

        $karyawan = $query->paginate(15)->appends($request->except('page'));
        $shift = ShiftPattern::all(); // Assuming you have a Shift model and you want all shifts
        $department = DB::table('department')->get();
        $jabatan = DB::table('jabatan')->get();
        $location = DB::table('konfigurasi_lokasi')->get();
        return view("karyawan.index", compact('karyawan', 'department', 'jabatan', 'location', 'shift'));
    }


    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make('chl12345');

        if ($request->hasFile('foto')) {
            $data['foto'] = $data['nik'] . '.' . $request->file('foto')->getClientOriginalExtension();
        }

        try {
            $simpan = DB::table('karyawan')->insert($data);
            if ($simpan) {
                if ($request->hasFile('foto')) {
                    $folderPath = 'public/uploads/karyawan/';
                    $request->file('foto')->storeAs($folderPath, $data['foto']);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return Redirect::back()->with(['danger' => 'Data Gagal Di Simpan']);
        }
    }



    public function edit(Request $request)
    {

        $nik = $request->nik;
        $department = DB::table('department')->get();
        $jabatan = DB::table('jabatan')->get();
        $location = DB::table('konfigurasi_lokasi')->get();
        $karyawan = DB::table('karyawan')
            ->where('nik', $nik)
            ->first();
        return view('karyawan.edit', compact('department', 'karyawan', 'jabatan', 'location'));
    }

    public function update($nik, StoreEmployeeRequest $request)
    {
        $data = $request->validated();
        $old_foto = $request->old_foto;

        if ($request->hasFile('foto')) {
            $data['foto'] = $data['nik'] . '.' . $request->file('foto')->getClientOriginalExtension();
        } else {
            $data['foto'] = $old_foto;
        }

        try {
            $update = DB::table('karyawan')->where('nik', $nik)->update($data);
            if ($update) {
                if ($request->hasFile('foto')) {
                    $folderPath = 'public/uploads/karyawan/';
                    $folderPathOld = 'public/uploads/karyawan/' . $old_foto;
                    Storage::delete($folderPathOld);
                    $request->file('foto')->storeAs($folderPath, $data['foto']);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['danger' => 'Data Gagal Di Update']);
        }
    }

    public function delete($nik)
    {
        $delete = DB::table('karyawan')->where('nik', $nik)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

    public function downloadTemplateKar()
    {
        $filePath = public_path('storage/uploads/kar/template_karyawan.xlsx');

        return response()->download($filePath, 'template_karyawan.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    public function uploadKaryawan(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Redirect back if validation fails
        if ($validator->fails()) {
            return redirect()->back()->with('danger', 'Please upload a valid XLSX file.');
        }

        try {
            // Get the uploaded file and load the spreadsheet
            $file = $request->file('file');
            $filePath = $file->getRealPath();
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Read the data from the spreadsheet
            $header = [];
            $data = [];
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }

                if ($rowIndex == 1) {
                    $header = $rowData;
                } else {
                    $mappedData = array_combine($header, $rowData);

                    // Convert Excel date serial numbers to date strings
                    $tglMasuk = Date::excelToDateTimeObject($mappedData['tgl_masuk'])->format('Y-m-d');
                    $tglResign = !empty($mappedData['tgl_resign']) ? Date::excelToDateTimeObject($mappedData['tgl_resign'])->format('Y-m-d') : null;
                    $dob = Date::excelToDateTimeObject($mappedData['DOB'])->format('Y-m-d');
                    $fdSiDob = !empty($mappedData['fd_si_dob']) ? Date::excelToDateTimeObject($mappedData['fd_si_dob'])->format('Y-m-d') : null;
                    $fdAnak1Dob = !empty($mappedData['fd_anak1_dob']) ? Date::excelToDateTimeObject($mappedData['fd_anak1_dob'])->format('Y-m-d') : null;
                    $fdAnak2Dob = !empty($mappedData['fd_anak2_dob']) ? Date::excelToDateTimeObject($mappedData['fd_anak2_dob'])->format('Y-m-d') : null;
                    $fdAnak3Dob = !empty($mappedData['fd_anak3_dob']) ? Date::excelToDateTimeObject($mappedData['fd_anak3_dob'])->format('Y-m-d') : null;

                    $data[] = [
                        'nik' => $mappedData['nik'],
                        'nip' => $mappedData['nip'],
                        'nama_lengkap' => $mappedData['nama_lengkap'],
                        'jabatan' => $mappedData['jabatan'],
                        'email' => $mappedData['email'],
                        'no_hp' => $mappedData['no_hp'],
                        'tgl_masuk' => $tglMasuk,
                        'tgl_resign' => $tglResign,
                        'DOB' => $dob,
                        'kode_dept' => $mappedData['kode_dept'],
                        'grade' => $mappedData['grade'],
                        'employee_status' => $mappedData['employee_status'],
                        'base_poh' => $mappedData['base_poh'],
                        'nama_pt' => $mappedData['nama_pt'],
                        'sex' => $mappedData['sex'],
                        'marital_status' => $mappedData['marital_status'],
                        'birthplace' => $mappedData['birthplace'],
                        'religion' => $mappedData['religion'],
                        'address' => $mappedData['address'],
                        'address_rt' => $mappedData['address_rt'],
                        'address_rw' => $mappedData['address_rw'],
                        'address_kel' => $mappedData['address_kel'],
                        'address_kec' => $mappedData['address_kec'],
                        'address_kota' => $mappedData['address_kota'],
                        'address_prov' => $mappedData['address_prov'],
                        'kode_pos' => $mappedData['kode_pos'],
                        'nik_ktp' => $mappedData['nik_ktp'],
                        'blood_type' => $mappedData['blood_type'],
                        'gelar' => $mappedData['gelar'],
                        'major' => $mappedData['major'],
                        'kampus' => $mappedData['kampus'],
                        'job_exp' => $mappedData['job_exp'],
                        'email_personal' => $mappedData['email_personal'],
                        'family_card' => $mappedData['family_card'],
                        'no_npwp' => $mappedData['no_npwp'],
                        'alamat_npwp' => $mappedData['alamat_npwp'],
                        'bpjstk' => $mappedData['bpjstk'],
                        'bpjskes' => $mappedData['bpjskes'],
                        'rek_no' => $mappedData['rek_no'],
                        'bank_name' => $mappedData['bank_name'],
                        'rek_name' => $mappedData['rek_name'],
                        'father_name' => $mappedData['father_name'],
                        'mother_name' => $mappedData['mother_name'],
                        'fd_si_name' => $mappedData['fd_si_name'],
                        'fd_si_nik' => $mappedData['fd_si_nik'],
                        'fd_si_kota' => $mappedData['fd_si_kota'],
                        'fd_si_dob' => $fdSiDob,
                        'fd_anak1_name' => $mappedData['fd_anak1_name'],
                        'fd_anak1_nik' => $mappedData['fd_anak1_nik'],
                        'fd_anak1_kota' => $mappedData['fd_anak1_kota'],
                        'fd_anak1_dob' => $fdAnak1Dob,
                        'fd_anak2_name' => $mappedData['fd_anak2_name'],
                        'fd_anak2_nik' => $mappedData['fd_anak2_nik'],
                        'fd_anak2_kota' => $mappedData['fd_anak2_kota'],
                        'fd_anak2_dob' => $fdAnak2Dob,
                        'fd_anak3_name' => $mappedData['fd_anak3_name'],
                        'fd_anak3_nik' => $mappedData['fd_anak3_nik'],
                        'fd_anak3_kota' => $mappedData['fd_anak3_kota'],
                        'fd_anak3_dob' => $fdAnak3Dob,
                        'em_name' => $mappedData['em_name'],
                        'em_telp' => $mappedData['em_telp'],
                        'em_relation' => $mappedData['em_relation'],
                        'em_alamat' => $mappedData['em_alamat'],
                    ];
                }
            }

            // Insert data into the database
            DB::table('karyawan')->insert($data);

            // Redirect back with success message
            return redirect()->back()->with('success', 'Data successfully uploaded.');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('danger', 'Error uploading data: ' . $e->getMessage());
        }
    }

    public function storeShift(Request $request, $nik)
    {
        // Ensure the $karyawan is found
        $karyawan = Karyawan::where('nik', $nik)->first();

        if (!$karyawan) {
            return redirect()->back()->withErrors('Karyawan not found.');
        }

        // Update the shift pattern ID
        $karyawan->shift_pattern_id = $request->shift_pattern_id;
        $karyawan->save();

        return redirect()->back()->with('success', 'Shift updated successfully!');
    }

}
