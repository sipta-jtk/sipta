<?php

namespace App\Modules\UserManagement\Controllers;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Modules\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;



class MahasiswaController extends Controller
{

    public function addNewMhs(Request $request){
        $request->validate([
            'nim' => 'required|unique:mahasiswa,nim',
            'email' => 'required|email|unique:user,email',
            'nama' => 'required|string|',
            'id_prodi' => 'required',
            'tahun_masuk' => 'required',
            'kelas' => 'required',
            'no_wa' => 'required',
        ]);

        
        
        


        $randomCode = "password123";
        $user = User::create([
            'username' => $request->nim,
            'email' => $request->email,
            'nama' => $request->nama,
            'no_whatsapp' => $request->no_wa,
            'photo' => 'default.jpg',
            'role_user' => 'mahasiswa',
            'password' => Hash::make($randomCode) // Default password, bisa diubah nanti
        ]);

        

        // 2. Simpan data ke tabel `dosen`
        Mahasiswa::create([
            'nim' => $request->nim,
            'tahun_masuk' => $request->tahun_masuk,
            'kelas' => $request->kelas,
            'id_prodi' => $request->id_prodi,
            'status_ta' => 'mahasiswa_non_ta',
        ]);
        $email = $request->email;
        $nama = $request->nama;

        // Commit transaksi jika semua berhasil
        return redirect()->route('manage.mhs')->with('success', "Mahasiswa berhasil ditambahkan! Password: $randomCode");
        

}
public function updateMhs(Request $request)
{
    $request->validate([
        'nim' => 'required',
        'email' => 'required|email',
        'nama' => 'required|string|',
        'kelas' => 'required',
        'no_wa' => 'required',
        'tahun_masuk' => 'required',
        'id_prodi' => 'required',
    ]); 


    $nim = $request->nim;
   

    $user = User::where('username', $nim)->first();
    $user->update([
        'nama' => $request->nama,
        'no_whatsapp' => $request->no_wa,
    ]);
    $mahasiswa =  Mahasiswa::where('nim', $request->nim)->first();
    $mahasiswa->update([
        'kelas' => $request->kelas,
        'tahun_masuk' => $request->tahun_masuk,
        'id_prodi' => $request->id_prodi,
    ]);

    return redirect()->route('manage.mhs')->with('success', 'Data Mahasiswa berhasil diupdate!');                    
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
            $data[] = [
                'username' => $col[0],
                'nama' => $col[1],
                'email' => $col[2],
                'tahun_masuk' => $col[3],
                'kelas' => $col[4],
                'no_wa' => $col[5],
                'emailExist' => $isEmailExisting,
                'usernameExist' => $isUsernameExisting,
            ];
            
        }
        
        return redirect()->route('previewDataMhs')->with('importedData', $data);

    }

    public function previewDataMhs()
{
    $data = session('importedData', []);
    $prodi = Prodi::all();
    return view('UserManagement.views.preview-data-mhs', compact('data', 'prodi'));
}

    public function inputBulk(Request $request){
        $validatedData = $request->validate([
            'data.*.username' => 'required|string|unique:user,username',
            'data.*.nama' => 'required|string',
            'data.*.email' => 'required|email|unique:user,email',
            'data.*.tahun_masuk' => 'required|integer',
            'data.*.kelas' => 'required|string',
            'data.*.no_wa' => 'required|string',
            'data.*.id_prodi' => 'required|integer',
        ], [
            'data.*.id_prodi.required' => 'Silakan pilih program studi.',
        ]);
        $usernames = array_column($validatedData['data'], 'username');
        $emails = array_column($validatedData['data'], 'email');
    
        if (count($usernames) !== count(array_unique($usernames))) {
            return back()->withErrors(['msg' => 'Terdapat username yang duplikat dalam file!']);
        }
    
        if (count($emails) !== count(array_unique($emails))) {
            return back()->withErrors(['msg' => 'Terdapat email yang duplikat dalam file!']);
        }

        $users = [];
        $mahasiswa = [];
    
        foreach ($validatedData['data'] as $userData) {
            $randomCode = "password123"; // Atau gunakan default password
    
            $users[] = [
                'username' => $userData['username'],
                'email' => $userData['email'],
                'nama' => $userData['nama'],
                'no_whatsapp' => $userData['no_wa'],
                'photo' => 'default.jpg',
                'role_user' => 'mahasiswa',
                'password' => Hash::make($randomCode)
            ];
    
            $mahasiswa[] = [
                'nim' => $userData['username'],
                'tahun_masuk' => $userData['tahun_masuk'],
                'kelas' => $userData['kelas'],
                'id_prodi' => $userData['id_prodi'],
                'status_ta' => 'mahasiswa_non_ta'
            ];

        $email = $userData['email'];
        $nama = $userData['nama'];
 

        }
    
        // 3. Bulk Insert
        User::insert($users);
        Mahasiswa::insert($mahasiswa);
    
        return redirect()->route('manage.mhs')->with('success', 'Data mahasiswa berhasil diimport!');

    }

}