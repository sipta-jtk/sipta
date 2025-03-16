<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PrioritasPembimbing;


class PrioritasPembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('prioritas_pembimbing')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        PrioritasPembimbing::create([
            'id_pengajuan' => 1,
            'nip' => '197312271999031003',
            'urutan_prioritas' => 1
        ]);

        PrioritasPembimbing::create([
            'id_pengajuan' => 2,
            'nip' => '198502102015042001',
            'urutan_prioritas' => 2
        ]);
    }
}
