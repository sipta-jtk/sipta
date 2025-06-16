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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Services\Notifikasi;
use App\Notifications\TestEmailNotification;

class MahasiswaController extends Controller
{
    public function addNewMhs(Request $request){
        $request->validate([
            'nim' => 'required|unique:user,username',
            'email' => [
                'required',
                'email',
                'unique:user,email',
                'regex:/^[a-zA-Z0-9._%+-]+@polban\.ac\.id$/'
            ],            
            'nama' => 'required|string|',
            'id_prodi' => 'required',
            'tahun_masuk' => 'required',
            'kelas' => 'required',
            'no_wa' => ['required', 'between:1,15',  'regex:/^[0-9\-]+$/']
        ],[
            'nim.unique' => 'NIM sudah terdaftar, silahkan masukkan NIM yang lain',
            'email.unique' => 'Email sudah terdaftar, silahkan masukkan email yang lain',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi. Jika kosong isi dengan -',
            'no_wa.digits_between' => 'Nomor WhatsApp maksimal sampai 15 digit.',
            'no_wa.regex' => 'Nomor WhatsApp hanya boleh berisi angka / -.',
            'id_prodi.required' => 'Program Studi tidak terpilih, Harus dipilih.',
            'tahun_masuk.required' => 'Tahun Masuk tidak terpilih, Harus dipilih.',
            'kelas.required' => 'Kelas tidak terpilih, Harus dipilih.',
            'nama.required' => 'Nama tidak boleh kosong.',
            'email.required' => 'Email tidak boleh kosong.',
            'nim.required' => 'NIM tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'nim.string' => 'NIM harus berupa string.',
            'email.regex' => 'Harap menggunakan email Polban dengan domain @polban.ac.id',
        ]);

        $password = Str::random(8);
        DB::beginTransaction();

        $prodi = Prodi::find($request->id_prodi);
        $prodiName = $prodi->nama_prodi;
        $prodiPrefix = substr($prodiName, 0, 2); // ambil 2 karakter pertama

        if ($prodiPrefix === 'D3') {
            $tahunTA = $request->tahun_masuk + 3;
        } elseif ($prodiPrefix === 'D4') {
            $tahunTA = $request->tahun_masuk + 4;
        }

        try {
        $user = User::create([
            'username' => $request->nim,
            'email' => $request->email,
            'nama' => $request->nama,
            'no_whatsapp' => $request->no_wa,
            'photo' => 'default.jpg',
            'role_user' => 'mahasiswa',
            'password' => Hash::make($password)
        ]);
        Mahasiswa::create([
            'nim' => $request->nim,
            'tahun_masuk' => $request->tahun_masuk,
            'kelas' => $request->kelas,
            'id_prodi' => $request->id_prodi,
            'status_ta' => 'mahasiswa_non_ta',
            'tahun_ta' => $tahunTA
        ]);

        DB::commit();

        // Send notification with error handling
        try {
            $user = User::where('username', $request->nim)->first();
            if ($user) {
                $user->notify(new TestEmailNotification(
                    '[Pemberitahuan] Akun Berhasil Dibuat',
                    [
                        'nama' => $request->nama,
                        'email' => $request->email,
                        'username' => $request->nim,
                        'password' => $password // Send plain text password only in email
                    ]
                ));
            }
        } catch (\Exception $notifEx) {
            \Log::error('Gagal mengirim notifikasi akun baru: ' . $notifEx->getMessage(), [
                'nim' => $request->nim,
                'nama' => $request->nama
            ]);
        }

        return redirect()->route('manage.mhs')->with('success', "Mahasiswa berhasil ditambahkan!");
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('manage.mhs')->with('error', 'Gagal menambah mahasiswa: ' . $e->getMessage());
    }
}
public function updateMhs(Request $request)
{
    $user_updated = User::where('username', $request->nim)->first();
    $request->validate([
        'nim' => [
            'required',
            Rule::unique('user', 'username')->ignore($user_updated->username,'username'),
            ],
        'email' => [
           'required',
            'email',
            'regex:/^[a-zA-Z0-9._%+-]+@polban\.ac\.id$/',
            Rule::unique('user', 'email')->ignore($user_updated->username,'username'),
            ],
        'nama' => 'required|string|',
        'tahun_ta' => 'required',
        'kelas' => 'required',
        'no_wa' => ['required', 'between:1,15',  'regex:/^[0-9\-]+$/'],
        'tahun_masuk' => 'required',
        'id_prodi' => 'required',
    ],[
        'nim.required' => 'NIM tidak boleh kosong.',
        'email.required' => 'Email tidak boleh kosong.',
        'email.email' => 'Format email tidak valid.',
        'email.regex' => 'Harap menggunakan email Polban dengan domain @polban.ac.id',
        'nim.unique' => 'NIM sudah terdaftar, silahkan masukkan NIM yang lain',
        'email.unique' => 'Email sudah terdaftar, silahkan masukkan email yang lain',
        'tahun_ta.required' => 'Tahun TA wajib diisi.',
        'no_wa.digits_between' => 'Nomor WhatsApp maksimal sampai 15 digit.',
        'no_wa.regex' => 'Nomor WhatsApp hanya boleh berisi angka / -.'
    ]); 


    $nim = $request->nim;
    DB::beginTransaction();
    try {
    $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
    // Cek jika tahun_ta berubah dan id_kota tidak null
    if ($mahasiswa->tahun_ta != $request->tahun_ta && $mahasiswa->id_kota !== null) {
        return redirect()->route('manage.mhs')->with('error', 'Tahun TA tidak dapat diubah karena sudah terdaftar kota.');
    }

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
        'tahun_ta' => $request->tahun_ta,
    ]);

    DB::commit();
    return redirect()->route('manage.mhs')->with('success', 'Data mahasiswa berhasil diubah!');           
} catch (\Exception $e) {
    DB::rollBack();
    return redirect()->route('manage.mhs')->with('error', 'Gagal mengubah mahasiswa: ' . $e->getMessage());
}
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
            'data.*.username' => 'required|string|unique:user,username|max:22',
            'data.*.nama' => 'required|string',
            'data.*.email' => 'required|email|unique:user,email|regex:/^[a-zA-Z0-9._%+-]+@polban\.ac\.id$/',
            'data.*.tahun_masuk' => 'required|integer',
            'data.*.kelas' => 'required|string',
            'data.*.no_wa' => ['required', 'between:1,15',  'regex:/^[0-9\-]+$/'],
            'data.*.id_prodi' => 'required|integer',
        ], [
            'data.*.username.required' => 'NIM tidak boleh kosong.',
            'data.*.username.max' => 'NIM melebihi batas maksimum.',
            'data.*.email.required' => 'Email tidak boleh kosong.',
            'data.*.email.email' => 'Format email tidak valid.',
            'data.*.email.regex' => 'Harap menggunakan email Polban dengan domain @polban.ac.id',
            'data.*.username.unique' => 'NIM sudah terdaftar, silahkan masukkan NIM yang lain',
            'data.*.email.unique' => 'Email sudah terdaftar, silahkan masukkan email yang lain',
            'data.*.no_wa.required' => 'Nomor WhatsApp wajib diisi. Jika kosong isi dengan -',
            'data.*.no_wa.digits_between' => 'Nomor WhatsApp maksimal sampai 15 digit.',
            'data.*.no_wa.regex' => 'Nomor WhatsApp hanya boleh berisi angka / -.',
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
        DB::beginTransaction();
        try {
        foreach ($validatedData['data'] as $userData) {
            $password = Str::random(8);    
            $users[] = [
                'username' => $userData['username'],
                'email' => $userData['email'],
                'nama' => $userData['nama'],
                'no_whatsapp' => $userData['no_wa'],
                'photo' => 'default.jpg',
                'role_user' => 'mahasiswa',
                'password' => Hash::make($password)
            ];

            $prodi = Prodi::find($userData['id_prodi']);
            $prodiName = $prodi->nama_prodi;
            $prodiPrefix = substr($prodiName, 0, 2); // ambil 2 karakter pertama
            if ($prodiPrefix === 'D3') {
                $tahunTA = $userData['tahun_masuk'] + 3;
            } elseif ($prodiPrefix === 'D4') {
                $tahunTA = $userData['tahun_masuk'] + 4;
            }
    
            $mahasiswa[] = [
                'nim' => $userData['username'],
                'tahun_masuk' => $userData['tahun_masuk'],
                'kelas' => $userData['kelas'],
                'id_prodi' => $userData['id_prodi'],
                'status_ta' => 'mahasiswa_non_ta',
                'tahun_ta' => $tahunTA
            ];
        $email = $userData['email'];
        $nama = $userData['nama']; 
        $tahunTA = null;
        }
    
        // 3. Bulk Insert
        User::insert($users);
        Mahasiswa::insert($mahasiswa);
        DB::commit();  

        // Send notifications to new users
        try {
            if (!empty($users)) {
                foreach ($users as $userData) {
                    $user = User::where('username', $userData['username'])->first();
                    if ($user) {
                        $user->notify(new TestEmailNotification(
                            '[Pemberitahuan] Akun Berhasil Dibuat',
                            [
                                'nama' => $userData['nama'],
                                'email' => $userData['email'],
                                'username' => $userData['username'],
                                'password' => $password // Send plain text password only in email
                            ]
                        ));
                    }
                }
            }
        } catch (\Exception $notifEx) {
            \Log::error('Gagal mengirim notifikasi akun baru: ' . $notifEx->getMessage(), [
                'username' => $userData['username'] ?? null
            ]);
        }
        return redirect()->route('manage.mhs')->with('success', 'Data mahasiswa berhasil diimport!');
    }
    catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('manage.mhs')->with('error', 'Gagal mengubah mahasiswa: ' . $e->getMessage());
    }
}

public function aktifkanAkun(Request $request)
    {
        $request->validate([
            'nim' => 'required',
        ]);

        DB::beginTransaction();
        try {
            User::where('username', $request->nim)->update(['status_user' => 'aktif']);
            DB::commit();
            return redirect()->route('manage.mhs')->with('success', 'Mahasiswa berhasil diaktifkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('manage.mhs')->with('error', 'Gagal mengaktifkan Mahasiswa: ' . $e->getMessage());
        }
    }
    public function nonAktifkanAkun(Request $request)
    {
        $request->validate([
            'nim' => 'required',
        ]);
        DB::beginTransaction();
        try {
            User::where('username', $request->nim)->update(['status_user' => 'nonaktif']);
            DB::commit();
            return redirect()->route('manage.mhs')->with('success', 'Mahasiswa berhasil dinonaktifkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('manage.mhs')->with('error', 'Gagal menonaktifkan Mahasiswa: ' . $e->getMessage());
        }
    }

}
