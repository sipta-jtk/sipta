<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Models\User;
use App\Modules\Controller;
use Illuminate\View\View;

class DaftarPengajuanDosbingController extends Controller
{
    public function view_daftarPengajuanDosbing(): View
    {
        $kelompokData = [
            [
                'id' => 1,
                'kode' => 'KoTA100',
                'bidang' => 'Machine Learning',
                'judul' => 'Prediksi Cuaca',
                'tanggal' => '12-02-2024',
                'anggota' => [
                    ['nama' => 'Hasna Fitriyani K', 'nim' => '221524011'],
                    ['nama' => 'Juicy Luicy', 'nim' => '221524012'],
                    ['nama' => 'Kaleb J', 'nim' => '221524013'],
                ]
            ],
            [
                'id' => 2,
                'kode' => 'KoTA101',
                'bidang' => 'Keamanan Jaringan',
                'judul' => 'Enkripsi Data',
                'tanggal' => '15-03-2024',
                'anggota' => [
                    ['nama' => 'Kahitna', 'nim' => '221524014'],
                    ['nama' => 'Rayi Putra', 'nim' => '221524015'],
                    ['nama' => 'Yovie', 'nim' => '221524016'],
                ]
            ],
            [
                'id' => 3,
                'kode' => 'KoTA102',
                'bidang' => 'Web Development',
                'judul' => 'Aplikasi E-Commerce',
                'tanggal' => '20-04-2024',
                'anggota' => [
                    ['nama' => 'Baskara', 'nim' => '221524017'],
                    ['nama' => 'Adnan', 'nim' => '221524018'],
                    ['nama' => 'Nina', 'nim' => '221524019'],
                ]
            ],
            [
                'id' => 4,
                'kode' => 'KoTA102',
                'bidang' => 'Web Development',
                'judul' => 'Aplikasi E-Commerce',
                'tanggal' => '20-04-2024',
                'anggota' => [
                    ['nama' => 'Baskara', 'nim' => '221524017'],
                    ['nama' => 'Adnan', 'nim' => '221524018'],
                    ['nama' => 'Nina', 'nim' => '221524019'],
                ]
            ],
            [
                'id' => 5,
                'kode' => 'KoTA102',
                'bidang' => 'Web Development',
                'judul' => 'Aplikasi E-Commerce',
                'tanggal' => '20-04-2024',
                'anggota' => [
                    ['nama' => 'Baskara', 'nim' => '221524017'],
                    ['nama' => 'Adnan', 'nim' => '221524018'],
                    ['nama' => 'Nina', 'nim' => '221524019'],
                ]
            ],
            [
                'id' => 6,
                'kode' => 'KoTA102',
                'bidang' => 'Web Development',
                'judul' => 'Aplikasi E-Commerce',
                'tanggal' => '20-04-2024',
                'anggota' => [
                    ['nama' => 'Baskara', 'nim' => '221524017'],
                    ['nama' => 'Adnan', 'nim' => '221524018'],
                    ['nama' => 'Nina', 'nim' => '221524019'],
                ]
            ],
        ];

        // Kirim data ke view
        // dd($kelompokData);
        return view('PengajuanAlokasiPembimbing.views.DaftarPengajuanDosbing.topik', compact('kelompokData'));
    }
}
