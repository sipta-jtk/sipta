<?php

use App\Modules\Repository\Controllers\RepositoryController;
use App\Modules\Repository\Controllers\SubkategoriController;

/*
|--------------------------------------------------------------------------
| Repository Routes
|--------------------------------------------------------------------------
*/

Route::prefix('repository')->group(function () {
    // Route utama untuk halaman daftar dokumen berdasarkan kategori
    Route::get('/', [RepositoryController::class, 'dashboard'])->name('Repository.dashboard');
    
    Route::get('/{kategori}', [RepositoryController::class, 'index'])->name('Repository.index');
    
    // Route untuk menyimpan dokumen baru berdasarkan kategori
    Route::post('/{kategori}', [RepositoryController::class, 'store'])->name('Repository.store');
    
    // Route untuk menampilkan halaman edit dokumen berdasarkan kategori dan ID
    Route::get('/{kategori}/{id}/edit', [RepositoryController::class, 'edit'])->name('Repository.edit');
    
    // Route untuk memperbarui dokumen berdasarkan kategori dan ID
    Route::put('/{kategori}/{id}', [RepositoryController::class, 'update'])->name('Repository.update');
    
    // Route untuk menghapus dokumen berdasarkan kategori dan ID
    Route::delete('/{kategori}/{id}', [RepositoryController::class, 'destroy'])->name('Repository.destroy');
    
    // Route untuk mendownload dokumen berdasarkan kategori dan ID
    Route::get('/{kategori}/{id}/download', [RepositoryController::class, 'download'])->name('Repository.download');
    
    Route::post('/subkategori', [SubkategoriController::class, 'store'])->name('subkategori.store');

    // Route untuk menampilkan halaman log aktivitas
    Route::get('/log-aktivitas', [RepositoryController::class, 'logAktivitas']);

    // Route untuk menampilkan halaman monitoring penyimpanan
    Route::get('/monitoring-penyimpanan', [RepositoryController::class, 'monitoringPenyimpanan']);
});