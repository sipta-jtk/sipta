<?php

namespace App\Modules\UserManagement\Controllers;

use App\Models\Dosen;
use App\Models\AlokasiPembimbing;

use App\Modules\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MahasiswaController extends Controller
{


 

    public function addNewMhs(Request $request){
        $request->validate([
            'nim' => 'required|unique:dosen,nip',
            'email' => 'required|email|unique:user,email',
            'nama' => 'required|string|',
        ]);


        $randomCode = Str::random(7);
        $user = User::create([
            'username' => $request->nip,
            'email' => $request->email,
            'nama' => $request->nama,
            'no_whatsapp' => $request->no_wa,
            'photo' => 'default.jpg',
            'role_user' => 'mahasiswa',
            'password' => $randomCode // Default password, bisa diubah nanti
        ]);

        // 2. Simpan data ke tabel `dosen`
        Dosen::create([
            'nim' => $request->nim,
            'tahun_masuk' => $request->tahun_masuk,
            'kelas' => $request->kelas,
            'id_prodi' => $request->id_prodi,
            'status_ta' => 'mahasiswa_non_ta',
        ]);

        // Commit transaksi jika semua berhasil
        return redirect()->route('manage.dosen')->with('success', 'Dosen berhasil ditambahkan!');

        

}
public function deleteDosen(Request $request)
{
    $request->validate([
        'nip' => 'required'
    ]);

    $nip = $request->nip;
    AlokasiPembimbing::where('nip', $nip)->delete();
    Dosen::where('nip', $nip)->delete();
    User::where('username', $nip)->delete();

    return redirect()->route('manage.dosen')->with('success', 'Dosen berhasil dihapus!');                    
}

public function updateDosen(Request $request)
{
    $request->validate([
        'nip' => 'required',
        'email' => 'required',
        'nama' => 'required|string|',
        'id' => 'required|string',
        'kode' => 'required|string',
        'max_d4' => 'required|integer|min:0',
        'max_d3' => 'required|integer|min:0',
        'no_wa' => 'required'
    ]);


    $nip = $request->nip;
    // if($dosen->nip != $nip){
    //     return redirect()->route('manage.dosen')->with('error', 'Dosen tidak ditemukan!');
    // }
   

    $user = User::where('username', $nip)->first();
    $user->update([
        'username' => $request->nip,
        'email' => $request->email,
        'nama' => $request->nama,
        'no_whatsapp' => $request->no_wa,
    ]);
    $dosen =  Dosen::where('nip', $request->nip)->first();
    $dosen->update([
        'id_dosen' => $request->id,
        'kode_dosen' => $request->kode,
        'maks_bimbingan_d4' => $request->max_d4,
        'maks_bimbingan_d3' => $request->max_d3,
    ]);

    return redirect()->route('manage.dosen')->with('success', 'Dosen berhasil dihapus!');                    
}

}

