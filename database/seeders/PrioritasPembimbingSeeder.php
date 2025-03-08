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
            'nip' => '19731227 199903 1 003',
            'urutan_prioritas' => 1
        ]);

        PrioritasPembimbing::create([
            'id_pengajuan' => 2,
            'nip' => '19850210 201504 2 001',
            'urutan_prioritas' => 2
        ]);
    }
}
