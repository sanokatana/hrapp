<?php

namespace App\Http\Middleware;

use App\Models\Karyawan;
use App\Models\PengajuanCuti;
use App\Models\Pengajuanizin;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            $today = now();
            $nextWeek = now()->addDays(7);

            // Format the dates as month-day (e.g., "01-22")
            $startDate = $today->format('m-d');
            $endDate = $nextWeek->format('m-d');

            // Query for birthdays within the range
            $birthdays = Karyawan::where('status_kar', 'Aktif')
                ->where(function ($query) use ($startDate, $endDate) {
                    if ($startDate <= $endDate) {
                        // Normal case: within the same year
                        $query->whereRaw("DATE_FORMAT(DOB, '%m-%d') BETWEEN ? AND ?", [$startDate, $endDate]);
                    } else {
                        // Overlapping case: spans across December to January
                        $query->whereRaw("DATE_FORMAT(DOB, '%m-%d') BETWEEN ? AND '12-31'", [$startDate])
                            ->orWhereRaw("DATE_FORMAT(DOB, '%m-%d') BETWEEN '01-01' AND ?", [$endDate]);
                    }
                })
                ->get();

            // Get the current user's information
            $user = Auth::guard('user')->user();
            $nik = $user->nik;
            $role = $user->level; // Assuming 'role' is available as an attribute


            // Check if the user is in a privileged role (Superadmin, HRD, or Management)
            if (in_array($role, ['Superadmin', 'HRD', 'Management'])) {
                // Retrieve all izinRequests and cutiApplications for privileged roles
                $izinRequests = Pengajuanizin::select('pengajuan_izin.*', 'karyawan.nama_lengkap')
                    ->leftJoin('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
                    ->where('pengajuan_izin.status_approved', 0)
                    ->get();

                $cutiApplications = PengajuanCuti::select('pengajuan_cuti.*', 'karyawan.nama_lengkap')
                ->leftJoin('karyawan', 'pengajuan_cuti.nik', '=', 'karyawan.nik')
                    ->where('status_approved', 0)
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
