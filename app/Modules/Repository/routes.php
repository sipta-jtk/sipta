<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Repository\Controllers\RepositoryController;
use App\Modules\Repository\Controllers\SubkategoriController;

/*
|--------------------------------------------------------------------------
| Repository Routes
|--------------------------------------------------------------------------
*/

Route::get('/repository', [RepositoryController::class, 'dashboard'])->name('Repository.dashboard');
Route::get('repository/{kategori}', [RepositoryController::class, 'index'])->name('Repository.index');


Route::prefix('/repository')->middleware('auth', 'can:mahasiswa_ta-access')->group(function () {
    
    // Akmal Goniyyu Hartono
    
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
    
    Route::get('/{kategori}/subkategori', [SubkategoriController::class, 'index'])->name('Subkategori.index');

    Route::post('/{kategori}/subkategori', [SubkategoriController::class, 'store'])->name('Subkategori.store');

    // Saabiq Muhyiyuddin Aulawi
    // Route untuk menampilkan halaman log aktivitas
    Route::get('/log-aktivitas', [RepositoryController::class, 'logAktivitas']);

    // Route untuk menampilkan halaman monitoring penyimpanan
    Route::get('/monitoring-penyimpanan', [RepositoryController::class, 'monitoringPenyimpanan']);

    // Farrel Keiza Muhammad Yamin Putra
    // Route utama untuk halaman daftar dokumen berdasarkan kategori
    Route::get('/repository/list_kelompok_ta', [RepositoryController::class, 'Repository.list_kelompok_ta']);
        
    // Muhammad Fahrizal Alzaelani

    // Muhammad Alvyn Adhianto
    Route::get('/v0', action: [RepositoryController::class, 'v0']);
    Route::get('/kategori', action: [RepositoryController::class, 'v1']);
    Route::get('/dokumen', action: [RepositoryController::class, 'v2']);
    Route::get('/uji', action: [RepositoryController::class, 'p1']);
});