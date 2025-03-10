<?php

use App\Modules\UserManagement\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use App\Modules\UserManagement\Controllers\PengajuanKoTAController;
use App\Modules\UserManagement\Controllers\KonfirmasiKoTAController;
use App\Modules\UserManagement\Controllers\DetailKoTAController;

Route::group(['prefix' => 'user_management'], function () {
    Route::get('/', [UserManagementController::class, 'render']);
});

Route::get('/pengajuan-kota', [PengajuanKoTAController::class, 'index'])->name('pengajuan-kota');
Route::post('/pengajuan-kota', [PengajuanKoTAController::class, 'submit'])->name('pengajuan-kota.submit');
Route::get('/konfirmasi-kota', [KonfirmasiKoTAController::class, 'index'])->name('konfirmasi-kota');
Route::get('/detail-kota', [DetailKoTAController::class, 'index'])->name('detail-kota');