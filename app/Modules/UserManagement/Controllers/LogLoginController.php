<?php

namespace App\Modules\UserManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoginUser;
use App\Models\User;
use Carbon\Carbon;

class LogLoginController extends Controller
{
    public function index(Request $request)
    {
        // Query log login beserta relasi user
        $query = LoginUser::with('user');

        // Filter berdasarkan tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('waktu_aktivitas', [$startDate, $endDate]);
        }

        // Filter pencarian berdasarkan nama user atau username
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan role user (jika diperlukan)
        if ($request->filled('role')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role_user', $request->input('role'));
            });
        }

        // Ambil data dengan pagination dan urutkan berdasarkan waktu terbaru
        $logLogin = $query->orderByDesc('waktu_aktivitas')->paginate(10);

        // Pastikan query string tetap ada saat pagination
        $logLogin->appends($request->all());

        // Ambil daftar role untuk filter
        $roles = User::distinct()->pluck('role_user');
        
        // Get prefix from environment
        $prefix = env('PREFIX_URL', 'sipta');

        // Kirim ke view
        return view('UserManagement.views.log_login', compact('logLogin', 'roles', 'prefix'));
    }
}