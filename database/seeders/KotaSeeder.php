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
                'judul_ta' => 'PENGEMBANGAN SISTEM PENJAMINAN MUTU EKSTERNAL POLITEKNIK NEGERI BANDUNG',
                'id_bidang' => 4,
                'nama_kota' => 'Kota 107',
                'tahun_kota' => 2025,
                'status_kota' => 'pra_kota',
                'jenis_ta' => 'pengembangan'
            ],
            [
                'id_kota' => 2,
                'judul_ta' => 'PENGEMBANGAN APLIKASI MANAJEMEN PENGGAJIAN DALAM PROYEK',
                'id_bidang' => 4,
                'nama_kota' => 'Kota 108',
                'tahun_kota' => 2025,
                'status_kota' => 'pra_kota',
                'jenis_ta' => 'pengembangan'
            ],
            [
                'id_kota' => 3,
                'judul_ta' => 'PENGEMBANGAN APLIKASI KEANGGOTAAN DAN ORGANISASI PC PERSIS BANJARAN BERBASIS WEBSITE',
                'id_bidang' => 1,
                'nama_kota' => 'KoTA 205',
                'tahun_kota' => 2025,
                'status_kota' => 'pra_kota',
                'jenis_ta' => 'pengembangan'
            ],
            [
                'id_kota' => 4,
                'judul_ta' => 'ANALISIS PERBANDINGAN KINERJA METODE GRAPH EMBBEDING NODE2VEC DAN FASTRP DALAM PENGUKURAN SIMILARITAS ANTAR FILM',
                'id_bidang' => 2,
                'nama_kota' => 'KoTA 405',
                'tahun_kota' => 2025,
                'status_kota' => 'pra_kota',
                'jenis_ta' => 'penelitian'
            ],
            [
                'id_kota' => 5,
                'judul_ta' => 'PENGARUH PENGGUNAAN HYBRID RETRIEVAL DAN FINE-TUNING LARGE LANGUAGE MODEL PADA RETRIEVAL-AUGMENTED GENERATION (RAG) TERHADAP KUALITAS CERITA PENDEK OTOMATIS',
                'id_bidang' => 2,
                'nama_kota' => 'KoTA 406',
                'tahun_kota' => 2025,
                'status_kota' => 'pra_kota',
                'jenis_ta' => 'penelitian'
            ],
            [
                'id_kota' => 6,
                'judul_ta' => 'PERANCANGAN KERANGKA DATA-DRIVEN UNTUK MENDUKUNG  EVALUASI MATA KULIAH PROYEK JTK POLBAN MELALUI PENDEKATAN DESIGN SCIENCE RESEARCH',
                'id_bidang' => 4,
                'nama_kota' => 'KoTA 407',
                'tahun_kota' => 2025,
                'status_kota' => 'pra_kota',
                'jenis_ta' => 'penelitian'
            ],

            //Backup
            // [
            //     'id_kota' => 1,
            //     'judul_ta' => null,
            //     'id_bidang' => null,
            //     'nama_kota' => 'Kota 401',
            //     'tahun_kota' => 2025,
            //     'status_kota' => 'pra_kota',
            //     'jenis_ta' => null
            // ],
            // [
            //     'id_kota' => 2,
            //     'judul_ta' => 'PENGEMBANGAN APLIKASI AUDIT MUTU INTERNAL BERBASIS WEBSITE SPMI POLBAN',
            //     'id_bidang' => 6,
            //     'nama_kota' => 'Kota 402',
            //     'tahun_kota' => 2025,
            //     'status_kota' => 'aktif',
            //     'jenis_ta' => 'pengembangan'
            // ],
            // [
            //     'id_kota' => 3,
            //     'judul_ta' => 'RANCANG BANGUN APLIKASI PEMANTAUAN CUACA REALTIME',
            //     'id_bidang' => 3,
            //     'nama_kota' => 'Kota 403',
            //     'tahun_kota' => 2024,
            //     'status_kota' => 'lulus',
            //     'jenis_ta' => 'penelitian'
            // ],
            // [
            //     'id_kota' => 4,
            //     'judul_ta' => 'PENGEMBANGAN SISTEM INFORMASI AKADEMIK BERBASIS WEB',
            //     'id_bidang' => 4,
            //     'nama_kota' => 'Kota 501',
            //     'tahun_kota' => 2025,
            //     'status_kota' => 'bubar',
            //     'jenis_ta' => 'pengembangan'
            // ],
            // [
            //     'id_kota' => 5,
            //     'judul_ta' => null,
            //     'id_bidang' => null,
            //     'nama_kota' => 'Kota 201',
            //     'tahun_kota' => 2025,
            //     'status_kota' => 'pra_kota',
            //     'jenis_ta' => null
            // ],
            // [
            //     'id_kota' => 6,
            //     'judul_ta' => 'RANCANG BANGUN SISTEM MONITORING KUALITAS UDARA MENGGUNAKAN IOT',
            //     'id_bidang' => 3,
            //     'nama_kota' => 'Kota 202',
            //     'tahun_kota' => 2025,
            //     'status_kota' => 'aktif',
            //     'jenis_ta' => 'penelitian'
            // ],
            // [
            //     'id_kota' => 7,
            //     'judul_ta' => 'PENGEMBANGAN DASHBOARD BUSINESS INTELLIGENCE UNTUK ANALISIS PENJUALAN',
            //     'id_bidang' => 5,
            //     'nama_kota' => 'Kota 101',
            //     'tahun_kota' => 2024,
            //     'status_kota' => 'lulus',
            //     'jenis_ta' => 'pengembangan'
            // ],
            // [
            //     'id_kota' => 8,
            //     'judul_ta' => 'PENERAPAN KNOWLEDGE MANAGEMENT DALAM SISTEM PENDUKUNG KEPUTUSAN AKADEMIK',
            //     'id_bidang' => 6,
            //     'nama_kota' => 'Kota 203',
            //     'tahun_kota' => 2025,
            //     'status_kota' => 'bubar',
            //     'jenis_ta' => 'pengembangan'
            // ],
            // [
            //     'id_kota' => 9,
            //     'judul_ta' => null,
            //     'id_bidang' => null,
            //     'nama_kota' => 'Kota 204',
            //     'tahun_kota' => 2025,
            //     'status_kota' => 'pra_kota',
            //     'jenis_ta' => null
            // ],
        ];

        foreach ($data as $item) {
            Kota::create($item);
        }
    }
}