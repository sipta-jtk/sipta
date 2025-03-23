<?php

use App\Modules\KelolaPenilaianTA\Controllers\FormulirPenilaianController;
use Illuminate\Support\Facades\Route;
use App\Modules\KelolaPenilaianTA\Controllers\MonitoringNilaiMahasiswaController;
use App\Modules\KelolaPenilaianTA\Controllers\PemberianNilaiDanFeedbackController;
use App\Modules\KelolaPenilaianTA\Controllers\PemberianFeedbackController;
use App\Modules\KelolaPenilaianTA\Controllers\PengelolaanNilaiController;
use App\Modules\KelolaPenilaianTA\Controllers\RekapitulasiNilaiController;
use App\Modules\KelolaPenilaianTA\Controllers\PemberianNilaiController;
use Illuminate\Support\Facades\DB;

Route::group(['prefix' => 'kelola-penilaian-ta'], function () {

    // ================= FORMULIR PENILAIAN =================
    Route::prefix('formulir-penilaian')->middleware('auth', 'can:akses-penilaian-koordinator-ta')->group(function () {
        // Route::get('/', [FormulirPenilaianController::class, 'getFormPenilaian']);
        Route::get('/', [FormulirPenilaianController::class, 'getFormPenilaian'])->name('formulir-penilaian.index');
        Route::get('/tambah-rubrik-penilaian', [FormulirPenilaianController::class, 'tambahRubrikPenilaian'])->name('formulir-penilaian.tambah-rubrik-penilaian');
        Route::get('/tambah-aspek-formulir', [FormulirPenilaianController::class, 'tambahAspekFormulir'])->name('formulir-penilaian.tambah-aspek-formulir');
        Route::post('/formulir-penilaian/simpan', [FormulirPenilaianController::class, 'store'])->name('formulir-penilaian.simpan');

        Route::get('/ubah-formulir/{id}', [FormulirPenilaianController::class, 'edit'])->name('formulir-penilaian.edit');
        Route::put('/update-formulir/{id}', [FormulirPenilaianController::class, 'update'])->name('formulir-penilaian.update');
    
        Route::get('/ubah-aspek-penilaian/{id}', [FormulirPenilaianController::class, 'ubahAspek'])->name('aspek-penilaian.edit');
        Route::put('/aspek-penilaian/{id}', [FormulirPenilaianController::class, 'update'])->name('aspek-penilaian.update');
        Route::put('/update-aspek-penilaian/{id}', [FormulirPenilaianController::class, 'updateAspek'])->name('aspek-penilaian.update');

        Route::get('/get-kriteria/{kodeFTA}', [FormulirPenilaianController::class, 'getKriteriaByKodeFTA']);

        Route::get('/detail-penilaian/{idFta}/{idProdi}', [FormulirPenilaianController::class, 'viewDetailPenilaian'])
        ->name('detail.penilaian');
        Route::get('/detail-feedback/{idFta}/{idProdi}', [FormulirPenilaianController::class, 'viewDetailFeedback'])
        ->name('detail.feedback');

        Route::post('/tambah-rubrik-penilaian', 
        [FormulirPenilaianController::class, 'storeRubrik'])
        ->name('formulir-penilaian.store-rubrik');
        Route::get('/kelola-rubrik', [FormulirPenilaianController::class, 'viewTabelRubrik'])->name('tabelRubrik');
        Route::get('/tambah-rubrik/{idFta}', [FormulirPenilaianController::class, 'tambahFormRubrik'])->name('formulir-penilaian.rubrik.tambah');
        Route::get('/ubah-rubrik/{idFta}', [FormulirPenilaianController::class, 'ubahFormRubrik'])->name('formulir-penilaian.rubrik.edit');
        Route::post('/update-rubrik-penilaian', [FormulirPenilaianController::class, 'updateRubrik'])->name('formulir-penilaian.update-rubrik');
    });


    // Route yang dikomentari
    // Route::get('KelolaPenilaianTA/formulir-penilaian/edit', [FormulirPenilaianController::class, 'edit'])->name('formulir-penilaian.edit');
    // Route::put('formulir-penilaian/{id}', [FormulirPenilaianController::class, 'update'])->name('formulir-penilaian.update');

    // ================= MONITORING NILAI MAHASISWA =================
    Route::prefix('monitoring')->middleware('auth', 'can:akses-penilaian-mahasiswa')->group(function () {
        Route::get('/mahasiswa', [MonitoringNilaiMahasiswaController::class, 'monitoringMahasiswa'])
        ->name('monitoring.mahasiswa');
        Route::get('/feedback/{id_fta}/{id_kota}', [MonitoringNilaiMahasiswaController::class, 'monitoringFeedback'])
        ->name('monitoring.feedback');
        Route::get('/rubrik/{kodeFta}/{idProdi}', [MonitoringNilaiMahasiswaController::class, 'monitoringRubrik'])
        ->name('monitoring.rubrik');
    });

   // ================= PENGELOLAAN NILAI =================
    Route::get('/pengelolaan-nilai', [PengelolaanNilaiController::class, 'kelolaNilai'])->middleware(['auth', 'can:akses-penilaian-koordinator-ta']);


    Route::middleware(['auth', 'can:akses-penilaian-koordinator-ta'])->group(function () {
        // ================= REKAPITULASI NILAI =================
        Route::get('/rekapitulasi-nilai-sidang', [RekapitulasiNilaiController::class, 'getRekapNilaiSidang']);
        Route::get('/rekapitulasi-nilai-akhir', [RekapitulasiNilaiController::class, 'getRekapNilaiAkhir']);
        Route::post('/rekapitulasi-nilai/export', [RekapitulasiNilaiController::class, 'exportExcelNilaiSidang'])->name('rekapitulasi-nilai.export');
        Route::get('/rekapitulasi/export', [RekapitulasiNilaiController::class, 'exportExcelNilaiSidang'])->name('rekapitulasi.export');
        Route::post('/rekapitulasi-nilai-akhir/export', [RekapitulasiNilaiController::class, 'exportExcelNilaiAkhir'])->name('rekapitulasi-nilai-akhir.export');
        Route::get('/rekapitulasi-akhir/export', [RekapitulasiNilaiController::class, 'exportExcelNilaiAkhir'])->name('rekapitulasi-akhir.export');
        Route::get('/pengaturan-nilai-akhir', [RekapitulasiNilaiController::class, 'getPengaturanNilaiAkhir']);
        Route::put('/pengaturan-nilai/update', [RekapitulasiNilaiController::class, 'updatePengaturanNilaiAkhir'])->name('pengaturan-bobot.update');
    });


    // ================= PEMBERIAN FEEDBACK =================
    Route::prefix('nilai-seminar')->middleware(['auth', 'can:akses-penilaian-koordinator-ta'])->group(function () {
        Route::get('{id}', [PengelolaanNilaiController::class, 'detailNilaiMahasiswa']);
        Route::get('/{id}/masukan/{kota}', [PemberianFeedbackController::class, 'pengisianMasukanSeminar']);
        Route::post('/{id}/masukan/{kota}/tambah', [PemberianFeedbackController::class, 'simpanMasukanSeminar'])->name('pengisian.masukan.store');
    });

        // ================= PEMBERIAN NILAI DAN FEEDBACK =================
        Route::prefix('nilai-seminar')->middleware(['auth', 'can:akses-penilaian-koordinator-ta'])->group(function () {
            Route::get('/{id}/nilai/{kota}', [PemberianNilaiController::class, 'pengisianNilaiSeminar']);
            Route::post('/{id}/nilai/{kota}/tambah', [PemberianNilaiController::class, 'simpanNilaiSeminar']);
            Route::patch('/{id}/nilai/{kota}/edit', [PemberianNilaiController::class, 'editNilaiSeminar']);
        });

    Route::prefix('nilai-sidang')->group(function () {
        Route::get('/akhir/nilai', [PemberianNilaiDanFeedbackController::class, 'pengisianNilaiSidangAkhir']);
        Route::get('/akhir/masukan', [PemberianNilaiDanFeedbackController::class, 'pengisianMasukanSidangAkhir']);
    });
    Route::get('/nilai-tugas-akhir', [PemberianNilaiDanFeedbackController::class, 'pengisianNilaiTA']);
    
});
