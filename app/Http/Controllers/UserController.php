<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        $query->orderBy('name', 'asc');

        if (!empty($request->nama_lengkap)) {
            $query->where('name', 'like', '%' . $request->nama_lengkap . '%');
        }

        $user = $query->paginate(10);
        return view("user.index", compact('user'));
    }



    public function store(Request $request)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $email = $request->email;
        $level = $request->level;
        $password = Hash::make($request->password);

        try {
            $data = [
                'nik' => $nik,
                'name' => $nama_lengkap,
                'email' => $email,
                'level' => $level,
                'password' => $password,
            ];
            $simpan = DB::table('users')->insert($data);
            if ($simpan) {
                return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['danger' => 'Data Gagal Di Simpan']);
        }
    }

    public function edit(Request $request)
    {

        $nik = $request->nik;
        $user = DB::table('users')
            ->where('nik', $nik)
            ->first();
        return view('user.edit', compact('user'));
    }

    public function update($nik, Request $request)
    {
        $user = User::where('nik', $nik)->firstOrFail();

        // Update the user's level
        $user->level = $request->input('level');

        // Check if a new password is provided and if it matches the confirmation
        if ($request->filled('new_password')) {
            if ($request->input('new_password') === $request->input('new_password_confirmation')) {
                // Hash the new password and update
                $user->password = Hash::make($request->input('new_password'));
            } else {
                return Redirect::back()->with(['danger' => 'Password confirmation does not match the new password.']);
            }
        }

        try {
            $user->save();
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['danger' => 'Data Gagal Di Update']);
        }
    }


    public function getEmployeeByNik(Request $request)
    {
        $nik = $request->nik;
        $employee = DB::table('karyawan')->where('nik', $nik)->first();

        return response()->json($employee);
    }

    public function getEmployeeNameUser(Request $request)
    {
        $searchTerm = $request->nama_lengkap;
        $employee = DB::table('karyawan')
                    ->where('nama_lengkap', 'like', '%' . $searchTerm . '%')
                    ->get(['nik', 'nama_lengkap','email']);

        return response()->json($employee);
    }

    public function delete($nik)
    {
        $delete = DB::table('users')->where('nik', $nik)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }
}
