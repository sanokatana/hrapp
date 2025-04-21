<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\SK;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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
        $sk = $query->paginate(50)->appends($request->query());

        return view('sk.index', compact('sk'));
    }

    public function store(Request $request)
    {
        $user = Auth::guard('user')->user();
        $name = $user->name;
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_sk = $request->no_sk;
        $tgl_sk = $request->tgl_sk;
        $nama_pt = $request->nama_pt;
        $masa_probation = $request->masa_probation;
        $diketahui = $request->diketahui;
        $status = $request->status;
        $file_sk = $request->file_sk;
        $nama_pt = DB::table('karyawan')
            ->where('nik', $nik)
            ->value('nama_pt');

        // If no_kontrak is empty, generate it
        if (empty($no_sk)) {
            // Get the last contract number (xxx)
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
            $skDate = Carbon::parse($tgl_sk);
            $skMonth = $skDate->month;
            $skYear = $skDate->year;

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
            $romanMonth = $romanMonths[$skMonth];

            // Generate the no_kontrak value
            $no_sk = "{$nextNumber}/{$nama_pt}-HRD/SK.Pgt/{$romanMonth}/{$skYear}";
        }

        $data = [
            'nik' => $nik,
            'no_sk' => $no_sk,
            'tgl_sk' => $tgl_sk,
            'nama_pt' => $nama_pt,
            'nama_karyawan' => $nama_lengkap,
            'masa_probation' => $masa_probation,
            'diketahui' => $diketahui,
            'status' => $status,
            'file_sk' => $file_sk,
            'created_by' => $name,
        ];

        // Insert data into kontrak table
        $simpan = DB::table('tb_sk')->insert($data);

        if ($simpan) {
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $sk = DB::table('tb_sk')
            ->select('tb_sk.*', 'karyawan.nama_lengkap') // Select fields from contracts and nama_lengkap from karyawan
            ->join('karyawan', 'tb_sk.nik', '=', 'karyawan.nik') // Join with karyawan table on nik
            ->where('tb_sk.id', $id)
            ->first();
        return view('sk.edit', compact('sk'));
    }

    public function update($id, Request $request)
    {
        $user = Auth::guard('user')->user();

        $name = $user->name;

        $data = [
            'no_sk' => $request->no_sk,
            'tgl_sk' => $request->tgl_sk,
            'nama_pt' => $request->nama_pt,
            'masa_probation' => $request->masa_probation,
            'diketahui' => $request->diketahui,
            'status' => $request->status,
            'file_sk' => $request->file_sk,
            'updated_by' => $name,
        ];

        $update = DB::table('tb_sk')->where('id', $id)->update($data);

        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function delete($id)
    {
        // Step 3: Delete the record from 'kontrak' table after inserting the history
        $delete = DB::table('kontrak')->where('id', $id)->delete();

        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

    public function printContract($id, Request $request)
    {
        $print_type = $request->print_type ?? 'sk';
        $autoPrint = $request->autoPrint ?? true;
        $diketahuiOleh = $request->diketahui_oleh;

        $sk = DB::table('tb_sk')
            ->select(
                'tb_sk.*',
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                'karyawan.tgl_masuk',
                'karyawan.grade',
                'karyawan.nama_pt',
                'karyawan.tgl_masuk',
                'karyawan.kode_dept',
                'jabatan.nama_jabatan'
            )
            ->join('karyawan', 'tb_sk.nik', '=', 'karyawan.nik')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->where('tb_sk.id', $id)
            ->first();

        // Fetch PT details from tb_pt table
        $ptDetails = DB::table('tb_pt')
            ->where('short_name', $sk->nama_pt)
            ->first();

        $dateNow = DateHelper::formatIndonesiaDate($sk->tgl_sk);
        $dateNow1 = DateHelper::formatIndonesiaDate(Carbon::now());
        $namaJabatan = DB::table('jabatan')
            ->where('id', $sk->jabatan)
            ->first();

        // Determine which view to use based on print_type
        if ($print_type === 'iom') {
            // For IOM PGT, also pass the diketahui_oleh parameter
            return view('sk.print', compact('sk', 'dateNow', 'dateNow1', 'namaJabatan', 'ptDetails', 'autoPrint', 'diketahuiOleh'));
        } else {
            // For regular SK
            return view('sk.printSK', compact('sk', 'dateNow', 'namaJabatan', 'ptDetails', 'autoPrint'));
        }
    }
}
