<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanAlokasiPembimbingController;

Route::group(['prefix' => 'PengajuanAlokasiPembimbing'], function () {
    // Route::get('/', [PengajuanAlokasiPembimbingController::class, 'index']);
    Route::get('/kesediaan-membimbing', [PengajuanAlokasiPembimbingController::class, 'view_kesediaanMembimbing']);
});