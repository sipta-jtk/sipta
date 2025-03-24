<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Dosen;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('dosen')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('dosen')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'nip' => '196610181995121001',
                'id_kbk' => 2,
                'id_dosen' => 'JO',
                'kode_dosen' => 'KO007N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'koordinator_ta',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '198706302019031011',
                'id_kbk' => 1,
                'id_dosen' => 'WW',
                'kode_dosen' => 'KO079N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '197109031999032001',
                'id_kbk' => 2,
                'id_dosen' => 'SN',
                'kode_dosen' => 'KO009N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '198104072006041001',
                'id_kbk' => 3,
                'id_dosen' => 'PH',
                'kode_dosen' => 'KO048N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '198502102015042001',
                'id_kbk' => 3,
                'id_dosen' => 'HA',
                'kode_dosen' => 'KO060N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '199301062019031017',
                'id_kbk' => 1,
                'id_dosen' => 'LH',
                'kode_dosen' => 'KO072N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ]
        ];

        foreach ($data as $item) {
            Dosen::create($item);
        }
    }
}
