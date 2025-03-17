<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class MahasiswaMelihatJadwalController extends Controller
{
    public function view_MahasiswaMelihatJadwal(): View
    {
        $nim = 221524049;

        $query = "
        SELECT      
            dosen_user.nama AS nama_dosen, 
            jadwal_dosen_pembimbing.nip,
            jadwal_dosen_pembimbing.hari, 
            jadwal_dosen_pembimbing.jam_mulai, 
            jadwal_dosen_pembimbing.jam_selesai 
        FROM `user` AS mahasiswa_user
        JOIN mahasiswa ON mahasiswa_user.username = mahasiswa.nim
        JOIN kota ON mahasiswa.id_kota = kota.id_kota
        JOIN pengajuan_pembimbing ON pengajuan_pembimbing.id_kota = kota.id_kota
        JOIN alokasi_pembimbing ON alokasi_pembimbing.id_pengajuan_pembimbing = pengajuan_pembimbing.id_pengajuan_pembimbing
        LEFT JOIN jadwal_dosen_pembimbing ON jadwal_dosen_pembimbing.nip = alokasi_pembimbing.nip
        LEFT JOIN `user` AS dosen_user ON dosen_user.username = jadwal_dosen_pembimbing.nip
        WHERE mahasiswa.nim = $nim 
          AND pengajuan_pembimbing.status_pengajuan = 'diterima' 
          AND alokasi_pembimbing.status_alokasi = 'fix'";    
        
        $data = DB::select($query);

        // **Group Data by nama_dosen**
        $groupedData = [];
        foreach ($data as $item) {
            $namaDosen = $item->nama_dosen;

            if (!isset($groupedData[$namaDosen])) {
                $groupedData[$namaDosen] = [
                    'nama_dosen' => $namaDosen,
                    'jadwal' => []
                ];
            }

            $groupedData[$namaDosen]['jadwal'][] = [
                'hari' => ucfirst($item->hari),
                'jam_mulai' => date('H:i', strtotime($item->jam_mulai)),
                'jam_selesai' => date('H:i', strtotime($item->jam_selesai))
            ];
        }

        // Convert associative array to indexed array
        $groupedData = array_values($groupedData);

        

        return view('PengajuanAlokasiPembimbing.views.MahasiswaMelihatJadwal.MahasiswaMelihatJadwal', ['groupedData' => $groupedData]);
    }
}

