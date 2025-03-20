<?php

namespace App\Modules\Repository\Controllers;

use Illuminate\Http\Request;
use App\Models\Subkategori;
use Illuminate\Routing\Controller;

class SubkategoriController extends Controller
{
    // In SubkategoriController.php
    public function store(Request $request)
    {
        $request->validate([
            'nama_subkategori' => 'required|string|max:255',
        ]);

        $subkategori = Subkategori::create([
            'nama_subkategori' => $request->nama_subkategori,
        ]);

        return response()->json([
            'success' => true,
            'subkategori' => $subkategori,
        ]);
    }
}
