<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriPenilaian;

class KategoriPenilaianSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kategori_penilaian')->truncate();

        $data = [
            ['id_kategori' => 1, 'id_fta' => 2, 'kunci_penilaian' => false],
            ['id_kategori' => 2, 'id_fta' => 4, 'kunci_penilaian' => false],
            ['id_kategori' => 3, 'id_fta' => 6, 'kunci_penilaian' => false],
            ['id_kategori' => 4, 'id_fta' => 8, 'kunci_penilaian' => false],
            ['id_kategori' => 5, 'id_fta' => 10, 'kunci_penilaian' => false],
            ['id_kategori' => 6, 'id_fta' => 11, 'kunci_penilaian' => false],
            ['id_kategori' => 7, 'id_fta' => 13, 'kunci_penilaian' => false],
            ['id_kategori' => 8, 'id_fta' => 14, 'kunci_penilaian' => false],
            ['id_kategori' => 9, 'id_fta' => 16, 'kunci_penilaian' => false],
            ['id_kategori' => 10, 'id_fta' => 17, 'kunci_penilaian' => false],
            ['id_kategori' => 11, 'id_fta' => 19, 'kunci_penilaian' => false],
        ];

        foreach ($data as $item) {
            KategoriPenilaian::create($item);
        }
    }
}
