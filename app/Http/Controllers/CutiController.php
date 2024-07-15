<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        // Join cuti table with karyawan table on NIK
        $query = Cuti::query();
        $query->select('cuti.*', 'karyawan.nama_lengkap', 'karyawan.tgl_masuk', 'department.nama_dept');
        $query->join('karyawan', 'cuti.nik', '=', 'karyawan.nik');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        // Order by NIK
        $query->orderBy('nik', 'asc');
        $query->orderBy('tahun', 'desc');

        // Filter by Nama if provided
        if (!empty($request->nama_kar)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_kar . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }

        if (!empty($request->nik_req)) {
            $query->where('cuti.nik', 'like', '%' . $request->nik_req . '%');
        }

        if (!empty($request->tahun_req)) {
            $query->where('cuti.tahun', 'like', '%' . $request->tahun_req . '%');
        }
        if ($request->has('status')) {
            if ($request->status === '0' || $request->status === '1' || $request->status === '2') {
                $query->where('status', $request->status);
            }
        } else {
            // Default to '0' (Pending) if no status_approved_hrd is provided
            $query->where('status', 1);
        }

        // Paginate the results
        $cuti = $query->paginate(10);
        $department = DB::table('department')->get();

        // Return the view with the results
        return view("cuti.index", compact('cuti', 'department'));
    }



    public function store(Request $request)
    {
        $nik = $request->nik;
        $tahun = $request->tahun;
        $sisa_cuti = $request->sisa_cuti;
        $created_by = Auth::guard('user')->user()->nik;
        $created_at = Carbon::now();

        try {

            $nama_lengkap = DB::table('karyawan')->where('nik', $created_by)->value('nama_lengkap');

            $data = [
                'nik' => $nik,
                'tahun' => $tahun,
                'sisa_cuti' => $sisa_cuti,
                'created_at' => $created_at,
                'created_by' => $nama_lengkap,
            ];
            $simpan = DB::table('cuti')->insert($data);

            if ($simpan) {
                return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['danger' => 'Data Gagal Di Simpan']);
        }
    }

    public function edit($id)
    {
        $cuti = Cuti::findOrFail($id);
        return view('cuti.edit', compact('cuti'));
    }

    public function update($id, Request $request)
    {

        $updated_by = Auth::guard('user')->user()->nik;

        try {

            $nama_lengkap = DB::table('karyawan')->where('nik', $updated_by)->value('nama_lengkap');

            $cuti = Cuti::findOrFail($id);
            $cuti->nik = $request->nik;
            $cuti->tahun = $request->tahun;
            $cuti->sisa_cuti = $request->sisa_cuti;
            $cuti->updated_at = Carbon::now();
            $cuti->updated_by = $nama_lengkap;
            $cuti->save();
            return Redirect::back()->with('success', 'Data Berhasil Di Update');
        } catch (\Exception $e) {
            return Redirect::back()->with('danger', 'Data Gagal Di Update: ' . $e->getMessage());
        }
    }
    public function delete($id)
    {
        $delete = DB::table('cuti')->where('id', $id)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

    public function cekCutiKaryawan()
    {
        $today = Carbon::today();

        // Get records where periode_akhir is past today's date
        $cutiRecords = DB::table('cuti')
            ->where('periode_akhir', '<', $today)
            ->where('status', 1) // Assuming only active records need to be checked
            ->get();

        foreach ($cutiRecords as $record) {
            // Calculate new period values
            $newPeriode = $record->tahun + 1;
            $newPeriodeAwal = Carbon::parse($record->periode_akhir)->addDay()->format('Y-m-d');
            $newPeriodeAkhir = Carbon::parse($record->periode_akhir)->addYear()->format('Y-m-d');
            if($record->sisa_cuti >=0){
                $newSisaCuti = 12;
            } else {
                $newSisaCuti = 12 + $record->sisa_cuti;
            }

            // Insert a new record
            DB::table('cuti')->insert([
                'nik' => $record->nik,
                'tahun' => $newPeriode,
                'periode_awal' => $newPeriodeAwal,
                'periode_akhir' => $newPeriodeAkhir,
                'sisa_cuti' => $newSisaCuti,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update the old record's status
            DB::table('cuti')
                ->where('id', $record->id)
                ->update(['status' => 0]);
        }

        return redirect()->back()->with('success', 'Cuti karyawan has been updated successfully.');
    }

    public function getEmployeeByNik(Request $request)
    {
        $nik = $request->nik;
        $employee = DB::table('karyawan')->where('nik', $nik)->first();

        return response()->json($employee);
    }

    public function getEmployeeName(Request $request)
    {
        $searchTerm = $request->nama_lengkap;
        $employee = DB::table('karyawan')->where('nama_lengkap', 'like', '%'.$searchTerm.'%')->get(['nik', 'nama_lengkap']);
        return response()->json($employee);
    }

    public function downloadTemplate()
    {
        $filePath = public_path('storage/uploads/cuti/template_cuti.csv');

        return response()->download($filePath, 'template_cuti.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
    public function uploadCuti(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('danger', 'Please upload a valid CSV file.');
        }

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $fileHandle = fopen($filePath, 'r');

        // Skip the header row
        $headers = fgetcsv($fileHandle, 1000, ';'); // Specify delimiter as semicolon

        $data = [];
        while ($row = fgetcsv($fileHandle, 1000, ';')) { // Specify delimiter as semicolon
            // Ensure each row has the correct number of columns
            if (count($row) == 6) { // Assuming there are exactly 6 columns in your CSV
                $data[] = [
                    'nik' => $row[0],
                    'tahun' => $row[1],
                    'sisa_cuti' => $row[2],
                    'periode_awal' => Carbon::createFromFormat('d/m/Y', $row[3])->format('Y-m-d'),
                    'periode_akhir'  => Carbon::createFromFormat('d/m/Y', $row[4])->format('Y-m-d'),
                    'status' => $row[5],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            } else {
                return redirect()->back()->with('danger', 'Invalid CSV format: Each row must have exactly 7 columns.');
            }
        }

        fclose($fileHandle);

        try {
            DB::table('cuti')->insert($data);
            return redirect()->back()->with('success', 'Data Berhasil Di Simpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'Data Gagal Di Simpan: ' . $e->getMessage());
        }
    }
}
