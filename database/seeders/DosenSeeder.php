<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'nip' => '197312271999031003',
                'maks_bimbingan_d4' => 3,
                'maks_bimbingan_d3' => 2,
                'id_kbk' => 1,
                'id_dosen' => 'AD',
                'kode_dosen' => 'KO001N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen_pembimbing'
            ],
            [
                'nip' => '198502102015042001',
                'maks_bimbingan_d4' => 2,
                'maks_bimbingan_d3' => 1,
                'id_kbk' => 2,
                'id_dosen' => 'HA',
                'kode_dosen' => 'KO060N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'koordinator_ta'
            ],
            [
                'nip' => '197201061999031002',
                'maks_bimbingan_d4' => 1,
                'maks_bimbingan_d3' => 1,
                'id_kbk' => 1,
                'id_dosen' => 'BW',
                'kode_dosen' => 'KO003N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'kajur'
            ],
            [
                'nip' => '196810141993032002',
                'maks_bimbingan_d4' => 0,
                'maks_bimbingan_d3' => 0,
                'id_kbk' => 3,
                'id_dosen' => 'AN',
                'kode_dosen' => 'KO002N',
                'status_dosen' => 'nonaktif',
                'role_dosen' => 'dosen'
            ],
            [
                'nip' => '197604182001121004',
                'maks_bimbingan_d4' => 3,
                'maks_bimbingan_d3' => 1,
                'id_kbk' => 4,
                'id_dosen' => 'IA',
                'kode_dosen' => 'KO023N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen'
            ]
        ];

        foreach ($data as $item) {
            Dosen::create($item);
        }
    }
}
