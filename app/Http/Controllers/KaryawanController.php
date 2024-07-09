<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();
        $query->select('karyawan.*', 'department.nama_dept', 'atasan.nama_lengkap as nama_atasan', 'jabatan.nama_jabatan');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        $query->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id');
        $query->leftJoin('karyawan as atasan', 'karyawan.nik_atasan', '=', 'atasan.nik'); // Self-join to get atasan's name
        $query->orderBy('karyawan.nama_lengkap', 'asc');

        if (!empty($request->nama_karyawan)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }

        if (!empty($request->level)) {
            $query->where('karyawan.level', $request->level);
        }

        if (!empty($request->nik_atasan)) {
            $query->where('karyawan.nik_atasan', $request->nik_atasan);
        }

        $karyawan = $query->paginate(10);

        // Query to get non-officer employees
        $atasan = Karyawan::where('level', '!=', 'Officer')->get();

        $department = DB::table('department')->get();
        $jabatan = DB::table('jabatan')->get();
        return view("karyawan.index", compact('karyawan', 'department', 'atasan', 'jabatan'));
    }



    public function store(Request $request)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $email = $request->email;
        $level = $request->level;
        $DOB = $request->DOB;
        $tgl_masuk = $request->tgl_masuk;
        $nik_atasan = $request->nik_atasan;
        $password = Hash::make('chl12345');
        $kode_dept = $request->kode_dept;
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = null;
        }

        try {
            $data = [
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'tgl_masuk' => $tgl_masuk,
                'email' => $email,
                'DOB' => $DOB,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password,
                'level' => $level,
                'nik_atasan' => $nik_atasan
            ];
            $simpan = DB::table('karyawan')->insert($data);
            if ($simpan) {
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/karyawan/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['danger' => 'Data Gagal Di Simpan']);
        }
    }

    public function edit(Request $request)
    {

        $nik = $request->nik;
        $department = DB::table('department')->get();
        $jabatan = DB::table('jabatan')->get();
        $atasan = Karyawan::where('level', '!=', 'Officer')->get();
        $karyawan = DB::table('karyawan')
            ->where('nik', $nik)
            ->first();
        return view('karyawan.edit', compact('department', 'karyawan', 'atasan','jabatan'));
    }

    public function update($nik, Request $request)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $old_foto = $request->old_foto;
        $email = $request->email;
        $DOB = $request->DOB;
        $level = $request->level;
        $tgl_masuk = $request->tgl_masuk;
        $tgl_resign = $request->tgl_resign;
        $nik_atasan = $request->nik_atasan;
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        try {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'DOB' => $DOB,
                'email' => $email,
                'level' => $level,
                'tgl_masuk' => $tgl_masuk,
                'tgl_resign' => $tgl_resign,
                'nik_atasan' => $nik_atasan,
            ];
            $update = DB::table('karyawan')->where('nik', $nik)->update($data);
            if ($update) {
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/karyawan/";
                    $folderPathOld = "public/uploads/karyawan/" . $old_foto;
                    Storage::delete($folderPathOld);
                    $request->file('foto')->storeAs($folderPath, $foto);
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
