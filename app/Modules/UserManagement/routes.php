<?php

use App\Modules\UserManagement\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use App\Modules\UserManagement\Controllers\PengajuanCeraiKoTAController;
use App\Modules\UserManagement\Controllers\FormCeraiKoTAController;

Route::group(['prefix' => 'user_management'], function () {
    Route::get('/user_management', [UserManagementController::class, 'render']);
});

Route::get('/pengajuan-cerai-kota', [PengajuanCeraiKoTAController::class, 'index'])->name('pengajuan.cerai.kota');
Route::get('/pengajuan-cerai-kota/{id}', [PengajuanCeraiKoTAController::class, 'show'])->name('pengajuan.cerai.kota.show');
Route::get('/form-cerai-kota', [FormCeraiKoTAController::class, 'index'])->name('form.cerai.kota');
Route::post('/form-cerai-kota', [FormCeraiKoTAController::class, 'submit'])->name('form.cerai.kota.submit');
