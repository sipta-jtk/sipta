<?php

use Illuminate\Support\Facades\Route;
use App\Modules\RepositoryTA\Controllers\RepositoryTAController;

# Farrel 
Route::get('/repository', [RepositoryTAController::class, 'index']);

Route::get('/repository/list_kelompok_ta', [RepositoryTAController::class, 'list_kelompok_ta']);
# Akmal

# Fahrizal

# Saabiq

# Alvyn 