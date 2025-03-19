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
    Schema::disableForeignKeyConstraints();

    PengajuanJadwalKota::truncate();

    $records = [
        [
            'status_mahasiswa' => true,
            'status_dosen_pembimbing_1' => true,
            'status_dosen_pembimbing_2' => true,
            'status_dosen_penguji_1' => true,
            'status_dosen_penguji_2' => true,
            'status_koordinator_ta' => true,
            'id_penjadwalan' => 1,
            'id_kota' => 2,
        ],
        [
            'status_mahasiswa' => true,
            'status_dosen_pembimbing_1' => true,
            'status_dosen_pembimbing_2' => false,
            'status_dosen_penguji_1' => false,
            'status_dosen_penguji_2' => false,
            'status_koordinator_ta' => true,
            'id_penjadwalan' => 2,
            'id_kota' => 6,
        ],
    ];

    foreach ($records as $record) {
        PengajuanJadwalKota::create($record);
    }

    Schema::enableForeignKeyConstraints();
    }
}
