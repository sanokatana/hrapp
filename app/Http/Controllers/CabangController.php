<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Company;
use App\Models\User; // ← add this
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CabangController extends Controller
{
    public function index(): View
    {
        /** @var User $user */             // ← tell Intelephense the exact type
        $user = Auth::guard('user')->user();

        $selectedCompanyId = session('selected_company_id');

        if ($user->level === 'Superadmin') {
            $cabangs = Cabang::when($selectedCompanyId, fn ($q) => $q->where('company_id', $selectedCompanyId))
                ->with('company')
                ->orderBy('nama')
                ->get();

            $companies = Company::orderBy('short_name')->get();
        } else {
            // IMPORTANT: prefix the table when filtering via relation
            $cabangs = $user->cabang()
                ->when($selectedCompanyId, fn ($q) => $q->where('cabang.company_id', $selectedCompanyId))
                ->with('company')
                ->orderBy('nama')
                ->get();

            $companies = $user->companies()->orderBy('short_name')->get();
        }

        return view('cabang.index', compact('cabangs', 'companies', 'selectedCompanyId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $companyId = session('selected_company_id');
        
        $data = $request->validate([
            'company_id'   => ['required', 'exists:tb_pt,id'],
            'kode'         => ['required', 'string', 'max:50', 'unique:cabang,kode'],
            'nama'         => ['required', 'string', 'max:255'],
            'alamat'       => ['nullable', 'string', 'max:255'],
            'kota'         => ['nullable', 'string', 'max:120'],
            'latitude'     => ['required', 'numeric', 'between:-90,90'],
            'longitude'    => ['required', 'numeric', 'between:-180,180'],
            'radius_meter' => ['required', 'integer', 'min:10', 'max:5000'],
        ]);
        
        // Ensure company_id matches selected company for non-superadmin users
        if ($companyId && $data['company_id'] != $companyId) {
            return Redirect::back()->with('danger', 'You can only create branches for the selected company');
        }

    $cabang = Cabang::create($data);

        /** @var \App\Models\User $user */
        $user = Auth::guard('user')->user();

        // OPTION A: attach only the creator
        $user->cabang()->syncWithoutDetaching([$cabang->id]);

        // OPTION B: attach everyone who has access to the cabang's company
        $usersInCompany = User::whereHas('companies', fn ($q) =>
            $q->where('tb_pt.id', $cabang->company_id)
        )->pluck('id');

        $cabang->users()->syncWithoutDetaching($usersInCompany);


        return Redirect::back()->with('success', 'Data Berhasil Di Simpan');
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $cabang = Cabang::where('id', $id)->first();
        $companies = Company::orderBy('short_name')->get();
        
        if (!$cabang) {
            return redirect()->back()->with('danger', 'Cabang not found');
        }
        
        return view('cabang.edit', compact('cabang', 'companies'));
    }

    public function update(Request $request, Cabang $cabang): RedirectResponse
    {
        $validated = $request->validate([
            'company_id'   => ['required', 'exists:tb_pt,id'],
            'kode'         => ['required', 'string', 'max:50', 'unique:cabang,kode,' . $cabang->id],
            'nama'         => ['required', 'string', 'max:255'],
            'alamat'       => ['nullable', 'string', 'max:255'],
            'kota'         => ['nullable', 'string', 'max:120'],
            'latitude'     => ['required', 'numeric', 'between:-90,90'],
            'longitude'    => ['required', 'numeric', 'between:-180,180'],
            'radius_meter' => ['required', 'integer', 'min:10', 'max:5000'],
        ]);

        $cabang->update($validated);

        return Redirect::back()->with('success', 'Data Berhasil Di Update');
    }

    public function destroy(Cabang $cabang): RedirectResponse
    {
        $cabang->delete();

        return Redirect::back()->with('success', 'Data Berhasil Di Hapus');
    }
}
