<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\PengajuanCuti;
use App\Models\Pengajuanizin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    public function izinapprovalhrd(Request $request)
    {
        $query = Pengajuanizin::query();
        $query->join('karyawan',  'pengajuan_izin.nik', '=', 'karyawan.nik');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        $query->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id');
        $query->select('pengajuan_izin.*', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'department.nama_dept', 'jabatan.nama_jabatan')
            ->where('pengajuan_izin.status', '!=', 'Cuti');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_create', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        if (!empty($request->nama_lengkap)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Handle the status_approved filter
        if ($request->has('status_approved')) {
            if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2') {
                $query->where('status_approved', $request->status_approved);
            }
        } else {
            // Default to '1' (Approved) if no status_approved is provided
            $query->where('status_approved', 1);
        }

        // Handle the status_approved_hrd filter
        if ($request->has('status_approved_hrd')) {
            if ($request->status_approved_hrd === '0' || $request->status_approved_hrd === '1' || $request->status_approved_hrd === '2') {
                $query->where('status_approved_hrd', $request->status_approved_hrd);
            }
        } else {
            // Default to '0' (Pending) if no status_approved_hrd is provided
            $query->where('status_approved_hrd', 0);
        }

        $izinapproval = $query->paginate(10);
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
        }   else {
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

                    if ($keputusan_potong === 'Potong Cuti'){
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
        $query->select('pengajuan_cuti.*', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'department.nama_dept', 'karyawan.tgl_masuk', 'tipe_cuti.tipe_cuti', 'jabatan.nama_jabatan');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_Cuti', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nik)) {
            $query->where('pengajuan_cuti.nik', $request->nik);
        }

        if (!empty($request->nama_lengkap)) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Handle the status_approved filter
        if ($request->has('status_approved')) {
            if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2') {
                $query->where('status_approved', $request->status_approved);
            }
        } else {
            // Default to '1' (Approved) if no status_approved is provided
            $query->where('status_approved', 1);
        }

        // Handle the status_approved_hrd filter
        if ($request->has('status_approved_hrd')) {
            if ($request->status_approved_hrd === '0' || $request->status_approved_hrd === '1' || $request->status_approved_hrd === '2') {
                $query->where('status_approved_hrd', $request->status_approved_hrd);
            }
        } else {
            // Default to '0' (Pending) if no status_approved_hrd is provided
            $query->where('status_approved_hrd', 0);
        }

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

                // Update the cuti record
                DB::table('cuti')
                    ->where('nik', $nik)
                    ->where('tahun', $periode)
                    ->update(['sisa_cuti' => $newSisaCuti]);
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
                    'status_approved_hrd' => 0,
                    'tgl_status_approved_hrd' => null,
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

        // Get the list of employee NIKs whose jabatan_atasan matches the current user's jabatan
        $employeeNiks = Karyawan::join('jabatan as j1', 'karyawan.jabatan', '=', 'j1.id')
            ->join('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id')
            ->where('j2.id', $currentUserJabatanId)
            ->pluck('karyawan.nik');

        // Begin query on pengajuan_izin table
        $query = Pengajuanizin::query();
        $query->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->select('pengajuan_izin.*', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'department.nama_dept', 'jabatan.nama_jabatan')
            ->whereIn('pengajuan_izin.nik', $employeeNiks) // Filter to only include employees supervised by the current user
            ->where('pengajuan_izin.status', '!=', 'Cuti');

        // Apply date filter if provided
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin', [$request->dari, $request->sampai]);
        }

        // Apply NIK filter if provided
        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        // Apply name filter if provided
        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Apply approval status filter if provided
        if ($request->has('status_approved')) {
            if (in_array($request->status_approved, ['0', '1', '2'])) {
                $query->where('status_approved', $request->status_approved);
            }
        } else {
            // Default to '0' (Pending) if no status_approved is provided
            $query->where('status_approved', 0);
        }

        // Paginate the results
        $izinapproval = $query->paginate(10);
        $izinapproval->appends($request->all());

        // Return the view with the filtered results
        return view('approval.izinapproval', compact('izinapproval'));
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

        // Get the current user's jabatan id
        $currentUser = Karyawan::where('nik', $nik)->first();
        $currentUserJabatanId = $currentUser->jabatan;

        // Get the list of employee NIKs whose jabatan_atasan matches the current user's jabatan
        $employeeNiks = Karyawan::join('jabatan as j1', 'karyawan.jabatan', '=', 'j1.id')
            ->join('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id')
            ->where('j2.id', $currentUserJabatanId)
            ->pluck('karyawan.nik');

        // Begin query on pengajuan_cuti table
        $query = PengajuanCuti::query();
        $query->join('karyawan', 'pengajuan_cuti.nik', '=', 'karyawan.nik')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti')
            ->select('pengajuan_cuti.*', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'department.nama_dept', 'karyawan.tgl_masuk', 'tipe_cuti.tipe_cuti', 'jabatan.nama_jabatan')
            ->whereIn('pengajuan_cuti.nik', $employeeNiks); // Filter to only include employees supervised by the current user

        // Apply date filter if provided
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_cuti', [$request->dari, $request->sampai]);
        }

        // Apply NIK filter if provided
        if (!empty($request->nik)) {
            $query->where('pengajuan_cuti.nik', $request->nik);
        }

        // Apply name filter if provided
        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Apply approval status filter if provided
        if ($request->has('status_approved')) {
            if (in_array($request->status_approved, ['0', '1', '2'])) {
                $query->where('status_approved', $request->status_approved);
            }
        } else {
            // Default to '0' (Pending) if no status_approved is provided
            $query->where('status_approved', 0);
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
        $id = $request->id_cuti_form;
        $nik = $request->nik_cuti_form;
        $periode = $request->periode_cuti_form;
        $status_approved = $request->status_approved;
        $currentDate = Carbon::now();

        $update = DB::table('pengajuan_cuti')
            ->where('id', $id)
            ->update([
                'status_approved' => $status_approved,
                'tgl_status_approved' => $currentDate
            ]);

        if ($update) {

            $leaveApplication = DB::table('pengajuan_cuti')->where('id', $id)->first();

            if ($leaveApplication->status_approved == 2) {
                // Get the cuti record for this user and period
                $cutiRecord = DB::table('cuti')
                    ->where('nik', $nik)
                    ->where('tahun', $periode)
                    ->first();

                // Calculate the new sisa_cuti
                $newSisaCuti = $cutiRecord->sisa_cuti + $leaveApplication->jml_hari;

                // Update the cuti record
                DB::table('cuti')
                    ->where('nik', $nik)
                    ->where('tahun', $periode)
                    ->update(['sisa_cuti' => $newSisaCuti]);

                DB::table('pengajuan_cuti')
                    ->where('id', $id)
                    ->update([
                        'status_approved_hrd' => $status_approved,
                        'tgl_status_approved_hrd' => $currentDate
                    ]);
            }
            return redirect('/approval/cutiapproval')->with(['success' => 'Pengajuan Cuti Berhasil Di Update']);
        } else {
            return redirect('/approval/cutiapproval')->with(['error' => 'Pengajuan Cuti Gagal Di Update']);
        }
    }
    public function batalapprovecuti($id)
    {
        $leaveApplication = DB::table('pengajuan_cuti')->where('id', $id)->first();

        if (!$leaveApplication) {
            return response()->json(['success' => false, 'message' => 'Pengajuan Cuti tidak ditemukan']);
        }

        if ($leaveApplication->status_approved == 1) {

            // Update status_approved to Pending and tgl_status_approved to null
            DB::table('pengajuan_cuti')
                ->where('id', $id)
                ->update([
                    'status_approved' => 0,
                    'tgl_status_approved' => null,
                ]);
            return response()->json(['success' => true, 'message' => 'Pengajuan Cuti berhasil dibatalkan']);
        } else if ($leaveApplication->status_approved == 2) {
            // Fetch current sisa_cuti
            DB::table('pengajuan_cuti')
                ->where('id', $id)
                ->update([
                    'status_approved' => 0,
                    'tgl_status_approved' => null,
                    'status_approved_hrd' => 0,
                    'tgl_status_approved_hrd' => null,
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
}
