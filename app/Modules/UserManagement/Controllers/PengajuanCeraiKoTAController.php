<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use App\Models\User;

class PengajuanCeraiKoTAController extends Controller
{
    public function index()
    {   
        $pengajuan = collect([
            (object) [
                'id' => '1',
                'nama' => 'Alice',
                'kelompok_ta' => 'KoTA 202',
                'judul_ta' => 'Sistem AI untuk Diagnosis Penyakit',
                'tahun_ta' => 2024
            ],
            (object) [
                'id' => '2',
                'nama' => 'Bob',
                'kelompok_ta' => 'KoTA 301',
                'judul_ta' => 'Analisis Sentimen dengan Machine Learning',
                'tahun_ta' => 2025
            ],
            (object) [
                'id' => '3',
                'nama' => 'Charlie',
                'kelompok_ta' => 'KoTA 201',
                'judul_ta' => 'Optimasi Algoritma Kriptografi',
                'tahun_ta' => 2023
            ]
        ]);
    
        return view('UserManagement.views.pengajuan-cerai-kota', compact('pengajuan'));
    }

    public function show($id)
    {
        $pengajuan = collect([
            (object) [
                'id' => '1',
                'nama' => 'Alice',
                'kelompok_ta' => 'KoTA 202',
                'judul_ta' => 'Sistem AI untuk Diagnosis Penyakit',
                'tahun_ta' => 2024
            ],
            (object) [
                'id' => '2',
                'nama' => 'Bob',
                'kelompok_ta' => 'KoTA 301',
                'judul_ta' => 'Analisis Sentimen dengan Machine Learning',
                'tahun_ta' => 2025
            ],
            (object) [
                'id' => '3',
                'nama' => 'Charlie',
                'kelompok_ta' => 'KoTA 201',
                'judul_ta' => 'Optimasi Algoritma Kriptografi',
                'tahun_ta' => 2023
            ]
        ]);
    
        $item = $pengajuan->firstWhere('id', $id);
        
        if (!$item) {
            return abort(404, 'Data tidak ditemukan');
        }
    
        return view('UserManagement.views.form-cerai-kota', compact('item'));
    }

}