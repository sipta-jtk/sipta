<?php

use Illuminate\Support\Facades\Route;
use App\Modules\UserManagement\Controllers\KBKController;

Route::get('/kelola-kbk', [KBKController::class, 'index'])->name('kelola-kbk');
Route::post('/kelola-kbk', [KBKController::class, 'store'])->name('kelola-kbk.store');
Route::post('/kelola-kbk/update/{id}', [KBKController::class, 'update'])->name('kelola-kbk.update');
Route::delete('/kelola-kbk/{id}', [KBKController::class, 'destroy'])->name('kelola-kbk.destroy');
