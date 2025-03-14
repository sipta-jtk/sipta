<?php

use App\Modules\PengajuanAlokasiPembimbing\Components\DaftarKesediaanMembimbing\DaftarKesediaanMembimbing;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\DaftarKesediaanMembimbingController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\KesediaanBimbinganController;
use Illuminate\Support\Facades\Route;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\AlokasiPembimbingController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanAlokasiPembimbingController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\DaftarPengajuanDosbingController;

use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanPembimbing\PengajuanPembimbingController;

use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengelolaanPeriodeController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\RekapFTA02Controller;


Route::group(['prefix' => 'PengajuanAlokasiPembimbing', 'as' => 'pengajuanalokasipembimbing.'], function () {
    
    Route::group(['prefix' => 'kesediaan-membimbing', 'as' => 'kesediaan-membimbing.'], function () {
        Route::post('/next/{previous}/{target}', [KesediaanBimbinganController::class, 'next_page'])->name('next');

        Route::group(['prefix' => 'minat-bidang', 'as' => 'minat-bidang.'], function () {
            Route::get('/', [KesediaanBimbinganController::class, 'view_minatTopik'])->name('index');
            Route::post('/store', [KesediaanBimbinganController::class, 'save_minatTopik'])->name('store');
            Route::post('/add', [KesediaanBimbinganController::class, 'create_bidang'])->name('add');
        });
        Route::group(['prefix' => 'jumlah-mahasiswa', 'as' => 'jumlah-mahasiswa.'], function () {
            Route::get('/', [KesediaanBimbinganController::class, 'view_kuotaMahasiswa'])->name('index');
            Route::post('/store', [KesediaanBimbinganController::class, 'save_kuotaMahasiswa'])->name('store');
        });
        Route::group(['prefix' => 'jadwal', 'as' => 'jadwal.'], function () {
            Route::get('/', [KesediaanBimbinganController::class, 'view_jadwal'])->name('index');
            Route::post('/store', [KesediaanBimbinganController::class, 'save_jadwal'])->name('store');
        });
    });

    //add routes for daftar kesediaan membimbing
    Route::group(['prefix' => 'daftar-kesediaan-membimbing'], function () {
        Route::get('/', [DaftarKesediaanMembimbingController::class, 'view_daftarKesediaanMembimbing']);
    });
    Route::get('/alokasi-pembimbing', [AlokasiPembimbingController::class, 'index'])->name('alokasi-pembimbing.index');
    Route::post('/alokasi-pembimbing/submit', [AlokasiPembimbingController::class, 'submit'])->name('alokasi-pembimbing.submit');
    Route::post('/alokasi-pembimbing/simpan', [AlokasiPembimbingController::class, 'simpanDraft'])->name('alokasi-pembimbing.simpan');

    Route::group(['prefix' => 'pengajuan-pembimbing', 'as' => 'pengajuan-pembimbing.'], function () {
        Route::get('/data-kelompok', [PengajuanPembimbingController::class, 'view_dataKelompok']) -> name('data-kelompok');
        Route::get('/topik-tugas-akhir', [PengajuanPembimbingController::class, 'view_topikTugasAkhir']) -> name('topik-tugas-akhir');
        Route::get('/prioritas-dosen-pembimbing', [PengajuanPembimbingController::class, 'view_prioritasDosenPembimbing']) -> name('prioritas-dosen-pembimbing');
        Route::get('/pratinjau-formulir', [PengajuanPembimbingController::class, 'view_pratinjauFormulir']) -> name('pratinjau-formulir');
    });

    Route::group(['prefix' => 'DaftarPengajuanDosbing'], function () {
        Route::get('/', [DaftarPengajuanDosbingController::class, 'view_daftarPengajuanDosbing']);
    });

    Route::group(['prefix' => 'pengelolaan-periode', 'as' => 'pengelolaan-periode.'], function () {
        Route::get('/', [PengelolaanPeriodeController::class, 'view_PengelolaanPeriode'])->name('index');
        Route::post('/{mode}', [PengelolaanPeriodeController::class, 'save_PengelolaanPeriode'])->name('store');
        Route::delete('/{id}', [PengelolaanPeriodeController::class, 'delete_PengelolaanPeriode'])->name('delete');
    });
    
    Route::group(['prefix' => 'RekapFTA02'], function () {
        Route::get('/', [RekapFTA02Controller::class, 'view_rekapFTA02']);
    });

});
