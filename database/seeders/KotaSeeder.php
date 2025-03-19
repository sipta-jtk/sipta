<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Kota;

class KotaSeeder extends Seeder
{
    public function run()
    {

        if (!Schema::hasTable('kota')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kota')->truncate(); // Membersihkan tabel sebelum seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'id_kota' => 1,
                'judul_ta' => null,
                'id_bidang' => null,
                'nama_kota' => 'Kota 401',
                'tahun_kota' => 2025,
                'status_kota' => 'pra_kota'
            ],
            [
                'id_kota' => 2,
                'judul_ta' => 'PENGEMBANGAN APLIKASI AUDIT MUTU INTERNAL BERBASIS WEBSITE SPMI POLBAN',
                'id_bidang' => 6,
                'nama_kota' => 'Kota 402',
                'tahun_kota' => 2025,
                'status_kota' => 'aktif'
            ],
            [
                'id_kota' => 3,
                'judul_ta' => 'RANCANG BANGUN APLIKASI PEMANTAUAN CUACA REALTIME',
                'id_bidang' => 3,
                'nama_kota' => 'Kota 403',
                'tahun_kota' => 2024,
                'status_kota' => 'lulus'
            ],
            [
                'id_kota' => 4,
                'judul_ta' => 'PENGEMBANGAN SISTEM INFORMASI AKADEMIK BERBASIS WEB',
                'id_bidang' => 4,
                'nama_kota' => 'Kota 501',
                'tahun_kota' => 2025,
                'status_kota' => 'bubar'
            ],
            [
                'id_kota' => 5,
                'judul_ta' => null,
                'id_bidang' => null,
                'nama_kota' => 'Kota 201',
                'tahun_kota' => 2025,
                'status_kota' => 'pra_kota'
            ],
            [
                'id_kota' => 6,
                'judul_ta' => 'RANCANG BANGUN SISTEM MONITORING KUALITAS UDARA MENGGUNAKAN IOT',
                'id_bidang' => 3,
                'nama_kota' => 'Kota 202',
                'tahun_kota' => 2025,
                'status_kota' => 'aktif'
            ],
            [
                'id_kota' => 7,
                'judul_ta' => 'PENGEMBANGAN DASHBOARD BUSINESS INTELLIGENCE UNTUK ANALISIS PENJUALAN',
                'id_bidang' => 5,
                'nama_kota' => 'Kota 101',
                'tahun_kota' => 2024,
                'status_kota' => 'lulus'
            ],
            [
                'id_kota' => 7,
                'judul_ta' => 'PENERAPAN KNOWLEDGE MANAGEMENT DALAM SISTEM PENDUKUNG KEPUTUSAN AKADEMIK',
                'id_bidang' => 6,
                'nama_kota' => 'Kota 203',
                'tahun_kota' => 2025,
                'status_kota' => 'bubar'
            ],
            [
                'id_kota' => 9,
                'judul_ta' => null,
                'id_bidang' => null,
                'nama_kota' => 'Kota 204',
                'tahun_kota' => 2025,
                'status_kota' => 'pra_kota'
            ],
        ];

        foreach ($data as $item) {
            Kota::create($item);
        }
    }
}