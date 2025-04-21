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
        $request->validate([
            'nip' => 'required',
            'role' => 'required|in:dosen,koordinator_ta,kajur'
        ]);

        $nip = $request->nip;
        $old_role = Dosen::where('nip', $nip)->value('role_dosen');

        if ($request->role == "kajur" || $old_role == "kajur") {
            $kajur_lama = Dosen::where('role_dosen', 'kajur')->first();
            if ($kajur_lama) {
                $kajur_lama->update([
                    'role_dosen' => 'dosen'
                ]);
            }
        }

        Dosen::where('nip', $nip)->update([
            'role_dosen' => $request->role
        ]);

        return redirect()->route('manage.dosen');
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
            'nip' => 'required|unique:dosen,nip',
            'email' => 'required|email|unique:user,email',
            'nama' => 'required|string',
            'id' => 'required|string',
            'kode' => 'required|string',
            'no_wa' => 'required',
            'status_dosen' => 'required',
            'id_kbk' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $randomCode = "password123";
            $user = User::create([
                'username' => $request->nip,
                'email' => $request->email,
                'nama' => $request->nama,
                'no_whatsapp' => $request->no_wa,
                'photo' => 'default.jpg',
                'role_user' => 'dosen',
                'password' => Hash::make($randomCode)
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
    }

    /**
     * Menghapus dosen berdasarkan NIP.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Memperbarui data dosen.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDosen(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'email' => 'required',
            'nama' => 'required|string',
            'id' => 'required|string',
            'kode' => 'required|string',
            'no_wa' => 'required',
            'status_dosen'=> 'required',
            'id_kbk' => 'required',
        ]);

        $nip = $request->nip;

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
            'status_dosen' => $request->status_dosen,
            'id_kbk' => $request->id_kbk,
        ]);

        return redirect()->route('manage.dosen')->with('success', 'Dosen berhasil Diubah!');
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

        if (count($usernames) !== count(array_unique($usernames)) ||
            count($emails) !== count(array_unique($emails)) ||
            count($id_dosen) !== count(array_unique($id_dosen)) ||
            count($kode_dosen) !== count(array_unique($kode_dosen))) {
            return back()->withErrors(['msg' => 'Terdapat duplikat dalam file!']);
        }

        $users = [];
        $dosen = [];

        foreach ($validatedData['data'] as $userData) {
            $randomCode = "password123";

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
        }

        User::insert($users);
        Dosen::insert($dosen);

        return redirect()->route('manage.dosen')->with('success', 'Data dosen berhasil diimport!');
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
}
