<?php

namespace App\Modules\Repository\Controllers;

use Illuminate\Http\Request;
use App\Models\Subkategori;
use Illuminate\Routing\Controller;

class SubkategoriController extends Controller
{
    public function index($kategori)
    {
        // Fetch all subcategories
        $subkategoris = Subkategori::all();
        $id_kota = auth()->user()->mahasiswa->id_kota ?? null;
        
        // Return view with data
        return view('Repository.views.subkategori', [
            'subkategoris' => $subkategoris,
            'kategori' => $kategori,
            'id_kota' => $id_kota
        ]);
    }

    public function store(Request $request, $kategori)
    {
        $request->validate([
            'nama_subkategori' => 'required|string|max:255',
        ]);

        $data = [
            'nama_subkategori' => $request->nama_subkategori,
        ];

        Subkategori::create($data);

        return redirect()->route('Subkategori.index', $kategori)->with('success', 'Subkategori berhasil ditambahkan');
    }

    public function destroy($kategori, $id)
    {
        $subkategori = Subkategori::findOrFail($id);
        $subkategori->delete();

        return redirect()->route('Subkategori.index', $kategori)->with('success', 'Subkategori berhasil dihapus');
    }

    public function update(Request $request, $kategori, $id)
    {
        $request->validate([
            'nama_subkategori' => 'required|string|max:255',
        ]);

        $subkategori = Subkategori::findOrFail($id);
        $subkategori->update([
            'nama_subkategori' => $request->nama_subkategori
        ]);

        return redirect()->route('Subkategori.index', $kategori)->with('success', 'Subkategori berhasil diperbarui');
    }

}