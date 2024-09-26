<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            return redirect('/karlogin')->with(['warning'=>'NIK/Email or Password is incorrect']);
        }
    }

    public function proseslogincandidate(Request $request)
    {
        // Validate the incoming request for 'username' and 'password'
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Get the login credentials
        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        // Determine if the user wants to be remembered
        $remember = $request->has('remember');

        // Attempt to log in the user using the 'candidate' guard
        if (Auth::guard('candidate')->attempt($credentials, $remember)) {
            // Retrieve the logged-in candidate's data
            $candidate = Auth::guard('candidate')->user();

            // Check the candidate's status and handle login behavior
            switch ($candidate->status) {
                case 'In Process':
                    // Allow login and update stage if needed
                    if ($candidate->current_stage_id == 1) {
                        DB::table('candidates')
                            ->where('id', $candidate->id)
                            ->update(['current_stage_id' => 2]);
                    }
                    return response()->json(['success' => true]);

                case 'Hired':
                    // Prevent login and show the message that the candidate has been hired
                    Auth::guard('candidate')->logout();
                    return response()->json(['success' => false, 'message' => 'Congratulations, we are pleased to inform you that you have been hired.']);

                case 'Rejected':
                    // Prevent login and show the rejection message
                    Auth::guard('candidate')->logout();
                    return response()->json(['success' => false, 'message' => 'We regret to inform you that your application has been rejected.']);

                default:
                    // If the status is not recognized, allow login or handle accordingly
                    return response()->json(['success' => false, 'message' => 'Unknown candidate status.']);
            }
        } else {
            // If the credentials are incorrect, return an error message
            return response()->json(['success' => false, 'message' => 'Username or Password is incorrect']);
        }
    }


    public function proseslogout(){
        if(Auth::guard('karyawan')->check()){
            Auth::guard('karyawan')->logout();
            return redirect('/karlogin');
        }
    }

    public function proseslogoutadmin(){
        if(Auth::guard('user')->check()){
            Auth::guard('user')->logout();
            return redirect('/panel');
        }
    }

    public function proseslogoutcandidate(){
        if(Auth::guard('candidate')->check()){
            Auth::guard('candidate')->logout();
            return redirect('/candidate');
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

