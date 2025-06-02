<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PembatalanJadwalSeminarSidangController;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\VerifikasiPengajuanJadwalController;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\VerifikasiBerkasController;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\PengajuanJadwalKotaSeminar3DanSidang;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\VerifikasiBerkasPengajuanMahasiswaController;
use App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers\BeritaAcaraPelaksanaanSeminarDanSidangController;

// PENGAJUAN JADWAL
Route::middleware(['auth', 'can:all_mahasiswa'])->group(function () {
    //daftar pengajuan 
    Route::get('seminar3-pengajuan', [PengajuanJadwalKotaSeminar3DanSidang::class, 'indexPengajuan'])
        ->middleware(['auth'])
        ->name('seminar3-pengajuan');

    Route::get('sidang-pengajuan', [PengajuanJadwalKotaSeminar3DanSidang::class, 'indexPengajuan'])
        ->middleware(['auth'])
        ->name('sidang-pengajuan');

    //pengajuan jadwal seminar 3
    Route::get('pengajuan-seminar3', [PengajuanJadwalKotaSeminar3DanSidang::class, 'indexPengajuanSeminar3'])
        ->middleware(['auth'])
        ->name('pengajuan-seminar3');

    //pengajuan jadwal sidang
    Route::get('pengajuan-sidang', [PengajuanJadwalKotaSeminar3DanSidang::class, 'indexPengajuanSidang'])
        ->middleware(['auth'])
        ->name('pengajuan-sidang');

    //tambah pengajuan baru
    Route::post('pengajuan-tambah/{id_kota}', [PengajuanJadwalKotaSeminar3DanSidang::class, 'tambahPengajuanPenjadwalan'])
        ->middleware(['auth'])
        ->name('pengajuan-tambah');
});

// PEMBATALAN JADWAL
Route::middleware(['auth', 'can:dosen'])->group(function () {
    //jadwal seminar
    Route::get('/batal-jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'indexJadwalSeminar'])->name('jadwal.seminar');
    Route::post('/pembatalan-jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'pembatalanJadwalSeminar'])->name('pembatalan.seminar');

    //jadwal sidang
    Route::get('/batal-jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'indexJadwalSidang']);
    Route::post('/pembatalan-jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'pembatalanJadwalSidang'])->name('pembatalan.sidang');


});

Route::middleware(['auth', 'can:koordinator_ta'])->group(function () {
    //pembatalan jadwal seminar
    Route::get('/persetujuan-pembatalan-jadwal-seminar', [PembatalanJadwalSeminarSidangController::class, 'indexPersetujuanPembatalanJadwalSeminar'])->name('view.persetujuan.pembatalan.seminar');
    Route::post('/persetujuan-pembatalan-jadwal-seminar/{pembatalan_id}/{status}', [PembatalanJadwalSeminarSidangController::class, 'persetujuanPembatalanSeminar'])->name('persetujuan.pembatalan.seminar');

    //pembatalan jadwal sidang
    Route::get('/persetujuan-pembatalan-jadwal-sidang', [PembatalanJadwalSeminarSidangController::class, 'indexPersetujuanPembatalanJadwalSidang'])->name('view.persetujuan.pembatalan.sidang');
    Route::post('/persetujuan-pembatalan-jadwal-sidang/{pembatalan_id}/{status}', [PembatalanJadwalSeminarSidangController::class, 'persetujuanPembatalanSidang'])->name('persetujuan.pembatalan.sidang');
});

//verifikasi berkas pengajuan mahasiswa
Route::middleware(['auth', 'can:mahasiswa_ta'])->group(function () {
    Route::get('/verifikasi-berkas-3', [VerifikasiBerkasPengajuanMahasiswaController::class, 'create'])->middleware(['auth'])->name('verifikasi3.create');
    Route::post('/verifikasi-berkas-3', [VerifikasiBerkasPengajuanMahasiswaController::class, 'store'])->middleware(['auth'])->name('verifikasi3.store');
    Route::get('/verifikasi-berkas-sidang', [VerifikasiBerkasPengajuanMahasiswaController::class, 'create_sidang'])->middleware(['auth'])->name('verifikasi-sidang.create');
    Route::post('/verifikasi-berkas-sidang', [VerifikasiBerkasPengajuanMahasiswaController::class, 'store_sidang'])->middleware(['auth'])->name('verifikasi-sidang.store');
});
// Kelola Verifikasi Berkas Pengajuan
Route::group(['prefix' => 'kelola-pengajuan-berkas', 'as' => 'kelola.', 'middleware' => ['auth', 'can:koordinator_ta']], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'berkas.'], function () {
        Route::get('/', [VerifikasiBerkasController::class, 'listPengajuan'])->name('list');
        Route::get('/ditolak', [VerifikasiBerkasController::class, 'pengajuanDitolak'])->name('ditolak');
        Route::get('/diterima', [VerifikasiBerkasController::class, 'pengajuanDiterima'])->name('diterima');
        Route::get('/detail/{id}', [VerifikasiBerkasController::class, 'show'])->name('detail');
        Route::put('/verifikasi/{id}', [VerifikasiBerkasController::class, 'verifikasi'])->name('verifikasi');
    });
});
// Route untuk koordinator menampilkan list pengajuan jadwal
Route::group(['prefix' => 'koordinator-kelola-pengajuan-jadwal', 'as' => 'kelola.', 'middleware' => ['auth', 'can:koordinator_ta']], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'jadwal.'], function () {
        Route::get('/', [VerifikasiPengajuanJadwalController::class, 'getListAsKoordinatorTA'])->name('list');
        Route::put('/verifikasi/{id}', [VerifikasiPengajuanJadwalController::class, 'verifikasiAsKoordinatorTA'])->name('verifikasi');
    });
});

// Route untuk dosen pembimbing menampilkan list pengajuan jadwal
Route::group(['prefix' => 'kelola-pengajuan-jadwal-pembimbing', 'as' => 'kelola-pembimbing.', 'middleware' => ['auth', 'can:akses-dosen-kelola-pengajuan-jadwal']], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'jadwal.'], function () {
        Route::get('/', [VerifikasiPengajuanJadwalController::class, 'getListAsDosenPembimbing'])->name('list');
        Route::put('/verifikasi/{id}', [VerifikasiPengajuanJadwalController::class, 'verifikasiAsPembimbing'])->name('verifikasi');
    });
});

// Route untuk dosen penguji menampilkan list pengajuan jadwal
Route::group(['prefix' => 'kelola-pengajuan-jadwal-penguji', 'as' => 'kelola-penguji.', 'middleware' => ['auth', 'can:akses-dosen-kelola-pengajuan-jadwal']], function () {
    Route::group(['prefix' => '{tipe}', 'as' => 'jadwal.'], function () {
        Route::get('/', [VerifikasiPengajuanJadwalController::class, 'getListAsDosenPenguji'])->name('list');
        Route::put('/verifikasi/{id}', [VerifikasiPengajuanJadwalController::class, 'verifikasiAsPenguji'])->name('verifikasi');
    });
});

// Rekap berita acara seminar 3 dan sidang TA Koordinator TA
Route::middleware(['auth', 'can:koordinator_ta'])->group(function () {
    // Route untuk halaman rekap berita acara seminar 3
    Route::get('/rekapitulasi-berita-acara-seminar-3', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'rekapBeritaAcaraSeminar3'])
        ->name('rekap.presensi.seminar3');

    // Route untuk halaman rekap berita acara Sidang TA
    Route::get('/rekapitulasi-berita-acara-sidang-ta', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'rekapBeritaAcaraSidangTa'])
        ->name('rekap.presensi.sidang.ta');
});

Route::middleware(['auth', 'can:all_mahasiswa'])->group(function () {
    Route::get('/berita-acara-pelaksanaan-seminar-3', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'indexBeritaAcaraSeminar3'])
        ->name('presensi.seminar3');

    Route::get('/berita-acara-pelaksanaan-sidang-ta', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'indexBeritaAcaraSidangTA'])
        ->name('presensi.sidangta');

    Route::post('/presensi/hadir', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'simpanKehadiran'])
        ->name('presensi.hadir');

    Route::post('/presensi/dokumentasi', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'simpanDokumentasi'])
        ->name('presensi.dokumentasi');

    Route::post('/simpan-batas-revisi', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'simpanBatasRevisi'])
        ->name('simpan.batas.revisi');

    Route::post('/simpan-status-kelulusan', [BeritaAcaraPelaksanaanSeminarDanSidangController::class, 'simpanStatusKelulusan'])
        ->name('simpan.status.kelulusan');
});