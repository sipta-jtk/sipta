<?php

use App\Modules\KelolaPenilaianTA\Controllers\FormulirPenilaianController;
use App\Modules\KelolaPenilaianTA\Controllers\KelolaPenilaianTAController;
use Illuminate\Support\Facades\Route;
use App\Modules\KelolaPenilaianTA\Controllers\MonitoringNilaiMahasiswaController;
use App\Modules\KelolaPenilaianTA\Controllers\PemberianFeedbackController;
use App\Modules\KelolaPenilaianTA\Controllers\PengelolaanNilaiController;
use App\Modules\KelolaPenilaianTA\Controllers\RekapitulasiNilaiController;
use App\Modules\KelolaPenilaianTA\Controllers\PemberianNilaiController;



Route::get('/', [KelolaPenilaianTAController::class, 'getBeranda'])->name('beranda.get');

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
        Route::get('/detail-dosen-pembimbing/{idFta}/{idProdi}', [FormulirPenilaianController::class, 'viewDetailDosenPembimbing'])->name('detail.dosen-pembimbing');

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
        ->name('monitoring.rubrik.mahasiswa');
    });

    Route::prefix('monitoring')->middleware('auth', 'can:akses-monitoring-dosen-pembimbing')->group(function () {
        Route::get('/dosen-pembimbing', [MonitoringNilaiMahasiswaController::class, 'monitoringDosenPembimbing'])
        ->name('monitoring.dosen.pembimbing');
         Route::get('dosen-pembimbing/rubrik/{kodeFta}/{idProdi}', [MonitoringNilaiMahasiswaController::class, 'monitoringRubrik'])
        ->name('monitoring.rubrik.dosen');
        Route::get('dosen-pembimbing/feedback/{id_fta}/{id_kota}', [MonitoringNilaiMahasiswaController::class, 'monitoringFeedback'])
        ->name('monitoring.feedback.dosen');
    });

   // ================= PENGELOLAAN NILAI =================
    Route::prefix('pengelolaan-nilai')->middleware(['auth', 'can:akses-penilaian-koordinator-ta'])->group(function () {
        Route::get('/', [PengelolaanNilaiController::class, 'kelolaNilai'])->name('kelola.penilaian');
        Route::get('/{namaFta}/{idProdi}/preview-data-nilai', [PengelolaanNilaiController::class, 'previewDataNilai'])->name('previewDataNilai');
        Route::get('/{namaFta}/{idProdi}', [PengelolaanNilaiController::class, 'detailNilaiMahasiswa'])->name('kelola.penilaian.detail');
        Route::post('/{idKategori}/{action}/toggle-kunci', [PengelolaanNilaiController::class, 'toggleKunciPenilaian'])->name('kelola.penilaian.toggle-kunci');
        Route::get('/download-template-nilai', fn () => Response::download(public_path('KelolaPenilaianTA/template-nilai-seminar-1-dan-2.xlsx'), 'template-nilai-seminar-1-dan-2.xlsx'))->name('download.template-nilai');
        Route::post('/{namaFta}/{idProdi}/import-nilai', [PengelolaanNilaiController::class, 'import'])->name('import-nilai');
        Route::post('/{namaFta}/{idProdi}/input-bulk-nilai', [PengelolaanNilaiController::class, 'inputBulk'])->name('inputBulkNilai');
    });

    Route::prefix('pengelolaan-nilai')->middleware(['auth', 'can:akses-pemberian-nilai'])->group(function () {
        Route::post('/{namaFta}/{idKota}/{action}/toggle-publish', [PengelolaanNilaiController::class, 'togglePublishNilai'])->name('kelola.penilaian.toggle-publish');
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
    Route::prefix('nilai-seminar')->middleware(['auth', 'can:akses-pemberian-nilai'])->group(function () {
        Route::get('/nilai/{namaFta}/masukan/{idKota}/{idProdi}', [PemberianFeedbackController::class, 'pengisianMasukanSeminar'])->name('pengisian.masukan');
        Route::post('/nilai/{namaFta}/masukan/{idKota}/tambah', [PemberianFeedbackController::class, 'simpanMasukanSeminar'])->name('pengisian.masukan.store');
        Route::post('/nilai/{namaFta}/masukan/{idKota}/edit', [PemberianFeedbackController::class, 'ubahMasukanSeminar'])->name('pengisian.masukan.edit');
        Route::get('/repository/mahasiswa/{kategori}/{id}/download', [PemberianFeedbackController::class, 'download'])->name('dokumen.download');
    });

    // ================= PEMBERIAN NILAI =================
    Route::prefix('nilai-seminar')->middleware(['auth', 'can:akses-pemberian-nilai'])->group(function () {
        Route::get('/nilai/{namaFta}/{idKota}', [PemberianNilaiController::class, 'pengisianNilaiSeminar'])->name('pengisian.nilai');
        Route::post('/nilai/{namaFta}/{idKota}/tambah', [PemberianNilaiController::class, 'simpanNilaiSeminar'])->name('pengisian.nilai.store');
        Route::patch('/nilai/{namaFta}/{idKota}/edit', [PemberianNilaiController::class, 'ubahNilaiSeminar'])->name('pengisian.nilai.edit');
    });
});