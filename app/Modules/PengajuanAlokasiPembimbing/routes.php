<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanAlokasiPembimbingController;

Route::group(['prefix' => 'PengajuanAlokasiPembimbing'], function () {
    Route::group(['prefix' => 'kesediaan-membimbing'], function () {
        Route::get('/minat-bidang', [PengajuanAlokasiPembimbingController::class, 'view_minatTopik']);
    });
    Route::group(['prefix' => 'daftar-pengajuan-dosbing'], function () {
        Route::get('/', [PengajuanAlokasiPembimbingController::class, 'view_daftarPengajuanDosbing']);
    });
});