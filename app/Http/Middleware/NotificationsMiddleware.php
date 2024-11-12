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


            $izinRequests = Pengajuanizin::select('pengajuan_izin.*', 'karyawan.nama_lengkap')
                ->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
                ->where('pengajuan_izin.status_approved', 0)
                ->where('pengajuan_izin.status_approved_hrd', 0)
                ->get();

            $cutiApplications = PengajuanCuti::where('status_approved', 0)
                ->where('status_approved_hrd', 0)
                ->get();

            // Share data with all views
            view()->share(compact('birthdays', 'izinRequests', 'cutiApplications'));
        }

        return $next($request);
    }
}
