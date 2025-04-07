<?php

namespace App\Modules\UserManagement\Controllers;

use App\Models\Dosen;
use App\Models\AlokasiPembimbing;
use App\Models\Kbk;
use App\Modules\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


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
            $kajur_lama = Dosen::where('role_dosen', 'kajur')->first();
            $kajur_lama->update([
                'role_dosen' => 'dosen'
            ]);
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
            'no_wa' => 'required',
            'status_dosen' => 'required',
            'id_kbk' => 'required',
        ]);

        DB::beginTransaction();
        try{
        $randomCode = Str::random(7);
        $user = User::create([
            'username' => $request->nip,
            'email' => $request->email,
            'nama' => $request->nama,
            'no_whatsapp' => $request->no_wa,
            'photo' => 'default.jpg',
            'role_user' => 'dosen',
            'password' => Hash::make($randomCode) 
        ]);

        $email = $request->email;
        $nama = $request->nama;
        Mail::raw("Halo $nama, berikut adalah password untuk sipta anda : $randomCode", function ($message)  use($email,$nama,$randomCode){
            $message->to($email)
                    ->from('pemberitahuan.tugas.akhir@gmail.com', 'sipta')
                    ->subject('Info Akun Sipta');
        });




        Dosen::create([
            'nip' => $request->nip,
            'id_dosen' => $request->id,
            'kode_dosen' => $request->kode,
            'id_kbk' => $request->id_kbk,
            'status_dosen' => $request->status_dosen,
            'role_dosen' => 'dosen'
        ]);

        DB::commit();
        // Commit transaksi jika semua berhasil
        return redirect()->route('manage.dosen')->with('success', 'Dosen berhasil ditambahkan!');

    } catch (\Exception $e) {
        // Rollback jika terjadi kesalahan
        DB::rollBack();

        return redirect()->route('manage.dosen')->with('error', 'Gagal menambahkan dosen: ' . $e->getMessage());
    }

        

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
        'no_wa' => 'required',
        'status_dosen'=> 'required',
        'id_kbk' => 'required',
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
        'status_dosen' => $request->status_dosen,
        'id_kbk' => $request->id_kbk,
        
    ]);

    return redirect()->route('manage.dosen')->with('success', 'Dosen berhasil Diubah!');                    
}
    
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls',
    ]);

    $file = $request->file('file');
    $spreadsheet = IOFactory::load($file->getPathname());
    $sheet = $spreadsheet->getActiveSheet();
    $columns = $sheet->toArray();

    // Skip the header row
    array_shift($columns);
    
    $data = [];
    foreach ($columns as $col) {
        // dd($col); // Tampilkan data untuk debugging

        if (empty($col[0]) || empty($col[1]) || empty($col[2]) || empty($col[3])) {
            continue; // Skip baris jika ada data yang kosong
        }
        $isUsernameExisting = User::where('username', $col[0])->exists(); // Cek ke database
        $isEmailExisting = User::where('email', $col[2])->exists(); // Cek ke database
        $isIdExisting = Dosen::where('id_dosen', $col[3])->exists();
        $isKodeExisting = Dosen::where('kode_dosen', $col[4])->exists();
        
        $data[] = [
            'username' => $col[0],
            'nama' => $col[1],
            'email' => $col[2],
            'id_dosen' => $col[3],
            'kode_dosen' => $col[4],
            'no_wa' => $col[5],
            'emailExist' => $isEmailExisting,
            'usernameExist' => $isUsernameExisting,
            'kodeExist' => $isKodeExisting,
            'idExist' => $isIdExisting
        ];
        
    }
    
    return redirect()->route('previewDataDosen')->with('importedData', $data);

}

public function previewDataDosen()
{
    $data = session('importedData', []);
    $kbk = Kbk::all();
    return view('UserManagement.views.preview-data-dosen', compact('data', 'kbk'));
}

public function inputBulk(Request $request){
    $validatedData = $request->validate([
        'data.*.username' => 'required|string|unique:user,username',
        'data.*.nama' => 'required|string',
        'data.*.email' => 'required|email|unique:user,email',
        'data.*.id_dosen' => 'required|unique:dosen,id_dosen',
        'data.*.kode_dosen' => 'required|unique:dosen,kode_dosen',
        'data.*.no_wa' => 'required|string',
        'data.*.id_kbk' => 'required',
    ]);
    $usernames = array_column($validatedData['data'], 'username');
    $emails = array_column($validatedData['data'], 'email');
    $id_dosen = array_column($validatedData['data'], 'id_dosen');
    $kode_dosen = array_column($validatedData['data'], 'kode_dosen');


    if (count($usernames) !== count(array_unique($usernames))) {
        return back()->withErrors(['msg' => 'Terdapat username yang duplikat dalam file!']);
    }

    if (count($emails) !== count(array_unique($emails))) {
        return back()->withErrors(['msg' => 'Terdapat email yang duplikat dalam file!']);
    }

    if (count($id_dosen) !== count(array_unique($id_dosen))) {
        return back()->withErrors(['msg' => 'Terdapat email yang duplikat dalam file!']);
    }
    if (count($kode_dosen) !== count(array_unique($kode_dosen))) {
        return back()->withErrors(['msg' => 'Terdapat email yang duplikat dalam file!']);
    }


    $users = [];
    $dosen = [];

    foreach ($validatedData['data'] as $userData) {
        $randomCode = \Str::random(8); // Atau gunakan default password

        $users[] = [
            'username' => $userData['username'],
            'email' => $userData['email'],
            'nama' => $userData['nama'],
            'no_whatsapp' => $userData['no_wa'],
            'photo' => 'default.jpg',
            'role_user' => 'dosen',
            'password' => Hash::make($randomCode)
        ];

        $dosen[] = [
            'nip' => $userData['username'],
            'kode_dosen' => $userData['kode_dosen'],
            'id_dosen' => $userData['id_dosen'],
            'id_kbk' => $userData['id_kbk']
        ];

    $email = $userData['email'];
    $nama = $userData['nama'];
    Mail::raw("Halo $nama, berikut adalah password untuk sipta anda : $randomCode", function ($message)  use($email,$nama,$randomCode){
        $message->to($email)
                ->from('pemberitahuan.tugas.akhir@gmail.com', 'sipta')
                ->subject('Info Akun Sipta');
    });


    }

    // 3. Bulk Insert
    User::insert($users);
    Dosen::insert($dosen);

    return redirect()->route('manage.dosen')->with('success', 'Data dosen berhasil diimport!');

}

public function updateBulkRole(Request $request)
{
    $request->validate([
        'nip' => 'required|array',
        'role_dosen' => 'required|string',
    ]);

    Dosen::whereIn('nip', $request->nip)->update(['role_dosen' => $request->role_dosen]);

    return redirect()->route('manage.dosen')->with('success', 'Role dosen berhasil diperbarui!');

}

}

