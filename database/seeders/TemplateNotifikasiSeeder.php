<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\TemplateNotifikasi;

class TemplateNotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('template_notifikasi')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('template_notifikasi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        TemplateNotifikasi::create([
            'judul_notifikasi' => 'Pengumuman Seminar 1',
            'isi_in_apps' => 'Jangan lupa menghadiri Seminar 1 sesuai jadwal.',
            'isi_in_email' => 'Halo, Seminar 1 telah dijadwalkan. Pastikan Anda hadir sesuai jadwal.',
            'jenis_notifikasi' => 'pemberitahuan',
        ]);

        TemplateNotifikasi::create([
            'judul_notifikasi' => 'Pengingat Bimbingan',
            'isi_in_apps' => 'Segera lakukan bimbingan dengan dosen pembimbing Anda.',
            'isi_in_email' => 'Pengingat: Segera lakukan bimbingan dengan dosen pembimbing.',
            'jenis_notifikasi' => 'reminder',
        ]);
    }
}
