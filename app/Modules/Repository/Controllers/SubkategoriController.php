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
        
        // Return view with data
        return view('Repository.views.subkategori', [
            'subkategoris' => $subkategoris,
            'kategori' => $kategori
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
}