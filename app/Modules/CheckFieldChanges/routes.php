<?php

use Illuminate\Support\Facades\Route;
use App\Modules\CheckFieldChanges\Controllers\CheckFieldChangesController;

Route::get('/CheckFieldChanges', [CheckFieldChangesController::class, 'index']);
Route::post('/check-field-changes', [CheckFieldChangesController::class, 'update']);