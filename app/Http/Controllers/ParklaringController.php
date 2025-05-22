<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ParklaringController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('parklaring')
            ->select('parklaring.*', 'karyawan.nama_lengkap')
            ->leftJoin('karyawan', 'parklaring.nik', '=', 'karyawan.nik');

        if(!empty($request->no_parklaring)) {
            $query->where('parklaring.no_parklaring', 'like', '%' . $request->no_parklaring . '%');
        }

        if(!empty($request->nama_karyawan)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        $parklar = $query->orderBy('parklaring.created_at', 'desc')->paginate(10);
        return view('parklaring.index', compact('parklar'));
    }

    public function getEmployeeByNik(Request $request)
    {
        $nik = $request->nik;
        $employee = DB::table('karyawan')
            ->select('karyawan.*', 'jabatan.nama_jabatan as jabatan', 'department.department', 'tb_pt.short_name')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->leftJoin('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->leftJoin('tb_pt', 'karyawan.nama_pt', '=', 'tb_pt.short_name')
            ->where('karyawan.nik', $nik)
            ->first();
        return response()->json($employee);
    }

    public function getEmployeeName(Request $request)
    {
        $nama_lengkap = $request->nama_lengkap;
        $employees = DB::table('karyawan')
            ->select('karyawan.nik', 'karyawan.nama_lengkap', 'karyawan.tgl_masuk', 'karyawan.tgl_resign', 'jabatan.nama_jabatan as jabatan', 'department.kode_dept')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->leftJoin('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->where('karyawan.nama_lengkap', 'like', '%' . $nama_lengkap . '%')
            ->where('karyawan.status_kar', '=', 'Aktif') // Exclude resigned employees
            ->limit(10)
            ->get();
        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'tgl_terakhir' => 'required|date',
            'no_mode' => 'required|in:auto,manual',
        ]);

        // Get employee data to retrieve company info
        $karyawan = DB::table('karyawan')
            ->select('karyawan.*', 'tb_pt.short_name')
            ->leftJoin('tb_pt', 'karyawan.nama_pt', '=', 'tb_pt.short_name')
            ->where('karyawan.nik', $request->nik)
            ->first();

        if (!$karyawan) {
            return redirect('/parklaring')->with('danger', 'Karyawan tidak ditemukan');
        }

        // Generate or use manual parklaring number
        if ($request->no_mode === 'manual') {
            // Check if manual number already exists
            $existingNumber = DB::table('parklaring')
                ->where('no_parklaring', $request->manual_no_parklaring)
                ->exists();

            if ($existingNumber) {
                return redirect('/parklaring')->with('danger', 'Nomor Parklaring sudah digunakan');
            }

            $no_parklaring = $request->manual_no_parklaring;
        } else {
            // Generate auto number (format: 057/HC/SBC/II/2025)
            $currentMonth = Carbon::now()->format('m');
            $romanMonth = $this->numberToRoman($currentMonth);
            $currentYear = Carbon::now()->format('Y');

            // Get the counter for this month and year
            $latestParklaring = DB::table('parklaring')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->orderBy('id', 'desc')
                ->first();

            $counter = $latestParklaring ? (int)explode('/', $latestParklaring->no_parklaring)[0] + 1 : 1;
            $counterFormatted = sprintf('%03d', $counter);

            // Get company short name or use HC as default
            $companyCode = $karyawan && $karyawan->nama_pt ? 'HC/' . $karyawan->nama_pt : 'HC/SBC';

            $no_parklaring = "{$counterFormatted}/{$companyCode}/{$romanMonth}/{$currentYear}";
        }

        // Insert parklaring data
        $data = [
            'no_parklaring' => $no_parklaring,
            'nik' => $request->nik,
            'tgl_terakhir' => $request->tgl_terakhir,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $id = DB::table('parklaring')->insertGetId($data);

        // Update the employee's resignation date if not already set
        DB::table('karyawan')
            ->where('nik', $request->nik)
            ->update([
                'tgl_resign' => $request->tgl_terakhir,
                'status_kar' => 'Non-Aktif',
                'updated_at' => now()
            ]);

        if($id) {
            return redirect('/parklaring')->with('success', 'Data Parklaring berhasil disimpan dan tanggal resign karyawan diperbarui');
        } else {
            return redirect('/parklaring')->with('danger', 'Data Parklaring gagal disimpan');
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;
        $parklaring = DB::table('parklaring')
            ->select('parklaring.*', 'karyawan.nama_lengkap', 'karyawan.tgl_masuk', 'karyawan.jabatan', 'department.kode_dept as departemen', 'jabatan.nama_jabatan as jabatan')
            ->leftJoin('karyawan', 'parklaring.nik', '=', 'karyawan.nik')
            ->leftJoin('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->where('parklaring.id', $id)
            ->first();

        return view('parklaring.view', compact('parklaring'));
    }

    public function print($id)
    {
        $parklaring = DB::table('parklaring')
            ->select('parklaring.*', 'karyawan.nama_lengkap', 'karyawan.tgl_masuk', 'karyawan.jabatan', 'department.kode_dept as departemen', 'jabatan.nama_jabatan as jabatan', 'tb_pt.long_name as nama_pt')
            ->leftJoin('karyawan', 'parklaring.nik', '=', 'karyawan.nik')
            ->leftJoin('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->leftJoin('tb_pt', 'karyawan.nama_pt', '=', 'tb_pt.short_name')
            ->where('parklaring.id', $id)
            ->first();


        if (!$parklaring) {
            return redirect('/parklaring')->with('danger', 'Data Parklaring tidak ditemukan');
        }

        $karyawan = DB::table('karyawan')
            ->where('nik', $parklaring->nik)
            ->first();

        // Calculate masa kerja
        $tgl_masuk = Carbon::parse($karyawan->tgl_masuk);
        $tgl_terakhir = Carbon::parse($parklaring->tgl_terakhir);
        $diff = $tgl_masuk->diff($tgl_terakhir);

        $masa_kerja = '';
        if ($diff->y > 0) {
            $masa_kerja .= $diff->y . ' tahun ';
        }
        if ($diff->m > 0) {
            $masa_kerja .= $diff->m . ' bulan ';
        }
        if ($diff->d > 0) {
            $masa_kerja .= $diff->d . ' hari';
        }

        $tgl_masuk = Carbon::parse($karyawan->tgl_masuk)->format('d F Y');
        $tgl_terakhir = Carbon::parse($parklaring->tgl_terakhir)->format('d F Y');
        $tgl_cetak = Carbon::now()->locale('id')->isoFormat('D MMMM Y');
        $tgl_masuk_formatted = \App\Helpers\DateHelper::formatIndonesiaDate($karyawan->tgl_masuk);
        $tgl_terakhir_formatted = \App\Helpers\DateHelper::formatIndonesiaDate($parklaring->tgl_terakhir);
        $masa_kerja_range = $tgl_masuk_formatted . ' - ' . $tgl_terakhir_formatted;
        // HRD Manager - This could be from a setting or fixed value
        $hrd_manager = 'Nama HRD Manager'; // Replace with actual value or setting

        return view('parklaring.printParklaring', compact('parklaring','masa_kerja_range', 'karyawan', 'masa_kerja', 'tgl_masuk', 'tgl_terakhir_formatted', 'tgl_cetak', 'hrd_manager'));
    }

    public function delete($id)
    {
        $deleted = DB::table('parklaring')->where('id', $id)->delete();

        if($deleted) {
            return redirect('/parklaring')->with('success', 'Data Parklaring berhasil dihapus');
        } else {
            return redirect('/parklaring')->with('danger', 'Data Parklaring gagal dihapus');
        }
    }

    private function numberToRoman($num)
    {
        $roman = [
            'I', 'II', 'III', 'IV', 'V', 'VI',
            'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
        ];
        return $roman[$num - 1] ?? $num;
    }

    public function export()
    {
        $parklar = DB::table('parklaring')
            ->select(
                'parklaring.*',
                'karyawan.nama_lengkap',
                'karyawan.nik',
                'karyawan.tgl_masuk',
                'jabatan.nama_jabatan as jabatan',
                'tb_pt.long_name as nama_pt'
            )
            ->leftJoin('karyawan', 'parklaring.nik', '=', 'karyawan.nik')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->leftJoin('tb_pt', 'karyawan.nama_pt', '=', 'tb_pt.short_name')
            ->orderBy('parklaring.created_at', 'desc')
            ->get();

        // Make sure Laravel Excel package is properly imported at top of controller
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ParklaringExport($parklar),
            'parklaring_export_' . date('YmdHis') . '.xlsx'
        );
    }
}
