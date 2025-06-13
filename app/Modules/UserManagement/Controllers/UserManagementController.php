<?php

namespace App\Modules\UserManagement\Controllers;

use App\Models\Kaprodi;
use App\Models\Kbk;
use App\Models\Kota;
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
        ->select('mahasiswa.*', 'user.nama', 'user.status_user' , 'prodi.nama_prodi' ,'user.email', 'user.no_whatsapp');
        if ($user->role_user != 'kajur') {
            $kaprodi = Kaprodi::where('nip', $user->username)->first();
            $query = $query->where('mahasiswa.id_prodi', $kaprodi->id_prodi);
        }
        $mahasiswa = $query->get();
        return view('UserManagement.views.show_mhs', compact('mahasiswa'));
    }

  public function show_kelompok_ta(){
    $user = Auth::user(); 

    $query = DB::table('kota')
        ->join('mahasiswa', 'mahasiswa.id_kota', '=', 'kota.id_kota')
        ->join('user', 'user.username', '=', 'mahasiswa.nim')
        ->join('prodi', 'mahasiswa.id_prodi', '=', 'prodi.id_prodi')
        ->select('kota.id_kota','kota.tahun_kota', 'kota.nama_kota','kota.judul_ta','kota.status_kota') 
        ->orderBy('kota.tahun_kota', 'desc')
        ->distinct(); 
    if ($user->role_user != 'kajur') {
        $kaprodi = Kaprodi::where('nip', $user->username)->first();
        $query = $query->where('mahasiswa.id_prodi', $kaprodi->id_prodi);
    }
    $kota = $query->get();
    return view('UserManagement.views.show_kota', compact('kota'));
}
public function getDetailKotaProdi($id)
{
    $kota = DB::table('mahasiswa')
        ->join('user', 'user.username', '=', 'mahasiswa.nim')
        ->join('kota', 'mahasiswa.id_kota', '=', 'kota.id_kota')
        ->where('kota.id_kota', $id)
        ->select('mahasiswa.nim','mahasiswa.kelas','user.nama')
        ->get();
    $bidang = DB::table('kota')
        ->leftjoin('bidang', 'kota.id_bidang', '=', 'bidang.id_bidang')
        ->where('kota.id_kota', $id)
        ->select('bidang.bidang')   
        ->first();

    return response()->json([
        'kota' => $kota,
        'bidang' => $bidang
    ]);
}
}

