<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function proseslogin(Request $request){
        $loginField = filter_var($request->input('nik_or_email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'nik';
        $credentials = [
            $loginField => $request->input('nik_or_email'),
            'password' => $request->input('password'),
        ];
        $remember = $request->has('remember');

        if(Auth::guard('karyawan')->attempt($credentials, $remember)){
            return redirect('/dashboard');
        } else {
            return redirect('/')->with(['warning'=>'NIK/Email or Password is incorrect']);
        }
    }



    public function proseslogout(){
        if(Auth::guard('karyawan')->check()){
            Auth::guard('karyawan')->logout();
            return redirect('/');
        }
    }

    public function proseslogoutadmin(){
        if(Auth::guard('user')->check()){
            Auth::guard('user')->logout();
            return redirect('/panel');
        }
    }

    public function prosesloginadmin(Request $request){
        $loginField = filter_var($request->input('nik_or_email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'nik';
        $credentials = [
            $loginField => $request->input('nik_or_email'),
            'password' => $request->input('password'),
        ];
        $remember = $request->has('remember');

        if(Auth::guard('user')->attempt($credentials, $remember)){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'NIK/Email or Password is incorrect']);
        }
    }

}

