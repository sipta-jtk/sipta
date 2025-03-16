<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Kota;

class KotaSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kota')->truncate(); // Membersihkan tabel sebelum seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'judul_ta' => 'Smart City Bandung',
                'id_bidang' => 1,
                'nama_kota' => 'Kelompok 1',
                'tahun_kota' => 2022,
                'status_kota' => 'pra_kota',
            ],
            [
                'judul_ta' => 'IoT Perkotaan',
                'id_bidang' => 1,
                'nama_kota' => 'Kelompok 2',
                'tahun_kota' => 2021,
                'status_kota' => 'aktif',
            ],
            [
                'judul_ta' => 'Deteksi Anomali Tumbuhan',
                'id_bidang' => 1,
                'nama_kota' => 'Kelompok 3',
                'tahun_kota' => 2020,
                'status_kota' => 'lulus',
            ],
            [
                'judul_ta' => 'Kelompok Keberlanjutan Kota',
                'id_bidang' => 1,
                'nama_kota' => 'Kelompok 4',
                'tahun_kota' => 2019,
                'status_kota' => 'bubar',
            ],
        ];

        foreach ($data as $item) {
            Kota::create($item);
        }
    }
}