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
        // Matikan pengecekan foreign key sebelum truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('periode_pengajuan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    DB::table('periode_pengajuan')->insert([
        [
            'id_periode_pengajuan' => 1,
            'periode_mulai' => '2025-03-30',
            'periode_akhir' => '2025-04-30',
        ],
        [
            'id_periode_pengajuan' => 2,
            'periode_mulai' => '2025-05-01',
            'periode_akhir' => '2025-05-30',
        ],
    ]);
    }
}
