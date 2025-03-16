<?php

namespace App\Modules\CekPlagiarisme\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

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

        return view('CekPlagiarisme.views.detail', compact('dokumen'));
    }   

    public function PenentuanAmbangBatas(): View
    {
        return view('CekPlagiarisme.views.PenentuanAmbangBatas');
    }

     public function povMahasiswa(): View
     {
         // Data dummy untuk komentar
         $comments = [
             [
                 'user' => 'Nana Mardiana',
                 'role' => 'Dosen Pembimbing 1',
                 'date' => '2025-03-01 20:01:01',
                 'content' => 'Gunakan sumber referensi yang sahih, minimal sinta 3'
             ],
             [
                 'user' => 'Cinta Laura',
                 'role' => 'Dosen Pembimbing 2',
                 'date' => '2025-03-02 10:12:09',
                 'content' => 'Silakan sertakan jurnal yang relevan, kamu bisa memanfaatkan sciencesdirect, google scholar, atau web sejenisnya untuk mencari jurnal yang bisa dibuka untuk umum'
             ],
             [
                 'user' => 'Zayn Malik',
                 'role' => 'Dosen Pembimbing 3',
                 'date' => '2025-03-03 12:15:30',
                 'content' => 'Silakan sertakan jurnal yang relevan, kamu bisa memanfaatkan sciencesdirect, google scholar, atau web sejenisnya untuk mencari jurnal yang bisa dibuka untuk umum'
             ]
         ];
 
         return view('CekPlagiarisme.views.komentar', compact('comments'));
     }
}