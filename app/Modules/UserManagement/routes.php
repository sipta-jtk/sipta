<?php

use App\Models\User;
use App\Modules\UserManagement\Controllers\ForgotPasswordController;
use App\Modules\UserManagement\Controllers\UserManagementController;
use App\Modules\UserManagement\Controllers\DosenController;
use App\Modules\UserManagement\Controllers\AuthenticatedSessionController;
use App\Modules\UserManagement\Controllers\ProfileController;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Route;

// Route untuk login
Route::post('/login', [AuthenticatedSessionController::class, 'store']);


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
    Route::get('/', [UserManagementController::class, 'render']);
});


Route::post('/logout', [UserManagementController::class, 'logout'])->name('logout');


Route::get('/manajemen-akun-mahasiswa', [UserManagementController::class, 'manage_mhs'])
    ->middleware(['auth', 'role:admin'])
    ->name('manage.mhs');

Route::get('/manajemen-akun-dosen', [UserManagementController::class, 'manageDosen'])
    ->middleware(['auth', 'role:admin'])
    ->name('manage.dosen');

Route::get('/dosen',function(){
    return '<h1> hello dosen </h1>';
})->middleware(['auth', 'role:dosen']);
    
Route::post('/update_role', [DosenController::class, 'update_role'])->name('dosen.update_role');
Route::post('/add_new_dosen', [DosenController::class, 'add_new_dosen'])->name('dosen.add_new_dosen');
Route::post('/delete-dosen', [DosenController::class, 'deleteDosen'])->name('dosen.deleteDosen');
Route::post('/updateDosen', [DosenController::class, 'updateDosen'])->name('dosen.updateDosen');


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile')->middleware('auth');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
});

// Route::get('/test-spatie', function () {
//     $user = User::where('username', 'dosen001')->first(); // Sesuaikan dengan username yang ada

//     // Tambahkan role dan permission
//     return [
//         'has_dosen_role' => $user->hasRole('dosen'),
//         'can_edit_post' => $user->can('edit-profil')
//     ];
// }

// );
