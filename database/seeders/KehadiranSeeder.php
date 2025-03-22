<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class KehadiranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kehadiran')->insert([
            [
                'status_kelulusan' => 'lulus_dengan_perbaikan_laporan',
                'batas_revisi' => '2025-04-15',
                'foto_sidang' => 'sidang1.png',
                'id_penjadwalan' => 1,
                'username' => '221524036',
                'status_hadir' => 'hadir',
            ],
            [
                'status_kelulusan' => 'lulus_dengan_perbaikan_laporan',
                'batas_revisi' => '2025-04-30',
                'foto_sidang' => 'sidang2.png',
                'id_penjadwalan' => 2,
                'username' => '221524039',
                'status_hadir' => 'hadir',
            ],
            [
                'status_kelulusan' => 'lulus_tanpa_perbaikan_laporan',
                'batas_revisi' => '2025-04-30',
                'foto_sidang' => 'sidang3.png',
                'id_penjadwalan' => 3,
                'username' => '221524036',
                'status_hadir' => 'tidak_hadir',
            ],
            [
                'status_kelulusan' => 'mengulang_sidang_tugas_akhir',
                'batas_revisi' => '2025-04-30',
                'foto_sidang' => 'sidang4.png',
                'id_penjadwalan' => 4,
                'username' => '221524046',
                'status_hadir' => 'tidak_hadir',
            ],
            [
                'status_kelulusan' => 'tidak_lulus',
                'batas_revisi' => '2025-04-30',
                'foto_sidang' => 'sidang5.png',
                'id_penjadwalan' => 5,
                'username' => '221524053',
                'status_hadir' => 'hadir',
            ],
        ]);
    }
}