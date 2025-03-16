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
        // Menonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tabel kategori_penilaian
        DB::table('kategori_penilaian')->truncate();

        // Mengaktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Menambahkan data ke tabel kategori_penilaian
        $data = [
            ['kode_fta' => 1, 'nama_kategori' => 'Kualitas Proposal'],
            ['kode_fta' => 2, 'nama_kategori' => 'Metodologi'],
            ['kode_fta' => 3, 'nama_kategori' => 'Tinjauan Pustaka'],
            ['kode_fta' => 1, 'nama_kategori' => 'Presentasi'],
            ['kode_fta' => 2, 'nama_kategori' => 'Penguasaan Materi'],
            ['kode_fta' => 3, 'nama_kategori' => 'Hasil Implementasi'],
            ['kode_fta' => 1, 'nama_kategori' => 'Analisis'],
            ['kode_fta' => 2, 'nama_kategori' => 'Kualitas Penulisan'],
            ['kode_fta' => 3, 'nama_kategori' => 'Presentasi Hasil']
        ];

        foreach ($data as $item) {
            KategoriPenilaian::create($item);
        }

    }
}
