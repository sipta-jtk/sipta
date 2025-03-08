<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JadwalDosenPembimbing;

class JadwalDosenPembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JadwalDosenPembimbing::create([
            'nip' => '197312271999031003',
            'hari' => 'senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00'
        ]);

        JadwalDosenPembimbing::create([
            'nip' => '198502102015042001',
            'hari' => 'selasa',
            'jam_mulai' => '11:00:00',
            'jam_selesai' => '13:00:00'
        ]);

        JadwalDosenPembimbing::create([
            'nip' => '197201061999031002',
            'hari' => 'rabu',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00'
        ]);
    }
}
