<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class CabangController extends Controller
{
    public function index(Request $request): View
    {
        $branches = Cabang::with('company')->orderBy('nama')->get();
        $companies = Company::orderBy('short_name')->get();
        return view('cabang.index', compact('branches', 'companies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_id' => ['required', 'exists:tb_pt,id'],
            'kode' => ['required', 'string', 'max:50', 'unique:cabang,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'kota' => ['nullable', 'string', 'max:120'],
        ]);

        Cabang::create($data);

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
        $request->validate([
            'company_id' => ['required', 'exists:tb_pt,id'],
            'kode' => ['required', 'string', 'max:50', 'unique:cabang,kode,' . $cabang->id],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'kota' => ['nullable', 'string', 'max:120'],
        ]);

        $cabang->update($request->only(['company_id', 'kode', 'nama', 'alamat', 'kota']));

        return Redirect::back()->with('success', 'Data Berhasil Di Update');
    }

    public function destroy(Cabang $cabang): RedirectResponse
    {
        $cabang->delete();

        return Redirect::back()->with('success', 'Data Berhasil Di Hapus');
    }
}
