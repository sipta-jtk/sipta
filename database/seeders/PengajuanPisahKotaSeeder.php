<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        if (!Schema::hasTable('pengajuan_pisah_kota')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pengajuan_pisah_kota')->truncate();

        PengajuanPisahKota::create([
            'nim' => '221524033', 
            'id_kota' => '1' 
        ]);

        PengajuanPisahKota::create([
            'nim' => '221524042',
            'id_kota' => '5'
        ]);

        PengajuanPisahKota::create([
            'nim' => '221524061',
            'id_kota' => '8'
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
