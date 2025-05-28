<?php

use App\Modules\PengajuanAlokasiPembimbing\Controllers\DaftarKesediaanMembimbingController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\KesediaanBimbinganController;
use Illuminate\Support\Facades\Route;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\AlokasiPembimbingv2Controller;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\AlokasiPengujiController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\DaftarPengajuanDosbingController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\MahasiswaMelihatJadwalController;
use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengajuanPembimbing\PengajuanPembimbingController;

use App\Modules\PengajuanAlokasiPembimbing\Controllers\PengelolaanPeriodeController;


Route::group(['prefix' => 'PengajuanAlokasiPembimbing', 'as' => 'pengajuanalokasipembimbing.'], function () {

    Route::group(['prefix' => 'kesediaan-membimbing', 'as' => 'kesediaan-membimbing.', 'middleware' => ['auth', 'can:dosen']], function () {

        Route::post('/konfirmasi-kesediaan/{value}', [KesediaanBimbinganController::class, 'konfirmasi_kesediaan'])->name('konfirmasi-kesediaan');

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


    Route::group(['prefix' => 'daftar-kesediaan-membimbing', 'middleware' => ['auth', 'can:koordinator_ta'] ], function () {
        Route::get('/', [DaftarKesediaanMembimbingController::class, 'view_daftarKesediaanMembimbing']);
    });

    Route::group(['prefix' => 'jadwal-dosen-membimbing', 'middleware' => ['auth', 'can:mahasiswa_ta']], function () {
        Route::get('/', [MahasiswaMelihatJadwalController::class, 'view_MahasiswaMelihatJadwal']);
    });

    Route::group([
        'prefix' => 'alokasi-pembimbing',
        'as' => 'alokasi-pembimbing.',
        'middleware' => ['auth', 'can:akses-alokasi']
    ], function () {
        Route::get('/', [AlokasiPembimbingv2Controller::class, 'index'])->name('index');
        Route::get('/getDetailDosen', [AlokasiPembimbingv2Controller::class, 'getDetailDosen'])->name('getDetailDosen');
        Route::post('/fix-alokasi', [AlokasiPembimbingv2Controller::class, 'fixAlokasi'])->name('fixAlokasi');
        Route::post('/update-alokasi', [AlokasiPembimbingv2Controller::class, 'updateAlokasi'])->name('updateAlokasi');
        Route::post('/delete-alokasi', [AlokasiPembimbingv2Controller::class, 'deleteAlokasi'])->name('deleteAlokasi');
    });

    Route::group([
        'prefix' => 'alokasi-penguji',
        'as' => 'alokasi-penguji.',
        'middleware' => ['auth', 'can:akses-alokasi']
    ], function () {
        Route::get('/', [AlokasiPengujiController::class, 'index'])->name('index');
        Route::get('/getDetailDosen', [AlokasiPengujiController::class, 'getDetailDosen'])->name('getDetailDosen');
        Route::post('/fix-alokasi', [AlokasiPengujiController::class, 'fixAlokasi'])->name('fixAlokasi');
        Route::post('/update-alokasi', [AlokasiPengujiController::class, 'updateAlokasi'])->name('updateAlokasi');
        Route::post('/delete-alokasi', [AlokasiPengujiController::class, 'deleteAlokasi'])->name('deleteAlokasi');
    });

    


    Route::group(['prefix' => 'pengajuan-pembimbing', 'as' => 'pengajuan-pembimbing.', 'middleware' => ['auth', 'can:mahasiswa_kota']], function () {
        Route::get('/data-kelompok', [PengajuanPembimbingController::class, 'view_dataKelompok'])->name('data-kelompok');
        Route::get('/topik-tugas-akhir', [PengajuanPembimbingController::class, 'view_topikTugasAkhir'])->name('topik-tugas-akhir');
        Route::get('/check-existing-data', [PengajuanPembimbingController::class, 'checkExistingData'])->name('checkExistingData');

        Route::group(['prefix' => 'prioritas-dosen-pembimbing', 'as' => 'prioritas-dosen-pembimbing.'], function () {
            Route::get('/', [PengajuanPembimbingController::class, 'view_prioritasDosenPembimbing'])->name('index');
            Route::get('/dosen/history/{nip}', [PengajuanPembimbingController::class, 'getDosenHistory'])->name('dosen-history');
        });

        Route::group(['prefix' => 'pratinjau-formulir', 'as' => 'pratinjau-formulir.'], function () {
            Route::get('/', [PengajuanPembimbingController::class, 'view_pratinjauFormulir'])->name('index');
            Route::post('/finalisasi', [PengajuanPembimbingController::class, 'finalisasiData'])->name('finalisasi');
        });
    });

    Route::group(['prefix' => 'daftar-pengajuan-dosbing', 'as' => 'daftar-pengajuan-dosbing.', 'middleware' => ['auth', 'can:dosen']], function () {
        Route::get('/', [DaftarPengajuanDosbingController::class, 'view_daftarPengajuanDosbing'])->name('index');
        Route::post('/pengajuan/{id}/{action}', [DaftarPengajuanDosbingController::class, 'handlePengajuan'])->name('handlePengajuan');
    });
    


    Route::group(['prefix' => 'pengelolaan-periode', 'as' => 'pengelolaan-periode.','middleware' => ['auth', 'can:koordinator_ta']], function () {
        Route::get('/', [PengelolaanPeriodeController::class, 'view_PengelolaanPeriode'])->name('index');
        Route::post('/{mode}', [PengelolaanPeriodeController::class, 'save_PengelolaanPeriode'])->name('store');
        Route::delete('/{id}', [PengelolaanPeriodeController::class, 'delete_PengelolaanPeriode'])->name('delete');
    });


});

Route::get('/redirect-data-kelompok', function () {
    return redirect()->route('pengajuanalokasipembimbing.pengajuan-pembimbing.data-kelompok');
})->middleware(['auth', 'can:mahasiswa_kota'])->name('data-kelompok');