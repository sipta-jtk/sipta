<?php

namespace App\Modules\UserManagement\Controllers;

use App\Models\Kaprodi;
use App\Models\Kbk;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Modules\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



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
            ->join('kbk', 'dosen.id_kbk', '=', 'kbk.id_kbk')
            ->select('dosen.*', 'user.nama', 'user.email', 'user.no_whatsapp','user.status_user')
            // ->orderBy('dosen.nip', 'asc')
            ->get();

        $listkbk = Kbk::all();
        return view('UserManagement.views.manage_dosen', compact('dosen', 'listkbk'));
    }

    public function manage_mhs()
    {
        $mahasiswa = DB::table('mahasiswa')
        ->join('user', 'mahasiswa.nim', '=', 'user.username')
        ->join('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id_prodi')
        ->select('mahasiswa.*', 'user.nama', 'prodi.nama_prodi' ,'user.email', 'user.no_whatsapp','user.status_user')
        ->get();

        $listprodi = Prodi::all();
        return view('UserManagement.views.manage_mhs', compact('mahasiswa', 'listprodi'));
    }

    public function show_mhs(){
        $user = Auth::user(); 
        $query = DB::table('mahasiswa')
        ->join('user', 'mahasiswa.nim', '=', 'user.username')
        ->join('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id_prodi')
        ->select('mahasiswa.*', 'user.nama', 'prodi.nama_prodi' ,'user.email', 'user.no_whatsapp');
        if ($user->role_user != 'kajur') {
            $kaprodi = Kaprodi::where('nip', $user->username)->first();
            $query = $query->where('mahasiswa.id_prodi', $kaprodi->id_prodi);
        }
        $mahasiswa = $query->get();

        $listprodi = Prodi::all();
        return view('UserManagement.views.show_mhs', compact('mahasiswa', 'listprodi'));
    }
}

