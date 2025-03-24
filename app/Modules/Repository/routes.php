<?php
use Illuminate\Support\Facades\Route;
use App\Modules\Repository\Controllers\RepositoryController;
use App\Modules\Repository\Controllers\SubkategoriController;

/*
|--------------------------------------------------------------------------
| Repository Routes
|--------------------------------------------------------------------------
*/

// ============================
// Mahasiswa - Repository TA
// ============================

// Saabiq Muhyiyuddin Aulawi
// Route untuk menampilkan halaman log aktivitas
Route::get('/log-aktivitas', [RepositoryController::class, 'logAktivitas']);

// Route untuk menampilkan halaman monitoring penyimpanan
Route::get('/monitoring-penyimpanan', [RepositoryController::class, 'monitoringPenyimpanan']);

// Redirect otomatis ke dashboard mahasiswa berdasarkan id_kota
Route::get('/repository/mahasiswa', function () {
    $id_kota = auth()->user()->mahasiswa->id_kota ?? null;
    return redirect()->route('Repository.dashboard.kota.mahasiswa', $id_kota);
})->middleware(['auth', 'can:akses-sidebar-repo'])->name('Repository.redirect');

// Dashboard Repository TA (Mahasiswa)
Route::get('/repository/mahasiswa/kota/{id_kota}', [RepositoryController::class, 'dashboard'])
    ->middleware(['auth', 'can:akses-sidebar-repo'])
    ->name('Repository.dashboard.kota.mahasiswa');

// Dokumen berdasarkan kategori (Mahasiswa)
Route::get('/repository/mahasiswa/kota/{id_kota}/{kategori}', [RepositoryController::class, 'index'])
    ->middleware(['auth', 'can:akses-sidebar-repo'])
    ->name('Repository.index.kota');

// CRUD Dokumen (Mahasiswa)
Route::prefix('/repository/mahasiswa')->middleware(['auth', 'can:akses-sidebar-repo'])->group(function () {
    Route::post('/{kategori}', [RepositoryController::class, 'store'])->name('Repository.store');
    Route::get('/{kategori}/{id}/edit', [RepositoryController::class, 'edit'])->name('Repository.edit');
    Route::put('/{kategori}/{id}', [RepositoryController::class, 'update'])->name('Repository.update');
    Route::delete('/{kategori}/{id}', [RepositoryController::class, 'destroy'])->name('Repository.destroy');
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
Route::get('/repository/dosen/kelompok-ta', [RepositoryController::class, 'list_kelompok_ta'])
    ->middleware(['auth', 'can:akses-sidebar-repo-dosen'])
    ->name('Repository.list_kelompok_ta');

// Dashboard Repository TA berdasarkan id_kota (Dosen)
Route::get('/repository/dosen/kota/{id_kota}', [RepositoryController::class, 'dashboard'])
    ->middleware(['auth', 'can:akses-sidebar-repo-dosen'])
    ->name('Repository.dashboard.kota.dosen');

// Lihat Repository Mahasiswa
Route::prefix('/repository/dosen')->middleware(['auth', 'can:akses-sidebar-repo-dosen'])->group(function () {
    Route::get('/mahasiswa/{nim}', [RepositoryController::class, 'lihatRepositoryMahasiswa'])->name('Repository.mahasiswa');
    Route::get('/mahasiswa/{nim}/{kategori}/{id}/download', [RepositoryController::class, 'download'])->name('Repository.mahasiswa.download');
});

// ============================
// Utilitas
// ============================
Route::get('/log-aktivitas', [RepositoryController::class, 'logAktivitas']);
Route::get('/monitoring-penyimpanan', [RepositoryController::class, 'monitoringPenyimpanan']);

Route::get('/v0', [RepositoryController::class, 'v0']);
Route::get('/kategori', [RepositoryController::class, 'v1']);
Route::get('/dokumen', [RepositoryController::class, 'v2']);
Route::get('/uji', [RepositoryController::class, 'p1']);
