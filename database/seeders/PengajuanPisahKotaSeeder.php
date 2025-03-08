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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        PengajuanPisahKota::create([
            'nim' => '2201234567', // Ubah Manual
            'id_kota' => '10' // Ubah Manual
        ]);

        PengajuanPisahKota::create([
            'nim' => '2201234568',
            'id_kota' => '12'
        ]);
    }
}
