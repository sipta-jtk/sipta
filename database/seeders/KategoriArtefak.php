<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriArtefak extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ftaArtefaks = [];
        for ($i = 1; $i <= 23; $i++) {
            $ftaArtefaks[] = ['jenis_artefak' => 'FTA ' . str_pad($i, 2, '0', STR_PAD_LEFT)];
        }

        // Define additional specific artefaks
        $specificArtefaks = [
            ['jenis_artefak' => 'FTA 05a'],
            ['jenis_artefak' => 'FTA 06a'],
            ['jenis_artefak' => 'FTA 09a'],
            ['jenis_artefak' => 'Proposal Tugas Akhir'],
            ['jenis_artefak' => 'SRS'],
            ['jenis_artefak' => 'SDD'],
            ['jenis_artefak' => 'Laporan Tugas Akhir']
        ];

        // Merge both arrays
        $allArtefaks = array_merge($ftaArtefaks, $specificArtefaks);

        // Insert all artefak entries into the database
        DB::table('kategori_artefak')->insert($allArtefaks);
    }
}
