<?php

namespace App\Http\Controllers;

use App\Exports\KontrakExport;
use App\Helpers\DateHelper;
use App\Models\Contract;
use App\Models\SK;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        // Start the query with a left join to the karyawan table
        $query = Contract::query()
            ->select('kontrak.*', 'karyawan.nama_lengkap') // Select fields from contracts and nama_lengkap from karyawan
            ->leftJoin('karyawan', 'kontrak.nik', '=', 'karyawan.nik') // Use left join to include all contracts
            ->whereNotNull('karyawan.nama_lengkap'); // Ensure that nama_lengkap exists

        // Filter by specific fields if provided in request
        if (!empty($request->no_kontrak)) {
            $query->where('kontrak.no_kontrak', 'like', '%' . $request->no_kontrak . '%');
        }

        if (!empty($request->nama_karyawan)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        if ($request->has('status_kontrak')) {
            if ($request->status_kontrak === 'Active' || $request->status_kontrak === 'Expired' || $request->status_kontrak === 'Extended' || $request->status_kontrak === 'Terminated') {
                $query->where('kontrak.status', $request->status_kontrak);
            }
        } else {
            // Default to '0' (Pending) if no status_approved_hrd is provided
            $query->where('kontrak.status', 'Active');
        }

        // Order the results with Active contracts first
        $query->orderByRaw("CASE WHEN kontrak.status = 'Active' THEN 0 ELSE 1 END");
        $query->orderBy('kontrak.no_kontrak', 'DESC');

        // Paginate the results
        $contract = $query->paginate(50)->appends($request->query());

        return view('contract.index', compact('contract'));
    }

    public function store(Request $request)
    {
        $user = Auth::guard('user')->user();
        $name = $user->name;
        $nik = $request->nik;
        $no_kontrak = $request->no_kontrak;
        $start_date = $request->start_date; // Start date in 'Y-m-d' format
        $end_date_selection = $request->end_date_selection; // The dropdown value
        $end_date_manual = $request->end_date_manual; // The manual date input
        $position = $request->position;
        $status = $request->status;
        $reasoning = "New Contract";

        $periode_awal = $request->start_date;
        $dayOfWeek = date('l', strtotime($periode_awal));

        $daysInIndonesian = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $hari_kerja = $daysInIndonesian[$dayOfWeek];

        // Determine the end_date based on the selection
        if ($end_date_selection === 'manual') {
            $end_date = $end_date_manual; // Use the manually selected date
        } else {
            // Add the selected number of months to the start_date
            $monthsToAdd = (int)$end_date_selection; // Convert dropdown value to integer
            $startDateObj = new DateTime($start_date);
            $startDateObj->modify("+{$monthsToAdd} months"); // Add fixed months
            $startDateObj->modify("-1 day"); // Subtract one day
            $end_date = $startDateObj->format('Y-m-d'); // Format as 'Y-m-d'
        }


        // Generate no_kontrak if empty
        if (empty($no_kontrak)) {
            $lastContract = DB::table('kontrak')
                ->orderBy('id', 'desc')
                ->value('no_kontrak');

            if ($lastContract) {
                $parts = explode('/', $lastContract);
                $lastNumber = (int) $parts[0];
                $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $nextNumber = '001';
            }

            $nameParts = explode(' ', $name);
            $simplifiedName = count($nameParts) == 1
                ? strtoupper(substr($nameParts[0], 0, 3))
                : strtoupper(substr(implode('', array_map(fn($part) => substr($part, 0, 1), $nameParts)), 0, 3));

            $currentMonth = date('n');
            $currentYear = date('Y');

            $romanMonths = [
                1 => 'I',
                2 => 'II',
                3 => 'III',
                4 => 'IV',
                5 => 'V',
                6 => 'VI',
                7 => 'VII',
                8 => 'VIII',
                9 => 'IX',
                10 => 'X',
                11 => 'XI',
                12 => 'XII'
            ];
            $romanMonth = $romanMonths[$currentMonth];

            $no_kontrak = "{$nextNumber}/SPK-CHL/{$simplifiedName}/{$romanMonth}/{$currentYear}";
        }

        $data = [
            'nik' => $nik,
            'no_kontrak' => $no_kontrak,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'contract_type' => 'PKWT',
            'position' => $position,
            'status' => 'Active',
            'created_by' => $name,
            'hari_kerja' => $hari_kerja
        ];

        $id = DB::table('kontrak')->insertGetId($data);

        if ($id) {
            DB::table('kontrak_history')->insert([
                'kontrak_id' => $id,
                'nik' => $nik,
                'no_kontrak' => $no_kontrak,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'contract_type' => 'PKWT',
                'position' => $position,
                'status' => 'Active',
                'changed_by' => $name,
                'change_reason' => $reasoning,
                'hari_kerja' => $hari_kerja
            ]);

            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $contract = DB::table('kontrak')
            ->select('kontrak.*', 'karyawan.nama_lengkap') // Select fields from contracts and nama_lengkap from karyawan
            ->join('karyawan', 'kontrak.nik', '=', 'karyawan.nik') // Join with karyawan table on nik
            ->where('kontrak.id', $id)
            ->first();
        return view('contract.edit', compact('contract'));
    }

    public function view(Request $request)
    {
        $id = $request->id;
        $contract = DB::table('kontrak')
            ->select('kontrak.*', 'karyawan.nama_lengkap') // Select fields from kontrak and nama_lengkap from karyawan
            ->join('karyawan', 'kontrak.nik', '=', 'karyawan.nik') // Join with karyawan table on nik
            ->where('kontrak.id', $id) // Specify the kontrak table for the id column
            ->first();

        return view('contract.view', compact('contract')); // Pass the correct variable name
    }

    public function update($id, Request $request)
    {

        $currentData = DB::table('kontrak')->where('id', $id)->first();

        if ($currentData) {
            // Step 2: Insert the current data into the kontrak_history table
            DB::table('kontrak_history')->insert([
                'kontrak_id' => $currentData->id,
                'nik' => $currentData->nik,
                'no_kontrak' => $request->no_kontrak,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'contract_type' => $request->contract_type,
                'hari_kerja' => $request->hari_kerja,
                'position' => $request->position,
                'salary' => $request->salaryedit,
                'status' => $request->status,
                'changed_by' => Auth::guard('user')->user()->name,
                'change_reason' => $request->reasoning, // Store the reason for update
                'contract_file' => $request->contract_file,
            ]);
        }

        $dayOfWeek = date('l', strtotime($request->start_date)); // Get the day in English

        // Translate the day to Bahasa Indonesia
        $daysInIndonesian = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $hari_kerja = $daysInIndonesian[$dayOfWeek];

        // Prepare the new data for updating the kontrak table
        $data = [
            'no_kontrak' => $request->no_kontrak,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'hari_kerja' => $hari_kerja,
            'contract_type' => $request->contract_type,
            'position' => $request->position,
            'salary' => $request->salaryedit,
            'status' => $request->status,
            'contract_file' => $request->contract_file,
        ];

        $update = DB::table('kontrak')->where('id', $id)->update($data);

        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function delete($id)
    {
        // Step 1: Find the current data from the 'kontrak' table
        $currentData = DB::table('kontrak')->where('id', $id)->first();

        if ($currentData) {
            // Step 2: Insert the current data into the 'kontrak_history' table
            DB::table('kontrak_history')->insert([
                'kontrak_id' => $currentData->id,
                'nik' => $currentData->nik,
                'no_kontrak' => $currentData->no_kontrak,
                'start_date' => $currentData->start_date,
                'end_date' => $currentData->end_date,
                'contract_type' => $currentData->contract_type,
                'position' => $currentData->position,
                'hari_kerja' => $currentData->hari_kerja, // Add hari_kerja to kontrak_history
                'salary' => $currentData->salary,
                'status' => $currentData->status,
                'changed_by' => Auth::guard('user')->user()->name,
                'change_reason' => "Contract Deleted", // Reason for deletion
                'contract_file' => $currentData->contract_file,
            ]);

            // Step 3: Delete the record from 'kontrak' table after inserting the history
            $delete = DB::table('kontrak')->where('id', $id)->delete();

            if ($delete) {
                return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
            } else {
                return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
            }
        } else {
            // If the record does not exist
            return Redirect::back()->with(['warning' => 'Data Tidak Ditemukan']);
        }
    }

    public function checkExpired(Request $request)
    {
        try {
            // Get all active contracts that have passed their end date
            $expiredContracts = Contract::where('status', 'Active')
                ->where('end_date', '<', now()->format('Y-m-d'))
                ->get();

            // Update their status to Expired
            foreach ($expiredContracts as $contract) {
                $contract->status = 'Expired';
                $contract->save();
            }

            return response()->json([
                'success' => true,
                'count' => $expiredContracts->count(),
                'message' => 'Contracts checked successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking contracts: ' . $e->getMessage()
            ], 500);
        }
    }


    // YourController.php
    public function filterContracts(Request $request)
    {
        $nik = $request->query('nik');

        $contracts = Contract::where('nik', $nik)->get(['no_kontrak']);

        return response()->json($contracts);
    }

    public function getContractType(Request $request)
    {
        $noKontrak = $request->input('no_kontrak');

        // Fetch the contract details based on the no_kontrak
        $contract = Contract::where('no_kontrak', $noKontrak)->first();

        if ($contract) {
            // Return the contract type
            return response()->json([
                'contract_type' => $contract->contract_type
            ]);
        } else {
            // Handle case where no contract is found
            return response()->json(['contract_type' => ''], 404);
        }
    }

    public function uploadKontrak(Request $request)
    {
        $name = Auth::guard('user')->user()->name;
        $today = now()->startOfDay(); // Get today's date without time

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('danger', 'Please upload a valid Excel or CSV file.');
        }

        try {
            $file = $request->file('file');
            $filePath = $file->getRealPath();
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            $header = [];
            $data = [];

            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }

                if ($rowIndex == 1) {
                    $header = $rowData;
                } else {
                    $mappedData = array_combine($header, $rowData);

                    // Parse dates
                    $tglStart = $this->parseDate($mappedData['start_date']);
                    $tglStop = !empty($mappedData['end_date']) ? $this->parseDate($mappedData['end_date']) : null;

                    // Get hari_kerja in Indonesian
                    $hariKerja = $this->getIndonesianDayName($tglStart);

                    // Determine status
                    $status = 'Active';
                    if ($tglStop) {
                        $endDate = Carbon::parse($tglStop)->startOfDay();
                        $status = $endDate->lt($today) ? 'Expired' : 'Active';
                    }

                    $data[] = [
                        'nik' => $mappedData['nik'],
                        'no_kontrak' => $mappedData['no_kontrak'],
                        'hari_kerja' => $hariKerja, // Automatically determined day name
                        'start_date' => $tglStart,
                        'end_date' => $tglStop,
                        'contract_type' => $mappedData['contract_type'],
                        'position' => $mappedData['position'],
                        'status' => $status,
                        'created_by' => $name
                    ];
                }
            }

            DB::table('kontrak')->insert($data);
            return redirect()->back()->with('success', 'Data successfully uploaded.');
        } catch (Exception $e) {
            return redirect()->back()->with('danger', 'Error uploading data: ' . $e->getMessage());
        }
    }

    private function getIndonesianDayName($date)
    {
        $dayNames = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $englishDayName = Carbon::parse($date)->format('l'); // Get day name in English
        return $dayNames[$englishDayName];
    }

    private function parseDate($dateString)
    {
        try {
            // Remove any potential whitespace
            $dateString = trim($dateString);

            // If it's already a Y-m-d format, return it
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
                return $dateString;
            }

            // Try to parse dd/mm/yyyy format
            if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $dateString, $matches)) {
                return "{$matches[3]}-{$matches[2]}-{$matches[1]}";
            }

            // Try to parse Indonesian format (dd Bulan yyyy)
            $indonesianMonths = [
                'Januari' => '01', 'Februari' => '02', 'Maret' => '03',
                'April' => '04', 'Mei' => '05', 'Juni' => '06',
                'Juli' => '07', 'Agustus' => '08', 'September' => '09',
                'Oktober' => '10', 'November' => '11', 'Desember' => '12'
            ];

            // Split the date string
            $parts = explode(' ', $dateString);
            if (count($parts) === 3) {
                $day = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                $month = $indonesianMonths[$parts[1]] ?? null;
                $year = $parts[2];

                if ($month) {
                    return "$year-$month-$day";
                }
            }

            // If it's an Excel date number
            if (is_numeric($dateString)) {
                return Date::excelToDateTimeObject($dateString)->format('Y-m-d');
            }

            // Try Carbon's parse as last resort
            return Carbon::parse($dateString)->format('Y-m-d');

        } catch (Exception $e) {
            // Log the error and return null or throw exception
            Log::error("Date parsing error for: $dateString - " . $e->getMessage());
            throw new Exception("Invalid date format: $dateString");
        }
    }

    // Updated template download to remove status column since it's automatic
    public function downloadTemplateKontrak()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers (removed hari_kerja since it's automatic)
        $headers = ['nik', 'no_kontrak', 'start_date', 'end_date', 'contract_type', 'position'];
        $sheet->fromArray([$headers], NULL, 'A1');

        // Set date format for columns
        $sheet->getStyle('C:D')->getNumberFormat()->setFormatCode('dd mmmm yyyy');

        // Add example data (removed hari_kerja)
        $exampleData = [
            ['123456', 'CONT/2024/001', '21 Februari 2024', '21 Februari 2025', 'PKWT', 'Staff'],
            ['123457', 'CONT/2024/002', '21/02/2024', '21/02/2025', 'PKWT', 'Officer']
        ];
        $sheet->fromArray($exampleData, NULL, 'A2');

        // Add note about hari_kerja
        $sheet->setCellValue('A4', 'Note: Hari Kerja will be automatically determined from Start Date');
        $sheet->mergeCells('A4:F4');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('A4')->getFont()->setItalic(true);
        $sheet->getStyle('A4')->getFont()->setSize(10);
        $sheet->getStyle('A4')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED));

        // Create the writer
        $writer = new Xlsx($spreadsheet);

        // Create response with proper headers
        return new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="kontrak_template.xlsx"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }

    public function export()
    {
        return Excel::download(new KontrakExport, 'kontrak.xlsx');
    }

    public function peningkatanOrExtend(Request $request)
    {
        try {
            $contractId = $request->id;
            $contract = Contract::find($contractId);

            if (!$contract) {

                return redirect()->back()->with('danger', 'Error uploading data: ');
            }

            if ($request->actionType == 'extend') {

                // Extend the contract
                $newStartDate = $request->new_start_date;
                $newEndDate = $request->new_end_date;

                // Update the current contract's status to 'Extended'
                $contract->status = 'Expired';
                $contract->save();

                $dayOfWeek = date('l', strtotime($newStartDate)); // Get the day in English

                // Translate the day to Bahasa Indonesia
                $daysInIndonesian = [
                    'Sunday' => 'Minggu',
                    'Monday' => 'Senin',
                    'Tuesday' => 'Selasa',
                    'Wednesday' => 'Rabu',
                    'Thursday' => 'Kamis',
                    'Friday' => 'Jumat',
                    'Saturday' => 'Sabtu'
                ];
                $hari_kerja = $daysInIndonesian[$dayOfWeek];

                $user = Auth::guard('user')->user();
                $name = $user->name;

                $lastContract = DB::table('kontrak')
                    ->orderBy('id', 'desc')
                    ->value('no_kontrak');

                // Extract the number (xxx) from the last contract
                if ($lastContract) {
                    $parts = explode('/', $lastContract);
                    $lastNumber = (int)$parts[0];
                    $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT); // Increment and pad with leading zeros
                } else {
                    $nextNumber = '001'; // If no previous contract, start with 001
                }

                // Simplify the user's name to 3 letters
                $nameParts = explode(' ', $name);
                if (count($nameParts) == 1) {
                    // If there's only one name, take the first 3 letters
                    $simplifiedName = strtoupper(substr($nameParts[0], 0, 3));
                } else {
                    // Otherwise, take the initials of up to 3 names
                    $simplifiedName = '';
                    foreach ($nameParts as $index => $part) {
                        $simplifiedName .= strtoupper(substr($part, 0, 1));
                        if ($index == 2) break; // Only take up to 3 initials
                    }
                }

                // Get the current month and year
                $currentMonth = date('n');
                $currentYear = date('Y');

                // Convert the month to Roman numerals
                $romanMonths = [
                    1 => 'I',
                    2 => 'II',
                    3 => 'III',
                    4 => 'IV',
                    5 => 'V',
                    6 => 'VI',
                    7 => 'VII',
                    8 => 'VIII',
                    9 => 'IX',
                    10 => 'X',
                    11 => 'XI',
                    12 => 'XII'
                ];
                $romanMonth = $romanMonths[$currentMonth];

                // Generate the no_kontrak value
                $no_kontrak = "{$nextNumber}/SPK-CHL/{$simplifiedName}/{$romanMonth}/{$currentYear}";

                // Create a new contract with the same details but updated start and end dates
                Contract::create([
                    'nik' => $contract->nik,
                    'no_kontrak' => $no_kontrak,
                    'hari_kerja' => $hari_kerja,
                    'start_date' => $newStartDate,
                    'end_date' => $newEndDate,
                    'contract_type' => $contract->contract_type,
                    'position' => $contract->position,
                    'salary' => $contract->salary,
                    'status' => 'Active',
                    'created_by' => Auth::guard('user')->user()->name,
                ]);
            } elseif ($request->actionType == 'peningkatan') {
                // Peningkatan to Tetap
                $tglSk = $request->tgl_sk;
                $masaProbation = $request->masa_probation;
                $diketahui = $request->diketahui;

                // Update the current contract's status to 'Tetap'
                $contract->status = 'Expired';
                $contract->save();

                $lastContract = DB::table('tb_sk')
                    ->orderBy('id', 'desc')
                    ->value('no_sk');

                // Extract the number (xxx) from the last contract
                if ($lastContract) {
                    $parts = explode('/', $lastContract);
                    $lastNumber = (int)$parts[0];
                    $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT); // Increment and pad with leading zeros
                } else {
                    $nextNumber = '001'; // If no previous contract, start with 001
                }

                // Get the current month and year
                $currentMonth = date('n');
                $currentYear = date('Y');

                $karyawan = DB::table('karyawan')
                    ->where('nik', $contract->nik)
                    ->where('status_kar', 'Aktif')
                    ->first();

                // Convert the month to Roman numerals
                $romanMonths = [
                    1 => 'I',
                    2 => 'II',
                    3 => 'III',
                    4 => 'IV',
                    5 => 'V',
                    6 => 'VI',
                    7 => 'VII',
                    8 => 'VIII',
                    9 => 'IX',
                    10 => 'X',
                    11 => 'XI',
                    12 => 'XII'
                ];
                $romanMonth = $romanMonths[$currentMonth];

                // Generate the no_kontrak value
                $no_sk = "{$nextNumber}/{$karyawan->nama_pt}-HRD/SK.Pgt/{$romanMonth}/{$currentYear}";


                SK::create([
                    'nik' => $contract->nik,
                    'nama_karyawan' => $karyawan->nama_lengkap,
                    'no_sk' => $no_sk,
                    'tgl_sk' => $tglSk,
                    'nama_pt' => $karyawan->nama_pt,
                    'masa_probation' => $masaProbation,
                    'diketahui' => $diketahui,
                    'status' => 'Active',
                    'created_by' => Auth::guard('user')->user()->name,
                ]);
            }

            return redirect()->back()->with('success', 'Data successfully uploaded.');
        } catch (Exception $e) {
            // Log the error with details
            Log::error('Error in peningkatanOrExtend function: ' . $e->getMessage(), [
                'request_data' => $request->all(), // Logs the request data for debugging
                'contract_id' => $request->id
            ]);

            // Return a generic error message
            return redirect()->back()->with('danger', 'Error uploading data: ' . $e->getMessage());
        }
    }


    public function printContract(Request $request, $id)
    {
        $contract = DB::table('kontrak')
            ->select('kontrak.*', 'karyawan.nama_lengkap', 'karyawan.sex', 'karyawan.nik_ktp', 'karyawan.birthplace', 'karyawan.dob', 'karyawan.address', 'karyawan.jabatan')
            ->join('karyawan', 'kontrak.nik', '=', 'karyawan.nik')
            ->where('kontrak.id', $id)
            ->first();

        if (!$contract) {
            abort(404, 'Contract not found');
        }

        $dateNow = DateHelper::formatIndonesianDates($contract->start_date);
        $dateNow2 = DateHelper::formatIndonesianDate($contract->dob);
        $dateNow3 = DateHelper::formatIndonesiaDate(Carbon::now());
        $dateNow4 = DateHelper::formatDateDay($contract->start_date);
        $dateNow5 = DateHelper::formatIndonesiaDate($contract->start_date);
        $dateNow6 = DateHelper::formatIndonesiaDate($contract->end_date);

        // Calculate contract duration in months
        $startDate = Carbon::parse($contract->start_date);
        $endDate = Carbon::parse($contract->end_date)->addDay(); // Include the full last month
        $months = $startDate->diffInMonths($endDate);
        $monthsInWords = DateHelper::convertToIndonesianWords($months);

        $wrappedAddress = wordwrap($contract->address, 50, "\n", true);
        $addressParts = explode("\n", $wrappedAddress);
        $addressLine1 = $addressParts[0] ?? '';
        $addressLine2 = $addressParts[1] ?? '';

        $namaJabatan = DB::table('jabatan')
            ->where('id', $contract->jabatan)
            ->first();

        $atasanJabatan = DB::table('jabatan')
            ->where('id', optional($namaJabatan)->jabatan_atasan)
            ->first();

        // Get the type from the request (default to Non-Sales)
        $type = $request->query('type', 'Non-Sales');

        // Pass data to the view
        return view('contract.print', compact(
            'contract',
            'monthsInWords',
            'months',
            'dateNow',
            'dateNow2',
            'addressLine1',
            'addressLine2',
            'dateNow3',
            'dateNow4',
            'namaJabatan',
            'atasanJabatan',
            'dateNow5',
            'dateNow6',
            'type'
        ));
    }

}
