<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Dokumen;
use Illuminate\Support\Str;

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
                'judul' => 'Sistem Monitoring Tugas Akhir Berbasis Web di Jurusan Teknik Komputer dan Informatika',
                'persentase_plagiarisme' => 7.65,
                'highlight_dokumen' => true,
                'status_plagiarisme' => 'tidak_plagiarisme',
                'id_ambang_batas' => 1,
                'kategori' => 'laporan',
                'deskripsi' => 'Sistem berbasis web untuk memantau dan mengelola proses tugas akhir mahasiswa di Jurusan Teknik Komputer dan Informatika',
                'versi' => 2,
                'ukuran_file' => 15.65 * 1024,
                'id_kota' => 1,
                'id_subkategori' => 3,
                'username' => '221524033',
                'status_berkas' => 'valid',
                'file_path' => 'dokumen/' . Str::random(40) . '.pdf',
                'kode_fta' => '4',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'judul' => 'Optimalisasi Pengelolaan Tugas Akhir dengan Sistem Monitoring Berbasis Web',
                'persentase_plagiarisme' => 4.65,
                'highlight_dokumen' => true,
                'status_plagiarisme' => 'tidak_plagiarisme',
                'id_ambang_batas' => 1,
                'kategori' => 'presentasi',
                'deskripsi' => 'Sistem berbasis web untuk meningkatkan efisiensi dalam pengelolaan dan pemantauan tugas akhir mahasiswa.',
                'versi' => 1,
                'ukuran_file' => 20.00 * 1024, // Ukuran dalam KB
                'id_kota' => 1,
                'id_subkategori' => 3,
                'username' => '221524033',
                'status_berkas' => 'valid',
                'file_path' => 'dokumen/' . Str::random(40) . '.pdf',
                'kode_fta' => '7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'judul' => 'Pengembangan Aplikasi Audit Mutu Internal Berbasis Web untuk SPMI POLBAN',
                'persentase_plagiarisme' => 0,
                'highlight_dokumen' => false,
                'status_plagiarisme' => 'sedang_proses',
                'id_ambang_batas' => 1,
                'kategori' => 'laporan',
                'deskripsi' => 'Aplikasi berbasis web untuk mendukung proses audit mutu internal dalam Sistem Penjaminan Mutu Internal (SPMI) di POLBAN.',
                'versi' => 3,
                'ukuran_file' => 11.77 * 1024, // Ukuran dalam KB
                'id_kota' => 2,
                'id_subkategori' => 3,
                'username' => '221524036',
                'status_berkas' => 'valid',
                'file_path' => 'dokumen/' . Str::random(40) . '.pdf',
                'kode_fta' => '7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'judul' => 'Monitoring Cuaca Real-Time: Solusi Digital untuk Prediksi dan Pemantauan',
                'persentase_plagiarisme' => 12.60,
                'highlight_dokumen' => true,
                'status_plagiarisme' => 'tidak_plagiarisme',
                'id_ambang_batas' => 1,
                'kategori' => 'poster',
                'deskripsi' => 'Platform digital untuk memantau cuaca secara real-time guna meningkatkan akurasi prediksi dan mitigasi bencana.',
                'versi' => 6,
                'ukuran_file' => 32.65 * 1024, // Ukuran dalam KB
                'id_kota' => 3,
                'id_subkategori' => 9,
                'username' => '221524040',
                'status_berkas' => 'valid',
                'file_path' => 'dokumen/' . Str::random(40) . '.pdf',
                'kode_fta' => '11',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'judul' => 'Implementasi Content-Based Filtering dalam Sistem Rekomendasi Film',
                'persentase_plagiarisme' => 44.54,
                'highlight_dokumen' => true,
                'status_plagiarisme' => 'plagiarisme',
                'id_ambang_batas' => 1,
                'kategori' => 'laporan',
                'deskripsi' => 'Penerapan metode content-based filtering dalam sistem rekomendasi film untuk memberikan rekomendasi yang lebih personal.',
                'versi' => 1,
                'ukuran_file' => 33.22 * 1024, // Ukuran dalam KB
                'id_kota' => 5,
                'id_subkategori' => 3,
                'username' => '221524042',
                'status_berkas' => 'tidak_valid',
                'file_path' => 'dokumen/' . Str::random(40) . '.pdf',
                'kode_fta' => '11',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'judul' => 'Sistem Rekomendasi Film: Mengoptimalkan Pengalaman Pengguna dengan Content-Based Filtering',
                'persentase_plagiarisme' => 55.60,
                'highlight_dokumen' => true,
                'status_plagiarisme' => 'plagiarisme',
                'id_ambang_batas' => 1,
                'kategori' => 'presentasi',
                'deskripsi' => 'Sistem rekomendasi film yang mengoptimalkan pengalaman pengguna dengan menggunakan teknik content-based filtering.',
                'versi' => 1,
                'ukuran_file' => 17.32 * 1024, // Ukuran dalam KB
                'id_kota' => 5,
                'id_subkategori' => 3,
                'username' => '221524042',
                'status_berkas' => 'tidak_valid',
                'file_path' => 'dokumen/' . Str::random(40) . '.pdf',
                'kode_fta' => '15',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'judul' => 'Rancang Bangun Sistem Monitoring Kualitas Udara Berbasis IoT untuk Deteksi Polusi',
                'persentase_plagiarisme' => 9.53,
                'highlight_dokumen' => true,
                'status_plagiarisme' => 'tidak_plagiarisme',
                'id_ambang_batas' => 1,
                'kategori' => 'laporan',
                'deskripsi' => 'Sistem berbasis IoT untuk memantau kualitas udara secara real-time dan mendeteksi tingkat polusi di lingkungan sekitar.',
                'versi' => 2,
                'ukuran_file' => 22.32 * 1024, // Ukuran dalam KB
                'id_kota' => 6,
                'id_subkategori' => 3,
                'username' => '221524046',
                'status_berkas' => 'valid',
                'file_path' => 'dokumen/' . Str::random(40) . '.pdf',
                'kode_fta' => '15',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'judul' => 'Monitoring Kualitas Udara dengan IoT: Solusi untuk Lingkungan yang Lebih Sehat',
                'persentase_plagiarisme' => 8.75,
                'highlight_dokumen' => true,
                'status_plagiarisme' => 'tidak_plagiarisme',
                'id_ambang_batas' => 1,
                'kategori' => 'presentasi',
                'deskripsi' => 'Solusi berbasis IoT untuk pemantauan kualitas udara guna menciptakan lingkungan yang lebih sehat dan berkelanjutan.',
                'versi' => 2,
                'ukuran_file' => 16.5 * 1024, // Ukuran dalam KB
                'id_kota' => 6,
                'id_subkategori' => 3,
                'username' => '221524046',
                'status_berkas' => 'valid',
                'file_path' => 'dokumen/' . Str::random(40) . '.pdf',
                'kode_fta' => '17',
                'created_at' => now(),
                'updated_at' => now()
            ],                                                                        
        ];

        foreach ($data as $item) {
            Dokumen::create($item);
        }
    }
}