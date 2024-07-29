<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\PengajuanCutiController;
use App\Http\Controllers\PresensiController;
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

Route::middleware(['guest:karyawan'])->group(function (){
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin',[AuthController::class, 'proseslogin']);
});

Route::middleware(['guest:user'])->group(function (){
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');

    Route::post('/prosesloginadmin',[AuthController::class, 'prosesloginadmin']);
});

Route::middleware(['auth:karyawan'])->group(function(){
    Route::get('/dashboard',[DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    //Presensi
    Route::get('/presensi/create', [PresensiController::class, 'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);

    //Edit Profile
    Route::get('/editprofile', [PresensiController::class,'editprofile']);
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class,'updateprofile']);
    Route::post('/presensi/cek-sisa-cuti-profile', [PresensiController::class,'getSisaCutiProfile']);

    //Histori
    Route::get('/presensi/histori', [PresensiController::class, 'histori']);
    Route::get('/gethistori', [PresensiController::class,'gethistori']);

    //Izin
    Route::get('/presensi/izin', [PresensiController::class,'izin']);
    Route::post('/presensi/getizin', [PresensiController::class,'getizin']);
    Route::post('/presensi/getizincuti', [PresensiController::class,'getizincuti']);
    Route::get('/presensi/buatizin', [PresensiController::class,'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class,'storeizin']);

    //Cuti
    Route::get('/presensi/cuti', [PengajuanCutiController::class,'cuti']);
    Route::get('/presensi/buatcuti', [PengajuanCutiController::class,'buatcuti']);
    Route::post('/presensi/storecuti', [PengajuanCutiController::class,'storecuti']);
    Route::post('/presensi/cek-sisa-cuti', [PengajuanCutiController::class,'getSisaCuti']);

    //Cuti Khusus
    Route::get('/presensi/buatcutikhusus', [PengajuanCutiController::class,'buatcutikhusus']);
    Route::post('/presensi/storecutikhusus', [PengajuanCutiController::class, 'storecutikhusus'])->name('storecutikhusus');

    Route::get('/notifikasi', [PresensiController::class,'notifikasi']);

});

Route::middleware(['auth:user'])->group(function (){
    Route::get('/panel/dashboardadmin', [DashboardController::class,'dashboardadmin']);
    Route::get('/panel/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);

    // Karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::post('/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
    Route::post('/karyawan/{nik}/delete', [KaryawanController::class, 'delete']);
    Route::post('/karyawan/uploadKaryawan', [KaryawanController::class, 'uploadKaryawan']);
    Route::get('/karyawan/downloadTemplate', [KaryawanController::class, 'downloadTemplateKar']);

    //User
    Route::get('/data/user', [UserController::class,'index']);
    Route::post('/data/user/store', [UserController::class,'store']);
    Route::post('/data/user/edit', [UserController::class,'edit']);
    Route::post('/data/user/{nik}/update', [UserController::class,'update']);
    Route::post('/data/user/{nik}/delete', [UserController::class,'delete']);
    Route::get('/data/user/getEmployeeByNik', [UserController::class, 'getEmployeeByNik']);
    Route::get('/data/user/getEmployeeNameUser', [UserController::class, 'getEmployeeNameUser'])->name('getEmployeeNameUser');

    //Cuti
    Route::get('/cuti', [CutiController::class,'index']);
    Route::post('/cuti/store', [CutiController::class,'store']);
    Route::get('/cuti/{id}/edit', [CutiController::class, 'edit']);
    Route::post('/cuti/{id}/update', [CutiController::class,'update']);
    Route::post('/cuti/{id}/delete', [CutiController::class,'delete']);
    Route::get('/cek-cuti-karyawan', [CutiController::class, 'cekCutiKaryawan'])->name('cek.cuti.karyawan');
    Route::get('/cuti/getEmployeeByNik', [CutiController::class, 'getEmployeeByNik']);
    Route::get('/cuti/getEmployeeName', [CutiController::class, 'getEmployeeName'])->name('getEmployeeName');
    Route::post('/cuti/uploadCuti', [CutiController::class, 'uploadCuti']);
    Route::get('/cuti/downloadTemplate', [CutiController::class, 'downloadTemplate']);

    //Department
    Route::get('/department', [DepartmentController::class,'index']);
    Route::post('/department/store', [DepartmentController::class,'store']);
    Route::post('/department/edit', [DepartmentController::class,'edit']);
    Route::post('/department/{kode_dept}/update', [DepartmentController::class,'update']);
    Route::post('/department/{kode_dept}/delete', [DepartmentController::class,'delete']);

    //Presensi
    Route::get('/presensi/monitoring', [PresensiController::class,'monitoring']);
    Route::get('/getpresensi', [PresensiController::class,'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class,'tampilkanpeta']);

    //Approval

    Route::get('/approval/izinapproval', [ApprovalController::class,'izinapproval']);
    Route::post('/approval/approveizin', [ApprovalController::class, 'approveizin']);
    Route::post('/approval/batalapprove/{id}', [ApprovalController::class, 'batalapprove']);

    //Approval HRD
    Route::get('/approval/izinapprovalhrd', [ApprovalController::class,'izinapprovalhrd']);
    Route::post('/approval/approveizinhrd', [ApprovalController::class, 'approveizinhrd']);
    Route::post('/approval/batalapprovehrd/{id}', [ApprovalController::class, 'batalapprovehrd']);

    //Approval Cuti
    Route::get('/approval/cutiapproval', [ApprovalController::class,'cutiapproval']);
    Route::post('/approval/approvecuti', [ApprovalController::class, 'approvecuti']);
    Route::post('/approval/batalapprovecuti/{id}', [ApprovalController::class, 'batalapprovecuti']);

    //Approval Cuti HRD
    Route::get('/approval/cutiapprovalhrd', [ApprovalController::class,'cutiapprovalhrd']);
    Route::post('/approval/approvecutihrd', [ApprovalController::class, 'approvecutihrd']);
    Route::post('/approval/batalapprovecutihrd/{id}', [ApprovalController::class, 'batalapprovecutihrd']);

    //Konfigurasi
    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class,'index']);
    Route::post('/konfigurasi/lokasikantor/store', [KonfigurasiController::class,'store']);
    Route::post('/konfigurasi/lokasikantor/edit', [KonfigurasiController::class,'edit']);
    Route::post('/konfigurasi/lokasikantor/{nama_kantor}/update', [KonfigurasiController::class,'update']);
    Route::post('/konfigurasi/lokasikantor/{nama_kantor}/delete', [KonfigurasiController::class,'delete']);

    //Konfigurasi Tipe Cuti
    Route::get('/konfigurasi/tipecuti', [KonfigurasiController::class,'tipecuti']);
    Route::post('/konfigurasi/tipecuti/store', [KonfigurasiController::class,'tipecutistore']);
    Route::post('/konfigurasi/tipecuti/edit', [KonfigurasiController::class,'tipecutiedit']);
    Route::post('/konfigurasi/tipecuti/{id_tipe_cuti}/update', [KonfigurasiController::class,'tipecutiupdate']);
    Route::post('/konfigurasi/tipecuti/{id_tipe_cuti}/delete', [KonfigurasiController::class,'tipecutidelete']);

    //Konfigurasi Jabatan
    Route::get('/konfigurasi/jabatan', [KonfigurasiController::class,'jabatan']);
    Route::post('/konfigurasi/jabatan/store', [KonfigurasiController::class,'jabatanstore']);
    Route::post('/konfigurasi/jabatan/edit', [KonfigurasiController::class,'jabatanedit']);
    Route::post('/konfigurasi/jabatan/{id}/update', [KonfigurasiController::class, 'jabatanupdate'])->name('jabatan.update');
    Route::post('/konfigurasi/jabatan/{id_tipe_cuti}/delete', [KonfigurasiController::class,'jabatandelete']);

    //Konfigurasi Libur Nasional
    Route::get('/konfigurasi/libur-nasional', [KonfigurasiController::class,'libur']);
    Route::post('/konfigurasi/libur-nasional/store', [KonfigurasiController::class,'liburstore']);
    Route::post('/konfigurasi/libur-nasional/edit', [KonfigurasiController::class,'liburedit']);
    Route::post('/konfigurasi/libur-nasional/{tgl_libur}/update', [KonfigurasiController::class,'liburupdate']);
    Route::post('/konfigurasi/libur-nasional/{tgl_libur}/delete', [KonfigurasiController::class,'liburdelete']);

    //Cabang
    Route::get('/cabang', [CabangController::class,'index']);
    Route::post('/cabang/store', [CabangController::class,'store']);
    Route::post('/cabang/edit', [CabangController::class,'edit']);
    Route::post('/cabang/update', [CabangController::class,'update']);
    Route::post('/cabang/{kode_cabang}/delete', [CabangController::class,'delete']);

    //Attendance
    Route::get('/attendance/table',[AttendanceController::class,'index']);
    Route::post('/attendance/uploadAtt', [AttendanceController::class, 'uploadAtt']);
    Route::get('/attendance/att_monitoring', [AttendanceController::class,'att_monitoring']);
    Route::get('/attendance/get_att', [AttendanceController::class,'get_att']);
    Route::get('/attendance/daymonitor', [AttendanceController::class,'daymonitor']);
    Route::get('/attendance/showdaymonitor', [AttendanceController::class,'showdaymonitor']);

});
