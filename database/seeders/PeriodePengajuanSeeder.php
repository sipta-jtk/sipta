<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\PeriodePengajuan;

class PeriodePengajuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('periode_pengajuan')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('periode_pengajuan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        PeriodePengajuan::create([
            'periode_mulai' => '2025-03-15', // Ubah Manual
            'periode_akhir' => '2025-03-17' // Ubah Manual
        ]);

        PeriodePengajuan::create([
            'periode_mulai' => '2025-04-25',
            'periode_akhir' => '2025-04-27'
        ]);
    }
}
