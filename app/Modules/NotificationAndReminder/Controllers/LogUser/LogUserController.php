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
    public function getLogNotification()
    {
        try {
            // Mendapatkan ID pengguna saat ini
            $userId = Auth::id();

            // Validasi apakah pengguna terautentikasi
            if (!$userId) {
                return response()->json(['error' => 'Pengguna tidak terautentikasi'], 401);
            }

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
                ->orderBy('notifikasi_kirim.waktu_kirim', 'desc') // Urutkan berdasarkan waktu terbaru
                ->get();

            // Jika tidak ada data notifikasi
            if ($logNotifikasi->isEmpty()) {
                return response()->json(['message' => 'Tidak ada log notifikasi untuk pengguna ini'], 200);
            }

            // Mengembalikan data sebagai JSON
            return response()->json($logNotifikasi, 200);

        } catch (\Exception $e) {
            // Tangkap error jika terjadi masalah
            return response()->json(['error' => 'Gagal mengambil log notifikasi: ' . $e->getMessage()], 500);
        }
    }
}