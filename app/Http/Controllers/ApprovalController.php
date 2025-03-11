<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Mail\CutiApprovalNotification;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\PengajuanCuti;
use App\Models\Pengajuanizin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;
use mikehaertl\pdftk\Pdf;


class ApprovalController extends Controller
{

    public function izinapprovalhrd(Request $request)
    {
        $query = Pengajuanizin::query();
        $query->join('karyawan as k1', 'pengajuan_izin.nik', '=', 'k1.nik') // Employee
            ->join('jabatan as j1', 'k1.jabatan', '=', 'j1.id') // Employee's Jabatan
            ->leftJoin('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id') // Superior's Jabatan
            ->leftJoin('karyawan as k2', 'j2.id', '=', 'k2.jabatan') // Superior
            ->where('k1.status_kar', 'Aktif') // Only active employees
            ->where(function ($query) {
                $query->whereNull('k2.status_kar') // Allow cases where there's no superior
                    ->orWhere('k2.status_kar', 'Aktif'); // Only active superiors
            });

        $query->select(
            'pengajuan_izin.*',
            'k1.nama_lengkap',
            'j1.nama_jabatan',
            'k2.nama_lengkap as nama_atasan' // Fetch Superior's Name
        )
            ->where('pengajuan_izin.status', '!=', 'Cuti')
            ->where(function ($q) {
                $q->where('pengajuan_izin.status_approved', 0)
                    ->orWhere('pengajuan_izin.status_approved_hrd', 0);
            });

        $query->orderByRaw(
            'CASE
        WHEN pengajuan_izin.status_approved = 0 AND pengajuan_izin.status_approved_hrd = 0 THEN 0
        ELSE 1
    END ASC'
        );

        // Apply filters
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_create', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        if (!empty($request->nama_lengkap)) {
            $query->where('k1.nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Pagination
        $izinapproval = $query->paginate(25)->appends($request->query());

        return view('approval.approvalhr', compact('izinapproval'));
    }

    public function approveizinhrd(Request $request)
    {
        $id = $request->id_izin_form;
        $status_approved_hrd = $request->status_approved_hrd;
        $keputusan = $request->keputusan;
        $currentDate = Carbon::now();

        $izin = DB::table('pengajuan_izin')->where('id', $id)->first();
        $nik = $izin->nik;
        $tgl_izin = Carbon::parse($izin->tgl_izin);
        $tgl_izin_akhir = Carbon::parse($izin->tgl_izin_akhir);
        $jml_hari = $izin->jml_hari;
        $pukul = $izin->pukul;
        $keterangan = $izin->keterangan;
        $foto = $izin->foto;

        // Get employee data
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        $nama_lengkap = $karyawan->nama_lengkap ?? 'Unknown';
        $nip = $karyawan->nip;

        // Default update fields
        $updateData = [
            'status_approved_hrd' => $status_approved_hrd,
            'tgl_status_approved_hrd' => $currentDate,
            'keputusan' => $keputusan,
            'tgl_jadwal_off' => null,
        ];

        // Handle Potong Cuti
        if ($keputusan === 'Potong Cuti') {
            $potongcuti = $request->potongcuti;
            $tgl_potong = Carbon::parse($request->tgl_potong);
            $tgl_potong_sampai = Carbon::parse($request->tgl_potong_sampai);
            $keputusan_potong = $request->keputusan_potong;

            if ($keputusan_potong !== 'Potong Cuti') {
                // Adjust leave dates
                if ($tgl_potong->eq($tgl_izin)) {
                    $updateData['tgl_izin'] = $tgl_potong_sampai->copy()->addDay();
                } elseif ($tgl_potong_sampai->eq($tgl_izin_akhir)) {
                    $updateData['tgl_izin_akhir'] = $tgl_potong->copy()->subDay();
                }

                $updateData['jml_hari'] = $izin->jml_hari - $potongcuti;
            }

            // Insert cut leave record
            DB::table('pengajuan_izin')->insert([
                'nik' => $nik,
                'tgl_izin' => $tgl_potong,
                'tgl_izin_akhir' => $tgl_potong_sampai,
                'jml_hari' => $potongcuti,
                'foto' => 'No Document',
                'tgl_create' => $currentDate,
                'status' => 'Tmk',
                'keterangan' => $keterangan,
                'keputusan' => 'Potong Cuti',
                'status_approved' => 0,
                'status_approved_hrd' => 0,
            ]);

            // Deduct leave balance if approved
            if ($status_approved_hrd == 1) {
                $cuti = DB::table('cuti')->where('nik', $nik)->where('status', 1)->first();
                if ($cuti) {
                    DB::table('cuti')->where('id', $cuti->id)->update([
                        'sisa_cuti' => $cuti->sisa_cuti - $potongcuti
                    ]);
                }
            }
        }

        // Handle Lain-Lain
        if ($keputusan === 'Lain-lain') {
            $updateData['keputusan'] = $request->lainlain;
        }

        // Handle Tukar Jadwal Off
        if ($keputusan === 'Tukar Jadwal Off') {
            $updateData['tgl_jadwal_off'] = $request->tgl_jadwal_off;
        }

        if ($status_approved_hrd == 2) {
            $updateData['status_approved'] = 2;
            $updateData['tgl_status_approved'] = $currentDate;
        }

        // Perform the update
        $update = DB::table('pengajuan_izin')->where('id', $id)->update($updateData);

        // Send Email Notification if approved
        $atasanJabatan = DB::table('jabatan')->where('id', $karyawan->jabatan)->first();
        $atasanJabatanId = $atasanJabatan->jabatan_atasan;
        $atasan = DB::table('karyawan')->where('jabatan', $atasanJabatanId)->where('status_kar', 'Aktif')->first();

        if ($update && $status_approved_hrd == 1 && $atasan && $atasan->email) {
            $token = Str::random(40); // Generate unique token

            // Store the token in the database
            DB::table('pengajuan_izin')
                ->where('id', $id)
                ->update(['approval_token' => $token]);

            $approveUrl = url("/approve/izin/$token?status=1");
            $denyUrl = url("/approve/izin/$token?status=2");
            $emailContent = "
                Approved By HRD <br>
                Pengajuan Absensi Karyawan<br><br>
                Nama : {$nama_lengkap}<br>
                NIK : {$nik}<br>
                Tanggal Izin : " . DateHelper::formatIndonesianDate($tgl_izin) . "<br>
                Tanggal Izin Sampai : " . (!empty($tgl_izin_akhir) ? DateHelper::formatIndonesianDate($tgl_izin_akhir) : '') . "<br>
                Jumlah Hari : {$jml_hari}<br>
                Status : " . DateHelper::getStatusText($izin->status) . "<br>
                Waktu Izin: {$pukul}<br>
                Keterangan : {$keterangan}<br>";

            // Count the number of attachments
            $lampiranCount = 0;
            if ($foto && $foto !== 'No_Document') {
                $fotoFiles = explode(',', $foto); // Split by comma to get an array of file names
                $lampiranCount = count($fotoFiles); // Count the number of files
            }

            $emailContent .= "Lampiran: {$lampiranCount} file(s)<br><br>";

            $emailContent .= "
                <a href='{$approveUrl}' style='padding:10px 20px; background:green; color:white; text-decoration:none;'>Accept</a>
                <a href='{$denyUrl}' style='padding:10px 20px; background:red; color:white; text-decoration:none; margin-left:10px;'>Deny</a>
                <br><br>
                Atau Mohon Cek Di <a href='hrms.ciptaharmoni.com/panel'>HRMS</a><br><br>
                Terima Kasih
            ";


            try {
                Mail::html($emailContent, function ($message) use ($atasan, $nama_lengkap, $currentDate, $foto, $nik) {

                    $message->to($atasan->email)
                        ->subject("Pengajuan Izin Dari {$nama_lengkap} - {$currentDate->format('Y-m-d H:i:s')}")
                        ->priority(1);

                    $message->getHeaders()->addTextHeader('Importance', 'high');
                    $message->getHeaders()->addTextHeader('X-Priority', '1');

                    // Attach the file from public storage
                    if ($foto && $foto !== 'No_Document') {
                        $fotoFiles = explode(',', $foto); // Split by comma to get an array of file names
                        foreach ($fotoFiles as $file) {
                            $filePath = public_path("storage/uploads/karyawan/{$nik}.{$nama_lengkap}/{$file}");
                            if (file_exists($filePath)) {
                                $message->attach($filePath);
                            }
                        }
                    }
                });
            } catch (\Exception $e) {
                Log::error("Failed to send email: " . $e->getMessage());
            }
        }

        return redirect('/approval/izinapprovalhrd')->with([
            'success' => $update ? 'Data Berhasil Di Update' : 'Data Gagal Di Update'
        ]);
    }

    public function approveViaToken($token, Request $request)
    {
        $leaveApplication = DB::table('pengajuan_izin')->where('approval_token', $token)->first();

        if (!$leaveApplication) {
            return view('approval.error', ['message' => 'Invalid or expired approval link.']);
        }

        $statusApproved = $request->query('status'); // 1 = Approved, 2 = Denied
        $currentDate = Carbon::now();

        DB::table('pengajuan_izin')
            ->where('id', $leaveApplication->id)
            ->update([
                'status_approved' => $statusApproved,
                'tgl_status_approved' => $currentDate,
                'approval_token' => null // Ensure one-time use
            ]);

        return view('approval.success', ['message' => 'Leave request has been processed successfully.']);
    }

    public function approveViaTokenCuti($token, Request $request)
    {
        $leaveApplication = DB::table('pengajuan_cuti')->where('approval_token', $token)->first();

        if (!$leaveApplication) {
            return view('approval.error', ['message' => 'Invalid or expired approval link.']);
        }

        $karyawan = DB::table('karyawan')->where('nik', $leaveApplication->nik)->first();
        $atasanJabatan = DB::table('jabatan')->where('id', $karyawan->jabatan)->first();
        $atasanJabatanId = $atasanJabatan->jabatan_atasan;
        $atasan = DB::table('karyawan')->where('jabatan', $atasanJabatanId)->where('status_kar', 'Aktif')->first();

        $status = $request->query('status'); // 1 = Approved, 2 = Denied
        $currentDate = now();

        // Check if the approval is atasan or management level
        if ($leaveApplication->status_approved == 0) {
            // This is an Atasan approval
            $newToken = Str::random(40);
            $updateData = [
                'status_approved' => $status,
                'tgl_status_approved' => $currentDate,
                'approval_token' => ($status == 1) ? $newToken : null, // Generate new token only if approved
            ];

            // If the atasan is Al.Imron, Setia, or Andreas, update status_management too
            if (in_array($atasan->email, ['al.imron@ciptaharmoni.com', 'setia.rusli@ciptaharmoni.com', 'andreas.audyanto@ciptaharmoni.com'])) {
                $updateData['status_management'] = $status;
                $updateData['tgl_status_management'] = $currentDate;
            }

            DB::table('pengajuan_cuti')->where('id', $leaveApplication->id)->update($updateData);

            // Generate new approve and deny URLs for management
            $approveUrl = url("/approve/cuti/$newToken?status=1");
            $denyUrl = url("/approve/cuti/$newToken?status=2");
            $leaveApplication->nama_karyawan = $karyawan->nama_lengkap;

            // Send email only if it's not Al.Imron approving
            // Determine management notification logic
            $managementEmails = [];

            if (in_array($atasan->email, ['setia.rusli@ciptaharmoni.com', 'andreas.audyanto@ciptaharmoni.com'])) {
                $managementEmails = ['al.imron@ciptaharmoni.com']; // Only notify Al.Imron
                $showApprovalButtons = false;
            } else {
                $managementEmails = [
                    'al.imron@ciptaharmoni.com',
                    'setia.rusli@ciptaharmoni.com',
                    'andreas.audyanto@ciptaharmoni.com'
                ];
                $showApprovalButtons = true;
            }

            Mail::to($managementEmails)
                ->send(new CutiApprovalNotification(
                    $leaveApplication,
                    $approveUrl,
                    $denyUrl,
                    $showApprovalButtons
                ));
        } else {
            // This is a Management approval
            DB::table('pengajuan_cuti')
                ->where('id', $leaveApplication->id)
                ->update([
                    'status_management' => $status,
                    'tgl_status_management' => $currentDate,
                    'approval_token' => null, // Remove token after final approval
                ]);
        }

        return view('approval.success', ['message' => 'Leave request has been processed successfully.']);
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
        $query->join('jabatan as current_jabatan', 'karyawan.jabatan', '=', 'current_jabatan.id');
        $query->leftJoin('jabatan as superior_jabatan', 'current_jabatan.jabatan_atasan', '=', 'superior_jabatan.id');
        $query->leftJoin('karyawan as superior', 'superior.jabatan', '=', 'superior_jabatan.id');
        $query->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti');

        $query->select(
            'pengajuan_cuti.*',
            'karyawan.nama_lengkap',
            'current_jabatan.nama_jabatan',
            'department.nama_dept',
            'karyawan.tgl_masuk',
            'tipe_cuti.tipe_cuti',
            'superior.nama_lengkap as nama_atasan'
        );

        $query->orderByRaw(
            'CASE
            WHEN status_approved = 0 AND status_approved_hrd = 0 AND status_management = 0 THEN 0
            ELSE 1
        END ASC'
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

        $query->where('karyawan.status_kar', 'Aktif');

        // Get paginated results
        $cutiapproval = $query->paginate(50)->appends($request->all());

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
                'tgl_status_approved_hrd' => $currentDate,
                'keputusan' => $keputusan
            ]);

        $leaveApplication = DB::table('pengajuan_cuti')->where('id', $id)->first();

        if ($leaveApplication && $status_approved_hrd == 2) {
            $cutiRecord = DB::table('cuti')->where('nik', $nik)->where('tahun', $periode)->first();
            $newSisaCuti = $cutiRecord->sisa_cuti + $leaveApplication->jml_hari;

            DB::table('cuti')
                ->where('nik', $nik)
                ->where('tahun', $periode)
                ->update(['sisa_cuti' => $newSisaCuti, 'tunda' => $leaveApplication->jml_hari]);
        }

        // Send Email Notification if approved
        if ($update && $status_approved_hrd == 1) {
            $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
            $atasanJabatan = DB::table('jabatan')->where('id', $karyawan->jabatan)->first();
            $atasan = DB::table('karyawan')->where('jabatan', $atasanJabatan->jabatan_atasan)->where('status_kar', 'Aktif')->first();

            if ($atasan && $atasan->email) {
                $token = Str::random(40);
                DB::table('pengajuan_cuti')->where('id', $id)->update(['approval_token' => $token]);

                $approveUrl = url("/approve/cuti/$token?status=1");
                $denyUrl = url("/approve/cuti/$token?status=2");
                $emailContent = "
                    Approved By HRD <br>
                    Pengajuan Cuti Karyawan<br><br>
                    Nama : {$karyawan->nama_lengkap}<br>
                    NIK : {$nik}<br>
                    Periode Cuti : {$periode}<br>
                    Sisa Cuti : {$leaveApplication->sisa_cuti}<br>
                    Tanggal Cuti : " . DateHelper::formatIndonesianDate($leaveApplication->tgl_cuti) . "<br>
                    Tanggal Cuti Sampai : " . (!empty($tgl_cuti_sampai) ? DateHelper::formatIndonesianDate($leaveApplication->tgl_cuti_sampai) : '') . "<br>
                    Jumlah Hari : {$leaveApplication->jml_hari}<br>
                    Sisa Cuti Setelah : {$leaveApplication->sisa_cuti_setelah}<br>
                    Karywan Pengganti : {$leaveApplication->kar_ganti}<br>
                    Note : {$leaveApplication->note}<br><br>
                    <a href='{$approveUrl}' style='padding:10px 20px; background:green; color:white; text-decoration:none;'>Accept</a>
                    <a href='{$denyUrl}' style='padding:10px 20px; background:red; color:white; text-decoration:none; margin-left:10px;'>Deny</a>
                    <br><br>
                    Atau Mohon Cek Di <a href='hrms.ciptaharmoni.com/panel'>HRMS</a><br><br>
                    Terima Kasih
                ";

                try {
                    Mail::html($emailContent, function ($message) use ($atasan, $karyawan, $currentDate) {
                        $message->to($atasan->email)
                            ->subject("Pengajuan Cuti Dari {$karyawan->nama_lengkap} - {$currentDate->format('Y-m-d H:i:s')}")
                            ->priority(1);
                        $message->getHeaders()->addTextHeader('Importance', 'high');
                        $message->getHeaders()->addTextHeader('X-Priority', '1');
                    });
                } catch (\Exception $e) {
                    Log::error("Failed to send email: " . $e->getMessage());
                }
            }
        }

        return redirect('/approval/cutiapprovalhrd')->with([
            'success' => $update ? 'Pengajuan Cuti Berhasil Di Update' : 'Pengajuan Cuti Gagal Di Update'
        ]);
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


    //--------------------------------------------------------------------------------------------------------------------------------------------------------

    // MANAGER / ATASAN CODE


    public function izinapproval(Request $request)
    {
        $nik = Auth::guard('user')->user()->nik;

        // Get the current user's details
        $currentUser = Karyawan::where('nik', $nik)->first();
        $currentUserJabatanId = $currentUser->jabatan;
        $currentUserKodeDept = $currentUser->kode_dept;

        // Base query: Join necessary tables
        $query = Pengajuanizin::query();
        $query->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->select('pengajuan_izin.*', 'karyawan.nama_lengkap', 'karyawan.jabatan', 'department.nama_dept', 'jabatan.nama_jabatan');

        // Only include pending requests
        $query->where(function ($q) {
            $q->where('status_approved', 0)
                ->orWhere('status_approved_hrd', 0);
        });

        // Management Logic
        if ($currentUserKodeDept === 'Management') {
            // Fetch NIKs of subordinates
            $subordinateNiks = Karyawan::join('jabatan as j1', 'karyawan.jabatan', '=', 'j1.id')
                ->join('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id')
                ->where('j2.id', $currentUserJabatanId)
                ->pluck('karyawan.nik')
                ->toArray();

            // Prioritize subordinates' requests by ordering
            $query->orderByRaw("
            CASE
                WHEN pengajuan_izin.nik IN ('" . implode("','", $subordinateNiks) . "') THEN 1
                ELSE 2
            END
        ");
        } else {
            // Non-management Logic
            $jabatanAtasan = Jabatan::where('id', $currentUserJabatanId)->value('jabatan_atasan');

            // If Atasan, filter requests from subordinates
            if ($jabatanAtasan) {
                $employeeNiks = Karyawan::join('jabatan as j1', 'karyawan.jabatan', '=', 'j1.id')
                    ->join('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id')
                    ->where('j2.id', $currentUserJabatanId)
                    ->pluck('karyawan.nik');

                $query->whereIn('pengajuan_izin.nik', $employeeNiks);
            }
        }

        // Paginate results
        $izinapproval = $query->paginate(20);
        $izinapproval->appends($request->all());

        // Debugging Output (optional, for testing only)
        // dd($query->toSql(), $query->getBindings());

        // Return the view
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
                        'keputusan' => $keputusan,
                        'approval_token' => null
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

        $query->where(function ($q) {
            $q->where('status_approved', 0)
                ->orWhere('status_approved_hrd', 0)
                ->orWhere('status_management', 0);
        });
        // Check if the user belongs to the Management department
        if ($currentUserKodeDept === 'Management') {
            // Management can only see requests where status_approved = 1 and status_approved_hrd = 1

            $subordinateNiks = Karyawan::join('jabatan as j1', 'karyawan.jabatan', '=', 'j1.id')
                ->join('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id')
                ->where('j2.id', $currentUserJabatanId)
                ->pluck('karyawan.nik')
                ->toArray();

            // Prioritize subordinates' requests by ordering
            $query->orderByRaw("
                CASE
                    WHEN pengajuan_cuti.nik IN ('" . implode("','", $subordinateNiks) . "') THEN 1
                    ELSE 2
                END
            ");
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
                $query->whereIn('pengajuan_cuti.nik', $employeeNiks);
            }
        }

        // Paginate the results
        $cutiapproval = $query->paginate(50);
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
        $currentUserNik = Auth::guard('user')->user()->nik;
        $currentUser = Karyawan::where('nik', $currentUserNik)->first();
        $currentUserJabatanId = $currentUser->jabatan;
        $currentUserEmail = $currentUser->email;
        $id = $request->id_cuti_form;
        $status_approved = $request->status_approved; // 1 = Approve, 2 = Decline
        $currentDate = Carbon::now();

        $leaveApplication = DB::table('pengajuan_cuti')->where('id', $id)->first();
        if (!$leaveApplication) {
            return redirect('/approval/cutiapproval')->with(['error' => 'Pengajuan Cuti tidak ditemukan']);
        }

        $karyawanNik = $leaveApplication->nik;
        $karyawan = DB::table('karyawan')->where('nik', $karyawanNik)->first();

        // Determine the user's role
        $isAtasan = Karyawan::join('jabatan as j1', 'karyawan.jabatan', '=', 'j1.id')
            ->join('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id')
            ->where('karyawan.nik', $karyawanNik)
            ->where('j2.id', $currentUserJabatanId)
            ->exists();

        $isManagement = $currentUser->kode_dept == 'Management';

        // Update fields based on role
        $updateFields = [];
        if ($isAtasan && $isManagement) {
            $updateFields = [
                'status_approved' => $status_approved,
                'tgl_status_approved' => $currentDate,
                'status_management' => $status_approved,
                'tgl_status_management' => $currentDate,
            ];
        } elseif ($isAtasan) {
            $updateFields = [
                'status_approved' => $status_approved,
                'tgl_status_approved' => $currentDate,
            ];
        } elseif ($isManagement) {
            $updateFields = [
                'status_management' => $status_approved,
                'tgl_status_management' => $currentDate,
            ];
        }

        if (!empty($updateFields)) {
            $update = DB::table('pengajuan_cuti')->where('id', $id)->update($updateFields);

            if ($update) {
                // Handle email notifications for atasan approval
                if ($isAtasan && $status_approved == 1) {
                    $newToken = Str::random(40);

                    // Update the approval token
                    DB::table('pengajuan_cuti')
                        ->where('id', $id)
                        ->update(['approval_token' => $newToken]);

                    // Generate approval URLs
                    $approveUrl = url("/approve/cuti/$newToken?status=1");
                    $denyUrl = url("/approve/cuti/$newToken?status=2");

                    // Add employee name to leave application object
                    $leaveApplication->nama_karyawan = $karyawan->nama_lengkap;

                    // Determine management notification logic
                    $managementEmails = [];

                    if (in_array($currentUserEmail, ['setia.rusli@ciptaharmoni.com', 'andreas.audyanto@ciptaharmoni.com'])) {
                        $managementEmails = ['al.imron@ciptaharmoni.com']; // Only notify Al.Imron
                        $showApprovalButtons = false;
                    } else {
                        $managementEmails = [
                            'al.imron@ciptaharmoni.com',
                            'setia.rusli@ciptaharmoni.com',
                            'andreas.audyanto@ciptaharmoni.com'
                        ];
                        $showApprovalButtons = true;
                    }

                    Mail::to($managementEmails)
                        ->send(new CutiApprovalNotification(
                            $leaveApplication,
                            $approveUrl,
                            $denyUrl,
                            $showApprovalButtons
                        ));
                }

                // Handle cuti/tunda logic
                $cutiRecord = DB::table('cuti')
                    ->where('nik', $karyawanNik)
                    ->where('tahun', $leaveApplication->periode)
                    ->first();

                if ($cutiRecord) {
                    $currentTunda = $cutiRecord->tunda;
                    $currentSisa = $cutiRecord->sisa_cuti;

                    if ($status_approved == 2) { // Declined by Atasan or Management
                        $exceedingDays = max(0, $leaveApplication->jml_hari - max(0, $currentSisa));
                        $newTunda = $currentTunda + $exceedingDays;
                        $newSisa = $currentSisa + $leaveApplication->jml_hari;

                        DB::table('cuti')
                            ->where('nik', $karyawanNik)
                            ->where('tahun', $leaveApplication->periode)
                            ->update([
                                'tunda' => $newTunda,
                                'sisa_cuti' => $newSisa,
                            ]);

                        DB::table('pengajuan_cuti')
                            ->where('id', $id)
                            ->update([
                                'status_management' => $status_approved,
                                'tgl_status_management' => $currentDate,
                            ]);
                    }

                    if ($isManagement && $status_approved == 1) { // Approved by Management
                        // Remove Tunda only if Management is the final approver
                        $newTunda = max(0, $currentTunda - $leaveApplication->jml_hari);

                        DB::table('cuti')
                            ->where('nik', $karyawanNik)
                            ->where('tahun', $leaveApplication->periode)
                            ->update([
                                'tunda' => $newTunda,
                            ]);
                    }
                }

                return redirect('/approval/cutiapproval')->with(['success' => 'Pengajuan Cuti Berhasil Di Update']);
            }
        }

        return redirect('/approval/cutiapproval')->with(['error' => 'Anda tidak memiliki wewenang untuk menyetujui pengajuan ini']);
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
