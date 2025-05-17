<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use App\Models\Kbk; 
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
        // Validasi input
        $request->validate([
            'kbk' => 'required|string|max:100'
        ]);

     
        Kbk::create([
            'kbk' => $request->kbk
        ]);

        return redirect()->route('kelola-kbk')->with('success', 'KBK berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'kbk' => 'required|string|max:100'
        ]);

        // Update data KBK
        Kbk::where('id_kbk', $id)->update([
            'kbk' => $request->kbk
        ]);

        return redirect()->route('kelola-kbk')->with('success', 'KBK berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Hapus KBK berdasarkan ID
        Kbk::where('id_kbk', $id)->delete();

        return redirect()->route('kelola-kbk')->with('success', 'KBK berhasil dihapus!');
    }
}
