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
                'id_kategori' => 3,
                'nilai' => 76.00,
            ],
            [
                'nim' => '221524039',
                'nip' => '199312282019031013',
                'id_kategori' => 3,
                'nilai' => 77.00,
            ],
            [
                'nim' => '221524036',
                'nip' => '196111091993032001',
                'id_kategori' => 2,
                'nilai' => 76.00,
            ],
            [
                'nim' => '221524039',
                'nip' => '199312282019031013',
                'id_kategori' => 2,
                'nilai' => 78.00,
            ],
        ];

        foreach ($data as $item) {
            NilaiKategori::create($item);
        }
    }
}
