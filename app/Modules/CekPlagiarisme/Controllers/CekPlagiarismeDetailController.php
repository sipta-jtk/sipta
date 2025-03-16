<?php

namespace App\Modules\CekPlagiarisme\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class CekPlagiarismeDetailController extends Controller
{

    public function show($id): View
    {
        // Data dummy untuk detail dokumen
        $dokumen = (object) [
            'id' => $id,
            'judul' => 'Implementasi Algoritma Naive',
            'waktu' => '28-02-2025 14:20:14',
            'penulis' => 'Mumun Sumumun',
            'file' => null, // Jika ingin menampilkan file, gunakan 'contoh.pdf'
            'isi' => 'Ini adalah contoh isi dokumen.',
            'presentase' => 50,
            'komentar' => 'Gunakan sumber referensi yang sahih, minimal Sinta 3',
        ];

        return view('CekPlagiarisme.views.detail', compact('dokumen'));
    }   
}