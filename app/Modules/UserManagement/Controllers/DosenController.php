<?php

namespace App\Modules\UserManagement\Controllers;

use App\Models\Dosen;
use App\Modules\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DosenController extends Controller
{


                public function update_role(Request $request)
                {

                    
                    $request->validate([
                        'nip' => 'required',
                        'role' => 'required|in:dosen,koordinator_ta,kajur'
                    ]);
                    $nip = $request->nip;
                    $old_role = Dosen::where('nip', $nip)->value('role_dosen');
                    if($request->role == "kajur" || $old_role == "kajur"){
                        exit;
                    }

                    Dosen::where('nip', $nip)->update([
                        'role_dosen' => $request->role
                    ]);

                    return redirect()->route('manage.dosen');
                }

                public function add_new_dosen(Request $request){
                    $request->validate([
                        'nip' => 'required|unique:dosen,nip',
                        'email' => 'required|email|unique:user,email',
                        'nama' => 'required|string|',
                        'id' => 'required|string',
                        'kode' => 'required|string',
                        'max_d4' => 'required|integer|min:0',
                        'max_d3' => 'required|integer|min:0',
                        'no_wa' => 'required'
                    ]);


                    $randomCode = Str::random(7);
                    $user = User::create([
                        'username' => $request->nip,
                        'email' => $request->email,
                        'nama' => $request->nama,
                        'no_whatsapp' => $request->no_wa,
                        'photo' => "default.jpg",
                        'password' => $randomCode 
                    ]);

                    $user->assignRole('dosen');

                    // 2. Simpan data ke tabel `dosen`
                    Dosen::create([
                        'nip' => $request->nip,
                        'id_dosen' => $request->id,
                        'kode_dosen' => $request->kode,
                        'maks_bimbingan_d4' => $request->max_d4,
                        'maks_bimbingan_d3' => $request->max_d3,
                        'status_dosen' => 'aktif',
                        'role_dosen' => 'dosen'
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

