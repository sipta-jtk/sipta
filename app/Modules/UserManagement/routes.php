<?php

use App\Models\User;
use App\Modules\UserManagement\Controllers\UserManagementController;
use App\Modules\UserManagement\Controllers\DosenController;
use FontLib\Table\Type\name;
use App\Modules\UserManagement\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Modules\UserManagement\Controllers\PengajuanPisahKoTAController;
use App\Modules\UserManagement\Controllers\FormPisahKoTAController;
use App\Modules\UserManagement\Controllers\ProfileController;

// Route untuk login
Route::get('/login', function () {
    return view('UserManagement.views.auth.login');
})->name('login');

// Route untuk register
Route::get('/register', function () {
    return view('UserManagement.views.auth.register');
})->name('register');

// Route untuk menampilkan form reset password
Route::get('/reset-password/{token}', function ($token) {
    return view('UserManagement.views.auth.reset-password', ['token' => $token]);
})->name('password.reset');

// Route untuk memproses form reset password
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Route untuk forgot password
Route::get('/forgot-password', function () {
    return view('UserManagement.views.auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::group(['prefix' => 'user_management'], function () {
    Route::get('/user_management', [UserManagementController::class, 'render']);
});

//Pengajuan Pisah KoTA
// Route::get('/pengajuan-pisah-kota', [PengajuanPisahKoTAController::class, 'index'])->name('pengajuan.pisah.kota');
// Route::get('/pengajuan-pisah-kota/{id}', [PengajuanPisahKoTAController::class, 'show'])->name('pengajuan.pisah.kota.show');

Route::middleware(['auth', 'koordinator_ta'])->group(function () {
    Route::get('/pengajuan-pisah-kota', [PengajuanPisahKoTAController::class, 'index'])
        ->name('pengajuan.pisah.kota');
});
Route::middleware(['auth', 'koordinator_ta'])->group(function () {
    Route::get('/pengajuan-pisah-kota/{id}', [PengajuanPisahKoTAController::class, 'show'])
        ->name('pengajuan.pisah.kota.show');
});

Route::get('/form-pisah-kota', [FormPisahKoTAController::class, 'showFormPisah'])->name('form.pisah.kota');
Route::post('/form-pisah-kota/ajukan', [FormPisahKoTAController::class, 'ajukan'])->name('form.pisah.kota.ajukan');
Route::post('/form-pisah-kota/batal', [FormPisahKotaController::class, 'batal'])->name('form.pisah.kota.batal');
Route::patch('/pengajuan-pisah-kota/{id}/terima', [PengajuanPisahKoTAController::class, 'terima'])->name('pengajuan.pisah.kota.terima');

Route::get('/manajemen-akun-dosen', [UserManagementController::class, 'manage_dosen'])
    ->middleware('auth', 'can:admin')
    ->name('manage.dosen');
Route::post('/delete-dosen', [DosenController::class, 'deleteDosen'])->name('dosen.deleteDosen');
Route::post('/update-dosen', [DosenController::class, 'updateDosen'])->name('dosen.updateDosen');

Route::get('/manajemen-akun-mahasiswa', [UserManagementController::class, 'manage_dosen'])
    // ->middleware('auth')
    ->name('manage.dosen');

Route::post('/update_role', [DosenController::class, 'update_role'])->name('dosen.update_role');
Route::post('/add_new_dosen', [DosenController::class, 'add_new_dosen'])->name('dosen.add_new_dosen');


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile')->middleware('auth');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
});


Route::get('/admin-dashboard', function () {
    Gate::authorize('admin');
    return "Selamat datang di Dashboard Admin!";
});

