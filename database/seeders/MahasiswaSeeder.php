<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('mahasiswa')->truncate();

        $data = [
            [
                'nim' => '221511001',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511021',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511028',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511002',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511022',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511032',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511003',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511004',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511015',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511026',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511005',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511009',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511029',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511006',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511013',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511031',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511007',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 1
            ],
            [
                'nim' => '221511023',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 1
            ],
            [
                'nim' => '221511030',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 1
            ],
            [
                'nim' => '221511008',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 1
            ],
            [
                'nim' => '221511011',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 1
            ],
            [
                'nim' => '221511027',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 2
            ],
            [
                'nim' => '221511010',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511020',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511025',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511012',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511016',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511018',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511033',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511024',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            // Kelas B
            [
                'nim' => '221511034',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511046',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511052',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511035',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511041',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511054',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511037',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511058',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511063',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511042',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511045',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511048',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511043',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 3
            ],
            [
                'nim' => '221511051',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 3
            ],
            [
                'nim' => '221511065',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 3
            ],
            [
                'nim' => '221511040',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511060',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511066',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511039',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511057',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511059',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511038',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511062',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511064',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511044',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511049',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511050',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511036',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511014',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '221511056',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            [
                'nim' => '201511051',
                'tahun_masuk' => 2020,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null
            ],
            // D4
            // KoTA 405 - Danu Mahesa
            [
                'nim' => '211524037',
                'tahun_masuk' => 2025,
                'kelas' => '4B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 4
            ],

            // KoTA 405 - Regi Purnama
            [
                'nim' => '211524057',
                'tahun_masuk' => 2025,
                'kelas' => '4B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 4
            ],

            // KoTA 406 - Dea Salma Isnaini
            [
                'nim' => '211524038',
                'tahun_masuk' => 2025,
                'kelas' => '4B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 5
            ],

            // KoTA 406 - Mey Meizia Galtiady
            [
                'nim' => '211524048',
                'tahun_masuk' => 2025,
                'kelas' => '4B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 5
            ],

            // KoTA 407 - Delvito Rahim Derivansyah
            [
                'nim' => '211524039',
                'tahun_masuk' => 2025,
                'kelas' => '4B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 6
            ],

            // KoTA 407 - Mentari Ayu Alysia Sudrajat
            [
                'nim' => '211524047',
                'tahun_masuk' => 2025,
                'kelas' => '4B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'id_kota' => 6
            ],
        ];

        foreach ($data as $mahasiswa) {
            Mahasiswa::create($mahasiswa);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}