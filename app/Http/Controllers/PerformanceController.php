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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\SK;

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
            ->where('kontrak.status', 'Active')
            ->orderBy('kontrak.end_date') // Order by end_date to get the soonest ending contracts first
            ->get();

        // Calculate days remaining for each contract and filter contracts to only show those with end dates within the next 3 months
        foreach ($contracts as $contract) {
            $end_date = Carbon::parse($contract->end_date);
            $contract->days_left = $today->diffInDays($end_date, false); // Calculate remaining days
        }

        $hrd = Karyawan::whereIn('jabatan', [25, 47])
        ->get();

        return view('performance.notification', compact('contracts', 'hrd'));
    }

    public function dashboard()
    {
        // Get current date and month
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // Calculate basic statistics
        $totalActiveContracts = Contract::where('status', 'Active')->count();
        $newContractsThisMonth = Contract::whereMonth('start_date', $currentMonth)
            ->whereYear('start_date', $currentYear)
            ->count();
        $contractsEndingDecember = Contract::whereMonth('end_date', 12)
            ->whereYear('end_date', $currentYear)
            ->count();
        $expiredContracts = Contract::where('status', 'Expired')->count();

        // Get monthly trends
        $monthlyTrends = $this->getMonthlyTrends();

        // Get contract status distribution
        $statusStats = $this->getStatusDistribution();

        // Get recent contracts
        $recentContracts = Contract::orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('performance.dashboard', compact(
            'totalActiveContracts',
            'newContractsThisMonth',
            'contractsEndingDecember',
            'expiredContracts',
            'monthlyTrends',
            'statusStats',
            'recentContracts'
        ));
    }

    private function getMonthlyTrends()
    {
        $months = [];
        $newContracts = [];
        $endingContracts = [];

        // Get data for the last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthYear = $date->format('M Y');
            $months[] = $monthYear;

            // Count new contracts
            $newContracts[] = Contract::whereYear('start_date', $date->year)
                ->whereMonth('start_date', $date->month)
                ->count();

            // Count ending contracts
            $endingContracts[] = Contract::whereYear('end_date', $date->year)
                ->whereMonth('end_date', $date->month)
                ->count();
        }

        return [
            'months' => $months,
            'new' => $newContracts,
            'ending' => $endingContracts
        ];
    }

    private function getStatusDistribution()
    {
        $statuses = ['Active', 'Extended', 'Expired', 'Terminated'];
        $counts = [];

        foreach ($statuses as $status) {
            $counts[] = Contract::where('status', $status)->count();
        }

        return [
            'statuses' => $statuses,
            'counts' => $counts
        ];
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
                $message->to('human.resources@ciptaharmoni.com')
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


    public function printEvaluation($id, Request $request)
    {
        // Get contract data with employee relationship
        $contract = Contract::findOrFail($id);

        // Get employee data using NIK from contract
        $employee = DB::table('karyawan')
            ->where('nik', $contract->nik)
            ->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee data not found');
        }

        // Get jabatan data using jabatan_id from employee
        $jabatan = DB::table('jabatan')
            ->where('id', $employee->jabatan)
            ->first();

        $employeeAtasan = DB::table('karyawan')
            ->where('jabatan', $jabatan->jabatan_atasan)
            ->first();

        $type = $request->query('type');
        $management = $request->query('management');
        $hrd = $request->query('hrd');

        // Indonesian month abbreviations (4 letters max)
        $bulanIndo = [
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agt',
            '09' => 'Sept',
            '10' => 'Okt',
            '11' => 'Nov',
            '12' => 'Des'
        ];

        // Calculate periode evaluasi based on type
        $startDate = Carbon::parse($contract->start_date);
        if ($type === '3_bulan') {
            $endDate = $startDate->copy()->addMonths(3)->subDay();
        } else {
            $endDate = Carbon::parse($contract->end_date);
        }

        // Format the dates
        $startMonth = $bulanIndo[$startDate->format('m')];
        $endMonth = $bulanIndo[$endDate->format('m')];
        $startYear = $startDate->format('Y');
        $endYear = $endDate->format('Y');

        $contractData = [
            'nama_lengkap' => $employee->nama_lengkap,
            'kode_dept' => $employee->kode_dept,
            'hrd_name' => $request->query('hrd'), // Make sure we're using query parameter
            'jabatan_name' => $jabatan->nama_jabatan ?? '-',
            'atasan_name' => $employeeAtasan->nama_lengkap ?? '-',
            'tgl_masuk' => Carbon::parse($employee->tgl_masuk)->format('d-m-Y'),
            'periode_evaluasi' => [
                'formatted' => "{$startMonth} {$startYear} - {$endMonth} {$endYear}"
            ],
            'management' => $management
        ];

        // Return view based on management
        if ($management === 'Al Imron') {
            return view('performance.evaluationKontrak', [
                'data' => (object)$contractData,
                'type' => $type
            ]);
        } else {
            return view('performance.evaluation', [
                'data' => (object)$contractData,
                'type' => $type
            ]);
        }
    }

    public function peningkatanOrExtend(Request $request)
    {
        try {
            $contractId = $request->id;
            $contract = Contract::find($contractId);

            if (!$contract) {
                return redirect()->back()->with('danger', 'Contract not found');
            }

            switch ($request->actionType) {
                case 'extend':
                    // Update current contract status to Extended
                    $contract->status = 'Extended';
                    $contract->save();

                    // Generate new contract number
                    $no_kontrak = $this->generateContractNumber();

                    // Create new contract
                    Contract::create([
                        'nik' => $contract->nik,
                        'no_kontrak' => $no_kontrak,
                        'hari_kerja' => $this->getDayInIndonesian($request->new_start_date),
                        'start_date' => $request->new_start_date,
                        'end_date' => $request->new_end_date,
                        'contract_type' => $contract->contract_type,
                        'position' => $contract->position,
                        'salary' => $contract->salary,
                        'status' => 'Active',
                        'created_by' => Auth::guard('user')->user()->name,
                    ]);
                    break;

                case 'peningkatan':
                    // Update current contract status
                    $contract->status = 'Extended';
                    $contract->save();

                    // Generate new SK number
                    $no_sk = $this->generateSKNumber($contract->nik);

                    $karyawan = DB::table('karyawan')
                        ->where('nik', $contract->nik)
                        ->where('status_kar', 'Aktif')
                        ->first();

                    SK::create([
                        'nik' => $contract->nik,
                        'nama_karyawan' => $karyawan->nama_lengkap,
                        'no_sk' => $no_sk,
                        'tgl_sk' => $request->tgl_sk,
                        'nama_pt' => $karyawan->nama_pt,
                        'masa_probation' => $request->masa_probation,
                        'diketahui' => $request->diketahui,
                        'status' => 'Active',
                        'created_by' => Auth::guard('user')->user()->name,
                    ]);
                    break;

                case 'tidak_lanjut':
                    $contract->status = 'Expired';
                    $contract->reasoning = $request->alasan;
                    $contract->save();
                    break;

                case 'mengakhiri':
                    $contract->status = 'Terminated';
                    $contract->reasoning = $request->alasan_mengakhiri;
                    $contract->save();
                    break;
            }

            return redirect()->back()->with('success', 'Contract status updated successfully.');
        } catch (Exception $e) {
            Log::error('Error in peningkatanOrExtend function: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'contract_id' => $request->id
            ]);

            return redirect()->back()->with('danger', 'Error updating contract: ' . $e->getMessage());
        }
    }

    private function generateContractNumber()
    {
        $user = Auth::guard('user')->user();
        $lastContract = DB::table('kontrak')
            ->orderBy('id', 'desc')
            ->value('no_kontrak');

        // Extract number from last contract
        if ($lastContract) {
            $parts = explode('/', $lastContract);
            $lastNumber = (int)$parts[0];
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        // Simplify user name
        $simplifiedName = $this->simplifyName($user->name);

        // Get current month in Roman numerals
        $romanMonth = $this->getRomanMonth(date('n'));
        $currentYear = date('Y');

        return "{$nextNumber}/SPK-CHL/{$simplifiedName}/{$romanMonth}/{$currentYear}";
    }

    private function getDayInIndonesian($date)
    {
        $daysInIndonesian = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        return $daysInIndonesian[date('l', strtotime($date))];
    }

    private function simplifyName($name)
    {
        $nameParts = explode(' ', $name);
        if (count($nameParts) == 1) {
            return strtoupper(substr($nameParts[0], 0, 3));
        }

        $simplifiedName = '';
        foreach ($nameParts as $index => $part) {
            $simplifiedName .= strtoupper(substr($part, 0, 1));
            if ($index == 2) break;
        }
        return $simplifiedName;
    }

    private function getRomanMonth($month)
    {
        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romanMonths[$month];
    }
}
