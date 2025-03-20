<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\PreferensiKota;

class PreferensiKotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('preferensi_kota')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('preferensi_kota')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'nip' => '197312271999031003',
                'id_kota' => 5
            ],
            [
                'nip' => '196101141992021001',
                'id_kota' => 5
            ],
            [
                'nip' => '198009162009122001',
                'id_kota' => 6
            ],
            [
                'nip' => '198502102015042001',
                'id_kota' => 6
            ],
            [
                'nip' => '197604182001121004',
                'id_kota' => 6
            ],
            [
                'nip' => '198012122008122001',
                'id_kota' => 7
            ],
            [
                'nip' => '198004192005011002',
                'id_kota' => 8
            ],
            [
                'nip' => '196208151990031001',
                'id_kota' => 9
            ],
            [
                'nip' => '198104072006041001',
                'id_kota' => 9
            ],
            [
                'nip' => '196210211993031002',
                'id_kota' => 9
            ],
            [
                'nip' => '197312271999031003',
                'id_kota' => 6
            ],
            [
                'nip' => '196101141992021001',
                'id_kota' => 6
            ],
            [
                'nip' => '198009162009122001',
                'id_kota' => 5
            ],
            [
                'nip' => '198502102015042001',
                'id_kota' => 8
            ],
            [
                'nip' => '197604182001121004',
                'id_kota' => 9
            ],
            [
                'nip' => '196610181995121001',
                'id_kota' => 1
            ],
            [
                'nip' => '196312131992012001',
                'id_kota' => 1
            ],
            [
                'nip' => '197109031999032001',
                'id_kota' => 1
            ],
            [
                'nip' => '196303161995121001',
                'id_kota' => 2
            ],
            [
                'nip' => '196904041998031001',
                'id_kota' => 3
            ],
            [
                'nip' => '196111091993032001',
                'id_kota' => 4
            ],
            [
                'nip' => '196009281994031001',
                'id_kota' => 4
            ],
            [
                'nip' => '197912242008121001',
                'id_kota' => 3
            ],
            [
                'nip' => '197407182001121002',
                'id_kota' => 2
            ],
            [
                'nip' => '198801292015041003',
                'id_kota' => 1
            ],
            [
                'nip' => '198705172019031004',
                'id_kota' => 2
            ],
            [
                'nip' => '199304262019032028',
                'id_kota' => 1
            ],
            [
                'nip' => '199312282019031013',
                'id_kota' => 1
            ],
            [
                'nip' => '197407182001121002',
                'id_kota' => 3
            ],
            [
                'nip' => '198801292015041003',
                'id_kota' => 3
            ],
            [
                'nip' => '198705172019031004',
                'id_kota' => 4
            ],
            [
                'nip' => '199304262019032028',
                'id_kota' => 4
            ],
            [
                'nip' => '199312282019031013',
                'id_kota' => 2
            ]
        ];

        foreach ($data as $item) {
            PreferensiKota::create($item);
        }
    }
}
