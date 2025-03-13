<?php

use Illuminate\Support\Facades\Route;
use App\Modules\KelolaPenilaianTA\Controllers\KelolaPenilaianTAController;

Route::group(['prefix' => 'kelola-penilaian-ta'], function () {

    // ================= FORMULIR PENILAIAN =================
    Route::get('/fomulir-penilaian', [KelolaPenilaianTAController::class, 'indexForm']);
    Route::get('/formulir-penilaian/create', [KelolaPenilaianTAController::class, 'create'])->name('formulir-penilaian.create');
    Route::get('/formulir-penilaian/detail', function () {
        return view('KelolaPenilaianTA.views.detail_fta_011');
        return view('KelolaPenilaianTA.views.detail_fta_011');
    })->name('formulir-penilaian.detail');
    Route::get('/formulir-penilaian/edit', function () {
        return view('KelolaPenilaianTA.views.ubah_formulir_ta');
        return view('KelolaPenilaianTA.views.ubah_formulir_ta');
    })->name('formulir-penilaian.edit');
    Route::put('/formulir-penilaian/update', [KelolaPenilaianTAController::class, 'update'])->name('formulir-penilaian.update');
    
    // Route yang dikomentari
    // Route::get('KelolaPenilaianTA/formulir-penilaian/edit', [FormulirPenilaianController::class, 'edit'])->name('formulir-penilaian.edit');
    // Route::put('formulir-penilaian/{id}', [FormulirPenilaianController::class, 'update'])->name('formulir-penilaian.update');

    // ================= MONITORING =================
    Route::get('/monitoring-mahasiswa', [KelolaPenilaianTAController::class, 'indexMonitoringMahasiswa']);
    Route::get('/monitoring-feedback', [KelolaPenilaianTAController::class, 'indexMonitoringFeedback']);
    Route::get('/monitoring-rubrik', [KelolaPenilaianTAController::class, 'indexMonitoringRubrik']);

    // ================= PENGELOLAAN NILAI =================
    Route::get('/pengelolaan-nilai', [KelolaPenilaianTAController::class, 'kelolaNilai']);
    Route::get('/detail/{kategori}', [KelolaPenilaianTAController::class, 'detailNilaiMahasiswa']);

    // ================= REKAPITULASI NILAI =================
    Route::get('/rekapitulasi-nilai', [KelolaPenilaianTAController::class, 'getRekapNilaiSidang']);
    Route::post('/rekapitulasi-nilai/export', [KelolaPenilaianTAController::class, 'exportExcelNilaiSidang'])->name('rekapitulasi-nilai.export');
    Route::get('/rekapitulasi/export', [KelolaPenilaianTAController::class, 'exportExcel'])->name('rekapitulasi.export');

    // ================= PEMBERIAN NILAI DAN FEEDBACK =================
    Route::get('/masukan-seminar-1', [KelolaPenilaianTAController::class, 'pengisianMasukanSeminar1']);
    Route::get('/nilai-seminar-II', [KelolaPenilaianTAController::class, 'pengisianNilaiSeminarII']);
    Route::get('/nilai-seminar-II/masukan-seminar-II', [KelolaPenilaianTAController::class, 'pengisianMasukanSeminarII']);
    Route::get('/nilai-seminar-III', [KelolaPenilaianTAController::class, 'pengisianNilaiSeminarIII']);
    Route::get('/nilai-seminar-III/masukan-seminar-III', [KelolaPenilaianTAController::class, 'pengisianMasukanSeminarIII']);
    Route::get('/nilai-sidang-akhir', [KelolaPenilaianTAController::class, 'pengisianNilaiSidangAkhir']);
    Route::get('/nilai-sidang-akhir/masukan-sidang-akhir', [KelolaPenilaianTAController::class, 'pengisianMasukanSidangAkhir']);
    Route::get('/nilai-tugas-akhir', [KelolaPenilaianTAController::class, 'pengisianNilaiTA']);
});

// ================= HALAMAN UTAMA KELOLA PENILAIAN TA =================
Route::get('/KelolaPenilaianTA', [KelolaPenilaianTAController::class, 'index']);
