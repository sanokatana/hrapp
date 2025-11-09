<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanySwitcherController extends Controller
{
    public function switchCompany(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('user')->user();
        $companyId = (int) $request->company_id;

        // Verify access
        $hasAccess = $user->level === 'Superadmin'
            || $user->companies()->where('company_id', $companyId)->exists();

        if ($hasAccess) {
            session(['selected_company_id' => $companyId]);

            // Pick first cabang in this company
            $firstCabang = $user->level === 'Superadmin'
                ? \App\Models\Cabang::where('company_id', $companyId)->orderBy('nama')->first()
                : $user->cabang()->where('company_id', $companyId)->orderBy('nama')->first();

            if ($firstCabang) {
                session(['selected_cabang_id' => $firstCabang->id]);
            } else {
                session()->forget('selected_cabang_id'); // no cabang available
            }
        }

        return redirect()->back();
    }


    public function switchCabang(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('user')->user();

        $cabangId = $request->cabang_id;

        if ($cabangId === 'all') {
            session()->forget('selected_cabang_id');
        } else {
            // Verify user has access to this cabang
            if ($user->cabang()->where('cabang.id', $cabangId)->exists() || $user->level === 'Superadmin') {
                session(['selected_cabang_id' => $cabangId]);
            }
        }

        return redirect()->back();
    }
}
