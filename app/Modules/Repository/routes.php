<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Repository\Controllers\RepositoryController;
use App\Modules\Repository\Controllers\SubkategoriController;

// Get prefix from environment configuration
$prefix = env('PREFIX_URL', '');

/*
|--------------------------------------------------------------------------
| Repository Routes
|--------------------------------------------------------------------------
*/

// ============================
// Mahasiswa - Repository TA
// ============================

// Redirect otomatis ke dashboard mahasiswa berdasarkan id_kota
Route::get("/{$prefix}/repository/mahasiswa", function () {
    $id_kota = auth()->user()->mahasiswa->id_kota ?? null;
    return redirect()->route('Repository.dashboard.kota.mahasiswa', $id_kota);
})->middleware(['auth', 'can:akses-sidebar-repo'])->name('Repository.redirect');

// Dashboard Repository TA (Mahasiswa)
Route::get("/{$prefix}/repository/mahasiswa/kota/{id_kota}", [RepositoryController::class, 'dashboard'])
    ->middleware(['auth', 'can:akses-sidebar-repo'])
    ->name('Repository.dashboard.kota.mahasiswa');

// Dokumen berdasarkan kategori (Mahasiswa)
Route::get("/{$prefix}/repository/mahasiswa/kota/{id_kota}/{kategori}", [RepositoryController::class, 'index'])
    ->middleware(['auth', 'can:akses-sidebar-repo'])
    ->name('Repository.index.kota');

// CRUD Dokumen (Mahasiswa)
Route::prefix("/{$prefix}/repository/mahasiswa")->middleware(['auth', 'can:akses-sidebar-repo'])->group(function () {
    Route::post('/{kategori}', [RepositoryController::class, 'store'])->name('Repository.store');
    Route::get('/{kategori}/{id}/edit', [RepositoryController::class, 'edit'])->name('Repository.edit');
    Route::put('/{kategori}/{id}', [RepositoryController::class, 'update'])->name('Repository.update');
    Route::delete('/{kategori}/{id}', [RepositoryController::class, 'destroy'])->name('Repository.destroy');
    Route::post('/save-notes/{id}', [RepositoryController::class, 'saveNotes'])->name('Repository.saveNotes');
    Route::get('/{kategori}/{id}/download', [RepositoryController::class, 'download'])->name('Repository.download');

    Route::get('/{kategori}/subkategori', [SubkategoriController::class, 'index'])->name('Subkategori.index');
    Route::post('/{kategori}/subkategori', [SubkategoriController::class, 'store'])->name('Subkategori.store');
    Route::delete('/{kategori}/subkategori/{id}', [SubkategoriController::class, 'destroy'])->name('Subkategori.destroy');
    Route::put('/{kategori}/subkategori/{id}', [SubkategoriController::class, 'update'])->name('Subkategori.update');
});

// ============================
// Dosen - Repository Mahasiswa
// ============================

// List KoTA (kelompok TA)
Route::get("/{$prefix}/repository/dosen/kelompok-ta", [RepositoryController::class, 'list_kelompok_ta'])
    ->middleware(['auth', 'can:akses-koordinator-admin'])
    ->name('Repository.list_kelompok_ta');

// List KoTA Bimbingan (kelompok TA)
Route::get("/{$prefix}/repository/dosen/kelompok-ta-bimbingan", [RepositoryController::class, 'list_kelompok_dosen_pembimbing'])
    ->middleware(['auth', 'can:pembimbing'])
    ->name('Repository.list_kelompok_dosen_pembimbing');

// List KoTA Yang Di Uji (kelompok TA)
Route::get("/{$prefix}/repository/dosen/kelompok-ta-uji", [RepositoryController::class, 'list_kelompok_dosen_penguji'])
    ->middleware(['auth', 'can:penguji'])
    ->name('Repository.list_kelompok_dosen_penguji');

// Dashboard Repository TA berdasarkan id_kota (Dosen)
Route::get("/{$prefix}/repository/dosen/kota/{id_kota}", [RepositoryController::class, 'dashboard'])
    ->middleware(['auth', 'can:akses-sidebar-repo-dosen'])
    ->name('Repository.dashboard.kota.dosen');

// Lihat Repository Mahasiswa
Route::prefix("/{$prefix}/repository/dosen")->middleware(['auth', 'can:akses-sidebar-repo-dosen'])->group(function () {
    Route::get('/mahasiswa/{nim}', [RepositoryController::class, 'lihatRepositoryMahasiswa'])->name('Repository.mahasiswa');
    Route::get('/mahasiswa/{nim}/{kategori}/{id}/download', [RepositoryController::class, 'download'])->name('Repository.mahasiswa.download');
});

// ============================
// Utilitas
// ============================
// Saabiq Muhyiyuddin Aulawi
// Route untuk menampilkan halaman log aktivitas
// ============================
// Utilitas
// ============================

// Log Aktivitas (Dosen - Koordinator TA)
Route::get("/{$prefix}/repository/koor-ta/log-aktivitas", [RepositoryController::class, 'logAktivitas'])
    ->middleware(['auth', 'can:akses-sidebar-repo-dosen'])
    ->name('Repository.log-aktivitas');

// Monitoring Penyimpanan
Route::get("/{$prefix}/repository/koor-ta/monitoring-penyimpanan", [RepositoryController::class, 'monitoringPenyimpanan'])
    ->middleware(['auth', 'can:akses-sidebar-repo-dosen'])
    ->name('Repository.monitoring-penyimpanan');

// Get Filtered Storage Data (for AJAX)
Route::get("/{$prefix}/repository/koor-ta/get-filtered-data", [RepositoryController::class, 'getFilteredStorageData'])
    ->middleware(['auth', 'can:akses-sidebar-repo-dosen'])
    ->name('Repository.get-filtered-data');


Route::get("/{$prefix}/v0", [RepositoryController::class, 'v0']);
Route::get("/{$prefix}/kategori", [RepositoryController::class, 'v1']);
Route::get("/{$prefix}/dokumen", [RepositoryController::class, 'v2']);
Route::get("/{$prefix}/uji", [RepositoryController::class, 'p1']);
