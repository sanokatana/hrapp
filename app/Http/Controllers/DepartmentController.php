<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $companyId = session('selected_company_id');
        $cabangId = session('selected_cabang_id');
        
        $query = Department::with(['company', 'cabang']);
        
        if ($companyId) {
            $query->where('company_id', $companyId);
        }
        
        if ($cabangId) {
            $query->where('cabang_id', $cabangId);
        }
        
        $departments = $query->orderBy('nama')->get();
        
        $companies = Company::orderBy('short_name')->get();
        $branches = Cabang::when($companyId, function($q) use ($companyId) {
            return $q->where('company_id', $companyId);
        })->orderBy('nama')->get();
        
        return view('departments.index', compact('departments', 'companies', 'branches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $companyId = session('selected_company_id');
        
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:30', 'unique:department,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'company_id' => ['required', 'exists:tb_pt,id'],
            'cabang_id' => ['nullable', 'exists:cabang,id'],
        ]);
        
        // Ensure company_id matches selected company
        if ($companyId && $data['company_id'] != $companyId) {
            return Redirect::back()->with('danger', 'You can only create departments for the selected company');
        }

        Department::create($data);

        return Redirect::back()->with('success', 'Data Berhasil Di Simpan');
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $department = Department::where('id', $id)->first();
        $companies = Company::orderBy('short_name')->get();
        $branches = Cabang::orderBy('nama')->get();
        
        if (!$department) {
            return redirect()->back()->with('danger', 'Department not found');
        }
        
        return view('departments.edit', compact('department', 'companies', 'branches'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $request->validate([
            'kode' => ['required', 'string', 'max:30', 'unique:department,kode,' . $department->id],
            'nama' => ['required', 'string', 'max:255'],
            'company_id' => ['required', 'exists:tb_pt,id'],
            'cabang_id' => ['nullable', 'exists:cabang,id'],
        ]);

        $department->update($request->only(['kode', 'nama', 'company_id', 'cabang_id']));

        return Redirect::back()->with('success', 'Data Berhasil Di Update');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();

        return Redirect::back()->with('success', 'Data Berhasil Di Hapus');
    }
}
