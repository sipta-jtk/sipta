<?php

namespace App\Modules\UserManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user')
            ->where('action', 'login'); // hanya aktivitas login

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        $logins = $query->orderByDesc('waktu_aktivitas')->get();

        return view('UserManagement.views.log_aktivitas', compact('logins'));
    }
}
