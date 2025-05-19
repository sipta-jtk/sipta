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
        // Validasi input dan cek duplikasi
        $request->validate([
            'kbk' => 'required|string|max:100|unique:kbk,kbk'
        ], [
            'kbk.unique' => 'KBK sudah ada.'
        ]);

        Kbk::create([
            'kbk' => $request->kbk
        ]);

        return redirect()->route('kelola-kbk')->with('success', 'KBK berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        // Validasi input dan cek duplikasi, kecuali yang sedang diedit
        $request->validate([
            'kbk' => 'required|string|max:100|unique:kbk,kbk,' . $id . ',id_kbk'
        ], [
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