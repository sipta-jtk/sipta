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
            // For NIM 221524034
            [
                'nim' => '221524034',
                'nip' => '196610181995121001',
                'id_kategori' => 1,
                'nilai' => 76.00,
            ],
            [
                'nim' => '221524034',
                'nip' => '198706302019031011',
                'id_kategori' => 2,
                'nilai' => 83.00,
            ],
            [
                'nim' => '221524034',
                'nip' => '197109031999032001',
                'id_kategori' => 3,
                'nilai' => 87.65,
            ],
            
            // For NIM 221524035
            [
                'nim' => '221524035',
                'nip' => '198104072006041001',
                'id_kategori' => 1,
                'nilai' => 78.50,
            ],
            [
                'nim' => '221524035',
                'nip' => '198502102015042001',
                'id_kategori' => 2,
                'nilai' => 82.00,
            ],
            [
                'nim' => '221524035',
                'nip' => '199301062019031017',
                'id_kategori' => 3,
                'nilai' => 84.75,
            ],
            [
                'nim' => '221524035',
                'nip' => '196610181995121001',
                'id_kategori' => 4,
                'nilai' => 86.90,
            ],
            
            // For NIM 221524036
            [
                'nim' => '221524036',
                'nip' => '196610181995121001',
                'id_kategori' => 1,
                'nilai' => 76.00,
            ],
            [
                'nim' => '221524036',
                'nip' => '198104072006041001',
                'id_kategori' => 2,
                'nilai' => 82.67,
            ],
            [
                'nim' => '221524036',
                'nip' => '199301062019031017',
                'id_kategori' => 3,
                'nilai' => 87.88,
            ],
            [
                'nim' => '221524036',
                'nip' => '198706302019031011',
                'id_kategori' => 4,
                'nilai' => 89.99,
            ],
            
            // For NIM 221524037
            [
                'nim' => '221524037',
                'nip' => '196610181995121001',
                'id_kategori' => 1,
                'nilai' => 79.25,
            ],
            [
                'nim' => '221524037',
                'nip' => '197109031999032001',
                'id_kategori' => 2,
                'nilai' => 81.45,
            ],
            [
                'nim' => '221524037',
                'nip' => '198104072006041001',
                'id_kategori' => 3,
                'nilai' => 84.75,
            ],
            [
                'nim' => '221524037',
                'nip' => '198706302019031011',
                'id_kategori' => 4,
                'nilai' => 88.40,
            ],
        ];

        // Track inserted combinations
        $insertedCombinations = [];
        foreach ($data as $item) {
            $key = $item['nim'] . '-' . $item['nip'] . '-' . $item['id_kategori'];
            
            if (!in_array($key, $insertedCombinations)) {
                NilaiKategori::create($item);
                $insertedCombinations[] = $key;
            }
        }
    }
}
