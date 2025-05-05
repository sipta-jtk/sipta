<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\NilaiKategori;

class NilaiKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('nilai_kategori')) {
            return;
        }

        // Menonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('nilai_kategori')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'nim' => '221524036',
                'nip' => '196111091993032001',
                'id_kategori' => 1,
                'nilai' => 76.00,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524036',
                'nip' => '197312271999031003',
                'id_kategori' => 1,
                'nilai' => 77.00,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524036',
                'nip' => '196101141992021001',
                'id_kategori' => 1,
                'nilai' => 76.00,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524036',
                'nip' => '199312282019031013',
                'id_kategori' => 2,
                'nilai' => 83.00,
                'status_penilaian_dosen' => 'draf',
            ],
            [
                'nim' => '221524036',
                'nip' => '198009162009122001',
                'id_kategori' => 2,
                'nilai' => 82.67,
                'status_penilaian_dosen' => 'draf',
            ],
            [
                'nim' => '221524036',
                'nip' => '198502102015042001',
                'id_kategori' => 2,
                'nilai' => 84.55,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524036',
                'nip' => '197604182001121004',
                'id_kategori' => 3,
                'nilai' => 87.65,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524036',
                'nip' => '198012122008122001',
                'id_kategori' => 3,
                'nilai' => 85.43,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524036',
                'nip' => '198004192005011002',
                'id_kategori' => 3,
                'nilai' => 87.88,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524036',
                'nip' => '196208151990031001',
                'id_kategori' => 4,
                'nilai' => 86.88,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524036',
                'nip' => '196111091993032001',
                'id_kategori' => 4,
                'nilai' => 88.78,
                'status_penilaian_dosen' => 'draf',
            ],
            [
                'nim' => '221524036',
                'nip' => '198104072006041001',
                'id_kategori' => 4,
                'nilai' => 89.99,
                'status_penilaian_dosen' => 'draf',
            ],
            [
                'nim' => '221524039',
                'nip' => '196210211993031002',
                'id_kategori' => 1,
                'nilai' => 78.50,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524039',
                'nip' => '196610181995121001',
                'id_kategori' => 1,
                'nilai' => 79.25,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524039',
                'nip' => '199312282019031013',
                'id_kategori' => 1,
                'nilai' => 82.25,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524039',
                'nip' => '196312131992012001',
                'id_kategori' => 2,
                'nilai' => 82.00,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524039',
                'nip' => '198801292015041003',
                'id_kategori' => 2,
                'nilai' => 82.00,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524039',
                'nip' => '197109031999032001',
                'id_kategori' => 2,
                'nilai' => 81.45,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524039',
                'nip' => '196303161995121001',
                'id_kategori' => 3,
                'nilai' => 84.75,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524039',
                'nip' => '199112182019032014',
                'id_kategori' => 3,
                'nilai' => 84.75,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524039',
                'nip' => '196904041998031001',
                'id_kategori' => 3,
                'nilai' => 85.60,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524039',
                'nip' => '196009281994031001',
                'id_kategori' => 4,
                'nilai' => 86.90,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524039',
                'nip' => '198706302019031011',
                'id_kategori' => 4,
                'nilai' => 88.40,
                'status_penilaian_dosen' => 'dipublikasikan',
            ],
            [
                'nim' => '221524039',
                'nip' => '197407182001121002',
                'id_kategori' => 4,
                'nilai' => 89.30,
                'status_penilaian_dosen' => 'draf',
            ],
        ];

        foreach ($data as $item) {
            NilaiKategori::create($item);
        }
    }
}
