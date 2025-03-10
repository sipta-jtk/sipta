<?php

use Illuminate\Support\Facades\Route;
use App\Modules\NotificationAndReminder\Controllers\SettingNotification\SettingAwalNotif as SettingAwalNotifController;

Route::get('/notification_reminder/admin/settingawal', [SettingAwalNotifController::class, 'render']);
    
Route::post('/notification_reminder/admin/settingawal', [SettingAwalNotifController::class, 'store'])->name('notifikasi.store');


Route::get('/notification_reminder/admin/notifikasi', function () {
    return view('NotificationAndReminder::LogAdmin.logAdmin');
});

Route::get('/notification_reminder/user/notifikasi', function () {
    return view('NotificationAndReminder::LogUser.logUser');
});

