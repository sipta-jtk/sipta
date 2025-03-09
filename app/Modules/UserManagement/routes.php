<?php

use App\Modules\UserManagement\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user_management'], function () {
    Route::get('/', [UserManagementController::class, 'render']);
});