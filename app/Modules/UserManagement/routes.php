<?php

use App\Models\User;
use App\Modules\UserManagement\Controllers\UserManagementController;
use App\Modules\UserManagement\Controllers\DosenController;
use FontLib\Table\Type\name;
use App\Modules\UserManagement\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/', [UserManagementController::class, 'render']);
});

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
