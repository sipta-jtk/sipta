<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use App\Models\Kbk; 
use App\Models\Dosen;
use Illuminate\Http\Request;

class KBKController extends Controller
{
    public function index()
    {
        // Ambil semua data dari tabel Kbk
        $kbkList = Kbk::all();

        return view('UserManagement.views.kbk', compact('kbkList'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'kbk' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9\s]+$/',
                'unique:kbk,kbk'
            ]
        ], [
            'kbk.regex' => 'KBK hanya boleh mengandung huruf, angka, dan spasi.',
            'kbk.unique' => 'KBK sudah ada.'
        ]);

        Kbk::create([
            'kbk' => $request->kbk
        ]);
            
        return redirect()->route('kelola-kbk')->with('success', 'KBK berhasil ditambahkan!');
    }

   public function update(Request $request, $id)
    {
        
        $request->validate([
            'kbk' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9\s]+$/',
                'unique:kbk,kbk,' . $id . ',id_kbk'
            ]
        ], [
            'kbk.regex' => 'Nama KBK hanya boleh mengandung huruf, angka, dan spasi.',
            'kbk.unique' => 'Nama KBK sudah ada.'
        ]);

        Kbk::where('id_kbk', $id)->update([
            'kbk' => $request->kbk
        ]);

        return redirect()->route('kelola-kbk')->with('success', 'KBK berhasil diperbarui!');
    }


    public function destroy($id)
    {
        // Cek apakah ada dosen yang menggunakan id_kbk ini
        $jumlahDosen = Dosen::where('id_kbk', $id)->count();

        if ($jumlahDosen > 0) {
            return redirect()->route('kelola-kbk')->with('error', 'Tidak dapat menghapus KBK karena masih digunakan ');
        }

        // Lanjutkan penghapusan jika tidak digunakan
        Kbk::where('id_kbk', $id)->delete();

        return redirect()->route('kelola-kbk')->with('success', 'KBK berhasil dihapus!');
    }
}