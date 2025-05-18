<?php

namespace App\Modules\UserManagement\Controllers;

use App\Models\Dosen;
use App\Models\Kaprodi;
use App\Models\AlokasiPembimbing;
use App\Models\Kbk;
use App\Modules\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Class DosenController
 * 
 * Controller untuk mengelola data dosen seperti tambah, update, delete, dan import data dosen.
 */
class DosenController extends Controller
{
    /**
     * Mengubah role dosen berdasarkan NIP.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_role(Request $request)
    {

    // Validasi input
    $request->validate([
        'nip' => 'required',
        'role' => 'required|in:dosen,koordinator_ta,kajur'
    ]);

    DB::beginTransaction();

    try {
        $nip = $request->nip;
        $old_role = Dosen::where('nip', $nip)->value('role_dosen');
 
        // Handle kajur uniqueness
        if ($request->role == "kajur" || $old_role == "kajur") {
            $kajur_lama = Dosen::where('role_dosen', 'kajur')->first();
            if ($kajur_lama) {
                $kajur_lama->update([
                    'role_dosen' => 'dosen'
                ]);
            }
            $exists = Kaprodi::where('nip', $nip)->exists();
            if($exists){
                return redirect()->route('manage.dosen')->with('error', 'Gagal memperbarui role: Dosen ini sudah menjadi Kaprodi!');
            }
        }

            
        // Update selected dosen's role
        Dosen::where('nip', $nip)->update([
            'role_dosen' => $request->role
        ]);

        DB::commit();

        return redirect()->route('manage.dosen')->with('success', 'Perubahan role berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('manage.dosen')->with('error', 'Gagal memperbarui role: ' . $e->getMessage());
        }
        // Harus Ganti Template
        Notifikasi::kirim(
            '[Pemberitahuan] Pengajuan Jadwal Seminar/Sidang Baru Oleh Mahasiswa!',
            $nip, // Kirim notifikasi ke NIP dosen yang diupdate
            []
        );
    }

    /**
     * Menambahkan dosen baru ke sistem.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add_new_dosen(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:user,username',
            'email' => 'required|email|unique:user,email|regex:/^[a-zA-Z0-9._%+-]+@polban\.ac\.id$/',
            'nama' => 'required|string',
            'id' => 'required|string|unique:dosen,id_dosen',
            'kode' => 'required|string|unique:dosen,kode_dosen',
            'no_wa' => ['required', 'between:1,15',  'regex:/^[0-9\-]+$/'],
            'status_dosen' => 'required',
            'id_kbk' => 'required',
        ],[
            'nip.unique' => 'NIP sudah digunakan, gunakan NIP yang lain',
            'nip.required' => 'NIP wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Harap menggunakan email Polban dengan domain @polban.ac.id',
            'nama.required' => 'Nama wajib diisi.',
            'id.required' => 'ID Dosen wajib diisi.',
            'kode.required' => 'Kode Dosen wajib diisi.',
            'id_kbk.required' => 'ID KBK wajib diisi.',
            'email.unique' => 'Email sudah terdaftar, gunakan email yang lain',
            'id.unique' => 'ID Dosen sudah terdaftar, gunakan ID yang lain',
            'kode.unique' => 'Kode Dosen sudah terdaftar, gunakan kode yang lain',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi. Jika kosong isi dengan -',
            'no_wa.between' => 'Nomor WhatsApp maksimal sampai 15 digit. ',
            'no_wa.regex' => 'Nomor WhatsApp hanya boleh berisi angka.'
        ]);

        DB::beginTransaction();
        try {
            $password = Str::random(8);
            $user = User::create([
                'username' => $request->nip,
                'email' => $request->email,
                'nama' => $request->nama,
                'no_whatsapp' => $request->no_wa,
                'photo' => 'default.jpg',
                'role_user' => 'dosen',
                'password' => Hash::make($password)
            ]);

            Dosen::create([
                'nip' => $request->nip,
                'id_dosen' => $request->id,
                'kode_dosen' => $request->kode,
                'id_kbk' => $request->id_kbk,
                'status_dosen' => $request->status_dosen,
                'role_dosen' => 'dosen'
            ]);

            DB::commit();
            return redirect()->route('manage.dosen')->with('success', 'Dosen berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('manage.dosen')->with('error', 'Gagal menambahkan dosen: ' . $e->getMessage());
        }
        // Harus Ganti Template
        Notifikasi::kirim(
            '[Pemberitahuan] Pengajuan Jadwal Seminar/Sidang Baru Oleh Mahasiswa!',
            $request->nip, // Kirim notifikasi ke NIP dosen yang baru dibuat
            []
        );
    }



    /**
     * Memperbarui data dosen.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDosen(Request $request)
    {
        $dosen = Dosen::where('nip', $request->nip)->first();
        $user = User::where('username', $request->nip)->first();
        $request->validate([
            'nip' => [
                'required',
                Rule::unique('user', 'username')->ignore($user->username,'username'),
            ],
            'email' => [
                'required','email','regex:/^[a-zA-Z0-9._%+-]+@polban\.ac\.id$/',
                Rule::unique('user', 'email')->ignore($user->username,'username'),
            ],
            'nama' => 'required|string',
            'id' => [
                'required',
                'string',
                Rule::unique('dosen', 'id_dosen')->ignore($dosen->nip,'nip'),
            ],
            'kode' => [
                'required',
                'string',
                Rule::unique('dosen', 'kode_dosen')->ignore($dosen->nip,'nip'),
            ],
            'no_wa' => ['required', 'between:1,15',  'regex:/^[0-9\-]+$/'],
            'id_kbk' => 'required',
        ], [
            'nip.unique' => 'NIP sudah digunakan, gunakan NIP yang lain',
            'email.unique' => 'Email sudah terdaftar, gunakan email yang lain',
            'id.unique' => 'ID Dosen sudah terdaftar, gunakan ID yang lain',
            'kode.unique' => 'Kode Dosen sudah terdaftar, gunakan kode yang lain',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi. Jika kosong isi dengan -',
            'no_wa.between' => 'Nomor WhatsApp maksimal sampai 15 digit. ',
            'no_wa.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'nip.required' => 'NIP wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Harap menggunakan email Polban dengan domain @polban.ac.id',
            'nama.required' => 'Nama wajib diisi.',
            'id.required' => 'ID Dosen wajib diisi.',
            'kode.required' => 'Kode Dosen wajib diisi.',
            'id_kbk.required' => 'KBK wajib diisi.',
        ]);

        $nip = $request->nip;

        DB::beginTransaction();
        try {
        $user = User::where('username', $nip)->first();
        $user->update([
            'username' => $request->nip,
            'email' => $request->email,
            'nama' => $request->nama,
            'no_whatsapp' => $request->no_wa,
        ]);

        $dosen = Dosen::where('nip', $request->nip)->first();
        $dosen->update([
            'id_dosen' => $request->id,
            'kode_dosen' => $request->kode,
            'id_kbk' => $request->id_kbk,
        ]);
        DB::commit();
        return redirect()->route('manage.dosen')->with('success', "Dosen berhasil diubah!");
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('manage.dosen')->with('error', 'Gagal merubah dosen: ' . $e->getMessage());
    }
  
    }

    /**
     * Mengimpor data dosen dari file Excel.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $columns = $sheet->toArray();

        array_shift($columns);

        $data = [];
        foreach ($columns as $col) {
            if (empty($col[0]) || empty($col[1]) || empty($col[2]) || empty($col[3])) {
                continue;
            }

            $data[] = [
                'username' => $col[0],
                'nama' => $col[1],
                'email' => $col[2],
                'id_dosen' => $col[3],
                'kode_dosen' => $col[4],
                'no_wa' => $col[5],
                'emailExist' => User::where('email', $col[2])->exists(),
                'usernameExist' => User::where('username', $col[0])->exists(),
                'kodeExist' => Dosen::where('kode_dosen', $col[4])->exists(),
                'idExist' => Dosen::where('id_dosen', $col[3])->exists()
            ];
        }

        return redirect()->route('previewDataDosen')->with('importedData', $data);
    }

    /**
     * Menampilkan preview data dosen hasil import sebelum disimpan ke database.
     *
     * @return \Illuminate\View\View
     */
    public function previewDataDosen()
    {
        $data = session('importedData', []);
        $kbk = Kbk::all();
        return view('UserManagement.views.preview-data-dosen', compact('data', 'kbk'));
    }

    /**
     * Menyimpan data dosen hasil import dalam jumlah banyak (bulk).
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inputBulk(Request $request)
    {
        $validatedData = $request->validate([
            'data.*.username' => 'required|string|unique:user,username',
            'data.*.nama' => 'required|string',
            'data.*.email' => 'required|email|unique:user,email|regex:/^[a-zA-Z0-9._%+-]+@polban\.ac\.id$/',
            'data.*.id_dosen' => 'required|unique:dosen,id_dosen',
            'data.*.kode_dosen' => 'required|unique:dosen,kode_dosen',
            'data.*.no_wa' => ['required', 'between:1,15',  'regex:/^[0-9\-]+$/'],
            'data.*.id_kbk' => 'required',
        ],[
            'data.*.username.unique' => 'Terdapat NIP yang sudah digunakan, gunakan NIP yang lain',
            'data.*.email.unique' => 'Terdapat Email yang sudah terdaftar, gunakan email yang lain',
            'data.*.id_dosen.unique' => 'Terdapat ID Dosen yang sudah terdaftar, gunakan ID yang lain',
            'data.*.kode_dosen.unique' => 'Terdapat Kode Dosen yang sudah terdaftar, gunakan kode yang lain',
            'data.*.no_wa.required' => 'Nomor WhatsApp wajib diisi. Jika kosong isi dengan -',
            'data.*.no_wa.between' => 'Nomor WhatsApp maksimal sampai 15 digit. ',
            'data.*.no_wa.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'data.*.id_kbk.required' => 'Silakan pilih KBK.',
            'data.*.username.required' => 'NIP wajib diisi.',
            'data.*.email.required' => 'Email wajib diisi.',
            'data.*.email.regex' => 'Harap menggunakan email Polban dengan domain @polban.ac.id',
            'data.*.email.email' => 'Format email tidak valid.',
            'data.*.nama.required' => 'Nama wajib diisi.',
            'data.*.id_dosen.required' => 'ID Dosen wajib diisi.',
            'data.*.kode_dosen.required' => 'Kode Dosen wajib diisi.',
        ]);


        $usernames = array_column($validatedData['data'], 'username');
        $emails = array_column($validatedData['data'], 'email');
        $id_dosen = array_column($validatedData['data'], 'id_dosen');
        $kode_dosen = array_column($validatedData['data'], 'kode_dosen');

        if (count($usernames) !== count(array_unique($usernames)) ||
            count($emails) !== count(array_unique($emails)) ||
            count($id_dosen) !== count(array_unique($id_dosen)) ||
            count($kode_dosen) !== count(array_unique($kode_dosen))) {
            return back()->withErrors(['msg' => 'Terdapat duplikat dalam file!']);
        }

        $users = [];
        $dosen = [];
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
                'role_user' => 'dosen',
                'password' => Hash::make($password)
            ];

            $dosen[] = [
                'nip' => $userData['username'],
                'kode_dosen' => $userData['kode_dosen'],
                'id_dosen' => $userData['id_dosen'],
                'id_kbk' => $userData['id_kbk']
            ];
        }

        User::insert($users);
        Dosen::insert($dosen);
        DB::commit();

        // Harus Ganti Template
        if (!empty($users)) {
            foreach ($users as $user) {
                Notifikasi::kirim(
                    '[Pemberitahuan] Pengajuan Jadwal Seminar/Sidang Baru Oleh Mahasiswa!',
                    $user['username'], // Kirim notifikasi ke username akun yang dibuat
                    []
                );
            }
        }
        return redirect()->route('manage.dosen')->with('success', 'Data dosen berhasil dimasukkan dan diimport!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('manage.dosen')->with('error', 'Data dosen gagal dimasukkan: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui role beberapa dosen sekaligus.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBulkRole(Request $request)
    {
        $request->validate([
            'nip' => 'required|array',
            'role_dosen' => 'required|string',
        ]);
        Dosen::whereIn('nip', $request->nip)->update(['role_dosen' => $request->role_dosen]);
        return redirect()->route('manage.dosen')->with('success', 'Role dosen berhasil diperbarui!');
    }
    public function aktifkanAkun(Request $request)
    {
        $request->validate([
            'nip' => 'required',
        ]);

        DB::beginTransaction();
        try {
            User::where('username', $request->nip)->update(['status_user' => 'aktif']);
            DB::commit();
            return redirect()->route('manage.dosen')->with('success', 'Dosen berhasil diaktifkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('manage.dosen')->with('error', 'Gagal mengaktifkan dosen: ' . $e->getMessage());
        }
    }
    public function nonAktifkanAkun(Request $request)
    {
        $request->validate([
            'nip' => 'required',
        ]);
        DB::beginTransaction();
        try {
            User::where('username', $request->nip)->update(['status_user' => 'nonaktif']);
            DB::commit();
            return redirect()->route('manage.dosen')->with('success', 'Dosen berhasil dinonaktifkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('manage.dosen')->with('error', 'Gagal menonaktifkan dosen: ' . $e->getMessage());
        }
    }
}
