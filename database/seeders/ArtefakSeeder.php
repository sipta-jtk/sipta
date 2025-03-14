<?php

namespace Database\Seeders;

use App\Models\Artefak;
use Illuminate\Database\Seeder;

class ArtefakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artefak::create([
            'nama_artefak' => 'FTA 01',
            'deskripsi' => 'Persetujuan menjadi Dosen Pembimbing',
            'kategori_artefak' => 'FTA',
            'tenggat_waktu' => '2024-06-30 23:59:00',
        ]);

        Artefak::create([
            'nama_artefak' => 'FTA 02',
            'deskripsi' => 'Pengajuan Topik Tugas Akhir',
            'kategori_artefak' => 'FTA',
            'tenggat_waktu' => '2024-06-30 23:59:00',
        ]);

        Artefak::create([
            'nama_artefak' => 'FTA 03',
            'deskripsi' => 'Persetujuan Menjadi Pembimbing Tugas Akhir',
            'kategori_artefak' => 'FTA',
            'tenggat_waktu' => '2024-06-30 23:59:00',
        ]);

        Artefak::create([
            'nama_artefak' => 'FTA 04',
            'deskripsi' => 'Penilaian Seminar 1',
            'kategori_artefak' => 'FTA',
            'tenggat_waktu' => '2024-06-30 23:59:00',
        ]);

        Artefak::create([
            'nama_artefak' => 'FTA 05',
            'deskripsi' => 'Seminar 1',
            'kategori_artefak' => 'Kehadiran Seminar 1',
            'tenggat_waktu' => '2024-06-30 23:59:00',
        ]);

        Artefak::create([
            'nama_artefak' => 'FTA 05a',
            'deskripsi' => 'Lesson Learn Seminar 1',
            'kategori_artefak' => 'Resume Seminar 1',
            'tenggat_waktu' => '2024-06-30 23:59:00',
        ]);

        Artefak::create([
            'nama_artefak' => 'Proposal Tugas Akhir',
            'deskripsi' => 'Dokumen Lengkap Proposal Tugas Akhir',
            'kategori_artefak' => 'Dokumen',
            'tenggat_waktu' => '2024-06-30 23:59:00',
        ]);
    }
}
