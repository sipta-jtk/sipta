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
        // Aspek Penilaian
        Route::get('/tambah-aspek-formulir', [FormulirPenilaianController::class, 'tambahAspekFormulir'])->name('formulir-penilaian.tambah-aspek-formulir');
        Route::get('/ubah-aspek-penilaian/{id}', [FormulirPenilaianController::class, 'ubahAspek'])->name('aspek-penilaian.edit');
        Route::put('/update-aspek-penilaian/{id}', [FormulirPenilaianController::class, 'updateAspek'])->name('aspek-penilaian.update');

        // Rubrik Penilaian
        Route::get('/tambah-rubrik-penilaian', [FormulirPenilaianController::class, 'tambahRubrikPenilaian'])->name('formulir-penilaian.tambah-rubrik-penilaian');
        Route::post('/tambah-rubrik-penilaian', [FormulirPenilaianController::class, 'storeRubrik'])->name('formulir-penilaian.store-rubrik');
        Route::get('/ubah-rubrik/{idFta}', [FormulirPenilaianController::class, 'ubahFormRubrik'])->name('formulir-penilaian.rubrik.edit');
        Route::post('/update-rubrik-penilaian', [FormulirPenilaianController::class, 'updateRubrik'])->name('formulir-penilaian.update-rubrik');

        // Detail
        Route::get('/detail-penilaian/{idFta}/{idProdi}', [FormulirPenilaianController::class, 'viewDetailPenilaian'])->name('detail.penilaian');
        Route::get('/detail-feedback/{idFta}/{idProdi}', [FormulirPenilaianController::class, 'viewDetailFeedback'])->name('detail.feedback');

        // Formulir Penilaian
        Route::get('/', [FormulirPenilaianController::class, 'getFormPenilaian'])->name('formulir-penilaian.index');
        Route::post('/formulir-penilaian/simpan', [FormulirPenilaianController::class, 'store'])->name('formulir-penilaian.simpan');
        Route::get('/ubah-formulir/{id}', [FormulirPenilaianController::class, 'edit'])->name('formulir-penilaian.edit');
        Route::put('/update-formulir/{id}', [FormulirPenilaianController::class, 'update'])->name('formulir-penilaian.update');

        // Additional Routes
        Route::get('/get-kriteria/{kodeFTA}', [FormulirPenilaianController::class, 'getKriteriaByKodeFTA']);
        Route::get('/kelola-rubrik', [FormulirPenilaianController::class, 'viewTabelRubrik'])->name('tabelRubrik');
        Route::get('/tambah-rubrik/{idFta}', [FormulirPenilaianController::class, 'tambahFormRubrik'])->name('formulir-penilaian.rubrik.tambah');
    });

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
    Route::prefix('pengelolaan-nilai')->middleware(['auth', 'can:akses-penilaian-koordinator-ta'])->group(function () {
        Route::get('/', [PengelolaanNilaiController::class, 'kelolaNilai'])->name('kelola.penilaian');
        Route::get('/{namaFta}/{idProdi}', [PengelolaanNilaiController::class, 'detailNilaiMahasiswa'])->name('kelola.penilaian.detail');
        Route::post('/{namaFta}/{idKota}/{action}/toggle-publish', [PengelolaanNilaiController::class, 'togglePublishNilai'])->name('kelola.penilaian.toggle-publish');
        Route::post('/{idKategori}/{action}/toggle-kunci', [PengelolaanNilaiController::class, 'toggleKunciPenilaian'])->name('kelola.penilaian.toggle-kunci');
    });

    Route::post('/import-nilai', [PemberianNilaiController::class, 'importNilai'])->name('import.nilai');

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
        // Route::get('/{id}', [PengelolaanNilaiController::class, 'detailNilaiMahasiswa'])->name('pengelolaan-nilai.detail');
        Route::get('/nilai/{namaFta}/masukan/{idKota}', [PemberianFeedbackController::class, 'pengisianMasukanSeminar'])->name('pengisian.masukan');
        Route::post('/nilai/{namaFta}/masukan/{idKota}/tambah', [PemberianFeedbackController::class, 'simpanMasukanSeminar'])->name('pengisian.masukan.store');
        Route::post('/nilai/{namaFta}/masukan/{idKota}/edit', [PemberianFeedbackController::class, 'ubahMasukanSeminar'])->name('pengisian.masukan.edit');
    });

    // ================= PEMBERIAN NILAI =================
    Route::prefix('nilai-seminar')->middleware(['auth', 'can:akses-penilaian-koordinator-ta'])->group(function () {
        Route::get('/nilai/{namaFta}/{idKota}/{idProdi}', [PemberianNilaiController::class, 'pengisianNilaiSeminar'])->name('pengisian.nilai');
        Route::post('/nilai/{namaFta}/{idKota}/tambah', [PemberianNilaiController::class, 'simpanNilaiSeminar'])->name('pengisian.nilai.store');
        Route::patch('/nilai/{namaFta}/{idKota}/edit', [PemberianNilaiController::class, 'ubahNilaiSeminar'])->name('pengisian.nilai.edit');
    });

    Route::prefix('nilai-sidang')->group(function () {
        Route::get('/akhir/nilai', [PemberianNilaiDanFeedbackController::class, 'pengisianNilaiSidangAkhir'])->name('pengisian.nilai-sidang-akhir');
        Route::get('/akhir/masukan', [PemberianNilaiDanFeedbackController::class, 'pengisianMasukanSidangAkhir'])->name('pengisian.masukan-sidang-akhir');
    });
    Route::get('/nilai-tugas-akhir', [PemberianNilaiDanFeedbackController::class, 'pengisianNilaiTA'])->name('pengisian.nilai-tugas-akhir');
    
});
