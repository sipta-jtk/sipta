<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Dokumen;

class DokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('dokumen')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('dokumen')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'judul' => 'Dokumen 1',
                'persentase_plagiarisme' => 10.0,
                'highlight_dokumen' => true,
                'status_plagiarisme' => 'tidak_plagiarisme',
                'review' => 'Review Dokumen 1',
                'kategori' => 'laporan',
                'deskripsi' => 'Deskripsi Dokumen 1',
                'versi' => 1,
                'ukuran_file' => 1.0,
                'notes' => 'Notes Dokumen 1',
                'id_kota' => 1,
                'id_label' => 1,
                'id_subkategori' => 1,
                'username' => '221524059',
                'status_berkas' => 'valid',
                'uploaded_at' => now(),
                'updated_at' => now()
            ],
            [
                'judul' => 'Dokumen 2',
                'persentase_plagiarisme' => 70.0,
                'highlight_dokumen' => false,
                'status_plagiarisme' => 'plagiarisme',
                'review' => 'Review Dokumen 2',
                'kategori' => 'laporan',
                'deskripsi' => 'Deskripsi Dokumen 2',
                'versi' => 1,
                'ukuran_file' => 2.0,
                'notes' => 'Notes Dokumen 2',
                'id_kota' => 2,
                'id_label' => null,
                'id_subkategori' => 2,
                'username' => '221524049',
                'status_berkas' => 'ditunda',
                'uploaded_at' => now(),
                'updated_at' => now()
            ],
            [
                'judul' => 'Dokumen 3',
                'persentase_plagiarisme' => 10.0,
                'highlight_dokumen' => false,
                'status_plagiarisme' => 'tidak_plagiarisme',
                'review' => 'Review Dokumen 3',
                'kategori' => 'poster',
                'deskripsi' => 'Deskripsi Dokumen 3',
                'versi' => 1,
                'ukuran_file' => 1.2,
                'notes' => 'Notes Dokumen 3',
                'id_kota' => 1,
                'id_label' => 2,
                'id_subkategori' => 1,
                'username' => '221524039',
                'status_berkas' => 'tidak_valid',
                'uploaded_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($data as $item) {
            Dokumen::create($item);
        }
    }
}
