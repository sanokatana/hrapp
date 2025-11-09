<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Company;
use App\Models\Department;
use App\Models\Jabatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JabatanController extends Controller
{
    public function index(Request $request): View
    {
        $companyId = session('selected_company_id');
        $cabangId  = session('selected_cabang_id');

        $positions = Jabatan::with(['department', 'company', 'cabang'])
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->when($cabangId,  fn($q) => $q->where('cabang_id',  $cabangId))
            ->orderBy('nama')
            ->get();

        $companies   = Company::orderBy('short_name')->get();
        $branches    = Cabang::when($companyId, fn($q) => $q->where('company_id', $companyId))->orderBy('nama')->get();
        $departments = Department::when($companyId, fn($q) => $q->where('company_id', $companyId))->orderBy('nama')->get();

        return view('jabatans.index', compact('positions', 'companies', 'branches', 'departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $companyIdSession = session('selected_company_id');

        $data = $request->validate([
            'company_id'    => ['required', 'exists:tb_pt,id'],
            'cabang_id'     => [
                'nullable',
                Rule::exists('cabang', 'id')->where(fn($q) => $q->where('company_id', $request->company_id)),
            ],
            'department_id' => [
                'required',
                Rule::exists('department', 'id')->where(fn($q) => $q->where('company_id', $request->company_id)),
            ],
            'nama'          => ['required', 'string', 'max:255'],
            'level'         => ['nullable', 'string', 'max:100'],
        ]);

        if ($companyIdSession && (int)$data['company_id'] !== (int)$companyIdSession) {
            return Redirect::back()->with('danger', 'You can only create positions for the selected company');
        }

        Jabatan::create($data);

        return Redirect::back()->with('success', 'Data Berhasil Disimpan');
    }

    // returns the edit partial (AJAX)
    public function edit(Request $request)
    {
        $id = $request->id;
        $jabatan = Jabatan::with(['company','cabang','department'])->find($id);

        if (!$jabatan) {
            return response('Jabatan not found', 404);
        }

        $companies   = Company::orderBy('short_name')->get();
        $branches    = Cabang::where('company_id', $jabatan->company_id)->orderBy('nama')->get();
        $departments = Department::where('company_id', $jabatan->company_id)->orderBy('nama')->get();

        return view('jabatans.edit', compact('jabatan', 'companies', 'branches', 'departments'));
    }

    public function update(Request $request, Jabatan $jabatan): RedirectResponse
    {
        $data = $request->validate([
            'company_id'    => ['required', 'exists:tb_pt,id'],
            'cabang_id'     => [
                'nullable',
                Rule::exists('cabang', 'id')->where(fn($q) => $q->where('company_id', $request->company_id)),
            ],
            'department_id' => [
                'required',
                Rule::exists('department', 'id')->where(fn($q) => $q->where('company_id', $request->company_id)),
            ],
            'nama'          => ['required', 'string', 'max:255'],
            'level'         => ['nullable', 'string', 'max:100'],
        ]);

        $jabatan->update($data);

        return Redirect::back()->with('success', 'Data Berhasil Diupdate');
    }

    public function destroy(Jabatan $jabatan): RedirectResponse
    {
        $jabatan->delete();
        return Redirect::back()->with('success', 'Data Berhasil Dihapus');
    }

    // JSON helpers
    public function branchesByCompany(Company $company)
    {
        return response()->json(
            Cabang::where('company_id', $company->id)->orderBy('nama')->get(['id','nama'])
        );
    }

    public function departmentsByCompany(Company $company)
    {
        return response()->json(
            Department::where('company_id', $company->id)->orderBy('nama')->get(['id','nama'])
        );
    }
}
