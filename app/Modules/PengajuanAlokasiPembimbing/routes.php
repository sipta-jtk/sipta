<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanAlokasiPembimbingController;

Route::group(['prefix' => 'PengajuanAlokasiPembimbing'], function () {
    Route::group(['prefix' => 'kesediaan-membimbing'], function () {
        Route::get('/minat-bidang', [PengajuanAlokasiPembimbingController::class, 'view_minatTopik']);
    });

    //add routes for daftar kesediaan membimbing
    Route::group(['prefix' => 'daftar-kesediaan-membimbing'], function () {
        Route::get('/', [PengajuanAlokasiPembimbingController::class, 'view_daftarKesediaanMembimbing']);
    });
});