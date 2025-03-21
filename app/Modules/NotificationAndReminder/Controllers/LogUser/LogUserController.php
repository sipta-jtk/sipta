<?php

namespace App\Modules\NotificationAndReminder\Controllers\LogUser;

use App\Http\Controllers\Controller;
use App\Models\NotifikasiKirim;
use Illuminate\Support\Facades\Auth;

class LogUserController extends Controller
{
    /**
     * Mengambil log notifikasi milik pengguna saat ini.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogNotifications()
    {
        // Mendapatkan ID pengguna saat ini
        $userId = Auth::id();

        // Ambil log notifikasi berdasarkan user_id
        $logNotifikasi = NotifikasiKirim::join('notifikasi', 'notifikasi_kirim.id_notifikasi', '=', 'notifikasi.id_notifikasi')
            ->where('notifikasi_kirim.user_id', $userId) // Filter berdasarkan user_id
            ->select(
                'notifikasi_kirim.waktu_kirim',
                'notifikasi.judul',
                'notifikasi.isi_notifikasi',
                'notifikasi_kirim.respon_log',
                'notifikasi_kirim.username'
            )
            ->get();

        // Mengembalikan data sebagai JSON
        return response()->json($logNotifikasi);
    }
}