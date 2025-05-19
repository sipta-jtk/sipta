<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use App\Http\Controllers\Controller;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        $logins = $query->orderBy('created_at', 'desc')->get();

        return view('UserManagement.views.log_aktivitas', compact('logins'));
    }
}
