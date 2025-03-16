<?php

use App\Exports\RekapitulasiNilaiExport;
use App\Modules\KelolaPenilaianTA\Controllers\FormulirPenilaianController;
use Illuminate\Support\Facades\Route;
use App\Modules\KelolaPenilaianTA\Controllers\KelolaPenilaianTAController;

Route::group(['prefix' => 'kelola-penilaian-ta'], function () {

    // ================= FORMULIR PENILAIAN =================
    Route::prefix('formulir-penilaian')->group(function () {
        Route::get('/', [KelolaPenilaianTAController::class, 'formulirPenilaian']);
        Route::get('/tambah-formulir', [KelolaPenilaianTAController::class, 'tambahFormulir'])->name('formulir-penilaian.tambah-formulir');
        Route::get('/detail', function () {
            return view('KelolaPenilaianTA.views.formulir-penilaian.detail_fta_011');
        })->name('formulir-penilaian.detail');
        Route::get('/ubah-formulir', function () {
            return view('KelolaPenilaianTA.views.formulir-penilaian.ubah_formulir_ta');
        })->name('formulir-penilaian.edit');
        Route::put('/update', [KelolaPenilaianTAController::class, 'updateFormulir'])->name('formulir-penilaian.update');
    });


    // Route yang dikomentari
    // Route::get('KelolaPenilaianTA/formulir-penilaian/edit', [FormulirPenilaianController::class, 'edit'])->name('formulir-penilaian.edit');
    // Route::put('formulir-penilaian/{id}', [FormulirPenilaianController::class, 'update'])->name('formulir-penilaian.update');

    // ================= MONITORING NILAI MAHASISWA =================
    Route::prefix('monitoring')->middleware('auth')->group(function () {
        Route::get('/mahasiswa', [MonitoringNilaiMahasiswaController::class, 'monitoringMahasiswa'])->name('monitoring.mahasiswa');
        Route::get('/feedback', [MonitoringNilaiMahasiswaController::class, 'monitoringFeedback'])->name('monitoring.feedback');
        Route::get('/rubrik', [MonitoringNilaiMahasiswaController::class, 'monitoringRubrik'])->name('monitoring.rubrik');
    });

    // ================= PENGELOLAAN NILAI =================
    Route::get('/pengelolaan-nilai', [KelolaPenilaianTAController::class, 'kelolaNilai']);
    Route::get('/detail/{kategori}', [KelolaPenilaianTAController::class, 'detailNilaiMahasiswa']);

    // *********************** DUMMY PENILAIAN SEMINAR 2 ***********************
    Route::get('/seminar-2/tambah', [PengelolaanNilaiController::class, 'formPenilaianSeminar2']);
    Route::post('/seminar-2/tambah', [PengelolaanNilaiController::class, 'simpanNilaiMahasiswa'])->name('dummy.seminar-2.simpan-nilai');
    // Route::put('/seminar-2/edit/{nim}', [PengelolaanNilaiController::class, 'editNilaiMahasiswa'])->name('dummy.seminar-2.edit-nilai');

    // ================= REKAPITULASI NILAI =================
<<<<<<< HEAD
    Route::get('/rekapitulasi-nilai', [KelolaPenilaianTAController::class, 'getRekapNilaiSidang']);
    Route::post('/rekapitulasi-nilai/export', [KelolaPenilaianTAController::class, 'exportExcelNilaiSidang'])->name('rekapitulasi-nilai.export');
    Route::get('/rekapitulasi/export', [KelolaPenilaianTAController::class, 'exportExcel'])->name('rekapitulasi.export');
=======
    Route::get('/rekapitulasi-nilai-sidang', [RekapitulasiNilaiController::class, 'getRekapNilaiSidang']);
    Route::get('/rekapitulasi-nilai-akhir', [RekapitulasiNilaiController::class, 'getRekapNilaiAkhir']);
    Route::post('/rekapitulasi-nilai/export', [RekapitulasiNilaiController::class, 'exportExcelNilaiSidang'])->name('rekapitulasi-nilai.export');
    Route::get('/rekapitulasi/export', [RekapitulasiNilaiController::class, 'exportExcelNilaiSidang'])->name('rekapitulasi.export');
>>>>>>> parent of c670a01 ([Feature] add backend rekapitulasi nilai sidang dan pengaturan bobot)

    // ================= PEMBERIAN NILAI DAN FEEDBACK =================
    Route::prefix('nilai-seminar')->group(function () {
        Route::get('/1/masukan', [KelolaPenilaianTAController::class, 'pengisianMasukanSeminar1']);
        Route::get('/2', [KelolaPenilaianTAController::class, 'pengisianNilaiSeminarII']);
        Route::get('/2/masukan', [KelolaPenilaianTAController::class, 'pengisianMasukanSeminarII']);
        Route::get('/3', [KelolaPenilaianTAController::class, 'pengisianNilaiSeminarIII']);
        Route::get('/3/masukan', [KelolaPenilaianTAController::class, 'pengisianMasukanSeminarIII']);
    });
    Route::prefix('nilai-sidang')->group(function () {
        Route::get('/akhir', [KelolaPenilaianTAController::class, 'pengisianNilaiSidangAkhir']);
        Route::get('/akhir/masukan', [KelolaPenilaianTAController::class, 'pengisianMasukanSidangAkhir']);
    });
    Route::get('/nilai-tugas-akhir', [KelolaPenilaianTAController::class, 'pengisianNilaiTA']);
    
});

// ================= HALAMAN UTAMA KELOLA PENILAIAN TA =================
Route::get('/KelolaPenilaianTA', [KelolaPenilaianTAController::class, 'index']);
