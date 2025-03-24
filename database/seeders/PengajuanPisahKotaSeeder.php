<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\PengajuanPisahKota;

class PengajuanPisahKotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // if (!Schema::hasTable('pengajuan_pisah_kota')) {
        //     return;
        // }

        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('pengajuan_pisah_kota')->truncate();

        // $data = [
        //     [
        //         'id_pengajuan' => 1,
        //         'nim' => '221524033',
        //         'id_kota' => 1,
        //         'fta_20' => 1,
        //     ],
        //     [
        //         'id_pengajuan' => 2,
        //         'nim' => '221524042',
        //         'id_kota' => 5,
        //         'fta_20' => 1,
        //     ],
        //     [
        //         'id_pengajuan' => 3,
        //         'nim' => '221524061',
        //         'id_kota' => 8,
        //         'fta_20' => 1,
        //     ]
        // ];

        // foreach ($data as $item) {
        //     PengajuanPisahKota::create($item);
        // }

        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
