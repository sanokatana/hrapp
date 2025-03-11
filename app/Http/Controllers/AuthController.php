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

        if (Auth::guard('karyawan')->attempt($credentials, $remember)) {
            return redirect('/dashboard');
        } else {
            return redirect('/karlogin')
                ->with(['warning' => 'NIK/Email or Password is incorrect'])
                ->withInput($request->except('password'));
        }

    }

    public function proseslogincandidate(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Get the candidate by username first
        $candidate = DB::table('candidates')->where('username', $request->username)->first();

        if ($candidate) {
            // Check both regular password and temporary password
            if (Hash::check($request->password, $candidate->password) ||
                Hash::check($request->password, $candidate->temp_pass))
            {
                // Manual authentication since we're checking two password fields
                Auth::guard('candidate')->loginUsingId($candidate->id, $request->has('remember'));

                // Get fresh instance of authenticated candidate
                $authenticatedCandidate = Auth::guard('candidate')->user();

                // Check the candidate's status and handle login behavior
                switch ($authenticatedCandidate->status) {
                    case 'In Process':
                        // Get current stage info
                        $currentStage = DB::table('hiring_stages')
                            ->where('id', $authenticatedCandidate->current_stage_id)
                            ->first();

                        if ($currentStage) {
                            // Get next stage based on sequence
                            $nextStage = DB::table('hiring_stages')
                                ->where('recruitment_type_id', $currentStage->recruitment_type_id)
                                ->where('sequence', '>', $currentStage->sequence)
                                ->orderBy('sequence', 'asc')
                                ->first();

                            if ($nextStage) {
                                DB::table('candidates')
                                    ->where('id', $authenticatedCandidate->id)
                                    ->update(['current_stage_id' => $nextStage->id]);
                            }
                        }
                        return response()->json(['success' => true]);

                    case 'Hired':
                        Auth::guard('candidate')->logout();
                        return response()->json([
                            'success' => false,
                            'message' => 'Congratulations, we are pleased to inform you that you have been hired.'
                        ]);

                    case 'Rejected':
                        Auth::guard('candidate')->logout();
                        return response()->json([
                            'success' => false,
                            'message' => 'We regret to inform you that your application has been rejected.'
                        ]);

                    default:
                        return response()->json([
                            'success' => false,
                            'message' => 'Unknown candidate status.'
                        ]);
                }
            }
        }
        // If we get here, authentication failed
        return response()->json([
            'success' => false,
            'message' => 'Username or Password is incorrect'
        ]);
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

