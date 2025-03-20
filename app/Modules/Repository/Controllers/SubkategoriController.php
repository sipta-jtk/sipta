<?php

namespace App\Modules\Repository\Controllers;

use Illuminate\Http\Request;
use App\Models\Subkategori;
use App\Modules\Controller;

class SubkategoriController extends Controller
{
    // In SubkategoriController.php

    // Akmal Goniyyu Hartono
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_subkategori' => 'required|string|max:255',
            ]);

            $subkategori = Subkategori::create([
                'nama_subkategori' => $request->nama_subkategori,
            ]);

            return response()->json([
                'success' => true,
                'subkategori' => $subkategori,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
