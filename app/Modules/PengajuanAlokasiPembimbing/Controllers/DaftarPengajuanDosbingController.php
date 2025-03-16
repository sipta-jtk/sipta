<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Models\Bidang;
use App\Models\Kota;
use App\Models\User;
use App\Models\PengajuanPembimbing;
use App\Modules\Controller;
use Illuminate\View\View;

class DaftarPengajuanDosbingController extends Controller
{
    public function view_daftarPengajuanDosbing(): View
{
    $kotaList = Kota::pluck('nama_kota')->toArray();
    $bidangList = Bidang::pluck('bidang')->toArray();
    $judulList = Kota::pluck('judul_ta')->toArray();

    // Ambil daftar mahasiswa dari tabel user berdasarkan role_user = 'mahasiswa'
    $mahasiswaList = User::where('role_user', 'mahasiswa')->select('username as nim', 'nama')->get();
    $tanggalPengajuanList = PengajuanPembimbing::pluck('created_at')->toArray();

    $kelompokData = [];

    // Pastikan jumlah mahasiswa cukup untuk dimasukkan ke dalam kelompok
    $totalMahasiswa = count($mahasiswaList);
    $mahasiswaIndex = 0;

    for ($i = 0; $i < 9; $i++) {
        $anggota = [];

        // Ambil 3 mahasiswa untuk setiap kelompok
        for ($j = 0; $j < 3; $j++) {
            if ($mahasiswaIndex < $totalMahasiswa) {
                $anggota[] = [
                    'nama' => $mahasiswaList[$mahasiswaIndex]->nama,
                    'nim' => $mahasiswaList[$mahasiswaIndex]->nim,
                ];
                $mahasiswaIndex++;
            } else {
                // Jika mahasiswa habis, gunakan data dummy
                $anggota[] = [
                    'nama' => 'Mahasiswa Default',
                    'nim' => 'NIM0000',
                ];
            }
        }

        $kelompokData[] = [
            'id' => $i + 1,
            'kode' => $kotaList[$i % count($kotaList)] ?? 'Default Kota',
            'bidang' => $bidangList[$i % count($bidangList)] ?? 'Default Bidang',
            'judul' => $judulList[$i % count($judulList)] ?? 'Judul Default',
            'tanggal' => $tanggalPengajuanList[$i % count($tanggalPengajuanList)] ?? date('Y-m-d'),
            'anggota' => $anggota,
        ];
    }

    return view('PengajuanAlokasiPembimbing.views.DaftarPengajuanDosbing.topik', compact('kelompokData'));
}

}