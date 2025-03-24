<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\KuotaMembimbing;

class KuotaMembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('kuota_membimbing')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kuota_membimbing')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'nip' => '198706302019031011',
                'id_prodi' => 1,
                'jumlah' => 2
            ],
            [
                'nip' => '198706302019031011',
                'id_prodi' => 2,
                'jumlah' => 1
            ],
            [
                'nip' => '197109031999032001',
                'id_prodi' => 1,
                'jumlah' => 1
            ],
            [
                'nip' => '197109031999032001',
                'id_prodi' => 2,
                'jumlah' => 2
            ],
            [
                'nip' => '198104072006041001',
                'id_prodi' => 1,
                'jumlah' => 2
            ],
            [
                'nip' => '198104072006041001',
                'id_prodi' => 2,
                'jumlah' => 1
            ],
            [
                'nip' => '198502102015042001',
                'id_prodi' => 1,
                'jumlah' => 2
            ],
            [
                'nip' => '198502102015042001',
                'id_prodi' => 2,
                'jumlah' => 1
            ],
            [
                'nip' => '199301062019031017',
                'id_prodi' => 1,
                'jumlah' => 1
            ],
            [
                'nip' => '199301062019031017',
                'id_prodi' => 2,
                'jumlah' => 1
            ]
        ];

        foreach ($data as $item) {
            KuotaMembimbing::create($item);
        }
    }
}
