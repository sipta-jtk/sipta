<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class UserManagementController extends Controller
{
    // public function render()
    // {
    //     return view('UserManagement.views.manage_role');
    // }
    public function logout()
    {
        Auth::guard('web')->logout(); // Logout user
        Session::flush(); // Hapus semua sesi

        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login');
    }

    public function manageDosen()
    {   
        $dosen = DB::table('dosen')
        ->join('user', 'dosen.nip', '=', 'user.username')
        ->select('dosen.*', 'user.nama', 'user.email', 'user.no_whatsapp')
        ->get();    
        return view('UserManagement.views.manage_dosen', compact('dosen'));
    }

    public function manage_mhs()
    {
        $mahasiswa = DB::table('mahasiswa')
        ->join('user', 'mahasiswa.nim', '=', 'user.username')
        ->select('mahasiswa.*', 'user.nama', 'user.email', 'user.no_whatsapp')
        ->get();
        return view('UserManagement.views.manage_mhs', compact('mahasiswa'));
    }



}

