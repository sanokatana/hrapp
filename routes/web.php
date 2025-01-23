<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\PengajuanCutiController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SkController;
use App\Http\Controllers\TimeAttendanceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest:karyawan'])->group(function () {
    Route::get('/karlogin', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});

Route::middleware(['guest:web'])->group(function () {
    Route::get('/', function () {
        return view('auth.home');
    })->name('home');
});

Route::middleware(['guest:candidate'])->group(function () {
    Route::get('/candidate', function () {
        return view('auth.logincandidate');
    })->name('logincandidate');

    Route::post('/proseslogincandidate', [AuthController::class, 'proseslogincandidate']);
});

Route::middleware(['guest:user'])->group(function () {
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');

    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});

Route::middleware(['auth:candidate'])->group(function () {

    Route::get('/candidate/dashboard', [DashboardController::class, 'dashboardcandidate']);
    Route::get('/candidate/proseslogout', [AuthController::class, 'proseslogoutcandidate']);

    Route::get('/candidate/data', [CandidateController::class, 'candidate_data']);
    Route::post('/candidate/data/store', [CandidateController::class, 'candidate_store_form']);
    Route::get('/candidate/data/perlengkapan', [CandidateController::class, 'candidate_data_perlengkapan']);
    Route::post('/candidate/data/store/perlengkapan', [CandidateController::class, 'storePerlengkapan']);


});

Route::middleware(['auth:karyawan'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    //Presensi
    Route::get('/presensi/create', [PresensiController::class, 'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);

    //Edit Profile
    Route::get('/editprofile', [PresensiController::class, 'editprofile']);
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateprofile']);
    Route::post('/presensi/cek-sisa-cuti-profile', [PresensiController::class, 'getSisaCutiProfile']);

    //Histori
    Route::get('/presensi/histori', [PresensiController::class, 'histori']);
    Route::get('/gethistori', [PresensiController::class, 'gethistori']);

    //Izin
    Route::get('/presensi/izin', [PresensiController::class, 'izin']);
    Route::post('/presensi/getizin', [PresensiController::class, 'getizin']);
    Route::post('/presensi/getizincuti', [PresensiController::class, 'getizincuti']);
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);

    Route::get('/presensi/pembatalanizin', [PresensiController::class, 'pembatalanizin']);
    Route::post('/presensi/pembatalanizinYes', [PresensiController::class, 'batalIzin']);

    Route::get('/presensi/pembatalancuti', [PresensiController::class, 'pembatalancuti']);
    Route::post('/presensi/pembatalancutiYes', [PresensiController::class, 'batalCuti']);

    // In web.php or your routes file
    Route::get('/presensi/checkFile', [PresensiController::class, 'getFolder']);


    //Cuti
    Route::get('/presensi/cuti', [PengajuanCutiController::class, 'cuti']);
    Route::get('/presensi/buatcuti', [PengajuanCutiController::class, 'buatcuti']);
    Route::post('/presensi/storecuti', [PengajuanCutiController::class, 'storecuti']);
    Route::post('/presensi/cek-sisa-cuti', [PengajuanCutiController::class, 'getSisaCuti']);

    //Cuti Khusus
    Route::get('/presensi/buatcutikhusus', [PengajuanCutiController::class, 'buatcutikhusus']);
    Route::post('/presensi/storecutikhusus', [PengajuanCutiController::class, 'storecutikhusus']);

    Route::get('/notifikasi', [PresensiController::class, 'notifikasi']);
});

Route::middleware(['auth:user', 'notifications'])->group(function () {
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin'])->name('panel.dashboardadmin');

    Route::get('/panel/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);


    Route::get('/panel/accountSetting', [DashboardController::class, 'accountSetting']);
    Route::post('/update-password', [DashboardController::class, 'updatePassword'])->name('update-password');


    // Karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::post('/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
    Route::post('/karyawan/{nik}/delete', [KaryawanController::class, 'delete']);
    Route::post('/karyawan/uploadKaryawan', [KaryawanController::class, 'uploadKaryawan']);
    Route::get('/karyawan/downloadTemplate', [KaryawanController::class, 'downloadTemplateKar']);
    Route::post('/karyawan/storeshift/{nik}', [KaryawanController::class, 'storeShift'])->name('karyawan.storeShift');
    // Add this route to your web.php file
    Route::get('/karyawan/getshift/{nik}', [KaryawanController::class, 'getShift'])->name('karyawan.getshift');

    Route::get('/karyawan/export', [KaryawanController::class, 'export'])->name('export.karyawan');


    //User
    Route::get('/data/user', [UserController::class, 'index']);
    Route::post('/data/user/store', [UserController::class, 'store']);
    Route::post('/data/user/edit', [UserController::class, 'edit']);
    Route::post('/data/user/{nik}/update', [UserController::class, 'update']);
    Route::post('/data/user/{nik}/delete', [UserController::class, 'delete']);
    Route::get('/data/user/getEmployeeByNik', [UserController::class, 'getEmployeeByNik']);
    Route::get('/data/user/getEmployeeNameUser', [UserController::class, 'getEmployeeNameUser'])->name('getEmployeeNameUser');

    //Cuti
    Route::get('/cuti', [CutiController::class, 'index']);
    Route::post('/cuti/store', [CutiController::class, 'store']);
    Route::get('/cuti/{id}/edit', [CutiController::class, 'edit']);
    Route::post('/cuti/{id}/update', [CutiController::class, 'update']);
    Route::post('/cuti/{id}/delete', [CutiController::class, 'delete']);
    Route::get('/cek-cuti-karyawan', [CutiController::class, 'cekCutiKaryawan'])->name('cek.cuti.karyawan');
    Route::get('/cuti/getEmployeeByNik', [CutiController::class, 'getEmployeeByNik']);
    Route::get('/cuti/getEmployeeName', [CutiController::class, 'getEmployeeName'])->name('getEmployeeName');
    Route::post('/cuti/uploadCuti', [CutiController::class, 'uploadCuti']);
    Route::get('/cuti/downloadTemplate', [CutiController::class, 'downloadTemplate']);
    Route::get('/cuti/export', [CutiController::class, 'export'])->name('export.cuti');

    //Department
    Route::get('/department', [DepartmentController::class, 'index']);
    Route::post('/department/store', [DepartmentController::class, 'store']);
    Route::post('/department/edit', [DepartmentController::class, 'edit']);
    Route::post('/department/{kode_dept}/update', [DepartmentController::class, 'update']);
    Route::post('/department/{kode_dept}/delete', [DepartmentController::class, 'delete']);

    //Presensi
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::get('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanpeta']);

    //Approval

    Route::get('/approval/izinapproval', [ApprovalController::class, 'izinapproval']);
    Route::post('/approval/approveizin', [ApprovalController::class, 'approveizin']);
    Route::post('/approval/batalapprove/{id}', [ApprovalController::class, 'batalapprove']);

    //Approval Print
    Route::get('/approval/printIzin', [ApprovalController::class, 'printIzin'])->name('izin.print');
    Route::get('/pdfIzin-template', function () {
        $filePath = storage_path('app/public/uploads/templates/form_absen.pdf');
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return Response::file($filePath);
    })->name('pdfIzin.template');

    //Approval HRD
    Route::get('/approval/izinapprovalhrd', [ApprovalController::class, 'izinapprovalhrd']);
    Route::post('/approval/approveizinhrd', [ApprovalController::class, 'approveizinhrd']);
    Route::post('/approval/batalapprovehrd/{id}', [ApprovalController::class, 'batalapprovehrd']);

    //Approval Cuti
    Route::get('/approval/cutiapproval', [ApprovalController::class, 'cutiapproval']);
    Route::post('/approval/approvecuti', [ApprovalController::class, 'approvecuti']);
    Route::post('/approval/batalapprovecuti/{id}', [ApprovalController::class, 'batalapprovecuti']);

    Route::get('/approval/printCuti', [ApprovalController::class, 'printCuti'])->name('cuti.print');
    Route::get('/pdfCuti-template', function () {
        $filePath = storage_path('app/public/uploads/templates/cuti_form.pdf');
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return Response::file($filePath);
    })->name('pdfCuti.template');

    //Approval Cuti HRD
    Route::get('/approval/cutiapprovalhrd', [ApprovalController::class, 'cutiapprovalhrd']);
    Route::post('/approval/approvecutihrd', [ApprovalController::class, 'approvecutihrd']);
    Route::post('/approval/batalapprovecutihrd/{id}', [ApprovalController::class, 'batalapprovecutihrd']);

    //Konfigurasi
    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class, 'index']);
    Route::post('/konfigurasi/lokasikantor/store', [KonfigurasiController::class, 'store']);
    Route::post('/konfigurasi/lokasikantor/edit', [KonfigurasiController::class, 'edit']);
    Route::post('/konfigurasi/lokasikantor/{nama_kantor}/update', [KonfigurasiController::class, 'update']);
    Route::post('/konfigurasi/lokasikantor/{nama_kantor}/delete', [KonfigurasiController::class, 'delete']);

    //Konfigurasi Tipe Cuti
    Route::get('/konfigurasi/tipecuti', [KonfigurasiController::class, 'tipecuti']);
    Route::post('/konfigurasi/tipecuti/store', [KonfigurasiController::class, 'tipecutistore']);
    Route::post('/konfigurasi/tipecuti/edit', [KonfigurasiController::class, 'tipecutiedit']);
    Route::post('/konfigurasi/tipecuti/{id_tipe_cuti}/update', [KonfigurasiController::class, 'tipecutiupdate']);
    Route::post('/konfigurasi/tipecuti/{id_tipe_cuti}/delete', [KonfigurasiController::class, 'tipecutidelete']);

    //Konfigurasi Jabatan
    Route::get('/konfigurasi/jabatan', [KonfigurasiController::class, 'jabatan']);
    Route::post('/konfigurasi/jabatan/store', [KonfigurasiController::class, 'jabatanstore']);
    Route::post('/konfigurasi/jabatan/edit', [KonfigurasiController::class, 'jabatanedit']);
    Route::post('/konfigurasi/jabatan/{id}/update', [KonfigurasiController::class, 'jabatanupdate'])->name('jabatan.update');
    Route::post('/konfigurasi/jabatan/{id_tipe_cuti}/delete', [KonfigurasiController::class, 'jabatandelete']);

    //Konfigurasi Libur Nasional
    Route::get('/konfigurasi/libur-nasional', [KonfigurasiController::class, 'libur']);
    Route::post('/konfigurasi/libur-nasional/store', [KonfigurasiController::class, 'liburstore']);
    Route::post('/konfigurasi/libur-nasional/edit', [KonfigurasiController::class, 'liburedit']);
    Route::post('/konfigurasi/libur-nasional/{tgl_libur}/update', [KonfigurasiController::class, 'liburupdate']);
    Route::post('/konfigurasi/libur-nasional/{tgl_libur}/delete', [KonfigurasiController::class, 'liburdelete']);

    //Konfigurasi Libur Karyawan
    Route::get('/konfigurasi/liburkar', [KonfigurasiController::class, 'liburkar']);
    Route::post('/konfigurasi/liburkar/store', [KonfigurasiController::class, 'liburkarstore']);
    Route::post('/konfigurasi/liburkar/edit', [KonfigurasiController::class, 'liburkaredit']);
    Route::post('/konfigurasi/liburkar/update', [KonfigurasiController::class, 'liburkarupdate']);
    Route::post('/konfigurasi/liburkar/{id}/delete', [KonfigurasiController::class, 'liburkardelete']);
    Route::get('/konfigurasi/liburkar/{id}/days', [KonfigurasiController::class, 'getDays']);
    Route::post('/konfigurasi/liburkar/{liburId}/days/create', [KonfigurasiController::class, 'saveOrUpdateDays']);
    Route::post('/konfigurasi/liburkar/{liburId}/days/update', [KonfigurasiController::class, 'saveOrUpdateDays']);
    Route::post('/konfigurasi/liburkar/days/{id}', [KonfigurasiController::class, 'deleteDay']);


    //Cabang
    Route::get('/cabang', [CabangController::class, 'index']);
    Route::post('/cabang/store', [CabangController::class, 'store']);
    Route::post('/cabang/edit', [CabangController::class, 'edit']);
    Route::post('/cabang/update', [CabangController::class, 'update']);
    Route::post('/cabang/{kode_cabang}/delete', [CabangController::class, 'delete']);

    //Attendance
    Route::get('/attendance/table', [AttendanceController::class, 'index']);
    Route::post('/attendance/uploadAtt', [AttendanceController::class, 'uploadAtt']);
    Route::get('/attendance/att_monitoring', [AttendanceController::class, 'att_monitoring']);
    Route::get('/attendance/get_att', [AttendanceController::class, 'get_att']);
    Route::get('/attendance/daymonitor', [AttendanceController::class, 'daymonitor']);
    Route::get('/attendance/showdaymonitor', [AttendanceController::class, 'showdaymonitor']);
    Route::get('/attendance/database', [AttendanceController::class, 'database']);
    Route::post('/attendance/databaseupdate', [AttendanceController::class, 'databaseupdate']);

    //Time Attendance
    Route::get('/timeatt/table', [TimeAttendanceController::class, 'index']);
    Route::get('/timeatt/att_monitoring', [TimeAttendanceController::class, 'att_monitoring']);
    Route::get('/timeatt/get_att', [TimeAttendanceController::class, 'get_att']);
    Route::get('/timeatt/daymonitor', [TimeAttendanceController::class, 'daymonitor']);
    Route::get('/timeatt/showdaymonitor', [TimeAttendanceController::class, 'showdaymonitor']);

    //Shift
    Route::get('/shift', [ShiftController::class, 'shift']);
    Route::post('/shift/store', [ShiftController::class, 'shiftstore']);
    Route::post('/shift/edit', [ShiftController::class, 'shiftedit']);
    Route::post('/shift/update', [ShiftController::class, 'shiftupdate']);
    Route::post('/shift/{id}/delete', [ShiftController::class, 'shiftdelete']);

    //Shift Pattern
    Route::get('/shiftpatt', [ShiftController::class, 'shiftpatt']);
    Route::post('/shiftpatt/store', [ShiftController::class, 'shiftpattstore']);
    Route::post('/shiftpatt/edit', [ShiftController::class, 'shiftpattedit']);
    Route::post('/shiftpatt/update', [ShiftController::class, 'shiftpattupdate']);
    Route::post('/shiftpatt/{id}/delete', [ShiftController::class, 'shiftpattdelete']);
    Route::get('/shiftpatt/{id}/cycles', [ShiftController::class, 'getCycles']);
    Route::post('/shiftpatt/{id}/cycles', [ShiftController::class, 'saveCycles']);
    Route::post('/delete-cycle/{id}', [ShiftController::class, 'deleteCycle']);

    Route::get('/laporan/attendance', [LaporanController::class, 'index']);
    Route::get('/laporan/attendanceExport', [LaporanController::class, 'exportData']);

    Route::get('/laporan/time', [LaporanController::class, 'timeindex']);
    Route::get('/laporan/timeExport', [LaporanController::class, 'exportTime']);

    Route::get('/laporan/exportAttendanceView', [LaporanController::class, 'exportAttendanceView']);
    Route::get('/laporan/exportAttendance', [LaporanController::class, 'exportAttendance']);


    Route::get('/laporan/attendanceViewAtasan', [LaporanController::class, 'attendanceViewAtasan']);

    Route::get('/laporan/viewIzin', [LaporanController::class, 'viewIzin']);
    Route::get('/laporan/exportIzin', [LaporanController::class, 'exportIzin']);
    Route::get('/laporan/reportIzin', [LaporanController::class, 'reportIzin']);

    Route::get('/laporan/viewCuti', [LaporanController::class, 'viewCuti']);
    Route::get('/laporan/exportCuti', [LaporanController::class, 'exportCuti']);
    Route::get('/laporan/reportCuti', [LaporanController::class, 'reportCuti']);

    Route::get('/laporan/viewCutiSisa', [LaporanController::class, 'viewSisaCuti']);


    Route::get('/laporan/dailyMonitor', [LaporanController::class, 'showAttendanceTable']);


    Route::get('/email/management', [LaporanController::class, 'sendDailyReport']);

    // routes/web.php
    Route::get('/contracts', [ContractController::class, 'filterContracts']);
    Route::get('/contract/type', [ContractController::class, 'getContractType']);
    Route::get('/kontrak', [ContractController::class, 'index']);
    Route::get('/kontrak/exportData', [ContractController::class, 'export']);
    Route::get('/kontrak/export', [ContractController::class, 'downloadTemplateKontrak']);
    Route::post('/kontrak/uploadKontrak', [ContractController::class, 'uploadKontrak']);
    Route::post('/kontrak/store', [ContractController::class, 'store']);
    Route::post('/kontrak/edit', [ContractController::class, 'edit']);
    Route::post('/kontrak/view', [ContractController::class, 'view']);
    Route::post('/kontrak/{id}/update', [ContractController::class, 'update']);
    Route::post('/kontrak/{id}/delete', [ContractController::class, 'delete']);
    Route::post('/kontrak/peningkatanOrExtend', [ContractController::class, 'peningkatanOrExtend']);
    Route::post('/kontrak/{id}/print', [ContractController::class, 'printContract']);


    // SK

    Route::get('/sk', [SkController::class, 'index']);
    Route::get('/sk/exportData', [SkController::class, 'export']);
    Route::get('/sk/export', [SkController::class, 'downloadTemplateSk']);
    Route::post('/sk/uploadSk', [SkController::class, 'uploadSk']);
    Route::post('/sk/store', [SkController::class, 'store']);
    Route::post('/sk/edit', [SkController::class, 'edit']);
    Route::post('/sk/view', [SkController::class, 'view']);
    Route::post('/sk/{id}/update', [SkController::class, 'update']);
    Route::post('/sk/{id}/delete', [SkController::class, 'delete']);
    Route::post('/sk/{id}/print', [SkController::class, 'printContract']);

    //Performance
    Route::get('/performance/notification', [PerformanceController::class, 'notification']);
    Route::get('/performance/notificationEmail', [PerformanceController::class, 'notificationEmail']);
    Route::post('/contracts/send-email', [PerformanceController::class, 'sendEmail'])->name('contracts.sendEmail');


    //Recruitment - Candidate
    Route::get('/recruitment/candidate', [RecruitmentController::class, 'candidate']);
    Route::post('/recruitment/candidate/store', [RecruitmentController::class, 'candidate_store']);
    Route::post('/recruitment/candidate/edit', [RecruitmentController::class, 'candidate_edit']);
    Route::post('/recruitment/candidate/{id}/update', [RecruitmentController::class, 'candidate_update']);
    Route::post('/recruitment/candidate/{id}/delete', [RecruitmentController::class, 'candidate_delete']);
    Route::post('/recruitment/candidate/{id}/next', [RecruitmentController::class, 'candidate_next']);
    Route::post('/recruitment/candidate/{id}/reject', [RecruitmentController::class, 'candidate_reject']);
    Route::post('/recruitment/candidate/{id}/hired', [RecruitmentController::class, 'candidate_hire']);
    Route::post('/recruitment/candidate/{id}/back', [RecruitmentController::class, 'candidate_back']);
    Route::post('/recruitment/candidate/{id}/interview', [RecruitmentController::class, 'candidate_interview']);
    Route::post('/recruitment/candidate/interview/get', [RecruitmentController::class, 'candidate_interview_get']);

    Route::get('/recruitment/candidate/data/interview', [RecruitmentController::class, 'candidate_interview_data']);
    Route::post('/recruitment/candidate/data/interview/edit', [RecruitmentController::class, 'candidate_interview_edit']);


    Route::get('/recruitment/dashboard', [RecruitmentController::class, 'dashboard']);


    Route::get('/recruitment/candidate/data', [RecruitmentController::class, 'candidate_data']);
    Route::post('/recruitment/candidate/data/approve', [RecruitmentController::class, 'candidate_data_approve']);
    Route::post('/recruitment/candidate/data/view', [RecruitmentController::class, 'candidate_data_view']);
    Route::post('/recruitment/candidate/data/peningkatan', [RecruitmentController::class, 'candidate_peningkatan']);
    Route::post('/recruitment/interview/{id}/update', [RecruitmentController::class, 'candidate_interview_update']);
    Route::post('/recruitment/data/{id}/print', [RecruitmentController::class, 'printCandidateData']);

    //Recruitment - Candidate
    Route::get('/recruitment/jobs', [RecruitmentController::class, 'job_opening']);
    Route::post('/recruitment/jobs/store', [RecruitmentController::class, 'job_opening_store']);
    Route::post('/recruitment/jobs/edit', [RecruitmentController::class, 'job_opening_edit']);
    Route::post('/recruitment/jobs/{id}/update', [RecruitmentController::class, 'job_opening_update']);
    Route::post('/recruitment/jobs/{id}/delete', [RecruitmentController::class, 'job_opening_delete']);

    //Recruitment - Types
    Route::get('/recruitment', [RecruitmentController::class, 'recruitment']);
    Route::post('/recruitment/store', [RecruitmentController::class, 'recruitment_store']);
    Route::post('/recruitment/edit', [RecruitmentController::class, 'recruitment_edit']);
    Route::post('/recruitment/{id}/update', [RecruitmentController::class, 'recruitment_update']);
    Route::post('/recruitment/{id}/delete', [RecruitmentController::class, 'recruitment_delete']);

    //Recruitment - Stages
    Route::get('/recruitment/stages', [RecruitmentController::class, 'stage']);
    Route::post('/recruitment/stages/store', [RecruitmentController::class, 'stage_store']);
    Route::post('/recruitment/stages/edit', [RecruitmentController::class, 'stage_edit']);
    Route::post('/recruitment/stages/{id}/update', [RecruitmentController::class, 'stage_update']);
    Route::post('/recruitment/stages/{id}/delete', [RecruitmentController::class, 'stage_delete']);

    //Recruitment - Pipeline
    Route::get('/recruitment/pipeline', [RecruitmentController::class, 'pipeline']);
});


