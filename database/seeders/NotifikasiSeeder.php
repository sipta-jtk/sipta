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
            'tipe_notifikasi' => 'pemberitahuan',
            'judul' => '[Pemberitahuan] Dosen Pembimbing Tugas Akhir Telah Ditetapkan!',
            'isi_notifikasi' => "Halo Muhamad Agim,\n\nKami ingin mengingatkan bahwa dosen pembimbing untuk Tugas Akhir Anda telah ditetapkan. Berikut detailnya:\n\nNIP Dosen: 197312271999031003\nNama: Ade Chandra Nugraha, S.Si., M.T.\n\nJangan lupa mempersiapkan diri sebaik mungkin dan mulai koordinasi dengan pembimbing.\nTerima kasih atas perhatian Anda,\n{Tim Sipta}",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Notifikasi::create([
            'tipe_notifikasi' => 'reminder',
            'judul' => '[Reminder] Jadwal Ujian Sidang Tugas Akhir Telah Dijadwalkan!',
            'isi_notifikasi' => "Halo Muhamad Agim,\n\nKami ingin mengingatkan Anda bahwa jadwal ujian sidang Tugas Akhir Anda telah dijadwalkan. Berikut detailnya:\n\nTanggal: 2025-03-17 \nWaktu: 9:00:00\nTempat: 2\n\nMohon konfirmasi kehadiran Anda dengan mengklik tombol di bawah ini.\n{Link Konfirmasi Kehadiran}\n\nJangan lupa mempersiapkan diri sebaik mungkin untuk ujian sidang Anda.\n\nTerima kasih atas perhatian Anda",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Notifikasi::create([
            'tipe_notifikasi' => 'reminder',
            'judul' => '[Pemberitahuan] Mahasiswa Bimbingan Telah Ditetapkan!',
            'isi_notifikasi' => "Halo Ade Chandra Nugraha, S.Si., M.T.,\n\nKami ingin memberitahukan bahwa Anda telah ditetapkan sebagai pembimbing untuk mahasiswa berikut:\n\nTopik: RANCANG BANGUN SISTEM MONITORING KUALITAS UDARA MENGGUNAKAN IOT\nNama Mahasiswa: Muhamad Agim\n\nHarap mulai koordinasi dengan mahasiswa untuk bimbingan.\nTerima kasih atas perhatian Anda,\n{Tim Sipta}",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Notifikasi::create([
            'tipe_notifikasi' => 'peringatan',
            'judul' => 'Reminder Pengumpulan Tugas Akhir',
            'isi_notifikasi' => 'Jangan lupa untuk mengumpulkan tugas akhir anda ya tersisa 3 hari lagi',
            'sumber_notifikasi' => 'Mahasiswa',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Notifikasi::create([
            'tipe_notifikasi' => 'peringatan',
            'judul' => 'Reminder Pengumpulan Tugas Akhir',
            'isi_notifikasi' => 'Jangan lupa untuk mengumpulkan tugas akhir anda ya tersisa 3 hari lagi',
            'sumber_notifikasi' => 'Mahasiswa',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
