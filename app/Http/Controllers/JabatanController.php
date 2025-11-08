<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Company;
use App\Models\Department;
use App\Models\Jabatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class JabatanController extends Controller
{
    public function index(Request $request): View
    {
        $positions = Jabatan::with(['department', 'company', 'cabang'])
            ->orderBy('nama')
            ->get();
        $departments = Department::orderBy('nama')->get();
        $companies = Company::orderBy('short_name')->get();
        $branches = Cabang::orderBy('nama')->get();
        return view('jabatans.index', compact('positions', 'departments', 'companies', 'branches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'department_id' => ['required', 'exists:department,id'],
            'company_id' => ['required', 'exists:tb_pt,id'],
            'cabang_id' => ['nullable', 'exists:cabang,id'],
            'nama' => ['required', 'string', 'max:255'],
            'level' => ['nullable', 'string', 'max:100'],
        ]);

        Jabatan::create($data);

        return Redirect::back()->with('success', 'Data Berhasil Di Simpan');
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $jabatan = Jabatan::where('id', $id)->first();
        $companies = Company::orderBy('short_name')->get();
        $branches = Cabang::orderBy('nama')->get();
        $departments = Department::orderBy('nama')->get();
        
        if (!$jabatan) {
            return redirect()->back()->with('danger', 'Jabatan not found');
        }
        
        return view('jabatans.edit', compact('jabatan', 'companies', 'branches', 'departments'));
    }

    public function update(Request $request, Jabatan $jabatan): RedirectResponse
    {
        $request->validate([
            'department_id' => ['required', 'exists:department,id'],
            'company_id' => ['required', 'exists:tb_pt,id'],
            'cabang_id' => ['nullable', 'exists:cabang,id'],
            'nama' => ['required', 'string', 'max:255'],
            'level' => ['nullable', 'string', 'max:100'],
        ]);

        $jabatan->update($request->only(['department_id', 'company_id', 'cabang_id', 'nama', 'level']));

        return Redirect::back()->with('success', 'Data Berhasil Di Update');
    }

    public function destroy(Jabatan $jabatan): RedirectResponse
    {
        $jabatan->delete();

        return Redirect::back()->with('success', 'Data Berhasil Di Hapus');
    }
}
