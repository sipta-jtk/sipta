<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    // public function render()
    // {
    //     return view('UserManagement.views.manage_role');
    // }

    public function manage_dosen()
    {   
        $dosen = DB::table('dosen')
        ->join('user', 'dosen.nip', '=', 'user.username')
        ->select('dosen.*', 'user.nama', 'user.email', 'user.no_whatsapp')
        ->get();
        return view('UserManagement.views.manage_dosen', compact('dosen'));
    }

    public function manage_mhs()
    {
        $dosen = DB::table('dosen')
        ->join('user', 'mahasiswa.nip', '=', 'user.username')
        ->select('dosen.*', 'user.nama', 'user.email', 'user.no_whatsapp')
        ->get();
        return view('UserManagement.views.manage_dosen', compact('dosen'));
    }



}

