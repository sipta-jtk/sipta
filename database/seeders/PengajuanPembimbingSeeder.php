<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\PengajuanPembimbing;
use Carbon\Carbon;

class PengajuanPembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // if (!Schema::hasTable('pengajuan_pembimbing')) {
        //     return;
        // }

        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('kuota_membimbing')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // $data = [
        //     [
        //         'id_pengajuan_pembimbing' => 1,
        //         'id_kota' => 1,
        //         'status_pengajuan' => 'diproses',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'id_pengajuan_pembimbing' => 2,
        //         'id_kota' => 2,
        //         'status_pengajuan' => 'diterima',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'id_pengajuan_pembimbing' => 3,
        //         'id_kota' => 3,
        //         'status_pengajuan' => 'diterima',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'id_pengajuan_pembimbing' => 4,
        //         'id_kota' => 4,
        //         'status_pengajuan' => 'diterima',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'id_pengajuan_pembimbing' => 5,
        //         'id_kota' => 5,
        //         'status_pengajuan' => 'diterima',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'id_pengajuan_pembimbing' => 6,
        //         'id_kota' => 6,
        //         'status_pengajuan' => 'diproses',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'id_pengajuan_pembimbing' => 7,
        //         'id_kota' => 7,
        //         'status_pengajuan' => 'diterima',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'id_pengajuan_pembimbing' => 8,
        //         'id_kota' => 8,
        //         'status_pengajuan' => 'diterima',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'id_pengajuan_pembimbing' => 9,
        //         'id_kota' => 9,
        //         'status_pengajuan' => 'diproses',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ]
        // ];

        // foreach ($data as $item) {
        //     PengajuanPembimbing::create($item);
        // }
    }
}