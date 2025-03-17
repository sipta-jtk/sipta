<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('laporan_ta')->insert([
            [
                'versi' => 'V1.0',
                'judul' => 'Sistem Manajemen Tugas Akhir',
                'tanggal_dibuat' => '2025-03-01',
                'terakhir_diedit' => '2025-03-01',
            ],
            [
                'versi' => 'V1.1',
                'judul' => 'Pengembangan Fitur Notifikasi',
                'tanggal_dibuat' => '2025-03-10',
                'terakhir_diedit' => '2025-03-12',
            ],
            [
                'versi' => 'V2.0',
                'judul' => 'Integrasi dengan API',
                'tanggal_dibuat' => '2025-04-05',
                'terakhir_diedit' => '2025-04-05',
            ]
        ]);
    }
}
