<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\PengajuanCuti;
use App\Models\Pengajuanizin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;
use mikehaertl\pdftk\Pdf;


class ApprovalController extends Controller
{
    public function izinapprovalhrd(Request $request)
    {
        $query = Pengajuanizin::query();
        $query->join('karyawan',  'pengajuan_izin.nik', '=', 'karyawan.nik');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        $query->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id');
        $query->select(
            'pengajuan_izin.*',
            'karyawan.nama_lengkap',
            'karyawan.jabatan',
            'department.nama_dept',
            'jabatan.nama_jabatan'
        )
            ->where('pengajuan_izin.status', '!=', 'Cuti')
            ->where(function ($q) {
                $q->where('pengajuan_izin.status_approved', 0)
                    ->orWhere('pengajuan_izin.status_approved_hrd', 0);
            });

        // Filter by date range
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_create', [$request->dari, $request->sampai]);
        }

        // Filter by NIK
        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        // Filter by employee name
        if (!empty($request->nama_lengkap)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Pagination
        $izinapproval = $query->paginate(10)->appends($request->query());
        $izinapproval->appends($request->all());

        return view('approval.approvalhr', compact('izinapproval'));
    }



    public function approveizinhrd(Request $request)
    {
        $id = $request->id_izin_form;
        $status_approved_hrd = $request->status_approved_hrd;
        $keputusan = $request->keputusan;
        $currentDate = Carbon::now();

        if ($keputusan === 'Potong Cuti') {
            $potongcuti = $request->potongcuti;
            $tgl_potong = Carbon::parse($request->tgl_potong);
            $tgl_potong_sampai = Carbon::parse($request->tgl_potong_sampai);
            $keputusan_potong = $request->keputusan_potong;

            $izin = DB::table('pengajuan_izin')->where('id', $id)->first();
            $tgl_izin = Carbon::parse($izin->tgl_izin);
            $tgl_izin_akhir = Carbon::parse($izin->tgl_izin_akhir);

            if ($keputusan_potong !== 'Potong Cuti') {
                // Adjust tgl_izin and tgl_izin_akhir based on tgl_potong and tgl_potong_sampai
                if (Carbon::parse($tgl_potong)->eq(Carbon::parse($tgl_izin))) {
                    // Cut at the start of the leave period
                    $new_tgl_izin = $tgl_potong_sampai->copy()->addDay();
                    DB::table('pengajuan_izin')
                        ->where('id', $id)
                        ->update([
                            'tgl_izin' => $new_tgl_izin,
                            'tgl_izin_akhir' => $tgl_izin_akhir,
                            'jml_hari' => $izin->jml_hari - $potongcuti,
                            'status_approved_hrd' => $status_approved_hrd,
                            'tgl_status_approved_hrd' => $currentDate,
                            'keputusan' => $keputusan_potong,
                            'tgl_jadwal_off' => null,
                        ]);
                } elseif (Carbon::parse($tgl_potong_sampai)->eq(Carbon::parse($tgl_izin_akhir))) {
                    // Cut at the end of the leave period
                    $new_tgl_izin_akhir = $tgl_potong->copy()->subDay();
                    DB::table('pengajuan_izin')
                        ->where('id', $id)
                        ->update([
                            'tgl_izin' => $tgl_izin,
                            'tgl_izin_akhir' => $new_tgl_izin_akhir,
                            'jml_hari' => $izin->jml_hari - $potongcuti,
                            'status_approved_hrd' => $status_approved_hrd,
                            'tgl_status_approved_hrd' => $currentDate,
                            'keputusan' => $keputusan_potong,
                            'tgl_jadwal_off' => null,
                        ]);
                } else {
                    // Handle cases where tgl_potong is within the leave period but not at the start or end
                    DB::table('pengajuan_izin')
                        ->where('id', $id)
                        ->update([
                            'status_approved_hrd' => $status_approved_hrd,
                            'tgl_status_approved_hrd' => $currentDate,
                            'keputusan' => $keputusan_potong,
                            'tgl_jadwal_off' => null,
                        ]);
                }

                // Insert the new record for the cut days
                DB::table('pengajuan_izin')->insert([
                    'nik' => $izin->nik,
                    'tgl_izin' => $tgl_potong,
                    'tgl_izin_akhir' => $tgl_potong_sampai,
                    'jml_hari' => $potongcuti,
                    'foto' => 'No Document',
                    'tgl_create' => $currentDate,
                    'status' => 'Tmk',
                    'keterangan' => $izin->keterangan,
                    'keputusan' => 'Potong Cuti',
                    'status_approved' => 0,
                    'status_approved_hrd' => 0,
                ]);

                return redirect('/approval/izinapprovalhrd')->with(['success' => 'Data Berhasil Di Update']);
            } else {
                // Update pengajuan_izin table
                $update = DB::table('pengajuan_izin')
                    ->where('id', $id)
                    ->update([
                        'status_approved_hrd' => $status_approved_hrd,
                        'tgl_status_approved_hrd' => $currentDate,
                        'keputusan' => $keputusan_potong,
                        'tgl_jadwal_off' => null, // Ensure tgl_jadwal_off is null for Potong Cuti case
                    ]);

                if ($update) {
                    // Find the employee's nik

                    if ($keputusan_potong === 'Potong Cuti') {
                        $jml_hari = DB::table('pengajuan_izin')->where('id', $id)->value('jml_hari');
                        $nik = DB::table('pengajuan_izin')->where('id', $id)->value('nik');

                        // Find active cuti with status 1 (active) for the employee
                        $cuti = DB::table('cuti')
                            ->where('nik', $nik)
                            ->where('status', 1)
                            ->first();

                        if ($cuti) {
                            // Update sisa_cuti
                            $sisa_cuti = $cuti->sisa_cuti - $jml_hari;

                            // Update cuti table
                            DB::table('cuti')
                                ->where('id', $cuti->id)
                                ->update(['sisa_cuti' => $sisa_cuti]);
                        } else {
                            // Handle case where no active cuti record is found
                            return redirect('/approval/izinapprovalhrd')->with(['error' => 'Data Gagal Di Update: Cuti record not found']);
                        }
                    }

                    return redirect('/approval/izinapprovalhrd')->with(['success' => 'Data Berhasil Di Update']);
                } else {
                    return redirect('/approval/izinapprovalhrd')->with(['error' => 'Data Gagal Di Update']);
                }
            }
        } elseif ($keputusan === 'Lain-lain') {
            $lainlain = $request->lainlain; // Retrieve text for "Lain-lain" keputusan

            // Update pengajuan_izin table
            $update = DB::table('pengajuan_izin')
                ->where('id', $id)
                ->update([
                    'status_approved_hrd' => $status_approved_hrd,
                    'tgl_status_approved_hrd' => $currentDate,
                    'keputusan' => $lainlain, // Use the text from lainlain input for keputusan
                    'tgl_jadwal_off' => null, // Ensure tgl_jadwal_off is null for "Lain-lain" case
                ]);

            if ($update) {
                return redirect('/approval/izinapprovalhrd')->with(['success' => 'Data Berhasil Di Update']);
            } else {
                return redirect('/approval/izinapprovalhrd')->with(['error' => 'Data Gagal Di Update']);
            }
        } else {
            // For other cases, update pengajuan_izin without affecting cuti table
            // Check if tgl_jadwal_off should be null
            if ($keputusan !== 'Tukar Jadwal Off') {
                $tgl_jadwal_off = null; // Set tgl_jadwal_off to null for non-Tukar Jadwal Off cases
            } else {
                $tgl_jadwal_off = $request->tgl_jadwal_off;
            }

            // Update pengajuan_izin table
            $update = DB::table('pengajuan_izin')
                ->where('id', $id)
                ->update([
                    'status_approved_hrd' => $status_approved_hrd,
                    'tgl_status_approved_hrd' => $currentDate,
                    'keputusan' => $keputusan,
                    'tgl_jadwal_off' => $tgl_jadwal_off,
                ]);

            if ($update) {
                return redirect('/approval/izinapprovalhrd')->with(['success' => 'Data Berhasil Di Update']);
            } else {
                return redirect('/approval/izinapprovalhrd')->with(['error' => 'Data Gagal Di Update']);
            }
        }
    }

    public function batalapprovehrd($id)
    {
        // Retrieve the jml_potong value from the pengajuan_izin record
        $keputusan = DB::table('pengajuan_izin')->where('id', $id)->value('keputusan');
        $jml_hari = DB::table('pengajuan_izin')->where('id', $id)->value('jml_hari');

        // Find the employee's nik
        $nik = DB::table('pengajuan_izin')->where('id', $id)->value('nik');

        // Find active cuti with status 1 (active) for the employee
        $cuti = DB::table('cuti')
            ->where('nik', $nik)
            ->where('status', 1)
            ->first();

        // Update pengajuan_izin table
        $update = DB::table('pengajuan_izin')
            ->where('id', $id)
            ->update([
                'status_approved_hrd' => 0,
                'tgl_status_approved_hrd' => null,
                'tgl_jadwal_off' => null,
                'keputusan' => null,
            ]);

        if ($update) {
            if ($keputusan === 'Potong Cuti') {
                // Update sisa_cuti by adding back the jml_potong value
                $sisa_cuti = $cuti->sisa_cuti + $jml_hari;

                // Update cuti table
                DB::table('cuti')
                    ->where('id', $cuti->id)
                    ->update(['sisa_cuti' => $sisa_cuti]);
            }

            return response()->json(['success' => true, 'message' => 'Approval has been cancelled.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Data Gagal Di Update']);
        }
    }


    public function cutiapprovalhrd(Request $request)
    {
        $query = PengajuanCuti::query();
        $query->join('karyawan', 'pengajuan_cuti.nik', '=', 'karyawan.nik');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        $query->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id');
        $query->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti');
        $query->select(
            'pengajuan_cuti.*',
            'karyawan.nama_lengkap',
            'karyawan.jabatan',
            'department.nama_dept',
            'karyawan.tgl_masuk',
            'tipe_cuti.tipe_cuti',
            'jabatan.nama_jabatan'
        );

        // Apply date filter
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_Cuti', [$request->dari, $request->sampai]);
        }

        // Filter by NIK
        if (!empty($request->nik)) {
            $query->where('pengajuan_cuti.nik', $request->nik);
        }

        // Filter by employee name
        if (!empty($request->nama_lengkap)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Exclude rows where none of the statuses are 0
        $query->where(function ($q) {
            $q->where('status_approved', 0)
                ->orWhere('status_approved_hrd', 0)
                ->orWhere('status_management', 0);
        });

        // Get paginated results
        $cutiapproval = $query->paginate(10)->appends($request->all());

        // Add `sisa_cuti_real` for each record
        foreach ($cutiapproval as $d) {
            $cutiRecord = DB::table('cuti')
                ->where('nik', $d->nik)
                ->where('tahun', $d->periode)
                ->first();

            $d->sisa_cuti_real = $cutiRecord ? $cutiRecord->sisa_cuti : 0;
        }

        return view('approval.cutiapprovalhr', compact('cutiapproval'));
    }



    public function approvecutihrd(Request $request)
    {
        $id = $request->id_cuti_form;
        $nik = $request->nik_cuti_form;
        $periode = $request->periode_cuti_form;
        $keputusan = $request->keputusan;
        $status_approved_hrd = $request->status_approved_hrd;
        $currentDate = Carbon::now();

        $update = DB::table('pengajuan_cuti')
            ->where('id', $id)
            ->update([
                'status_approved_hrd' => $status_approved_hrd,
                'tgl_status_approved_hrd' => $currentDate
            ]);

        if ($update) {

            $leaveApplication = DB::table('pengajuan_cuti')->where('id', $id)->first();

            if ($leaveApplication->status_approved_hrd == 2) {
                // Get the cuti record for this user and period
                $cutiRecord = DB::table('cuti')
                    ->where('nik', $nik)
                    ->where('tahun', $periode)
                    ->first();

                DB::table('pengajuan_cuti')
                    ->where('id', $id)
                    ->update([
                        'keputusan' => $keputusan
                    ]);
                // Calculate the new sisa_cuti
                $newSisaCuti = $cutiRecord->sisa_cuti + $leaveApplication->jml_hari;
                $jmlTunda =

                // Update the cuti record
                DB::table('cuti')
                    ->where('nik', $nik)
                    ->where('tahun', $periode)
                    ->update(['sisa_cuti' => $newSisaCuti, 'tunda' => $leaveApplication->jml_hari]);
            }
            return redirect('/approval/cutiapprovalhrd')->with(['success' => 'Pengajuan Cuti Berhasil Di Update']);
        } else {
            return redirect('/approval/cutiapprovalhrd')->with(['error' => 'Pengajuan Cuti Gagal Di Update']);
        }
    }

    public function batalapprovecutihrd($id)
    {
        // Fetch leave application details
        $leaveApplication = DB::table('pengajuan_cuti')->where('id', $id)->first();
        $currentDate = Carbon::now();

        if (!$leaveApplication) {
            return response()->json(['success' => false, 'message' => 'Pengajuan Cuti tidak ditemukan']);
        }

        if ($leaveApplication->status_approved_hrd == 1) {

            // Update status_approved to Pending and tgl_status_approved to null
            DB::table('pengajuan_cuti')
                ->where('id', $id)
                ->update([
                    'status_approved_hrd' => 0,
                    'tgl_status_approved_hrd' => null,
                ]);
            return response()->json(['success' => true, 'message' => 'Pengajuan Cuti berhasil dibatalkan']);
        } else if ($leaveApplication->status_approved_hrd == 2) {
            // Fetch current sisa_cuti
            DB::table('pengajuan_cuti')
                ->where('id', $id)
                ->update([
                    'status_approved_hrd' => 2,
                    'tgl_status_approved_hrd' => $currentDate,
                    'status_management' => 2,
                    'tgl_status_management' => $currentDate,
                ]);

            $cutiRecord = DB::table('cuti')
                ->where('nik', $leaveApplication->nik)
                ->where('tahun', $leaveApplication->periode)
                ->first();

            if ($cutiRecord) {
                // Calculate new sisa_cuti by adding back jml_hari
                $newSisaCuti = $cutiRecord->sisa_cuti - $leaveApplication->jml_hari;

                // Update sisa_cuti in cuti table
                DB::table('cuti')
                    ->where('nik', $leaveApplication->nik)
                    ->where('tahun', $leaveApplication->periode)
                    ->update(['sisa_cuti' => $newSisaCuti]);
            }
            return response()->json(['success' => true, 'message' => 'Pengajuan Cuti berhasil dibatalkan']);
        } else {
            return response()->json(['success' => false, 'message' => 'Pengajuan Cuti gagal dibatalkan']);
        }
    }




    // MANAGER / ATASAN CODE


    public function izinapproval(Request $request)
    {
        $nik = Auth::guard('user')->user()->nik;

        // Get the current user's jabatan id
        $currentUser = Karyawan::where('nik', $nik)->first();
        $currentUserJabatanId = $currentUser->jabatan;
        $currentUserKodeDept = $currentUser->kode_dept;

        // Begin query on pengajuan_izin table
        $query = Pengajuanizin::query();
        $query->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->select('pengajuan_izin.*', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'department.nama_dept', 'jabatan.nama_jabatan');

        // Check if the user belongs to the Management department
        if ($currentUserKodeDept === 'Management') {
            // Management can only see requests where status_approved = 1 and status_approved_hrd = 1

            $query->where(function ($q) {
                $q->where('status_approved', 0)
                    ->orWhere('status_approved_hrd', 0);
            });
        } else {
            // Otherwise, check if the user is an Atasan
            $jabatanAtasan = Jabatan::where('id', $currentUserJabatanId)->value('jabatan_atasan');

            // If the user is an Atasan, only show requests where status_approved is 0 (pending)
            if ($jabatanAtasan) {
                $employeeNiks = Karyawan::join('jabatan as j1', 'karyawan.jabatan', '=', 'j1.id')
                    ->join('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id')
                    ->where('j2.id', $currentUserJabatanId)
                    ->pluck('karyawan.nik');

                // Apply filter by NIKs (subordinates only) and only show requests where status_approved is 0 (pending)
                $query->whereIn('pengajuan_izin.nik', $employeeNiks)
                    ->where(function ($q) {
                        $q->where('status_approved', 0)
                            ->orWhere('status_approved_hrd', 0);
                    });
            }
        }

        // Paginate the results
        $izinapproval = $query->paginate(10);
        $izinapproval->appends($request->all());

        // Return the view with the filtered results
        return view('approval.izinapproval', compact('izinapproval'));
    }

    // In YourController.php
    public function printIzin(Request $request)
    {
        $id = $request->input('id');

        // Fetch the data for the selected `pengajuan izin`
        $izin = Pengajuanizin::find($id);

        if (!$izin) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Fetch the employee data
        $karyawan = Karyawan::where('nip', $izin->nip)->first();

        if (!$karyawan) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $jabatan = DB::table('jabatan')->where('id', $karyawan->jabatan)->first();

        // Finding nama_hr (either jabatan 47 or 25)
        $hrKaryawan = Karyawan::where('jabatan', 47)->first()
            ?? Karyawan::where('jabatan', 25)->first();
        $nama_hr = $hrKaryawan ? $hrKaryawan->nama_lengkap : '';

        // Finding nama_atasan based on jabatan_atasan of current jabatan
        $jabatanAtasan = DB::table('jabatan')->where('id', $jabatan->jabatan_atasan)->first();
        $atasanKaryawan = Karyawan::where('jabatan', $jabatanAtasan->id)->first();
        $nama_atasan = $atasanKaryawan ? $atasanKaryawan->nama_lengkap : '';

        $tglForm = ($izin->tgl_izin == $izin->tgl_izin_akhir || empty($izin->tgl_izin_akhir))
            ? DateHelper::formatIndonesianDate($izin->tgl_izin)
            : DateHelper::formatIndonesianDate($izin->tgl_izin) . ' - ' . DateHelper::formatIndonesianDate($izin->tgl_izin_akhir);


        // Prepare data to return
        $data = [
            'nama_lengkap' => $karyawan->nama_lengkap,
            'bagian' => $karyawan->kode_dept,
            'tanggal' => $tglForm,
            'status' => $izin->status,
            'keterangan' => $izin->keterangan,
            'keputusan' => $izin->keputusan,
            'tgl_approved' => $izin->tgl_status_approved,
            'tgl_approved_hr' => $izin->tgl_status_approved_hrd,
            'nama_atasan' => $nama_atasan,
            'nama_hr' => $nama_hr,
            // Add other fields as necessary
        ];

        return response()->json($data);
    }

    public function printCuti(Request $request)
    {
        $id = $request->input('id');

        // Fetch the data for the selected `pengajuan izin`
        $cuti = PengajuanCuti::find($id);

        if (!$cuti) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Fetch the employee data
        $karyawan = Karyawan::where('nip', $cuti->nip)->first();

        if (!$karyawan) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        // Fetch the employee's current jabatan (position)
        $jabatan = DB::table('jabatan')->where('id', $karyawan->jabatan)->first();

        // Finding nama_hr (either jabatan 47 or 25)
        $hrKaryawan = Karyawan::where('jabatan', 47)->first()
            ?? Karyawan::where('jabatan', 25)->first();
        $nama_hr = $hrKaryawan ? $hrKaryawan->nama_lengkap : '';

        // Finding nama_atasan based on jabatan_atasan of current jabatan
        $jabatanAtasan = DB::table('jabatan')->where('id', $jabatan->jabatan_atasan)->first();
        $atasanKaryawan = Karyawan::where('jabatan', $jabatanAtasan->id)->first();
        $nama_atasan = $atasanKaryawan ? $atasanKaryawan->nama_lengkap : '';

        // Format the dates
        $tglForm = ($cuti->tgl_cuti == $cuti->tgl_cuti_akhir || empty($cuti->tgl_cuti_sampai))
            ? DateHelper::formatIndonesianDate($cuti->tgl_cuti)
            : DateHelper::formatIndonesianDate($cuti->tgl_cuti) . ' - ' . DateHelper::formatIndonesianDate($cuti->tgl_cuti_sampai);

        $tglMulai = DateHelper::formatIndonesianDate($karyawan->tgl_masuk);

        // Prepare data to return
        $data = [
            'nama_lengkap' => $karyawan->nama_lengkap ?? '',
            'jabatan' => $jabatan->nama_jabatan ?? '',
            'mulai' => $tglMulai ?? '',
            'periode' => $cuti->periode ?? '',
            'tanggal' => $tglForm ?? '',
            'sisa_cuti' => $cuti->sisa_cuti !== null ? (string) $cuti->sisa_cuti : '',
            'jml_hari' => $cuti->jml_hari !== null ? (string) $cuti->jml_hari : '',
            'sisa_setelah' => $cuti->sisa_cuti_setelah !== null ? (string) $cuti->sisa_cuti_setelah : '',
            'kar_ganti' => $cuti->kar_ganti ?? '',
            'note' => $cuti->note ?? '',
            'nama_hr' => $nama_hr,
            'nama_atasan' => $nama_atasan,
        ];

        return response()->json($data);
    }


    public function approveizin(Request $request)
    {
        $id = $request->id_izin_form;
        $status_approved = $request->status_approved;
        $keputusan = $request->keputusan;
        $currentDate = Carbon::now();

        $update = DB::table('pengajuan_izin')
            ->where('id', $id)
            ->update([
                'status_approved' => $status_approved,
                'tgl_status_approved' => $currentDate
            ]);

        if ($update) {
            $leaveApplication = DB::table('pengajuan_izin')->where('id', $id)->first();

            if ($leaveApplication->status_approved == 2) {

                DB::table('pengajuan_izin')
                    ->where('id', $id)
                    ->update([
                        'status_approved_hrd' => $status_approved,
                        'tgl_status_approved_hrd' => $currentDate,
                        'keputusan' => $keputusan
                    ]);
            }
            return redirect('/approval/izinapproval')->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return redirect('/approval/izinapproval')->with(['error' => 'Data Gagal Di Update']);
        }
    }

    public function batalapprove($id)
    {

        $leaveApplication = DB::table('pengajuan_izin')->where('id', $id)->first();

        if (!$leaveApplication) {
            return response()->json(['success' => false, 'message' => 'Pengajuan Izin tidak ditemukan']);
        }
        if ($leaveApplication->status_approved == 1) {

            // Update status_approved to Pending and tgl_status_approved to null
            DB::table('pengajuan_izin')
                ->where('id', $id)
                ->update([
                    'status_approved' => 0,
                    'tgl_status_approved' => null,
                ]);
            return response()->json(['success' => true, 'message' => 'Pengajuan Izin berhasil dibatalkan']);
        } else if ($leaveApplication->status_approved == 2) {
            // Fetch current sisa_cuti
            DB::table('pengajuan_izin')
                ->where('id', $id)
                ->update([
                    'status_approved' => 0,
                    'tgl_status_approved' => null,
                    'status_approved_hrd' => 0,
                    'tgl_status_approved_hrd' => null,
                ]);

            return response()->json(['success' => true, 'message' => 'Pengajuan Izin berhasil dibatalkan']);
        } else {
            return response()->json(['success' => false, 'message' => 'Pengajuan Izin gagal dibatalkan']);
        }
    }

    public function cutiapproval(Request $request)
    {
        $nik = Auth::guard('user')->user()->nik;

        // Get the current user's details
        $currentUser = Karyawan::where('nik', $nik)->first();
        $currentUserJabatanId = $currentUser->jabatan;
        $currentUserKodeDept = $currentUser->kode_dept;

        // Begin query on pengajuan_cuti table
        $query = PengajuanCuti::query();
        $query->join('karyawan', 'pengajuan_cuti.nik', '=', 'karyawan.nik')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti')
            ->select('pengajuan_cuti.*', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'department.nama_dept', 'karyawan.tgl_masuk', 'tipe_cuti.tipe_cuti', 'jabatan.nama_jabatan');

        // Check if the user belongs to the Management department
        if ($currentUserKodeDept === 'Management') {
            // Management can only see requests where status_approved = 1 and status_approved_hrd = 1

            $query->where(function ($q) {
                $q->where('status_approved', 0)
                    ->orWhere('status_approved_hrd', 0)
                    ->orWhere('status_management', 0);
            });
        } else {
            // Otherwise, check if the user is an Atasan
            $jabatanAtasan = Jabatan::where('id', $currentUserJabatanId)->value('jabatan_atasan');

            // If the user is an Atasan, only show requests where status_approved is 0 (pending)
            if ($jabatanAtasan) {
                $employeeNiks = Karyawan::join('jabatan as j1', 'karyawan.jabatan', '=', 'j1.id')
                    ->join('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id')
                    ->where('j2.id', $currentUserJabatanId)
                    ->pluck('karyawan.nik');

                // Apply filter by NIKs (subordinates only) and only show requests where status_approved is 0 (pending)
                $query->whereIn('pengajuan_cuti.nik', $employeeNiks)
                    ->where(function ($q) {
                        $q->where('status_approved', 0)
                            ->orWhere('status_approved_hrd', 0)
                            ->orWhere('status_management', 0);
                    });
            }
        }

        // Paginate the results
        $cutiapproval = $query->paginate(10);
        $cutiapproval->appends($request->all());

        foreach ($cutiapproval as $d) {
            // Fetch sisa_cuti from cuti table
            $cutiRecord = DB::table('cuti')
                ->where('nik', $d->nik)
                ->where('tahun', $d->periode)
                ->first();

            // Calculate sisa_cuti_real
            if ($cutiRecord) {
                $d->sisa_cuti_real = $cutiRecord->sisa_cuti;
            } else {
                $d->sisa_cuti_real = 0; // or any default value
            }
        }

        // Return the view with the filtered results
        return view('approval.cutiapproval', compact('cutiapproval'));
    }


    public function approvecuti(Request $request)
    {
        // Get the current user's NIK
        $currentUserNik = Auth::guard('user')->user()->nik;

        // Fetch the current user's data to determine their jabatan and department
        $currentUser = Karyawan::where('nik', $currentUserNik)->first();
        $currentUserJabatanId = $currentUser->jabatan;

        // Get the request inputs
        $id = $request->id_cuti_form;
        $status_approved = $request->status_approved;
        $currentDate = Carbon::now();

        // Fetch the leave application from pengajuan_cuti table
        $leaveApplication = DB::table('pengajuan_cuti')->where('id', $id)->first();
        if (!$leaveApplication) {
            return redirect('/approval/cutiapproval')->with(['error' => 'Pengajuan Cuti tidak ditemukan']);
        }

        // Find the NIK of the karyawan for the leave application
        $karyawanNik = $leaveApplication->nik;

        // Check if the current user is an atasan for the karyawan
        $isAtasan = Karyawan::join('jabatan as j1', 'karyawan.jabatan', '=', 'j1.id')
            ->join('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id')
            ->where('karyawan.nik', $karyawanNik)
            ->where('j2.id', $currentUserJabatanId)
            ->exists();

        // Check if the current user belongs to the "Management" department
        $isManagement = $currentUser->kode_dept == 'Management';

        // Prepare the fields to update based on the user's role
        $updateFields = [];
        if ($isAtasan && $isManagement) {
            // If the user is both an atasan and in Management
            $updateFields = [
                'status_approved' => $status_approved,
                'tgl_status_approved' => $currentDate,
                'status_management' => $status_approved,
                'tgl_status_management' => $currentDate,
            ];
        } elseif ($isAtasan) {
            // If the user is only an atasan
            $updateFields = [
                'status_approved' => $status_approved,
                'tgl_status_approved' => $currentDate,
            ];
        } elseif ($isManagement) {
            // If the user is only in Management
            $updateFields = [
                'status_management' => $status_approved,
                'tgl_status_management' => $currentDate,
            ];
        }

        // Perform the update if there are fields to update
        if (!empty($updateFields)) {
            $update = DB::table('pengajuan_cuti')
                ->where('id', $id)
                ->update($updateFields);

            if ($update) {
                // Handle leave status logic
                $cutiRecord = DB::table('cuti')
                    ->where('nik', $karyawanNik)
                    ->where('tahun', $leaveApplication->periode)
                    ->first();

                if ($cutiRecord) {
                    $currentTunda = $cutiRecord->tunda;
                    $currentSisa = $cutiRecord->sisa_cuti;

                    if ($status_approved == 2) { // Declined
                        // Return declined days to `sisa_cuti` and update `tunda`
                        $newTunda = max($currentTunda, $leaveApplication->jml_hari);
                        $newSisa = $currentSisa + $leaveApplication->jml_hari;

                        DB::table('cuti')
                            ->where('nik', $karyawanNik)
                            ->where('tahun', $leaveApplication->periode)
                            ->update([
                                'tunda' => $newTunda,
                                'sisa_cuti' => $newSisa,
                            ]);
                    } elseif ($status_approved == 1) { // Approved
                        // Deduct approved days from `tunda` and `sisa_cuti`
                        $newTunda = max(0, $currentTunda - $leaveApplication->jml_hari);
                        $newSisa = $currentSisa - $leaveApplication->jml_hari;

                        DB::table('cuti')
                            ->where('nik', $karyawanNik)
                            ->where('tahun', $leaveApplication->periode)
                            ->update([
                                'tunda' => $newTunda,
                                'sisa_cuti' => $newSisa,
                            ]);
                    }

                    // Update HRD approval fields
                    DB::table('pengajuan_cuti')
                        ->where('id', $id)
                        ->update([
                            'status_approved_hrd' => $status_approved,
                            'tgl_status_approved_hrd' => $currentDate,
                        ]);
                }

                return redirect('/approval/cutiapproval')->with(['success' => 'Pengajuan Cuti Berhasil Di Update']);
            } else {
                return redirect('/approval/cutiapproval')->with(['error' => 'Pengajuan Cuti Gagal Di Update']);
            }
        } else {
            return redirect('/approval/cutiapproval')->with(['error' => 'Anda tidak memiliki wewenang untuk menyetujui pengajuan ini']);
        }
    }


    public function batalapprovecuti($id)
    {
        $leaveApplication = DB::table('pengajuan_cuti')->where('id', $id)->first();

        if (!$leaveApplication) {
            return response()->json(['success' => false, 'message' => 'Pengajuan Cuti tidak ditemukan']);
        }

        $currentUserNik = Auth::guard('user')->user()->nik;
        $currentDate = now();

        // Fetch current user data
        $currentUser = Karyawan::where('nik', $currentUserNik)->first();
        $currentUserJabatanId = $currentUser->jabatan;
        $isManagement = $currentUser->kode_dept === 'Management';

        // Check if the current user is an atasan for the karyawan
        $isAtasan = Karyawan::join('jabatan as j1', 'karyawan.jabatan', '=', 'j1.id')
            ->join('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id')
            ->where('karyawan.nik', $leaveApplication->nik)
            ->where('j2.id', $currentUserJabatanId)
            ->exists();

        if ($isAtasan && $isManagement) {
            if ($leaveApplication->status_approved == 1 && $leaveApplication->status_management == 1) {
                DB::table('pengajuan_cuti')
                    ->where('id', $id)
                    ->update([
                        'status_approved' => 0,
                        'tgl_status_approved' => null,
                        'status_management' => 0,
                        'tgl_status_management' => null,
                    ]);
                return response()->json(['success' => true, 'message' => 'Pengajuan Cuti berhasil dibatalkan']);
            } elseif ($leaveApplication->status_approved == 2 && $leaveApplication->status_management == 2) {
                DB::table('pengajuan_cuti')
                    ->where('id', $id)
                    ->update([
                        'status_approved' => 2,
                        'tgl_status_approved' => $currentDate,
                        'status_approved_hrd' => 2,
                        'tgl_status_approved_hrd' => $currentDate,
                        'status_management' => 2,
                        'tgl_status_management' => $currentDate,
                    ]);

                $cutiRecord = DB::table('cuti')
                    ->where('nik', $leaveApplication->nik)
                    ->where('tahun', $leaveApplication->periode)
                    ->first();

                if ($cutiRecord) {
                    // Calculate new sisa_cuti by adding back jml_hari
                    $newSisaCuti = $cutiRecord->sisa_cuti + $leaveApplication->jml_hari;

                    // Update sisa_cuti in cuti table
                    DB::table('cuti')
                        ->where('nik', $leaveApplication->nik)
                        ->where('tahun', $leaveApplication->periode)
                        ->update(['sisa_cuti' => $newSisaCuti]);
                }
                return response()->json(['success' => true, 'message' => 'Pengajuan Cuti berhasil dibatalkan']);
            } else {
                return response()->json(['success' => false, 'message' => 'Pengajuan Cuti gagal dibatalkan']);
            }
            // If the user is both an atasan and in Management
        } elseif ($isAtasan) {
            if ($leaveApplication->status_approved == 1) {
                DB::table('pengajuan_cuti')
                    ->where('id', $id)
                    ->update([
                        'status_approved' => 0,
                        'tgl_status_approved' => null,
                    ]);
                return response()->json(['success' => true, 'message' => 'Pengajuan Cuti berhasil dibatalkan']);
            } elseif ($leaveApplication->status_approved == 2) {
                DB::table('pengajuan_cuti')
                    ->where('id', $id)
                    ->update([
                        'status_approved' => 2,
                        'tgl_status_approved' => $currentDate,
                        'status_approved_hrd' => 2,
                        'tgl_status_approved_hrd' => $currentDate,
                        'status_management' => 2,
                        'tgl_status_management' => $currentDate,
                    ]);

                $cutiRecord = DB::table('cuti')
                    ->where('nik', $leaveApplication->nik)
                    ->where('tahun', $leaveApplication->periode)
                    ->first();

                if ($cutiRecord) {
                    // Calculate new sisa_cuti by adding back jml_hari
                    $newSisaCuti = $cutiRecord->sisa_cuti + $leaveApplication->jml_hari;

                    // Update sisa_cuti in cuti table
                    DB::table('cuti')
                        ->where('nik', $leaveApplication->nik)
                        ->where('tahun', $leaveApplication->periode)
                        ->update(['sisa_cuti' => $newSisaCuti]);
                }
                return response()->json(['success' => true, 'message' => 'Pengajuan Cuti berhasil dibatalkan']);
            } else {
                return response()->json(['success' => false, 'message' => 'Pengajuan Cuti gagal dibatalkan']);
            }
        } elseif ($isManagement) {
            if ($leaveApplication->status_management == 1) {
                DB::table('pengajuan_cuti')
                    ->where('id', $id)
                    ->update([
                        'status_management' => 0,
                        'tgl_status_management' => null,
                    ]);
                return response()->json(['success' => true, 'message' => 'Pengajuan Cuti berhasil dibatalkan']);
            } elseif ($leaveApplication->status_management == 2) {
                DB::table('pengajuan_cuti')
                    ->where('id', $id)
                    ->update([
                        'status_management' => 2,
                        'tgl_status_management' => $currentDate,
                    ]);

                $cutiRecord = DB::table('cuti')
                    ->where('nik', $leaveApplication->nik)
                    ->where('tahun', $leaveApplication->periode)
                    ->first();

                if ($cutiRecord) {
                    // Calculate new sisa_cuti by adding back jml_hari
                    $newSisaCuti = $cutiRecord->sisa_cuti + $leaveApplication->jml_hari;

                    // Update sisa_cuti in cuti table
                    DB::table('cuti')
                        ->where('nik', $leaveApplication->nik)
                        ->where('tahun', $leaveApplication->periode)
                        ->update(['sisa_cuti' => $newSisaCuti]);
                }
                return response()->json(['success' => true, 'message' => 'Pengajuan Cuti berhasil dibatalkan']);
            } else {
                return response()->json(['success' => false, 'message' => 'Pengajuan Cuti gagal dibatalkan']);
            }
        }
    }
}
