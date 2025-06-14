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
                'nim' => '221524033',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 1
            ],
            [
                'nim' => '221524034',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'tahun_ta' => 2025,
                'id_kota' => null
            ],
            [
                'nim' => '221524035',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 1
            ],
            [
                'nim' => '221524036',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 2
            ],
            [
                'nim' => '221524037',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'tahun_ta' => 2025,
                'id_kota' => null
            ],
            [
                'nim' => '221524039',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 2
            ],
            [
                'nim' => '221524040',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 3
            ],
            [
                'nim' => '221524041',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'tahun_ta' => 2025,
                'id_kota' => null
            ],
            [
                'nim' => '221524042',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 5
            ],
            [
                'nim' => '221524043',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 5
            ],
            [
                'nim' => '221524044',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'tahun_ta' => 2025,
                'id_kota' => null
            ],
            [
                'nim' => '221524045',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 5
            ],
            [
                'nim' => '221524046',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 6
            ],
            [
                'nim' => '221524047',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'tahun_ta' => 2025,
                'id_kota' => null
            ],
            [
                'nim' => '221524049',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 6
            ],
            [
                'nim' => '221524050',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 6
            ],
            [
                'nim' => '221524051',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'tahun_ta' => 2025,
                'id_kota' => null
            ],
            [
                'nim' => '221524052',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 3
            ],
            [
                'nim' => '221524053',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 4
            ],
            [
                'nim' => '221524054',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'tahun_ta' => 2025,
                'id_kota' => null
            ],
            [
                'nim' => '221524055',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 7
            ],
            [
                'nim' => '221524056',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 7
            ],
            [
                'nim' => '221524057',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'tahun_ta' => 2025,
                'id_kota' => null
            ],
            [
                'nim' => '221524058',
                'tahun_masuk' => 2022,
                'kelas' => '3A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 7
            ],
            [
                'nim' => '221524059',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 8
            ],
            [
                'nim' => '221524060',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 9
            ],
            [
                'nim' => '221524061',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 8
            ],
            [
                'nim' => '221524062',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 8
            ],
            [
                'nim' => '221524063',
                'tahun_masuk' => 2022,
                'kelas' => '3B',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 9
            ]
        ];

        foreach ($data as $item) {
            Mahasiswa::create($item);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}