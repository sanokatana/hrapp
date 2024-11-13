<?php

namespace App\Http\Middleware;

use App\Models\Karyawan;
use App\Models\PengajuanCuti;
use App\Models\Pengajuanizin;
use Closure;
use Illuminate\Support\Facades\Auth;

class NotificationsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('user')->check()) {
            $startOfWeek = now()->startOfWeek()->format('m-d');
            $endOfWeek = now()->endOfWeek()->format('m-d');
            $birthdays = Karyawan::whereRaw("DATE_FORMAT(DOB, '%m-%d') BETWEEN ? AND ?", [$startOfWeek, $endOfWeek])->get();

            // Get the current user's information
            $user = Auth::guard('user')->user();
            $nik = $user->nik;
            $role = $user->role; // Assuming 'role' is available as an attribute

            // Check if the user is in a privileged role (Superadmin, HRD, or Management)
            if (in_array($role, ['Superadmin', 'HRD', 'Management'])) {
                // Retrieve all izinRequests and cutiApplications for privileged roles
                $izinRequests = Pengajuanizin::select('pengajuan_izin.*', 'karyawan.nama_lengkap')
                    ->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
                    ->where('pengajuan_izin.status_approved', 0)
                    ->where('pengajuan_izin.status_approved_hrd', 0)
                    ->get();

                $cutiApplications = PengajuanCuti::where('status_approved', 0)
                    ->where('status_approved_hrd', 0)
                    ->get();
            } else {
                // For other users, filter izinRequests for supervised employees
                $currentUser = Karyawan::where('nik', $nik)->first();
                $currentUserJabatanId = $currentUser->jabatan;

                // Get NIKs of employees supervised by the current user
                $employeeNiks = Karyawan::join('jabatan as j1', 'karyawan.jabatan', '=', 'j1.id')
                    ->join('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id')
                    ->where('j2.id', $currentUserJabatanId)
                    ->pluck('karyawan.nik');

                // Filter izinRequests for supervised employees
                $izinRequests = Pengajuanizin::select('pengajuan_izin.*', 'karyawan.nama_lengkap')
                    ->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
                    ->whereIn('pengajuan_izin.nik', $employeeNiks)
                    ->where('pengajuan_izin.status_approved', 0)
                    ->where('pengajuan_izin.status_approved_hrd', 0)
                    ->get();

                $cutiApplications = PengajuanCuti::whereIn('nik', $employeeNiks)
                    ->where('status_approved', 0)
                    ->where('status_approved_hrd', 0)
                    ->get();
            }

            // Share data with all views
            view()->share(compact('birthdays', 'izinRequests', 'cutiApplications'));
        }

        return $next($request);
    }

}
