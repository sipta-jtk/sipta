<?php

use App\Models\User;
use App\Modules\UserManagement\Controllers\UserManagementController;
use App\Modules\UserManagement\Controllers\DosenController;
use FontLib\Table\Type\name;
use App\Modules\UserManagement\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Modules\UserManagement\Controllers\PengajuanPisahKoTAController;
use App\Modules\UserManagement\Controllers\FormPisahKoTAController;
use App\Modules\UserManagement\Controllers\KBKController;
use App\Modules\UserManagement\Controllers\ProgramStudiController;


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

// Route lainnya
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

Route::get('/manage_dosen', [UserManagementController::class, 'manage_dosen'])
    ->middleware('role_no_auth:admin')
    ->name('manage.dosen');

Route::post('/update_role', [DosenController::class, 'update_role'])->name('dosen.update_role');
Route::post('/add_new_dosen', [DosenController::class, 'add_new_dosen'])->name('dosen.add_new_dosen');


// Route::get('/test-spatie', function () {
//     $user = User::where('username', 'dosen001')->first(); // Sesuaikan dengan username yang ada

//     // Tambahkan role dan permission
//     return [
//         'has_dosen_role' => $user->hasRole('dosen'),
//         'can_edit_post' => $user->can('edit-profil')
//     ];
// }

// );

Route::get('/kelola-kbk', [KBKController::class, 'index'])->name('kelola-kbk');
Route::post('/kelola-kbk', [KBKController::class, 'store'])->name('kelola-kbk.store');
Route::post('/kelola-kbk/update/{id}', [KBKController::class, 'update'])->name('kelola-kbk.update');
Route::delete('/kelola-kbk/{id}', [KBKController::class, 'destroy'])->name('kelola-kbk.destroy');

Route::get('program-studi', [ProgramStudiController::class, 'index'])->name('program-studi.index');
Route::get('program-studi/create', [ProgramStudiController::class, 'create'])->name('program-studi.create');
Route::post('program-studi', [ProgramStudiController::class, 'store'])->name('program-studi.store');
Route::get('program-studi/{id}/edit', [ProgramStudiController::class, 'edit'])->name('program-studi.edit');
Route::put('program-studi/{id}', [ProgramStudiController::class, 'update'])->name('program-studi.update');
Route::delete('program-studi/{id}', [ProgramStudiController::class, 'destroy'])->name('program-studi.destroy');
