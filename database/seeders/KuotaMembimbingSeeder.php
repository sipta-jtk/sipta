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
        // if (!Schema::hasTable('kuota_membimbing')) {
        //     return;
        // }

        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('kuota_membimbing')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // $data = [
        //     [
        //         'nip' => '197312271999031003',
        //         'id_prodi' => 1,
        //         'jumlah' => 2
        //     ],
        //     [
        //         'nip' => '196101141992021001',
        //         'id_prodi' => 1,
        //         'jumlah' => 5
        //     ],
        //     [
        //         'nip' => '198009162009122001',
        //         'id_prodi' => 1,
        //         'jumlah' => 3
        //     ],
        //     [
        //         'nip' => '198502102015042001',
        //         'id_prodi' => 1,
        //         'jumlah' => 1
        //     ],
        //     [
        //         'nip' => '197604182001121004',
        //         'id_prodi' => 1,
        //         'jumlah' => 2
        //     ],
        //     [
        //         'nip' => '198012122008122001',
        //         'id_prodi' => 1,
        //         'jumlah' => 5
        //     ],
        //     [
        //         'nip' => '198004192005011002',
        //         'id_prodi' => 1,
        //         'jumlah' => 4
        //     ],
        //     [
        //         'nip' => '196208151990031001',
        //         'id_prodi' => 1,
        //         'jumlah' => 3
        //     ],
        //     [
        //         'nip' => '198104072006041001',
        //         'id_prodi' => 1,
        //         'jumlah' => 6
        //     ],
        //     [
        //         'nip' => '196210211993031002',
        //         'id_prodi' => 1,
        //         'jumlah' => 3
        //     ],
        //     [
        //         'nip' => '196610181995121001',
        //         'id_prodi' => 2,
        //         'jumlah' => 6
        //     ],
        //     [
        //         'nip' => '196312131992012001',
        //         'id_prodi' => 2,
        //         'jumlah' => 5
        //     ],
        //     [
        //         'nip' => '197109031999032001',
        //         'id_prodi' => 2,
        //         'jumlah' => 1
        //     ],
        //     [
        //         'nip' => '196303161995121001',
        //         'id_prodi' => 2,
        //         'jumlah' => 3
        //     ],
        //     [
        //         'nip' => '196904041998031001',
        //         'id_prodi' => 2,
        //         'jumlah' => 5
        //     ],
        //     [
        //         'nip' => '196111091993032001',
        //         'id_prodi' => 2,
        //         'jumlah' => 2
        //     ],
        //     [
        //         'nip' => '196009281994031001',
        //         'id_prodi' => 2,
        //         'jumlah' => 3
        //     ],
        //     [
        //         'nip' => '197912242008121001',
        //         'id_prodi' => 2,
        //         'jumlah' => 6
        //     ],
        //     [
        //         'nip' => '197407182001121002',
        //         'id_prodi' => 2,
        //         'jumlah' => 2
        //     ],
        //     [
        //         'nip' => '198801292015041003',
        //         'id_prodi' => 2,
        //         'jumlah' => 4
        //     ],
        //     [
        //         'nip' => '198705172019031004',
        //         'id_prodi' => 2,
        //         'jumlah' => 2
        //     ],
        //     [
        //         'nip' => '199304262019032028',
        //         'id_prodi' => 2,
        //         'jumlah' => 3
        //     ],
        //     [
        //         'nip' => '199312282019031013',
        //         'id_prodi' => 2,
        //         'jumlah' => 2
        //     ]
        // ];

        // foreach ($data as $item) {
        //     KuotaMembimbing::create($item);
        // }
    }
}
