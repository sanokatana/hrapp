<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\PengajuanCuti;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\DateHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PengajuanCutiController extends Controller
{
    public function buatcuti()
    {
        $nip = auth()->user()->nip;
        $currentEmployee = DB::table('karyawan')->where('nip', $nip)->first();
        $kode_dept = $currentEmployee->kode_dept;
        $employees = DB::table('karyawan')
            ->where('kode_dept', $kode_dept)
            ->where('nip', '!=', $nip)
            ->get();

        $cuti = DB::table('cuti')
            ->where('nip', $nip)
            ->where('status', 1)
            ->first();

        $periode = $cuti ? $cuti->tahun : '';
        $periode_awal = $cuti ? $cuti->periode_awal : '';
        $periode_akhir = $cuti ? $cuti->periode_akhir : '';
        $cutiGet = Cuti::where('nip', $nip)
            ->where('tahun', $periode)
            ->first();



        return view('izin.buatcuti', compact('periode', 'periode_awal', 'periode_akhir', 'employees', 'cutiGet'));
    }


    public function storecuti(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nip = Auth::guard('karyawan')->user()->nip;
        $nama_lengkap = Auth::guard('karyawan')->user()->nama_lengkap;
        $email_karyawan = Auth::guard('karyawan')->user()->email;
        $periode = $request->periode;
        $sisa_cuti = $request->sisa_cuti;
        $tgl_cuti = $request->tgl_cuti;
        $tgl_cuti_sampai = $request->tgl_cuti_sampai;
        $jml_hari = $request->jml_hari;
        $sisa_cuti_setelah = $request->sisa_cuti_setelah;
        $kar_ganti = $request->kar_ganti;
        $note = $request->note;
        $currentDate = Carbon::now();
        $jenis = "Cuti Tahunan";

        $data = [
            'nik' => $nik,
            'nip' => $nip,
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
                    ->where('nip', $nip)
                    ->where('tahun', $periode)
                    ->first();

                if ($cuti) {
                    $new_sisa_cuti = $cuti->sisa_cuti - $jml_hari;

                    DB::table('cuti')
                        ->where('nip', $nip)
                        ->where('tahun', $periode)
                        ->update(['sisa_cuti' => $new_sisa_cuti]);
                }

                // Fetch the atasan details
                $atasanJabatan = DB::table('jabatan')->where('id', Auth::guard('karyawan')->user()->jabatan)->first();

                if ($atasanJabatan && $atasanJabatan->jabatan_atasan) {
                    $atasanJabatanId = $atasanJabatan->jabatan_atasan;
                    $atasan = DB::table('karyawan')->where('jabatan', $atasanJabatanId)->first();

                    if ($atasan && $atasan->email) {

                        $emailContent = "
                            Pengajuan Cuti Karyawan<br><br>
                            Nama : {$nama_lengkap}<br>
                            Tanggal : " . DateHelper::formatIndonesianDate($currentDate->toDateString()) . "<br>
                            Pukul : {$currentDate->format('H:i')}<br>
                            NIK : {$nik}<br>
                            NIP : {$nip}<br>
                            Periode : {$periode}<br>
                            Sisa Cuti : {$sisa_cuti}<br>
                            Tanggal Cuti : " . DateHelper::formatIndonesianDate($tgl_cuti) . "<br>
                            Tanggal Cuti Sampai : " . DateHelper::formatIndonesianDate($tgl_cuti_sampai) . "<br>
                            Jumlah Hari : {$jml_hari}<br>
                            Sisa Cuti Setelah : {$sisa_cuti_setelah}<br>
                            Karyawan Ganti : {$kar_ganti}<br>
                            Note : {$note}<br>
                            Jenis : {$jenis}<br><br>

                            Mohon Cek Di hrms.ciptaharmoni.com/panel<br><br>

                            Terima Kasih
                        ";

                        // Send the email using Mail::html
                        Mail::html($emailContent, function ($message) use ($atasan, $nama_lengkap, $email_karyawan) {
                            $message->to($atasan->email)
                                ->subject("Pengajuan Cuti Baru Dari {$nama_lengkap}")
                                ->cc(['human.resources@ciptaharmoni.com', 'al.imron@hotmail.com', $email_karyawan])
                                ->priority(1);  // Set email priority to high

                            // Set additional headers for importance
                            $message->getHeaders()->addTextHeader('Importance', 'high');  // Mark as important
                            $message->getHeaders()->addTextHeader('X-Priority', '1');  // 1 is the highest priority
                        });
                    }
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
        $nip = Auth::guard('karyawan')->user()->nip;
        $periode = $request->periode;

        $cuti = Cuti::where('nip', $nip)
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
        $nip = auth()->user()->nip;
        $currentEmployee = DB::table('karyawan')->where('nip', $nip)->first();
        $cuti = DB::table('cuti')
            ->where('nip', $nip)
            ->where('status', 1)
            ->first();

        $periode = $cuti ? $cuti->tahun : '';

        return view('izin.buatcutikhusus', compact('tipecuti', 'periode'));
    }

    public function storecutikhusus(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nip = Auth::guard('karyawan')->user()->nip;
        $nama_lengkap = Auth::guard('karyawan')->user()->nama_lengkap;
        $email_karyawan = Auth::guard('karyawan')->user()->email;
        $tgl_cuti = $request->tgl_cuti;
        $tgl_cuti_sampai = $request->tgl_cuti_sampai;
        $jml_hari = $request->jml_hari;
        $id_tipe_cuti = $request->id_tipe_cuti;
        $note = $request->note;
        $periode = $request->periode;
        $currentDate = Carbon::now();
        $jenis = "Cuti Khusus";

        $data = [
            'nik' => $nik,
            'nip' => $nip,
            'periode' => $periode,
            'tgl_cuti' => $tgl_cuti,
            'tgl_cuti_sampai' => $tgl_cuti_sampai,
            'jml_hari' => $jml_hari,
            'note' => $note,
            'jenis' => $jenis,
            'tipe' => $id_tipe_cuti,
        ];

        DB::beginTransaction();

        try {
            // Save the leave application
            $simpan = PengajuanCuti::create($data);

            if ($simpan) {
                // Fetch the atasan details
                $atasanJabatan = DB::table('jabatan')->where('id', Auth::guard('karyawan')->user()->jabatan)->first();
                $tipe = DB::table('tipe_cuti')->where('id_tipe_cuti', $id_tipe_cuti)->first(); // Use first() instead of get()

                if ($atasanJabatan && $atasanJabatan->jabatan_atasan) {
                    $atasanJabatanId = $atasanJabatan->jabatan_atasan;
                    $atasan = DB::table('karyawan')->where('jabatan', $atasanJabatanId)->first();

                    if ($atasan && $atasan->email) {
                        $emailContent = "
                        Pengajuan Cuti Karyawan<br><br>
                        Nama : {$nama_lengkap}<br>
                        Tanggal : " . DateHelper::formatIndonesianDate($currentDate->toDateString()) . "<br>
                        Pukul : {$currentDate->format('H:i')}<br>
                        NIK : {$nik}<br>
                        NIP : {$nip}<br>
                        Tanggal Cuti : " . DateHelper::formatIndonesianDate($tgl_cuti) . "<br>
                        Tanggal Cuti Sampai : " . DateHelper::formatIndonesianDate($tgl_cuti_sampai) . "<br>
                        Jumlah Hari : {$jml_hari}<br>
                        Note : {$note}<br>
                        Tipe : {$tipe->tipe_cuti}<br> <!-- Access the correct object property -->
                        Jenis : {$jenis}<br><br>

                        Mohon Cek Di hrms.ciptaharmoni.com/panel<br><br>

                        Terima Kasih
                    ";

                        Mail::html($emailContent, function ($message) use ($atasan, $nama_lengkap, $email_karyawan) {
                            $message->to($atasan->email)
                                ->subject("Pengajuan Cuti Baru Dari {$nama_lengkap}")
                                ->cc(['human.resources@ciptaharmoni.com', 'al.imron@hotmail.com', $email_karyawan])
                                ->priority(1);

                            $message->getHeaders()->addTextHeader('Importance', 'high');
                            $message->getHeaders()->addTextHeader('X-Priority', '1');
                        });
                    }
                }

                DB::commit();

                return redirect('/presensi/izin')->with(['success' => 'Pengajuan Cuti Berhasil Di Simpan']);
            } else {
                DB::rollBack();
                return redirect('/presensi/izin')->with(['error' => 'Pengajuan Cuti Gagal Di Simpan']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in storecutikhusus: ' . $e->getMessage());
            return redirect('/presensi/izin')->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
