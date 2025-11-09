<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Company;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\Karyawan;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class KaryawanController extends Controller
{
    public function index(Request $request): View
    {
        $user      = Auth::guard('user')->user();
        $companyId = session('selected_company_id');
        $cabangId  = session('selected_cabang_id');

        $employees = Karyawan::with(['company','cabang','department','jabatan'])
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->when($cabangId, fn($q) => $q->where('cabang_id', $cabangId))
            ->orderBy('nama_lengkap')
            ->get();

        /** @var \App\Models\User $user */
        $companies = $user->level === 'Superadmin'
            ? Company::orderBy('short_name')->get()
            : $user->companies()->orderBy('short_name')->get();

        if ($companyId) {
            $branches = $user->level === 'Superadmin'
                ? Cabang::where('company_id', $companyId)->orderBy('nama')->get()
                : $user->cabang()->where('cabang.company_id', $companyId)->orderBy('nama')->get();
        } else {
            $branches = collect();
        }

        $departments = Department::when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->orderBy('nama')->get();

        $positions   = Jabatan::when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->orderBy('nama')->get();

        return view('karyawan.index', compact('employees','companies','branches','departments','positions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $companyId = session('selected_company_id');

        $data = $request->validate([
            'nik'           => ['required', 'string', 'max:50', 'unique:karyawan,nik'],
            'nama_lengkap'  => ['required', 'string', 'max:255'],
            'email'         => ['nullable', 'email', 'max:255', 'unique:karyawan,email'],
            'no_hp'         => ['nullable', 'string', 'max:25'],
            'tgl_masuk'     => ['nullable', 'date'],
            'company_id'    => ['required', 'exists:tb_pt,id'],
            'cabang_id'     => [
                'nullable',
                Rule::exists('cabang','id')->where(fn($q) => $q->where('company_id', $request->company_id)),
            ],
            'department_id' => ['nullable', 'exists:department,id'],
            'jabatan_id'    => ['nullable', 'exists:jabatan,id'],
            // lokasi_id removed
            'status_kar'    => ['required', 'string', 'max:50'],
            'password'      => ['required', 'string', 'min:6'],
        ]);

        if ($companyId && $data['company_id'] != $companyId) {
            return Redirect::back()->with('danger', 'You can only create employees for the selected company');
        }

        $data['password'] = Hash::make($data['password']);

        Karyawan::create($data);

        return Redirect::back()->with('success', 'Karyawan created.');
    }

    public function edit(Request $request): View
    {
        $user      = Auth::guard('user')->user();
        $karyawan  = Karyawan::findOrFail($request->id);

        /** @var \App\Models\User $user */
        $companies = $user->level === 'Superadmin'
            ? Company::orderBy('short_name')->get()
            : $user->companies()->orderBy('short_name')->get();

        // We’ll load branches on the client side via AJAX. (No $branches needed.)
        $departments = Department::where('company_id', $karyawan->company_id)->orderBy('nama')->get();
        $positions   = Jabatan::where('company_id', $karyawan->company_id)->orderBy('nama')->get();

        return view('karyawan.edit', compact('karyawan','companies','departments','positions'));
    }

    public function update(Request $request, Karyawan $karyawan): RedirectResponse
    {
        $data = $request->validate([
            'nama_lengkap'  => ['required', 'string', 'max:255'],
            'email'         => ['nullable', 'email', 'max:255', 'unique:karyawan,email,' . $karyawan->id],
            'no_hp'         => ['nullable', 'string', 'max:25'],
            'tgl_masuk'     => ['nullable', 'date'],
            'tgl_resign'    => ['nullable', 'date'],
            'company_id'    => ['required', 'exists:tb_pt,id'],
            'cabang_id'     => [
                'nullable',
                Rule::exists('cabang','id')->where(fn($q) => $q->where('company_id', $request->company_id)),
            ],
            'department_id' => ['nullable', 'exists:department,id'],
            'jabatan_id'    => ['nullable', 'exists:jabatan,id'],
            // lokasi_id removed
            'status_kar'    => ['required', 'string', 'max:50'],
            'password'      => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $karyawan->update($data);

        return Redirect::back()->with('success', 'Karyawan updated.');
    }

    public function destroy(Karyawan $karyawan): RedirectResponse
    {
        $karyawan->delete();

        return Redirect::back()->with('success', 'Karyawan removed.');
    }

    public function branchesByCompany(Company $company)
    {
        $user = Auth::guard('user')->user();

        $query = Cabang::where('company_id', $company->id);

        /** @var \App\Models\User $user */
        if ($user->level !== 'Superadmin') {
            $allowedCabangIds = $user->cabang()->pluck('cabang.id');
            $query->whereIn('id', $allowedCabangIds);
        }

        return response()->json(
            $query->orderBy('nama')->get(['id','nama'])
        );
    }
}
