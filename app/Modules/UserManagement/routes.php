<?php

use App\Models\User;
use App\Modules\UserManagement\Controllers\UserManagementController;
use App\Modules\UserManagement\Controllers\DosenController;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user_management'], function () {
    Route::get('/', [UserManagementController::class, 'render']);
});

Route::get('/manage_dosen', [UserManagementController::class, 'manage_dosen'])
    ->middleware('role_no_auth:admin')
    ->name('manage.dosen');


    
Route::post('/update_role', [DosenController::class, 'update_role'])->name('dosen.update_role');

// Route::get('/test-spatie', function () {
//     $user = User::where('username', 'dosen001')->first(); // Sesuaikan dengan username yang ada

//     // Tambahkan role dan permission
//     return [
//         'has_dosen_role' => $user->hasRole('dosen'),
//         'can_edit_post' => $user->can('edit-profil')
//     ];
// }

// );
