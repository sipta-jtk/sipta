<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanAlokasiPembimbingController;

Route::group(['prefix' => 'PengajuanAlokasiPembimbing'], function () {
    // Route::get('/', [PengajuanAlokasiPembimbingController::class, 'index']);
    Route::get('/kesediaan-membimbing', [PengajuanAlokasiPembimbingController::class, 'view_kesediaanMembimbing']);
});

Route::group(['prefix' => 'PengajuanAlokasiPembimbing'], function () {
    // Route::get('/', [PengajuanAlokasiPembimbingController::class, 'index']);
    Route::get('/daftar-pengajuan-dosbing', [PengajuanAlokasiPembimbingController::class, 'view_daftarPengajuanDosbing']);
});