<?php

use App\Modules\PengajuanAlokasiPembimbing\Controllers\KesediaanBimbinganController;
use Illuminate\Support\Facades\Route;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanAlokasiPembimbingController;

Route::group(['prefix' => 'PengajuanAlokasiPembimbing', 'as' => 'pengajuanalokasipembimbing.'], function () {
    Route::group(['prefix' => 'kesediaan-membimbing', 'as' => 'kesediaan-membimbing.'], function () {
        Route::get('/minat-bidang', [KesediaanBimbinganController::class, 'view_minatTopik'])->name('minat-bidang');
        Route::get('/jumlah-mahasiswa', [KesediaanBimbinganController::class, 'view_kuotaMahasiswa'])->name('jumlah-mahasiswa');
        Route::get('/jadwal', [KesediaanBimbinganController::class, 'view_jadwal'])->name('jadwal');
    });
});