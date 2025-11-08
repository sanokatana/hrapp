<?php

namespace App\Http\Middleware;

use App\Models\Karyawan;
use Closure;
use Illuminate\Support\Facades\DB;

class NotificationsMiddleware
{
    public function handle($request, Closure $next)
    {
        $stats = [
            'activeEmployees' => 0,
            'presentToday' => 0,
            'absentToday' => 0,
        ];

        try {
            $active = Karyawan::where('status_kar', 'Aktif')->count();
            $present = DB::table('presensi')
                ->whereDate('tanggal', now()->toDateString())
                ->distinct()
                ->count('karyawan_id');

            $stats['activeEmployees'] = $active;
            $stats['presentToday'] = $present;
            $stats['absentToday'] = max($active - $present, 0);
        } catch (\Throwable $e) {
            // Leave defaults when the attendance tables are not available yet.
        }

        view()->share('dashboardStats', $stats);

        return $next($request);
    }
}
