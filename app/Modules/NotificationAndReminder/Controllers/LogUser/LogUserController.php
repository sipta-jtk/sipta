<?php

namespace App\Modules\NotificationAndReminder\Controllers\LogUser;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan pengguna saat ini
use Illuminate\Support\Facades\DB; // Untuk query database
use Carbon\Carbon; // Untuk manipulasi tanggal
use Illuminate\Http\Request; // Untuk menangani request HTTP

class LogUserController extends Controller
{
    /**
     * Menampilkan daftar log notifikasi milik pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mendapatkan ID pengguna saat ini
        $userId = Auth::id();

        // Mengambil data log notifikasi milik pengguna dari database
        $logs = DB::table('notifikasi')
            ->where('user_id', $userId) // Filter berdasarkan user_id
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan waktu terbaru
            ->get();

        // Kirim data ke view
        return view('NotificationAndReminder::log-user.index', compact('logs'));
    }

    /**
     * Menampilkan detail log notifikasi tertentu milik pengguna.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Mendapatkan ID pengguna saat ini
        $userId = Auth::id();

        // Mengambil detail log berdasarkan ID dan user_id
        $log = DB::table('notifikasi')
            ->where('id', $id)
            ->where('user_id', $userId) // Pastikan log milik pengguna
            ->first();

        // Jika log tidak ditemukan, kembalikan 404
        if (!$log) {
            abort(404, 'Log notifikasi tidak ditemukan.');
        }

        // Kirim data ke view detail
        return view('NotificationAndReminder::log-user.show', compact('log'));
    }

    /**
     * Menghapus log notifikasi tertentu milik pengguna.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Mendapatkan ID pengguna saat ini
        $userId = Auth::id();

        // Hapus log berdasarkan ID dan user_id
        $deleted = DB::table('notifikasi')
            ->where('id', $id)
            ->where('user_id', $userId) // Pastikan log milik pengguna
            ->delete();

        // Cek apakah penghapusan berhasil
        if ($deleted) {
            return redirect()->route('log-user.index')->with('success', 'Log notifikasi berhasil dihapus.');
        } else {
            return redirect()->route('log-user.index')->with('error', 'Gagal menghapus log notifikasi.');
        }
    }

    /**
     * Menampilkan log notifikasi milik pengguna dalam rentang tanggal tertentu.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterByDate(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Mendapatkan ID pengguna saat ini
        $userId = Auth::id();

        // Ambil data log berdasarkan rentang tanggal dan user_id
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $logs = DB::table('notifikasi')
            ->where('user_id', $userId) // Filter berdasarkan user_id
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('NotificationAndReminder::log-user.index', compact('logs'));
    }
}