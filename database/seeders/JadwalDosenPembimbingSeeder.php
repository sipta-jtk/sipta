<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalDosenPembimbing;

class JadwalDosenPembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('jadwal_dosen_pembimbing')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('jadwal_dosen_pembimbing')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'nip' => '197312271999031003',
                'hari' => 'senin',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00'
            ],
            [
                'nip' => '198502102015042001',
                'hari' => 'selasa',
                'jam_mulai' => '11:00:00',
                'jam_selesai' => '13:00:00'
            ],
            [
                'nip' => '197201061999031002',
                'hari' => 'rabu',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00'
            ],
        ];

        foreach ($data as $item) {
            JadwalDosenPembimbing::create($item);
        }
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
