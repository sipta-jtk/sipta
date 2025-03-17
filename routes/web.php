<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

$modulesPath = base_path('app/Modules');

foreach (scandir($modulesPath) as $module) {
    $routesFile = "{$modulesPath}/{$module}/routes.php";

    if (is_file($routesFile)) {
        require $routesFile; 
    }
}

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

// routes/web.php
// Route::get('/settings', function() {
//     return view('settings'); // Sesuaikan dengan nama view yang kamu buat
// });

Route::get('/seeallnotif', function() {
    return view('NotificationAndReminder::LogUser.logUser'); // Sesuaikan dengan nama view yang kamu buat
});

Route::get('/log-admin', function() {
    return view('NotificationAndReminder::LogAdmin.logAdmin'); // Sesuaikan dengan nama view yang kamu buat
});

Route::get('/edit-notif', function() {
    return view('NotificationAndReminder::SettingNotification.SettingAwalNotif'); // Sesuaikan dengan nama view yang kamu buat
});


