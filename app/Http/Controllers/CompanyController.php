<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $companies = Company::orderBy('short_name')->get();

        return view('companies.index', compact('companies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'short_name' => ['required', 'string', 'max:100', 'unique:tb_pt,short_name'],
            'long_name' => ['required', 'string', 'max:255'],
        ]);

        Company::create($data);

        return Redirect::route('companies.index')->with('success', 'Company created.');
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $company = Company::where('id', $id)->first();
        
        if (!$company) {
            return redirect()->back()->with('danger', 'Company not found');
        }
        
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $request->validate([
            'short_name' => ['required', 'string', 'max:100', 'unique:tb_pt,short_name,' . $company->id],
            'long_name' => ['required', 'string', 'max:255'],
        ]);

        $company->update($request->only(['short_name', 'long_name']));

        return Redirect::back()->with('success', 'Data Berhasil Di Update');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return Redirect::back()->with('success', 'Data Berhasil Di Hapus');
    }
}
