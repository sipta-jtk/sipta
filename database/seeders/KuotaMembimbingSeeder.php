<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KuotaMembimbing;

class KuotaMembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kuota_membimbing')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('kuota_membimbing')->insert([
            [
                'nip' => '197312271999031003',
                'id_prodi' => 1,
                'jumlah' => 5
            ],
            [
                'nip' => '198502102015042001',
                'id_prodi' => 2,
                'jumlah' => 6
            ],
        ]);
    }
}
