<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Modules\Controller;
use App\Models\Bidang;
use App\Models\Kota;
use App\Models\User;
use App\Models\PrioritasPembimbing;
use App\Models\Dosen;
use Illuminate\View\View;

class RekapFTA02Controller extends Controller
{
    public function view_rekapFTA02(): View
    {
        $kotaList = Kota::pluck('nama_kota')->toArray();
        $bidangList = Bidang::pluck('bidang')->toArray();
        $judulList = Kota::pluck('judul_ta')->toArray();
        $mahasiswaList = User::where('role_user', 'mahasiswa')->select('username as nim', 'nama')->get();
        $totalMahasiswa = count($mahasiswaList);
        $mahasiswaIndex = 0;

        $data = [];

        for ($i = 1; $i <= 10; $i++) {
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

            // Ambil prioritas pembimbing berdasarkan id_pengajuan (misalnya id_pengajuan = $i)
            $prioritas = PrioritasPembimbing::where('id_pengajuan', $i)
                ->orderBy('urutan_prioritas', 'asc')
                ->pluck('nip')
                ->toArray();

            // Konversi NIP ke ID Dosen
            $usulanDosen = Dosen::whereIn('nip', $prioritas)->pluck('id_dosen')->toArray();

            // Pastikan tetap ada 5 data dalam array
            while (count($usulanDosen) < 5) {
                $usulanDosen[] = "-"; // Gunakan '-' jika kurang dari 5
            }

            $data[] = [
                'kode' => $kotaList[$i % count($kotaList)] ?? 'Default Kota',
                'anggota' => $anggota,
                'jumlahMahasiswa' => 3,
                'bidang' => $bidangList[$i % count($bidangList)] ?? 'Default Bidang',
                'judul' => $judulList[$i % count($judulList)] ?? 'Judul Default',
                'usulanDosen' => $usulanDosen,
                'pembimbing1' => $usulanDosen[0] ?? "-",
                'pembimbing2' => $usulanDosen[1] ?? "-",
            ];
        }

        return view('PengajuanAlokasiPembimbing.views.RekapFTA02', compact('data'));
    }
}
