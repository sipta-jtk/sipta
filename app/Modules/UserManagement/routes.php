<?php

use Illuminate\Support\Facades\Route;
use App\Modules\UserManagement\Controllers\KBKController;
use App\Modules\UserManagement\Controllers\ProgramStudiController;


Route::get('/kelola-kbk', [KBKController::class, 'index'])->name('kelola-kbk');
Route::post('/kelola-kbk', [KBKController::class, 'store'])->name('kelola-kbk.store');
Route::post('/kelola-kbk/update/{id}', [KBKController::class, 'update'])->name('kelola-kbk.update');
Route::delete('/kelola-kbk/{id}', [KBKController::class, 'destroy'])->name('kelola-kbk.destroy');
//

// Route::get('program-studi', [ProgramStudiController::class, 'index'])->name('program-studi.index');
//

Route::get('program-studi', [ProgramStudiController::class, 'index'])->name('program-studi.index');
Route::get('program-studi/create', [ProgramStudiController::class, 'create'])->name('program-studi.create');
Route::post('program-studi', [ProgramStudiController::class, 'store'])->name('program-studi.store');
Route::get('program-studi/{id}/edit', [ProgramStudiController::class, 'edit'])->name('program-studi.edit');
Route::put('program-studi/{id}', [ProgramStudiController::class, 'update'])->name('program-studi.update');
Route::delete('program-studi/{id}', [ProgramStudiController::class, 'destroy'])->name('program-studi.destroy');
