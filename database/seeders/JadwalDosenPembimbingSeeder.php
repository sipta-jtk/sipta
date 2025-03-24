<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalDosenPembimbing;
use Illuminate\Support\Facades\Schema;

class JadwalDosenPembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jadwal_dosen_pembimbing')->insert([
            [
                'id_jadwal_dosbim' => 1, 
                'nip' => '198706302019031011', 
                'hari' => 'senin', 
                'jam_mulai' => '08:00:00', 
                'jam_selesai' => '10:00:00'
            ],
            [
                'id_jadwal_dosbim' => 2, 
                'nip' => '197109031999032001', 
                'hari' => 'selasa', 
                'jam_mulai' => '13:00:00', 
                'jam_selesai' => '15:00:00'
            ],
            [
                'id_jadwal_dosbim' => 3, 
                'nip' => '198104072006041001', 
                'hari' => 'rabu', 
                'jam_mulai' => '09:00:00', 
                'jam_selesai' => '11:00:00'
            ],
            [
                'id_jadwal_dosbim' => 4, 
                'nip' => '198502102015042001', 
                'hari' => 'kamis', 
                'jam_mulai' => '09:00:00', 
                'jam_selesai' => '11:00:00'
            ],
            [
                'id_jadwal_dosbim' => 5, 
                'nip' => '199301062019031017', 
                'hari' => 'jumat', 
                'jam_mulai' => '13:00:00', 
                'jam_selesai' => '15:00:00'
            ],
        ]);
    }
}
