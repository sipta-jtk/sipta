<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\PengajuanJadwalKota;

class PengajuanJadwalKotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('pengajuan_jadwal_kota')) {
            return;
        }

        // Menonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pengajuan_jadwal_kota')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'status_mahasiswa' => 1,
                'status_dosen_pembimbing_1' => 1,
                'status_dosen_pembimbing_2' => 0,
                'status_dosen_penguji_1' => 1,
                'status_dosen_penguji_2' => 0,
                'status_koordinator_ta' => 1,
                'id_penjadwalan' => 1,
                'id_kota' => 1,
                'nip' => '197312271999031003',
            ],
            [
                'status_mahasiswa' => 1,
                'status_dosen_pembimbing_1' => 0,
                'status_dosen_pembimbing_2' => 1,
                'status_dosen_penguji_1' => 1,
                'status_dosen_penguji_2' => 1,
                'status_koordinator_ta' => 0,
                'id_penjadwalan' => 2,
                'id_kota' => 2,
                'nip' => '198502102015042001',
            ],
        ];

        foreach ($data as $item) {
            PengajuanJadwalKota::create($item);
        }
    }
}
