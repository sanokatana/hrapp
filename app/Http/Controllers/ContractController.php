<?php

namespace App\Http\Controllers;

use App\Exports\KontrakExport;
use App\Models\Contract;
use App\Models\SK;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        // Start the query with a left join to the karyawan table
        $query = Contract::query()
            ->select('kontrak.*', 'karyawan.nama_lengkap') // Select fields from contracts and nama_lengkap from karyawan
            ->leftJoin('karyawan', 'kontrak.nik', '=', 'karyawan.nik') // Use left join to include all contracts
            ->orderBy('kontrak.id', 'asc');

        // Add filter for no_kontrak if provided in request
        if (!empty($request->no_kontrak)) {
            $query->where('kontrak.no_kontrak', 'like', '%' . $request->no_kontrak . '%');
        }

        if (!empty($request->nama_karyawan)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->status_kontrak)) {
            $query->where('kontrak.status', 'like', '%' . $request->status_kontrak . '%');
        }

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
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $contract_type = $request->contract_type;
        $position = $request->position;
        $salary = $request->salary;
        $status = $request->status;
        $contract_file = $request->contract_file;
        $reasoning = "New Contract";
        $periode_awal = $request->start_date; // Assuming `periode_awal` is the same as `start_date`

        // Get the day of the week for `periode_awal`
        $dayOfWeek = date('l', strtotime($periode_awal)); // Get the day in English

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

        // If no_kontrak is empty, generate it
        if (empty($no_kontrak)) {
            // Get the last contract number (xxx)
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
                1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V',
                6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X',
                11 => 'XI', 12 => 'XII'
            ];
            $romanMonth = $romanMonths[$currentMonth];

            // Generate the no_kontrak value
            $no_kontrak = "{$nextNumber}/SPK-CHL/{$simplifiedName}/{$romanMonth}/{$currentYear}";
        }

        $data = [
            'nik' => $nik,
            'no_kontrak' => $no_kontrak,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'contract_type' => $contract_type,
            'position' => $position,
            'salary' => $salary,
            'status' => $status,
            'contract_file' => $contract_file,
            'created_by' => $name,
            'hari_kerja' => $hari_kerja // Add hari_kerja to the data
        ];

        // Insert data into kontrak table
        $id = DB::table('kontrak')->insertGetId($data); // Retrieve the ID of the inserted record

        if ($id) {
            // Insert the same data into kontrak_history table
            DB::table('kontrak_history')->insert([
                'kontrak_id' => $id, // Use the retrieved ID
                'nik' => $nik,
                'no_kontrak' => $no_kontrak,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'contract_type' => $contract_type,
                'position' => $position,
                'salary' => $salary,
                'status' => $status,
                'changed_by' => $name,
                'change_reason' => $reasoning,
                'contract_file' => $contract_file,
                'hari_kerja' => $hari_kerja // Add hari_kerja to kontrak_history
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

    public function downloadTemplateKontrak()
    {
        $filePath = public_path('storage/uploads/templates/kontrak_template.xlsx');

        return response()->download($filePath, 'kontrak_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    public function uploadKontrak(Request $request)
    {
        $name = Auth::guard('user')->user()->name;

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Redirect back if validation fails
        if ($validator->fails()) {
            return redirect()->back()->with('danger', 'Please upload a valid XLSX file.');
        }

        try {
            // Get the uploaded file and load the spreadsheet
            $file = $request->file('file');
            $filePath = $file->getRealPath();
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Read the data from the spreadsheet
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

                    // Convert Excel date serial numbers to date strings
                    $tglStart = Date::excelToDateTimeObject($mappedData['start_date'])->format('Y-m-d');
                    $tglStop = !empty($mappedData['end_date']) ? Date::excelToDateTimeObject($mappedData['end_date'])->format('Y-m-d') : null;

                    $data[] = [
                        'nik' => $mappedData['nik'],
                        'no_kontrak' => $mappedData['no_kontrak'],
                        'hari_kerja' => $mappedData['hari_kerja'],
                        'start_date' => $tglStart,
                        'end_date' => $tglStop,
                        'contract_type' => $mappedData['contract_type'],
                        'position' => $mappedData['position'],
                        'status' => $mappedData['status'],
                        'created_by' => $name
                    ];
                }
            }

            // Insert data into the database
            DB::table('kontrak')->insert($data);

            // Redirect back with success message
            return redirect()->back()->with('success', 'Data successfully uploaded.');
        } catch (Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('danger', 'Error uploading data: ' . $e->getMessage());
        }
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
                $contract->status = 'Extended';
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

                // Create a new contract with the same details but updated start and end dates
                Contract::create([
                    'nik' => $contract->nik,
                    'no_kontrak' => $contract->no_kontrak,
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
                $contract->status = 'Non-Active';
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

}
