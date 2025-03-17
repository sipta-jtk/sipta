<?php

<<<<<<< HEAD
use App\Modules\PengajuanAlokasiPembimbing\Components\DaftarKesediaanMembimbing\DaftarKesediaanMembimbing;
=======
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
use App\Modules\PengajuanAlokasiPembimbing\Controllers\DaftarKesediaanMembimbingController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\KesediaanBimbinganController;
use Illuminate\Support\Facades\Route;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\AlokasiPembimbingController;
<<<<<<< HEAD
use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanAlokasiPembimbingController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\DaftarPengajuanDosbingController;

use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanPembimbing\PengajuanPembimbingController;

use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengelolaanPeriodeController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\RekapFTA02Controller;


Route::group(['prefix' => 'PengajuanAlokasiPembimbing', 'as' => 'pengajuanalokasipembimbing.'], function () {
    
    Route::group(['prefix' => 'kesediaan-membimbing', 'as' => 'kesediaan-membimbing.'], function () {
=======
use App\Modules\PengajuanAlokasiPembimbing\Controllers\DaftarPengajuanDosbingController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\MahasiswaMelihatJadwalController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanPembimbing\PengajuanPembimbingController;

use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengelolaanPeriodeController;


Route::group(['prefix' => 'PengajuanAlokasiPembimbing', 'as' => 'pengajuanalokasipembimbing.'], function () {

    Route::group(['prefix' => 'kesediaan-membimbing', 'as' => 'kesediaan-membimbing.'], function () {

        Route::post('/konfirmasi-kesediaan/{value}', [KesediaanBimbinganController::class, 'konfirmasi_kesediaan'])->name('konfirmasi-kesediaan');

>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
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
<<<<<<< HEAD
=======

    Route::group(['prefix' => 'jadwal-dosen-membimbing'], function () {
        Route::get('/', [MahasiswaMelihatJadwalController::class, 'view_MahasiswaMelihatJadwal']);
    });
    

>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
    Route::get('/alokasi-pembimbing', [AlokasiPembimbingController::class, 'index'])->name('alokasi-pembimbing.index');
    Route::post('/alokasi-pembimbing/submit', [AlokasiPembimbingController::class, 'submit'])->name('alokasi-pembimbing.submit');
    Route::post('/alokasi-pembimbing/simpan', [AlokasiPembimbingController::class, 'simpanDraft'])->name('alokasi-pembimbing.simpan');

    Route::group(['prefix' => 'pengajuan-pembimbing', 'as' => 'pengajuan-pembimbing.'], function () {
        Route::get('/data-kelompok', [PengajuanPembimbingController::class, 'view_dataKelompok']) -> name('data-kelompok');
        Route::get('/topik-tugas-akhir', [PengajuanPembimbingController::class, 'view_topikTugasAkhir']) -> name('topik-tugas-akhir');
<<<<<<< HEAD
        Route::get('/prioritas-dosen-pembimbing', [PengajuanPembimbingController::class, 'view_prioritasDosenPembimbing']) -> name('prioritas-dosen-pembimbing');
        Route::get('/pratinjau-formulir', [PengajuanPembimbingController::class, 'view_pratinjauFormulir']) -> name('pratinjau-formulir');
=======

        Route::group(['prefix' => 'prioritas-dosen-pembimbing', 'as' => 'prioritas-dosen-pembimbing.'], function () {
            Route::get('/', [PengajuanPembimbingController::class, 'view_prioritasDosenPembimbing'])->name('index');
            Route::get('/dosen/history/{nip}', [PengajuanPembimbingController::class, 'getDosenHistory'])->name('dosen-history');
        });

        Route::group(['prefix' => 'pratinjau-formulir', 'as' => 'pratinjau-formulir.'], function () {
            Route::get('/', [PengajuanPembimbingController::class, 'view_pratinjauFormulir'])->name('index');
            Route::post('/finalisasi', [PengajuanPembimbingController::class, 'finalisasiData'])->name('finalisasi');
        });
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
    });

    Route::group(['prefix' => 'DaftarPengajuanDosbing'], function () {
        Route::get('/', [DaftarPengajuanDosbingController::class, 'view_daftarPengajuanDosbing']);
<<<<<<< HEAD
    });
=======
        Route::post('/pengajuan/{id}/{action}', [DaftarPengajuanDosbingController::class, 'handlePengajuan']);
    });
    
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b

    Route::group(['prefix' => 'pengelolaan-periode', 'as' => 'pengelolaan-periode.'], function () {
        Route::get('/', [PengelolaanPeriodeController::class, 'view_PengelolaanPeriode'])->name('index');
        Route::post('/{mode}', [PengelolaanPeriodeController::class, 'save_PengelolaanPeriode'])->name('store');
        Route::delete('/{id}', [PengelolaanPeriodeController::class, 'delete_PengelolaanPeriode'])->name('delete');
    });
<<<<<<< HEAD
    
    Route::group(['prefix' => 'RekapFTA02'], function () {
        Route::get('/', [RekapFTA02Controller::class, 'view_rekapFTA02']);
    });
=======

>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b

});
