<?php

namespace App\Modules\NotificationAndReminder\Controllers\LogModal;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi; 
use App\Models\NotifikasiKirim;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogModalNotifController extends Controller
{
    public function getNotifications()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }

        $notifications = Notifikasi::join('notifikasi_kirim', 'notifikasi.id_notifikasi', '=', 'notifikasi_kirim.id_notifikasi')
            ->where('notifikasi_kirim.username', $user->username)
            ->orderBy('notifikasi_kirim.waktu_kirim', 'desc')
            ->select('notifikasi.*', 'notifikasi_kirim.waktu_kirim', 'notifikasi_kirim.status')
            ->take(5)
            ->get();

        return response()->json($notifications);
    }

    public function show($id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }

        $notification = Notifikasi::join('notifikasi_kirim', 'notifikasi.id_notifikasi', '=', 'notifikasi_kirim.id_notifikasi')
            ->where('notifikasi.id_notifikasi', $id)
            ->where('notifikasi_kirim.username', $user->username)
            ->first();

        if (!$notification) {
            return response()->json(['error' => 'Notifikasi tidak ditemukan'], 404);
        }
        
        return response()->json($notification);
    }
}