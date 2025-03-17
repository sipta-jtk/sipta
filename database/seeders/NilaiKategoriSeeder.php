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
                'nim' => '221524059',
                'nip' => '197312271999031003',
                'id_kategori' => 1,
                'nilai' => 85.5,
            ],
            [
                'nim' => '221524049',
                'nip' => '198502102015042001',
                'id_kategori' => 2,
                'nilai' => 78.0,
            ],
        ];

        foreach ($data as $item) {
            NilaiKategori::create($item);
        }
    }
}
