<?php

namespace App\Modules\UserManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        // Ambil instance query builder dengan relasi user
        $query = LogAktivitas::with('user')
            ->where('action', 'login'); // Fokus hanya aktivitas login

        // Jika parameter 'search' ada, filter berdasarkan nama user
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        // Ambil data dengan pagination (10 per halaman) dan urutkan berdasarkan waktu terbaru
        $logAktivitas = $query->orderByDesc('waktu_aktivitas')->paginate(10);

        // Pastikan query string tetap ada saat pagination
        $logAktivitas->appends($request->only('search'));

        // Kirim ke view
        return view('UserManagement.views.log_aktivitas', compact('logAktivitas'));
    }
}
