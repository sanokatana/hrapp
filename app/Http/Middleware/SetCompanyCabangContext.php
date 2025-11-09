<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cabang;

class SetCompanyCabangContext
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('user')->check()) {
            /** @var \App\Models\User $user */
            $user = Auth::guard('user')->user();

            // Company: pick first if not set
            $selectedCompanyId = session('selected_company_id');
            if (! $selectedCompanyId) {
                $firstCompany = $user->level === 'Superadmin'
                    ? \App\Models\Company::orderBy('short_name')->first()
                    : $user->companies()->orderBy('short_name')->first();

                if ($firstCompany) {
                    $selectedCompanyId = $firstCompany->id;
                    session(['selected_company_id' => $selectedCompanyId]);
                }
            }

            // Cabang: pick first (within selected company) if not set
            $selectedCabangId = session('selected_cabang_id');
            if (! $selectedCabangId && $selectedCompanyId) {
                $firstCabang = $user->level === 'Superadmin'
                    ? Cabang::where('company_id', $selectedCompanyId)->orderBy('nama')->first()
                    : $user->cabang()->where('company_id', $selectedCompanyId)->orderBy('nama')->first();

                if ($firstCabang) {
                    $selectedCabangId = $firstCabang->id;
                    session(['selected_cabang_id' => $selectedCabangId]);
                }
            }

            // Share with views
            view()->share('selectedCompanyId', $selectedCompanyId);
            view()->share('selectedCabangId', $selectedCabangId);
            view()->share('currentUser', $user);
        }

        return $next($request);
    }
}
