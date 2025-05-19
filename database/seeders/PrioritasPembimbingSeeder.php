<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PrioritasPembimbing;
use Illuminate\Support\Facades\Schema;

class PrioritasPembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('form_penilaian')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('form_penilaian')->truncate();

        DB::table('prioritas_pembimbing')->insert([
            [
                'id_prioritas_pembimbing' => 1, 
                'id_pengajuan' => 1, 
                'nip' => '197312271999031003', 
                'urutan_prioritas' => 1
            ],
            [
                'id_prioritas_pembimbing' => 2, 
                'id_pengajuan' => 2, 
                'nip' => '196810141993032002', 
                'urutan_prioritas' => 2
            ],
            [
                'id_prioritas_pembimbing' => 3, 
                'id_pengajuan' => 3, 
                'nip' => '197201061999031002', 
                'urutan_prioritas' => 1
            ],
            [
                'id_prioritas_pembimbing' => 4, 
                'id_pengajuan' => 4, 
                'nip' => '196012261992031001', 
                'urutan_prioritas' => 2
            ],
            [
                'id_prioritas_pembimbing' => 5, 
                'id_pengajuan' => 5, 
                'nip' => '196101141992021001', 
                'urutan_prioritas' => 1
            ],
            [
                'id_prioritas_pembimbing' => 6, 
                'id_pengajuan' => 6, 
                'nip' => '197312271999031003', 
                'urutan_prioritas' => 2
            ],
            [
                'id_prioritas_pembimbing' => 7, 
                'id_pengajuan' => 7, 
                'nip' => '196810141993032002', 
                'urutan_prioritas' => 1
            ],
            [
                'id_prioritas_pembimbing' => 8, 
                'id_pengajuan' => 8, 
                'nip' => '197201061999031002', 
                'urutan_prioritas' => 2
            ],
            [
                'id_prioritas_pembimbing' => 9, 
                'id_pengajuan' => 9, 
                'nip' => '196012261992031001', 
                'urutan_prioritas' => 1
            ],
            [
                'id_prioritas_pembimbing' => 10, 
                'id_pengajuan' => 10, 
                'nip' => '196101141992021001', 
                'urutan_prioritas' => 2
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}