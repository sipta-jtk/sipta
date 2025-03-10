<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriPenilaian;

class KategoriPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tabel kategori_penilaian
        DB::table('kategori_penilaian')->truncate();

        // Mengaktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Menambahkan data ke tabel kategori_penilaian
        KategoriPenilaian::create([
            'id_form_penilaian' => 5,
            'nama_kategori' => 'Kualitas Proposal',
            'bobot_kategori' => 25,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 1,
            'nama_kategori' => 'Metodologi Penelitian',
            'bobot_kategori' => 25,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 2,
            'nama_kategori' => 'Tinjauan Pustaka',
            'bobot_kategori' => 20,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 3,
            'nama_kategori' => 'Presentasi',
            'bobot_kategori' => 15,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 4,
            'nama_kategori' => 'Penguasaan Materi',
            'bobot_kategori' => 15,
        ]);

        // Form penilaian 2: Sidang Tugas Akhir
        KategoriPenilaian::create([
            'id_form_penilaian' => 5,
            'nama_kategori' => 'Hasil Implementasi',
            'bobot_kategori' => 25,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 1,
            'nama_kategori' => 'Analisis dan Pembahasan',
            'bobot_kategori' => 20,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 3,
            'nama_kategori' => 'Kualitas Penulisan',
            'bobot_kategori' => 15,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 2,
            'nama_kategori' => 'Presentasi Hasil',
            'bobot_kategori' => 15,
        ]);

        KategoriPenilaian::create([
            'id_form_penilaian' => 1,
            'nama_kategori' => 'Kemampuan Menjawab Pertanyaan',
            'bobot_kategori' => 25,
        ]);
    }
}
