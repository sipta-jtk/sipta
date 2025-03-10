<?php

use Illuminate\Support\Facades\Route;

Route::get('/notification_reminder/admin/notifikasi', function () {
    return view('NotificationAndReminder::LogAdmin.logAdmin');
});

Route::get('/notification_reminder/user/notifikasi', function () {
    return view('NotificationAndReminder::LogUser.logUser');
});

