<?php

namespace Database\Seeders;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PrioritasPembimbing;


class PrioritasPembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PrioritasPembimbing::create([
            'id_pengajuan' => 1,
            'nip' => '1987654321',
            'urutan_prioritas' => 1
        ]);

        PrioritasPembimbing::create([
            'id_pengajuan' => 2,
            'nip' => '1987654322',
            'urutan_prioritas' => 2
        ]);
    }
}
