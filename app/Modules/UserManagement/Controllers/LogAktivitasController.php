<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktivitas; // Ganti dari LogAktivitas ke LogLogin
use App\Models\User;
use App\Http\Controllers\Controller; // HARUS extend Controller bawaan Laravel

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user'); // pakai model LogLogin

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        $logs = LogAktivitas::orderBy('created_at', 'desc')->get();

        return view('UserManagement.views.log_aktivitas', compact('logins')); // harus passing $logins
    }
}
