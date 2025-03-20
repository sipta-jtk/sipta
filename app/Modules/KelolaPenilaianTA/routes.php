<?php

use App\Modules\KelolaPenilaianTA\Controllers\FormulirPenilaianController;
use Illuminate\Support\Facades\Route;
use App\Modules\KelolaPenilaianTA\Controllers\MonitoringNilaiMahasiswaController;
use App\Modules\KelolaPenilaianTA\Controllers\PemberianNilaiDanFeedbackController;
use App\Modules\KelolaPenilaianTA\Controllers\PengelolaanNilaiController;
use App\Modules\KelolaPenilaianTA\Controllers\RekapitulasiNilaiController;
use Illuminate\Support\Facades\DB;

Route::group(['prefix' => 'kelola-penilaian-ta'], function () {

    // ================= FORMULIR PENILAIAN =================
    Route::prefix('formulir-penilaian')->group(function () {
        // Route::get('/', [FormulirPenilaianController::class, 'getFormPenilaian']);
        Route::get('/', [FormulirPenilaianController::class, 'getFormPenilaian'])->name('formulir-penilaian.index');
        Route::get('/tambah-rubrik-penilaian', [FormulirPenilaianController::class, 'tambahRubrikPenilaian'])->name('formulir-penilaian.tambah-rubrik-penilaian');
        Route::get('/tambah-aspek-formulir', [FormulirPenilaianController::class, 'tambahAspekFormulir'])->name('formulir-penilaian.tambah-aspek-formulir');
        // Route::post('/kelola-penilaian-ta/formulir-penilaian/tambah-aspek-formulir', [FormulirPenilaianController::class, 'simpanAspekFormulir'])->name('formulir-penilaian.simpan');
        // Route::post('/tambah-aspek-formulir', [FormulirPenilaianController::class, 'simpanAspekFormulir'])->name('formulir-penilaian.simpan');
        Route::post('/formulir-penilaian/simpan', [FormulirPenilaianController::class, 'store'])->name('formulir-penilaian.simpan');

        Route::get('/detail', function () {
            return view('KelolaPenilaianTA.views.formulir-penilaian.detail_fta_011');})->name('formulir-penilaian.detail');
        Route::get('/ubah-formulir', function () {
            return view('KelolaPenilaianTA.views.formulir-penilaian.ubah_formulir_ta');})->name('formulir-penilaian.edit');
        Route::put('/update', [FormulirPenilaianController::class, 'updateFormulir'])->name('formulir-penilaian.update');

        Route::get('/ubah-aspek-penilaian/{id}', [FormulirPenilaianController::class, 'ubahAspek'])->name('aspek-penilaian.edit');
        Route::put('/update-aspek-penilaian/{id}', [FormulirPenilaianController::class, 'updateAspek'])->name('aspek-penilaian.update');
        
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
    Route::get('/pengelolaan-nilai', [PengelolaanNilaiController::class, 'kelolaNilai']);

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


    // ================= PEMBERIAN NILAI DAN FEEDBACK =================
    Route::prefix('nilai-seminar')->group(function () {
        Route::get('{id}', [PengelolaanNilaiController::class, 'detailNilaiMahasiswa']);
        Route::get('/{id}/masukan/{kota}', [PemberianNilaiDanFeedbackController::class, 'pengisianMasukanSeminar']);
        Route::get('/{id}/nilai/{kota}', [PemberianNilaiDanFeedbackController::class, 'pengisianNilaiSeminar']);
        Route::post('/{id}/nilai/{kota}/tambah', [PemberianNilaiDanFeedbackController::class, 'simpanNilaiSeminar']);
        Route::get('/{id}/nilai/{kota}/tambah', [PemberianNilaiDanFeedbackController::class, 'simpanNilaiSeminar']);
        Route::patch('/{id}/nilai/{kota}/edit', [PemberianNilaiDanFeedbackController::class, 'editNilaiSeminar']);
        Route::get('/{id}/nilai/{kota}/edit', [PemberianNilaiDanFeedbackController::class, 'editNilaiSeminar']);
    });

    Route::prefix('nilai-sidang')->group(function () {
        Route::get('/akhir/nilai', [PemberianNilaiDanFeedbackController::class, 'pengisianNilaiSidangAkhir']);
        Route::get('/akhir/masukan', [PemberianNilaiDanFeedbackController::class, 'pengisianMasukanSidangAkhir']);
    });
    Route::get('/nilai-tugas-akhir', [PemberianNilaiDanFeedbackController::class, 'pengisianNilaiTA']);
    
});
