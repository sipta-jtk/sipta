<?php

namespace App\Modules\CekPlagiarisme\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use App\Models\Dokumen;
use App\Models\Dosen;
use App\Models\ReviewDosenPembimbing;


class CekPlagiarismeDetailController extends Controller
{
    public function index(): View
    {
        // Data dummy untuk daftar dokumen
        $cekPlagiarisme = [
            (object) [
                'id' => 1,
                'judul' => 'Implementasi Algoritma Naive',
                'waktu' => '28-02-2025 21:00:11',
                'penulis' => 'Maman Sumaman',
                'presentase' => null, // Masih dalam proses
                'komentar' => null,
            ],
            (object) [
                'id' => 2,
                'judul' => 'Implementasi Algoritma Naive',
                'waktu' => '28-02-2025 19:28:24',
                'penulis' => 'Maman Samaman',
                'presentase' => 15,
                'komentar' => null,
            ],
            (object) [
                'id' => 3,
                'judul' => 'Implementasi Algoritma Naive',
                'waktu' => '28-02-2025 14:20:14',
                'penulis' => 'Mumun Sumumun',
                'presentase' => 50,
                'komentar' => 'Gunakan sumber referensi yang sahih, minimal Sinta 3',
            ],
            (object) [
                'id' => 4,
                'judul' => 'Implementasi Algoritma Naive',
                'waktu' => '28-02-2025 09:30:45',
                'penulis' => 'Mimin Simimin',
                'presentase' => 80,
                'komentar' => 'Di Parafrase yaa!!',
            ],
        ];

        return view('CekPlagiarisme.views.DaftarDokumen', compact('cekPlagiarisme'));
    }

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

        // Mengambil review (komentar) terkait dokumen
        $catatan = ReviewDosenPembimbing::where('id_dokumen', $id)
            ->with('dosen')
            ->get(); // Mendapatkan semua review yang ada

        return view('CekPlagiarisme.views.detail', compact('dokumen', 'catatan'));
    }

    public function PenentuanAmbangBatas(): View
    {
        return view('CekPlagiarisme.views.PenentuanAmbangBatas');
    }
}