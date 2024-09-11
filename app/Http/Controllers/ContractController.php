<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        // Start the query with a join to the karyawan table
        $query = Contract::query()
            ->select('kontrak.*', 'karyawan.nama_lengkap') // Select fields from contracts and nama_lengkap from karyawan
            ->join('karyawan', 'kontrak.nik', '=', 'karyawan.nik') // Join with karyawan table on nik
            ->orderBy('kontrak.id', 'asc');

        // Add filter for no_kontrak if provided in request
        if (!empty($request->no_kontrak)) {
            $query->where('kontrak.no_kontrak', 'like', '%' . $request->no_kontrak . '%');
        }

        // Paginate the results
        $contract = $query->paginate(20);

        return view('contract.index', compact('contract'));
    }


    public function store(Request $request){
        $name = Auth::guard('user')->user()->name;
        $nik = $request->nik;
        $no_kontrak = $request->no_kontrak;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $contract_type = $request->contract_type;
        $position = $request->position;
        $salary = $request->salary;
        $status = $request->status;
        $contract_file = $request->contract_file;
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
            'created_by' => $name
        ];

        $simpan = DB::table('kontrak')
        ->insert($data);
        if($simpan){
            return Redirect::back()->with(['success'=>'Data Berhasil Di Simpan']);
        }else {
            return Redirect::back()->with(['warning'=>'Data Gagal Di Simpan']);
        }
    }

    public function edit(Request $request){
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


}
