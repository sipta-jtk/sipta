<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\RekapitulasiNilaiAkhir;

class RekapitulasiNilaiAkhirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('rekapitulasi_nilai_akhir')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('rekapitulasi_nilai_akhir')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        RekapitulasiNilaiAkhir::create([
            'nim' => '221524059',
            'nilai_uts' => 80.5,
            'nilai_uas' => 70.5,
            'nilai_lain_lain' => 90.5,
            'nilai_akhir' => 85
        ]);

        RekapitulasiNilaiAkhir::create([
            'nim' => '221524049',
            'nilai_uts' => 90.5,
            'nilai_uas' => 90.5,
            'nilai_lain_lain' => 89.5,
            'nilai_akhir' => 90
        ]);
    }
}
