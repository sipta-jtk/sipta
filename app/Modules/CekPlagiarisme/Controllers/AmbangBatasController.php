<?php

namespace App\Modules\CekPlagiarisme\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\AmbangBatas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AmbangBatasController extends Controller
{
    public function getData()
    {
        // Ambil semua data dari database beserta relasi dosen
        $ambangBatas = AmbangBatas::with('dosen.user')->get();

        // Format data agar sesuai dengan struktur jsGrid
        $data = $ambangBatas->map(function ($item) {
            return [
                'id' => $item->id_ambang_batas,
                'ambang_batas' => $item->ambang_batas,
                'tanggal' => $item->created_at->format('Y-m-d H:i:s'),
                'koordinator' => $item->dosen && $item->dosen->user ? $item->dosen->user->nama : 'Tidak Ada', // Ambil nama dosen dari user
                'status' => ucfirst(str_replace('_', ' ', $item->status_ambang_batas)) // Ubah menjadi format yang lebih rapi
            ];
        });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'ambang_batas' => 'required|numeric|min:1|max:100',
        ]);

        DB::transaction(function () use ($request) {
            // **1. Ubah semua status menjadi "tidak_digunakan"**
            AmbangBatas::query()->update(['status_ambang_batas' => 'tidak_digunakan']);

            // **2. Tambahkan data baru dengan status "digunakan"**
            AmbangBatas::create([
                'ambang_batas' => $request->ambang_batas,
                'status_ambang_batas' => 'digunakan',
                'nip' => 198502102015042001, // Default NULL
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Ambang Batas berhasil ditambahkan!',
        ]);
    }
}
