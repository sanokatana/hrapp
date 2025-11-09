<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('companies');
        $query->orderBy('name', 'asc');

        if (!empty($request->nama)) {
            $query->where('name', 'like', '%' . $request->nama . '%');
        }

        $user = $query->paginate(10)->appends($request->query());
        return view("user.index", compact('user'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'nik' => ['required', 'string', 'max:50', 'unique:users,nik'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'level' => ['required', Rule::in(['Management', 'Admin', 'HRD', 'Superadmin'])],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'email' => $request->email,
            'level' => $request->level,
            'password' => Hash::make($request->password),
        ]);

        return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
    }

    public function edit(Request $request)
    {
        $user = User::findOrFail($request->id);
        $companies = Company::orderBy('short_name')->get();

        return view('user.edit', compact('user', 'companies'));
    }

    public function update(User $user, Request $request)
    {
        $request->validate([
            'level' => ['required', Rule::in(['Management', 'Admin', 'HRD', 'Superadmin'])],
            'new_password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'companies' => ['nullable', 'array'],
            'companies.*' => ['exists:tb_pt,id'],
        ]);

        $user->level = $request->input('level');

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->input('new_password'));
        }

        $user->save();
        
        // Sync company assignments
        if ($request->has('companies')) {
            $user->companies()->sync($request->companies);
        } else {
            $user->companies()->sync([]);
        }

        return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
    }

    public function delete(User $user)
    {
        $user->delete();
        
        return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
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
}
