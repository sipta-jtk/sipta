<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanAlokasiPembimbingController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\DaftarPengajuanDosbingController;


Route::group(['prefix' => 'PengajuanAlokasiPembimbing'], function () {
    Route::group(['prefix' => 'kesediaan-membimbing'], function () {
        Route::get('/minat-bidang', [PengajuanAlokasiPembimbingController::class, 'view_minatTopik']);
    });
});

Route::group(['prefix' => 'DaftarPengajuanDosbing'], function () {
    Route::get('/', [DaftarPengajuanDosbingController::class, 'view_daftarPengajuanDosbing']);
});