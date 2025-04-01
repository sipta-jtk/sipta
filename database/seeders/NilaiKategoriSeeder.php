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
            ['nim' => '221524037', 'nip' => '196101141992021001', 'id_kategori' => 2, 'nilai' => 39.16],
            ['nim' => '221524037', 'nip' => '196101141992021001', 'id_kategori' => 4, 'nilai' => 37.11],
            ['nim' => '221524037', 'nip' => '196111091993032001', 'id_kategori' => 2, 'nilai' => 41.18],
            ['nim' => '221524037', 'nip' => '196111091993032001', 'id_kategori' => 4, 'nilai' => 32.79],
            ['nim' => '221524037', 'nip' => '196208151990031001', 'id_kategori' => 2, 'nilai' => 88.45],
            ['nim' => '221524037', 'nip' => '196208151990031001', 'id_kategori' => 4, 'nilai' => 52.61],
            ['nim' => '221524037', 'nip' => '196210211993031002', 'id_kategori' => 3, 'nilai' => 18.68],
            ['nim' => '221524037', 'nip' => '196210211993031002', 'id_kategori' => 5, 'nilai' => 64.70],
            ['nim' => '221524037', 'nip' => '196303161995121001', 'id_kategori' => 3, 'nilai' => 28.06],
            ['nim' => '221524037', 'nip' => '196303161995121001', 'id_kategori' => 5, 'nilai' => 65.66],
            ['nim' => '221524037', 'nip' => '196312131992012001', 'id_kategori' => 3, 'nilai' => 84.26],
            ['nim' => '221524037', 'nip' => '196312131992012001', 'id_kategori' => 5, 'nilai' => 34.21],
            ['nim' => '221524036', 'nip' => '196101141992021001', 'id_kategori' => 3, 'nilai' => 95.16],
            ['nim' => '221524036', 'nip' => '196101141992021001', 'id_kategori' => 5, 'nilai' => 67.08],
            ['nim' => '221524036', 'nip' => '196111091993032001', 'id_kategori' => 3, 'nilai' => 46.55],
            ['nim' => '221524036', 'nip' => '196111091993032001', 'id_kategori' => 5, 'nilai' => 65.77],
            ['nim' => '221524036', 'nip' => '196208151990031001', 'id_kategori' => 3, 'nilai' => 79.20],
            ['nim' => '221524036', 'nip' => '196208151990031001', 'id_kategori' => 5, 'nilai' => 53.93],
            ['nim' => '221524036', 'nip' => '196210211993031002', 'id_kategori' => 2, 'nilai' => 16.49],
            ['nim' => '221524036', 'nip' => '196210211993031002', 'id_kategori' => 4, 'nilai' => 67.96],
            ['nim' => '221524036', 'nip' => '196303161995121001', 'id_kategori' => 2, 'nilai' => 67.74],
            ['nim' => '221524036', 'nip' => '196303161995121001', 'id_kategori' => 4, 'nilai' => 95.28],
            ['nim' => '221524036', 'nip' => '196312131992012001', 'id_kategori' => 2, 'nilai' => 74.08],
            ['nim' => '221524036', 'nip' => '196312131992012001', 'id_kategori' => 4, 'nilai' => 36.16],
        ];

        foreach ($data as $item) {
            NilaiKategori::create($item);
        }
    }
}
