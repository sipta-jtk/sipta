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
                'nip' => '197312271999031003',
                'id_kbk' => 3,
                'id_dosen' => 'AD',
                'kode_dosen' => 'KO001N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '196810141993032002',
                'id_kbk' => 2,
                'id_dosen' => 'AN',
                'kode_dosen' => 'KO002N',
                'status_dosen' => 'nonaktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'tidak_bersedia'
            ],
            [
                'nip' => '197201061999031002',
                'id_kbk' => 1,
                'id_dosen' => 'BW',
                'kode_dosen' => 'KO003N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'tidak_bersedia'
            ],
            [
                'nip' => '196012261992031001',
                'id_kbk' => 1,
                'id_dosen' => 'DP',
                'kode_dosen' => 'KO005N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'tidak_bersedia'
            ],
            [
                'nip' => '196101141992021001',
                'id_kbk' => 1,
                'id_dosen' => 'EB',
                'kode_dosen' => 'KO016N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '198009162009122001',
                'id_kbk' => 2,
                'id_dosen' => 'FI',
                'kode_dosen' => 'KO057N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '198604122014041001',
                'id_kbk' => 3,
                'id_dosen' => 'GI',
                'kode_dosen' => 'KO059N',
                'status_dosen' => 'nonaktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'tidak_bersedia'
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
                'nip' => '197604182001121004',
                'id_kbk' => 3,
                'id_dosen' => 'IA',
                'kode_dosen' => 'KO023N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '198012122008122001',
                'id_kbk' => 2,
                'id_dosen' => 'ID',
                'kode_dosen' => 'KO056N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '198004192005011002',
                'id_kbk' => 3,
                'id_dosen' => 'IS',
                'kode_dosen' => 'KO045N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'koordinator_ta',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '196208151990031001',
                'id_kbk' => 1,
                'id_dosen' => 'IW',
                'kode_dosen' => 'KO006N',
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
                'nip' => '196210211993031002',
                'id_kbk' => 1,
                'id_dosen' => 'JN',
                'kode_dosen' => 'KO018N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
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
                'nip' => '196312131992012001',
                'id_kbk' => 1,
                'id_dosen' => 'NJ',
                'kode_dosen' => 'KO008N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
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
                'nip' => '196303161995121001',
                'id_kbk' => 2,
                'id_dosen' => 'SP',
                'kode_dosen' => 'KO022N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '196904041998031001',
                'id_kbk' => 1,
                'id_dosen' => 'ST',
                'kode_dosen' => 'KO021N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '196111091993032001',
                'id_kbk' => 1,
                'id_dosen' => 'TR',
                'kode_dosen' => 'KO019N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '196009281994031001',
                'id_kbk' => 1,
                'id_dosen' => 'UT',
                'kode_dosen' => 'KO012N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '197912242008121001',
                'id_kbk' => 2,
                'id_dosen' => 'YA',
                'kode_dosen' => 'KO052N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'kajur',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '197407182001121002',
                'id_kbk' => 3,
                'id_dosen' => 'YD',
                'kode_dosen' => 'KO013N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '198604212018031001',
                'id_kbk' => 1,
                'id_dosen' => 'MV',
                'kode_dosen' => 'KO063N',
                'status_dosen' => 'nonaktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'tidak_bersedia'
            ],
            [
                'nip' => '198801292015041003',
                'id_kbk' => 2,
                'id_dosen' => 'ZA',
                'kode_dosen' => 'KO061N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '198705172019031004',
                'id_kbk' => 2,
                'id_dosen' => 'AB',
                'kode_dosen' => 'KO064N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '199304262019032028',
                'id_kbk' => 3,
                'id_dosen' => 'AP',
                'kode_dosen' => 'KO065N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '198405122019031008',
                'id_kbk' => 1,
                'id_dosen' => 'AE',
                'kode_dosen' => 'KO066N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '198906102019032019',
                'id_kbk' => 2,
                'id_dosen' => 'AM',
                'kode_dosen' => 'KO067N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'koordinator_ta',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '198911032019031019',
                'id_kbk' => 1,
                'id_dosen' => 'BN',
                'kode_dosen' => 'KO068N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '198409012019031008',
                'id_kbk' => 2,
                'id_dosen' => 'CF',
                'kode_dosen' => 'KO069N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199210222019032018',
                'id_kbk' => 1,
                'id_dosen' => 'HH',
                'kode_dosen' => 'KO071N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199301062019031017',
                'id_kbk' => 1,
                'id_dosen' => 'LH',
                'kode_dosen' => 'KO072N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199105302019031019',
                'id_kbk' => 1,
                'id_dosen' => 'MR',
                'kode_dosen' => 'KO074N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199112182019032014',
                'id_kbk' => 2,
                'id_dosen' => 'SD',
                'kode_dosen' => 'KO075N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '198903252019032023',
                'id_kbk' => 2,
                'id_dosen' => 'SW',
                'kode_dosen' => 'KO076N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '198608202019031014',
                'id_kbk' => 2,
                'id_dosen' => 'TG',
                'kode_dosen' => 'KO078N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
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
                'nip' => '199003022019032019',
                'id_kbk' => 1,
                'id_dosen' => 'RA',
                'kode_dosen' => 'KO062N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'koordinator_ta',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199312282019031013',
                'id_kbk' => 1,
                'id_dosen' => 'DC',
                'kode_dosen' => 'KO070N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'bersedia'
            ],
            [
                'nip' => '199209092019031015',
                'id_kbk' => 3,
                'id_dosen' => 'RZ',
                'kode_dosen' => 'KO073N',
                'status_dosen' => 'aktif',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199106142019032022',
                'id_kbk' => 2,
                'id_dosen' => 'SF',
                'kode_dosen' => 'KO077N',
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
