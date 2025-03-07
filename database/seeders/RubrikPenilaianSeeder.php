<?php

namespace Database\Seeders;

use App\Models\RubrikPenilaian;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Schema;

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

        DB::table('rubrik_penilaian')->truncate();

        $data = [
            ['id_kategori' => 1, 'judul_rubrik' => 'Kualitas Konten', 'detail_rubrik' => 'Menilai seberapa baik konten yang dibuat.', 'bobot_rubrik' => 40],
            ['id_kategori' => 2, 'judul_rubrik' => 'Presentasi', 'detail_rubrik' => 'Menilai cara menyampaikan materi.', 'bobot_rubrik' => 30],
            ['id_kategori' => 3, 'judul_rubrik' => 'Inovasi', 'detail_rubrik' => 'Menilai seberapa inovatif ide yang diajukan.', 'bobot_rubrik' => 20],
            ['id_kategori' => 4, 'judul_rubrik' => 'Kelengkapan Data', 'detail_rubrik' => 'Menilai kesesuaian data dengan topik.', 'bobot_rubrik' => 10],
        ];

        foreach ($data as $item) {
            RubrikPenilaian::create($item);
        }
    }
}
