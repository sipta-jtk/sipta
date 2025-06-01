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
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '196810141993032002',
                'id_kbk' => 2,  
                'id_dosen' => 'AN',
                'kode_dosen' => 'KO002N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //tidak_bersedia
            ],
            [
                'nip' => '197201061999031002',
                'id_kbk' => 1,
                'id_dosen' => 'BW',
                'kode_dosen' => 'KO003N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //tidak_bersedia
            ],
            [
                'nip' => '196012261992031001',
                'id_kbk' => 1,
                'id_dosen' => 'DP',
                'kode_dosen' => 'KO005N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //tidak_bersedia
            ],
            [
                'nip' => '196101141992021001',
                'id_kbk' => 1,
                'id_dosen' => 'EB',
                'kode_dosen' => 'KO016N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '198009162009122001',
                'id_kbk' => 2,
                'id_dosen' => 'FI',
                'kode_dosen' => 'KO057N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '198604122014041001',
                'id_kbk' => 3,
                'id_dosen' => 'GI',
                'kode_dosen' => 'KO059N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //tidak_bersedia
            ],
            [
                'nip' => '198502102015042001',
                'id_kbk' => 3,
                'id_dosen' => 'HA',
                'kode_dosen' => 'KO060N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '197604182001121004',
                'id_kbk' => 3,
                'id_dosen' => 'IA',
                'kode_dosen' => 'KO023N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '198012122008122001',
                'id_kbk' => 2,
                'id_dosen' => 'ID',
                'kode_dosen' => 'KO056N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '198004192005011002',
                'id_kbk' => 3,
                'id_dosen' => 'IS',
                'kode_dosen' => 'KO045N',
                'role_dosen' => 'dosen', //koordinator_ta
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '198706302019031011',
                'id_kbk' => 1,
                'id_dosen' => 'WW',
                'kode_dosen' => 'KO079N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '198104072006041001',
                'id_kbk' => 3,
                'id_dosen' => 'PH',
                'kode_dosen' => 'KO048N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '196210211993031002',
                'id_kbk' => 1,
                'id_dosen' => 'JN',
                'kode_dosen' => 'KO018N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'//bersedia
            ],
            [
                'nip' => '196610181995121001',
                'id_kbk' => 2,
                'id_dosen' => 'JO',
                'kode_dosen' => 'KO007N',
                'role_dosen' => 'dosen', //koordinator_ta
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '196312131992012001',
                'id_kbk' => 1,
                'id_dosen' => 'NJ',
                'kode_dosen' => 'KO008N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '197109031999032001',
                'id_kbk' => 2,
                'id_dosen' => 'SN',
                'kode_dosen' => 'KO009N',
                'role_dosen' => 'koordinator_ta', //dosen
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '196303161995121001',
                'id_kbk' => 2,
                'id_dosen' => 'SP',
                'kode_dosen' => 'KO022N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '196904041998031001',
                'id_kbk' => 1,
                'id_dosen' => 'ST',
                'kode_dosen' => 'KO021N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '196111091993032001',
                'id_kbk' => 1,
                'id_dosen' => 'TR',
                'kode_dosen' => 'KO019N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '196009281994031001',
                'id_kbk' => 1,
                'id_dosen' => 'UT',
                'kode_dosen' => 'KO012N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '197912242008121001',
                'id_kbk' => 2,
                'id_dosen' => 'YA',
                'kode_dosen' => 'KO052N',
                'role_dosen' => 'kajur',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '197407182001121002',
                'id_kbk' => 3,
                'id_dosen' => 'HA',
                'kode_dosen' => 'KO060N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '198604212018031001',
                'id_kbk' => 1,
                'id_dosen' => 'MV',
                'kode_dosen' => 'KO063N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //tidak_bersedia
            ],
            [
                'nip' => '198801292015041003',
                'id_kbk' => 2,
                'id_dosen' => 'ZA',
                'kode_dosen' => 'KO061N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '198705172019031004',
                'id_kbk' => 2,
                'id_dosen' => 'AB',
                'kode_dosen' => 'KO064N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '199304262019032028',
                'id_kbk' => 3,
                'id_dosen' => 'AP',
                'kode_dosen' => 'KO065N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '198405122019031008',
                'id_kbk' => 1,
                'id_dosen' => 'AE',
                'kode_dosen' => 'KO066N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '198906102019032019',
                'id_kbk' => 2,
                'id_dosen' => 'AM',
                'kode_dosen' => 'KO067N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '198911032019031019',
                'id_kbk' => 1,
                'id_dosen' => 'BN',
                'kode_dosen' => 'KO068N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '198409012019031008',
                'id_kbk' => 2,
                'id_dosen' => 'CF',
                'kode_dosen' => 'KO069N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199210222019032018',
                'id_kbk' => 1,
                'id_dosen' => 'HH',
                'kode_dosen' => 'KO071N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199301062019031017',
                'id_kbk' => 1,
                'id_dosen' => 'LH',
                'kode_dosen' => 'KO072N',
                'role_dosen' => 'koordinator_ta', //dosen
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199105302019031019',
                'id_kbk' => 1,
                'id_dosen' => 'MR',
                'kode_dosen' => 'KO074N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199112182019032014',
                'id_kbk' => 2,
                'id_dosen' => 'SD',
                'kode_dosen' => 'KO075N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '198903252019032023',
                'id_kbk' => 2,
                'id_dosen' => 'SW',
                'kode_dosen' => 'KO076N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '198608202019031014',
                'id_kbk' => 2,
                'id_dosen' => 'TG',
                'kode_dosen' => 'KO078N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199003022019032019',
                'id_kbk' => 1,
                'id_dosen' => 'RA',
                'kode_dosen' => 'KO062N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199312282019031013',
                'id_kbk' => 1,
                'id_dosen' => 'DC',
                'kode_dosen' => 'KO070N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi' //bersedia
            ],
            [
                'nip' => '199209092019031015',
                'id_kbk' => 3,
                'id_dosen' => 'RZ',
                'kode_dosen' => 'KO073N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ],
            [
                'nip' => '199106142019032022',
                'id_kbk' => 2,
                'id_dosen' => 'SF',
                'kode_dosen' => 'KO077N',
                'role_dosen' => 'dosen',
                'bersedia_membimbing' => 'belum_konfirmasi'
            ]
        ];

        foreach ($data as $item) {
            Dosen::create($item);
        }
    }
}
