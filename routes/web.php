<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ParklaringController;
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
        return view('auth.login');
    })->name('login');
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
});

Route::middleware(['auth:karyawan'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);
    //Presensi
});

Route::middleware(['auth:user', 'notifications'])->group(function () {
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin'])->name('panel.dashboardadmin');

    Route::get('/panel/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);

    // Company/Cabang Switcher
    Route::post('/switch-company', [App\Http\Controllers\CompanySwitcherController::class, 'switchCompany']);
    Route::post('/switch-cabang', [App\Http\Controllers\CompanySwitcherController::class, 'switchCabang']);

    Route::get('/panel/accountSetting', [DashboardController::class, 'accountSetting']);
    Route::post('/update-password', [DashboardController::class, 'updatePassword'])->name('update-password');

    // Karyawan
    Route::middleware('superadmin')->group(function () {
        Route::get('/data/user', [UserController::class, 'index']);
        Route::post('/data/user/store', [UserController::class, 'store']);
        Route::post('/data/user/edit', [UserController::class, 'edit']);
        Route::put('/data/user/{user}/update', [UserController::class, 'update'])->name('data.user.update');
        Route::delete('/data/user/{user}/delete', [UserController::class, 'delete'])->name('data.user.delete');
        Route::get('/data/user/getEmployeeByNik', [UserController::class, 'getEmployeeByNik']);
        Route::get('/data/user/getEmployeeNameUser', [UserController::class, 'getEmployeeNameUser'])->name('getEmployeeNameUser');
    });

    // Organisasi
    Route::middleware('superadmin')->group(function () {
        Route::resource('companies', CompanyController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::post('/companies/edit', [CompanyController::class, 'edit']);
    });

    Route::resource('cabang', CabangController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/cabang/edit', [CabangController::class, 'edit']);
    Route::resource('departments', DepartmentController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/departments/edit', [DepartmentController::class, 'edit']);
    Route::get('/jabatans',               [JabatanController::class, 'index'])->name('jabatans.index');
    Route::post('/jabatans',              [JabatanController::class, 'store'])->name('jabatans.store');
    Route::post('/jabatans/edit',         [JabatanController::class, 'edit'])->name('jabatans.edit'); // returns partial
    Route::put('/jabatans/{jabatan}',     [JabatanController::class, 'update'])->name('jabatans.update');
    Route::delete('/jabatans/{jabatan}',  [JabatanController::class, 'destroy'])->name('jabatans.destroy');
    Route::resource('karyawan', KaryawanController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);

    Route::get('/companies/{company}/branches', [KaryawanController::class, 'branchesByCompany']);
        Route::get('/companies/{company}/departments', [JabatanController::class, 'departmentsByCompany'])->name('companies.departments');
});
