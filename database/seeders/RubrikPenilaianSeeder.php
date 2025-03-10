<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\RubrikPenilaian;

class RubrikPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('rubrik_penilaian')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('rubrik_penilaian')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            ['judul_rubrik' => 'Kualitas Konten', 'id_kategori' => 8, 'detail_rubrik' => 'Menilai seberapa baik konten yang dibuat.', 'bobot_rubrik' => 40],
            ['judul_rubrik' => 'Presentasi', 'id_kategori' => 9, 'detail_rubrik' => 'Menilai cara menyampaikan materi.', 'bobot_rubrik' => 30],
            ['judul_rubrik' => 'Inovasi', 'id_kategori' => 7, 'detail_rubrik' => 'Menilai seberapa inovatif ide yang diajukan.', 'bobot_rubrik' => 20],
            ['judul_rubrik' => 'Kelengkapan Data', 'id_kategori' => 6, 'detail_rubrik' => 'Menilai kesesuaian data dengan topik.', 'bobot_rubrik' => 10],
        ];

        foreach ($data as $item) {
            RubrikPenilaian::create($item);
        }
    }
}
