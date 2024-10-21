<?php

namespace App\Http\Controllers;

use App\Models\SK;
use Illuminate\Http\Request;

class SkController extends Controller
{
    public function index(Request $request)
    {
        // Start the query with a left join to the karyawan table
        $query = SK::query()
            ->select('tb_sk.*', 'karyawan.nama_lengkap') // Select fields from contracts and nama_lengkap from karyawan
            ->leftJoin('karyawan', 'tb_sk.nik', '=', 'karyawan.nik') // Use left join to include all contracts
            ->orderBy('tb_sk.id', 'asc');

        // Add filter for no_kontrak if provided in request
        if (!empty($request->sk_no)) {
            $query->where('tb_sk.no_sk', 'like', '%' . $request->sk_no . '%');
        }

        if (!empty($request->nama_karyawan)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        // Paginate the results
        $contract = $query->paginate(50);

        return view('contract.index', compact('contract'));
    }
}
