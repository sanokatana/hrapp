<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\PengajuanCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengajuanCutiController extends Controller
{
    public function buatcuti()
    {
        $nik = auth()->user()->nik;
        $currentEmployee = DB::table('karyawan')->where('nik', $nik)->first();
        $kode_dept = $currentEmployee->kode_dept;
        $employees = DB::table('karyawan')
            ->where('kode_dept', $kode_dept)
            ->where('nik', '!=', $nik)
            ->get();

        $cuti = DB::table('cuti')
            ->where('nik', $nik)
            ->where('status', 1)
            ->first();

        $periode = $cuti ? $cuti->tahun : '';
        $periode_awal = $cuti ? $cuti->periode_awal : '';
        $periode_akhir = $cuti ? $cuti->periode_akhir : '';
        $cutiGet = Cuti::where('nik', $nik)
            ->where('tahun', $periode)
            ->first();



        return view('izin.buatcuti', compact('periode', 'periode_awal', 'periode_akhir', 'employees', 'cutiGet'));
    }


    public function storecuti(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $periode = $request->periode;
        $sisa_cuti = $request->sisa_cuti;
        $tgl_cuti = $request->tgl_cuti;
        $tgl_cuti_sampai = $request->tgl_cuti_sampai;
        $jml_hari = $request->jml_hari;
        $sisa_cuti_setelah = $request->sisa_cuti_setelah;
        $kar_ganti = $request->kar_ganti;
        $note = $request->note;
        $jenis = "Cuti Tahunan";

        $data = [
            'nik' => $nik,
            'periode' => $periode,
            'sisa_cuti' => $sisa_cuti,
            'tgl_cuti' => $tgl_cuti,
            'tgl_cuti_sampai' => $tgl_cuti_sampai,
            'jml_hari' => $jml_hari,
            'sisa_cuti_setelah' => $sisa_cuti_setelah,
            'kar_ganti' => $kar_ganti,
            'note' => $note,
            'jenis' => $jenis,
        ];

        // Start a transaction
        DB::beginTransaction();

        try {
            // Save the leave application
            $simpan = PengajuanCuti::create($data);

            if ($simpan) {
                // Update the sisa_cuti in the cuti table
                $cuti = DB::table('cuti')
                    ->where('nik', $nik)
                    ->where('tahun', $periode)
                    ->first();

                if ($cuti) {
                    $new_sisa_cuti = $cuti->sisa_cuti - $jml_hari;

                    DB::table('cuti')
                        ->where('nik', $nik)
                        ->where('tahun', $periode)
                        ->update(['sisa_cuti' => $new_sisa_cuti]);
                }

                // Commit the transaction
                DB::commit();

                return redirect('/presensi/izin')->with(['success' => 'Pengajuan Cuti Berhasil Di Simpan']);
            } else {
                // Rollback the transaction
                DB::rollBack();

                return redirect('/presensi/izin')->with(['error' => 'Pengajuan Cuti Gagal Di Simpan']);
            }
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();

            return redirect('/presensi/izin')->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function getSisaCuti(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $periode = $request->periode;

        $cuti = Cuti::where('nik', $nik)
            ->where('tahun', $periode)
            ->first();

        if ($cuti) {
            return response()->json(['sisa_cuti' => $cuti->sisa_cuti]);
        } else {
            return response()->json(['sisa_cuti' => 0]);
        }
    }



    //Cuti Khusus

    public function cuti()
    {
        $tipecuti = DB::table('tipe_cuti')->get();

        return view('izin.cuti', compact('tipecuti'));
    }

    public function buatcutikhusus()
    {
        $tipecuti = DB::table('tipe_cuti')->get();
        $nik = auth()->user()->nik;
        $currentEmployee = DB::table('karyawan')->where('nik', $nik)->first();
        $cuti = DB::table('cuti')
            ->where('nik', $nik)
            ->where('status', 1)
            ->first();

        $periode = $cuti ? $cuti->tahun : '';

        return view('izin.buatcutikhusus', compact('tipecuti', 'periode'));
    }


    public function storecutikhusus(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_cuti = $request->tgl_cuti;
        $tgl_cuti_sampai = $request->tgl_cuti_sampai;
        $jml_hari = $request->jml_hari;
        $id_tipe_cuti = $request->id_tipe_cuti;
        $note = $request->note;
        $jenis = "Cuti Khusus";

        $data = [
            'nik' => $nik,
            'tgl_cuti' => $tgl_cuti,
            'tgl_cuti_sampai' => $tgl_cuti_sampai,
            'jml_hari' => $jml_hari,
            'note' => $note,
            'jenis' => $jenis,
            'tipe' => $id_tipe_cuti,
        ];

        // Start a transaction
        DB::beginTransaction();

        try {
            // Save the leave application
            $simpan = PengajuanCuti::create($data);

            if ($simpan) {
                // Commit the transaction
                DB::commit();

                return redirect('/presensi/izin')->with(['success' => 'Pengajuan Cuti Berhasil Di Simpan']);
            } else {
                // Rollback the transaction
                DB::rollBack();

                return redirect('/presensi/izin')->with(['error' => 'Pengajuan Cuti Gagal Di Simpan']);
            }
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();

            return redirect('/presensi/izin')->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
