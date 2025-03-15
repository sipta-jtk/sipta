<?php

use App\Exports\RekapitulasiNilaiExport;
use App\Modules\KelolaPenilaianTA\Controllers\FormulirPenilaianController;
use Illuminate\Support\Facades\Route;
use App\Modules\KelolaPenilaianTA\Controllers\MonitoringNilaiMahasiswaController;
use App\Modules\KelolaPenilaianTA\Controllers\PemberianNilaiDanFeedbackController;
use App\Modules\KelolaPenilaianTA\Controllers\PengelolaanNilaiController;
use App\Modules\KelolaPenilaianTA\Controllers\RekapitulasiNilaiController;

Route::group(['prefix' => 'kelola-penilaian-ta'], function () {

    // ================= FORMULIR PENILAIAN =================
    Route::prefix('formulir-penilaian')->group(function () {
        Route::get('/', [FormulirPenilaianController::class, 'formulirPenilaian']);
        Route::get('/tambah-formulir', [FormulirPenilaianController::class, 'tambahFormulir'])->name('formulir-penilaian.tambah-formulir');
        Route::get('/detail', function () {
            return view('KelolaPenilaianTA.views.formulir-penilaian.detail_fta_011');})->name('formulir-penilaian.detail');
        Route::get('/ubah-formulir', function () {
            return view('KelolaPenilaianTA.views.formulir-penilaian.ubah_formulir_ta');})->name('formulir-penilaian.edit');
        Route::put('/update', [FormulirPenilaianController::class, 'updateFormulir'])->name('formulir-penilaian.update');
    });


    // Route yang dikomentari
    // Route::get('KelolaPenilaianTA/formulir-penilaian/edit', [FormulirPenilaianController::class, 'edit'])->name('formulir-penilaian.edit');
    // Route::put('formulir-penilaian/{id}', [FormulirPenilaianController::class, 'update'])->name('formulir-penilaian.update');

    // ================= MONITORING NILAI MAHASISWA =================
    Route::prefix('monitoring')->group(function () {
        Route::get('/mahasiswa', [MonitoringNilaiMahasiswaController::class, 'monitoringMahasiswa'])->name('monitoring.mahasiswa');
        Route::get('/feedback', [MonitoringNilaiMahasiswaController::class, 'monitoringFeedback'])->name('monitoring.feedback');
        Route::get('/rubrik', [MonitoringNilaiMahasiswaController::class, 'monitoringRubrik'])->name('monitoring.rubrik');
    });

    // ================= PENGELOLAAN NILAI =================
    Route::get('/pengelolaan-nilai', [PengelolaanNilaiController::class, 'kelolaNilai']);
    Route::get('/detail/{kategori}', [PengelolaanNilaiController::class, 'detailNilaiMahasiswa']);

    // ================= REKAPITULASI NILAI =================
    Route::get('/rekapitulasi-nilai-sidang', [RekapitulasiNilaiController::class, 'getRekapNilaiSidang']);
    Route::get('/rekapitulasi-nilai-akhir', [RekapitulasiNilaiController::class, 'getRekapNilaiAkhir']);
    Route::post('/rekapitulasi-nilai/export', [RekapitulasiNilaiController::class, 'exportExcelNilaiSidang'])->name('rekapitulasi-nilai.export');
    Route::get('/rekapitulasi/export', [RekapitulasiNilaiController::class, 'exportExcelNilaiSidang'])->name('rekapitulasi.export');

    // ================= PEMBERIAN NILAI DAN FEEDBACK =================
    Route::prefix('nilai-seminar')->group(function () {
        Route::get('/1/masukan', [PemberianNilaiDanFeedbackController::class, 'pengisianMasukanSeminar1']);
        Route::get('/2', [PemberianNilaiDanFeedbackController::class, 'pengisianNilaiSeminarII']);
        Route::get('/2/masukan', [PemberianNilaiDanFeedbackController::class, 'pengisianMasukanSeminarII']);
        Route::get('/3', [PemberianNilaiDanFeedbackController::class, 'pengisianNilaiSeminarIII']);
        Route::get('/3/masukan', [PemberianNilaiDanFeedbackController::class, 'pengisianMasukanSeminarIII']);
    });
    Route::prefix('nilai-sidang')->group(function () {
        Route::get('/akhir', [PemberianNilaiDanFeedbackController::class, 'pengisianNilaiSidangAkhir']);
        Route::get('/akhir/masukan', [PemberianNilaiDanFeedbackController::class, 'pengisianMasukanSidangAkhir']);
    });
    Route::get('/nilai-tugas-akhir', [PemberianNilaiDanFeedbackController::class, 'pengisianNilaiTA']);
    
});
