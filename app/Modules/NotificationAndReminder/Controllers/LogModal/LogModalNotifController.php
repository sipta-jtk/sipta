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
        $notifikasi = Notifikasi::orderBy('created_at', 'desc')->take(5)->get();
        return response()->json($notifikasi);
    }

    public function show($id)
    {
        $notification = Notifikasi::where('id_notifikasi', $id)->first();
        if (!$notification) {
            return response()->json(['error' => 'Notifikasi tidak ditemukan'], 404);
        }
        return response()->json($notification);
    }

}
