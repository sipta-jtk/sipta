<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalDosenPembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jadwal_dosen_pembimbing')->insert([
            [
                'nip' => '197312271999031003',
                'hari' => 'senin',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00'
            ],
            [
                'nip' => '197312271999031003',
                'hari' => 'jumat',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00'
            ],
            [
                'nip' => '197312271999031003',
                'hari' => 'rabu',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:00:00'
            ],
            [
                'nip' => '196101141992021001',
                'hari' => 'selasa',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:00:00'
            ],
            [
                'nip' => '196101141992021001',
                'hari' => 'kamis',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00'
            ],
            [
                'nip' => '198009162009122001',
                'hari' => 'rabu',
                'jam_mulai' => '10:30:00',
                'jam_selesai' => '12:30:00'
            ],
            [
                'nip' => '198009162009122001',
                'hari' => 'senin',
                'jam_mulai' => '14:30:00',
                'jam_selesai' => '16:30:00'
            ],
            [
                'nip' => '198502102015042001',
                'hari' => 'kamis',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00'
            ],
            [
                'nip' => '198502102015042001',
                'hari' => 'sabtu',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00'
            ],
            [
                'nip' => '198502102015042001',
                'hari' => 'sabtu',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00'
            ],
            [
                'nip' => '197604182001121004',
                'hari' => 'jumat',
                'jam_mulai' => '14:30:00',
                'jam_selesai' => '16:30:00'
            ],
            [
                'nip' => '198012122008122001',
                'hari' => 'sabtu',
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '09:00:00'
            ],
            [
                'nip' => '198004192005011002',
                'hari' => 'minggu',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '12:00:00'
            ],
            [
                'nip' => '196208151990031001',
                'hari' => 'senin',
                'jam_mulai' => '11:00:00',
                'jam_selesai' => '13:00:00'
            ],
            [
                'nip' => '198104072006041001',
                'hari' => 'rabu',
                'jam_mulai' => '15:00:00',
                'jam_selesai' => '17:00:00'
            ],
            [
                'nip' => '196210211993031002',
                'hari' => 'selasa',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00'
            ],
            [
                'nip' => '196610181995121001',
                'hari' => 'kamis',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:00:00'
            ],
            [
                'nip' => '196312131992012001',
                'hari' => 'kamis',
                'jam_mulai' => '10:30:00',
                'jam_selesai' => '12:30:00'
            ],
            [
                'nip' => '197109031999032001',
                'hari' => 'rabu',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00'
            ],
            [
                'nip' => '196303161995121001',
                'hari' => 'rabu',
                'jam_mulai' => '14:30:00',
                'jam_selesai' => '16:30:00'
            ],
            [
                'nip' => '196904041998031001',
                'hari' => 'rabu',
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '09:00:00'
            ],
            [
                'nip' => '196111091993032001',
                'hari' => 'selasa',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '12:00:00'
            ],
            [
                'nip' => '196009281994031001',
                'hari' => 'selasa',
                'jam_mulai' => '11:00:00',
                'jam_selesai' => '13:00:00'
            ],
            [
                'nip' => '197912242008121001',
                'hari' => 'kamis',
                'jam_mulai' => '15:00:00',
                'jam_selesai' => '17:00:00'
            ],
            [
                'nip' => '197407182001121002',
                'hari' => 'kamis',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00'
            ],
            [
                'nip' => '198801292015041003',
                'hari' => 'rabu',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:00:00'
            ],
            [
                'nip' => '198705172019031004',
                'hari' => 'rabu',
                'jam_mulai' => '10:30:00',
                'jam_selesai' => '12:30:00'
            ],
            [
                'nip' => '199304262019032028',
                'hari' => 'rabu',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00'
            ],
            [
                'nip' => '199312282019031013',
                'hari' => 'selasa',
                'jam_mulai' => '14:30:00',
                'jam_selesai' => '16:30:00'
            ],
        ]);
    }
}