<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notifikasi;
use Carbon\Carbon;

class NotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notifikasi::create([
            'tipe_notifikasi' => 'informasi',
            'judul' => 'Pembaruan Sistem',
            'isi_notifikasi' => 'Sistem akan diperbarui pada pukul 23:00 WIB.',
            'sumber_notifikasi' => 'Admin',
            'created_at' => Carbon::now()
        ]);

        Notifikasi::create([
            'tipe_notifikasi' => 'peringatan',
            'judul' => 'Reminder Pengumpulan Progres',
            'isi_notifikasi' => 'Jangan lupa untuk menyelesaikan progres kemarin',
            'sumber_notifikasi' => 'Pembimbing TA',
            'created_at' => Carbon::now()
        ]);
    }
}
