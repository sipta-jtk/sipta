<?php

namespace App\Modules\NotificationAndReminder\Controllers\LogModal;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi; 
use App\Models\NotifikasiKirim;
use Illuminate\Http\Request;

class LogModalNotifController extends Controller
{
    public function getNotifications()
    {
        $notifikasi = Notifikasi::orderBy('created_at', 'desc')->take(2)->get();
        return response()->json($notifikasi);
    }

}
