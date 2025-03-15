<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\PengajuanJadwalKota;

class PengajuanJadwalKotaSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pengajuan_jadwal_kota')->truncate(); // Membersihkan tabel sebelum seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'status_mahasiswa' => 1,
                'status_dosen_pembimbing_1' => 1,
                'status_dosen_pembimbing_2' => 1,
                'status_dosen_penguji_1' => 0,
                'status_dosen_penguji_2' => 1,
                'status_koordinator_ta' > 1,
                'id_penjadwalan' => 1,
                'id_kota' => 1,
                'nip' => '197312271999031003'
            ],
            [
                'status_mahasiswa' => 1,
                'status_dosen_pembimbing_1' => 1,
                'status_dosen_pembimbing_2' => 1,
                'status_dosen_penguji_1' => 0,
                'status_dosen_penguji_2' => 1,
                'status_koordinator_ta' > 1,
                'id_penjadwalan' => 1,
                'id_kota' => 1,
                'nip' => '198502102015042001'
            ],
        ];

        foreach ($data as $item) {
            PengajuanJadwalKota::create($item);
        }
    }
}