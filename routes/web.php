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
$prefix = env('PREFIX_URL', 'sipta');

// Load routes from modules dynamically
foreach (scandir($modulesPath) as $module) {
    $routesFile = "{$modulesPath}/{$module}/routes.php";

    if (is_file($routesFile)) {
        Route::prefix($prefix)->group(function () use ($routesFile) {
            require $routesFile;
        });
    }
}

// Default route for the homepage
Route::get('/', function () {
    return view('welcome');
})->middleware('auth'); // Only authenticated users can access this page

// Logout route
Route::post('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('logout');

// Additional routes
Route::get('/penentuan-ambang-batas', function () {
    return view('CekPlagiarisme.views.PenentuanAmbangBatas');
});

Route::get('/cek-plagiarisme', function () {
    return view('CekPlagiarisme.views.DaftarDokumen');
});

Route::get('/seeallnotif', function () {
    return view('NotificationAndReminder::LogUser.logUser'); // Adjust the view name as needed
});

Route::get('/log-admin', function () {
    return view('NotificationAndReminder::LogAdmin.logAdmin'); // Adjust the view name as needed
});

Route::get('/edit-notif', function () {
    return view('NotificationAndReminder::SettingNotification.SettingAwalNotif'); // Adjust the view name as needed
});