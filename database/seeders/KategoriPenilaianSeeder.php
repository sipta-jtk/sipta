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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kategori_penilaian')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            
            [
                'id_kategori' => 1,
                'id_fta' => 2,
                'nama_kategori' => 'Seminar 2',
                'kunci_penilaian' => false,
            ],
            [
                'id_kategori' => 2,
                'id_fta' => 4,
                'nama_kategori' => 'Seminar 3',
                'kunci_penilaian' => false,
            ],
            [
                'id_kategori' => 3,
                'id_fta' => 6,
                'nama_kategori' => 'Sidang D3',
                'kunci_penilaian' => false,
            ],
            [
                'id_kategori' => 4,
                'id_fta' => 8,
                'nama_kategori' => 'Pelaksanaan TA',
                'kunci_penilaian' => false,
            ]
        ];

        foreach ($data as $item) {
            KategoriPenilaian::create($item);
        }

    }
}
