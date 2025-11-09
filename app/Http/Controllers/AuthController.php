<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function proseslogin(Request $request)
    {
        $request->validate([
            'nik_or_email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginValue = $request->input('nik_or_email');
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'nik';

        if (Auth::guard('karyawan')->attempt([
            $field => $loginValue,
            'password' => $request->input('password'),
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return redirect('/karlogin')
            ->with(['warning' => 'NIK/Email or Password is incorrect'])
            ->withInput($request->except('password'));
    }

    public function proseslogout()
    {
        if (Auth::guard('karyawan')->check()) {
            Auth::guard('karyawan')->logout();
        }

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/karlogin');
    }

    public function prosesloginadmin(Request $request)
    {
        $request->validate([
            'nik_or_email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginValue = $request->input('nik_or_email');
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::guard('user')->attempt([
            $field => $loginValue,
            'password' => $request->input('password'),
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::guard('user')->user();

            // Pick first available company for this user (or any if superadmin)
            $firstCompany = $user->level === 'Superadmin'
                ? \App\Models\Company::orderBy('short_name')->first()
                : $user->companies()->orderBy('short_name')->first();

            if ($firstCompany) {
                session(['selected_company_id' => $firstCompany->id]);

                // Pick first cabang within that company (respecting user access)
                $firstCabang = $user->level === 'Superadmin'
                    ? \App\Models\Cabang::forCompany($firstCompany->id)->orderBy('nama')->first()
                    : $user->cabang()
                        ->where((new \App\Models\Cabang)->getTable() . '.company_id', $firstCompany->id)
                        ->orderBy('nama')
                        ->first();

                session(['selected_cabang_id' => optional($firstCabang)->id]);
            }

            return response()->json(['success' => true]);
        }



        return response()->json([
            'success' => false,
            'message' => 'Email or Password is incorrect',
        ]);
    }

    public function proseslogoutadmin()
    {
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
        }

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/panel');
    }
}

