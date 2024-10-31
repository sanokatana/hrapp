<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PerformanceController extends Controller
{
    public function notification()
    {
        // Calculate the current date
        $today = Carbon::now();

        // Get contracts ending within 1, 2, or 3 months from today
        $contracts = DB::table('kontrak')
            ->join('karyawan', 'kontrak.nik', '=', 'karyawan.nik')
            ->select('kontrak.no_kontrak', 'kontrak.end_date', 'karyawan.nama_lengkap', 'kontrak.nik', 'kontrak.position', 'kontrak.id')
            ->whereBetween('kontrak.end_date', [
                $today,
                $today->copy()->addMonths(3)  // Contract end date within the next 3 months
            ])
            ->orderBy('kontrak.end_date') // Order by end_date to get the soonest ending contracts first
            ->get();

        // Calculate days remaining for each contract and filter contracts to only show those with end dates within the next 3 months
        foreach ($contracts as $contract) {
            $end_date = Carbon::parse($contract->end_date);
            $contract->days_left = $today->diffInDays($end_date, false); // Calculate remaining days
        }

        return view('performance.notification', compact('contracts'));
    }
}
