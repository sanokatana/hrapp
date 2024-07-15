<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreEmployeeRequest;
use Illuminate\Support\Facades\Log;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();
        $query->select('karyawan.*', 'department.nama_dept', 'atasan.nama_lengkap as nama_atasan', 'jabatan.nama_jabatan');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        $query->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id');
        $query->leftJoin('karyawan as atasan', 'karyawan.nik_atasan', '=', 'atasan.nik'); // Self-join to get atasan's name
        $query->orderBy('karyawan.tgl_masuk', 'asc');

        if (!empty($request->nama_karyawan)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }

        $karyawan = $query->paginate(10)->appends($request->except('page'));

        // Query to get non-officer employees
        $atasan = Karyawan::where('level', '!=', 'Officer')->get();

        $department = DB::table('department')->get();
        $jabatan = DB::table('jabatan')->get();
        $location = DB::table('konfigurasi_lokasi')->get();
        return view("karyawan.index", compact('karyawan', 'department', 'atasan', 'jabatan','location'));
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
        $atasan = Karyawan::where('level', '!=', 'Officer')->get();
        $location = DB::table('konfigurasi_lokasi')->get();
        $karyawan = DB::table('karyawan')
            ->where('nik', $nik)
            ->first();
        return view('karyawan.edit', compact('department', 'karyawan', 'atasan','jabatan','location'));
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
}
