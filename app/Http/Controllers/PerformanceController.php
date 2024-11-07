<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Contract;
use App\Models\Jabatan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
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

    public function notificationEmail()
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

        return view('performance.send', compact('contracts'));
    }

    public function sendEmail(Request $request)
    {
        $selectedContracts = $request->input('selected_contracts', []);

        if (empty($selectedContracts)) {
            return redirect()->back()->with('danger', 'No contracts selected.');
        }

        // Fetch contracts based on selected IDs
        $contracts = Contract::whereIn('id', $selectedContracts)->get();



        foreach ($contracts as $contract) {
            // Get the karyawan associated with the contract using nik
            $karyawan = Karyawan::where('nik', $contract->nik)->first();

            if (!$karyawan) {
                continue; // Skip if karyawan not found
            }

            // Fetch atasan's email based on jabatan hierarchy
            $jabatan = Jabatan::find($karyawan->jabatan); // Assuming jabatan_id is stored in karyawan table

            if (!$jabatan) {
                continue; // Skip if jabatan not found
            }

            // Find atasan (supervisor) based on atasan_jabatan field
            $atasan = Karyawan::where('jabatan', $jabatan->atasan_jabatan)->first();

            $today = Carbon::now();
            $end_date = Carbon::parse($contract->end_date);
            $contract->days_left = $today->diffInDays($end_date, false); // Calculate remaining days

            // Prepare email content
            $emailContent = "
                Kontrak | {$contract->no_kontrak} | {$karyawan->nama_lengkap} akan expire {$contract->days_left} hari lagi di tanggal " . DateHelper::formatIndonesianDate($contract->end_date) . "<br><br>
                Mohon Cek Di hrms.ciptaharmoni.com/panel<br><br>
                Terima Kasih
            ";

            // Send the email using Mail::html
            Mail::html($emailContent, function ($message) use ($atasan, $karyawan, $contract) {
                $message->to('chandrazahran@gmail.com')
                        ->subject("Notifikasi Expiring Kontrak - {$karyawan->nama_lengkap}")
                        ->cc([$atasan->email, $karyawan->email ?? '', 'al.imron@ciptaharmoni.com'])
                        ->priority(1);  // Set email priority to high

                // Set additional headers for importance
                $message->getHeaders()->addTextHeader('Importance', 'high');  // Mark as important
                $message->getHeaders()->addTextHeader('X-Priority', '1');  // 1 is the highest priority
            });
        }

        return redirect()->back()->with('success', 'Email notifications sent successfully.');
    }


}
