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
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'tahun_ta' => 2025,
                'id_kota' => null
            ],
            [
                'nim' => '221511021',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'tahun_ta' => 2025,
                'id_kota' => null
            ],
            [
                'nim' => '221511028',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511002',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511022',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511032',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511003',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511004',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511015',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511026',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511005',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511009',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511029',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511006',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511013',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511031',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            // Untuk keperluan Demo
            // [
            //     'nim' => '221511007',
            //     'tahun_masuk' => 2022,
            //     'kelas' => 'A',
            //     'id_prodi' => 1,
            //     'status_ta' => 'mahasiswa_ta',
            //     'id_kota' => null,                
            //     'tahun_ta' => 2025
            // ],
            [
                'nim' => '221511023',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511030',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511008',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511011',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511027',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511010',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511020',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511025',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511012',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511016',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511018',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511033',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511024',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            // Kelas B
            [
                'nim' => '221511034',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511046',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511052',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511035',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_ta',
                'tahun_ta' => 2025,
                'id_kota' => 8
            ],
            [
                'nim' => '221511041',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511054',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511037',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511058',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511063',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511042',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511045',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511048',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511043',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511051',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511065',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511040',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511060',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511066',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511039',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511057',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511059',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511038',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511062',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511064',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511044',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511049',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511050',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511036',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511014',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '221511056',
                'tahun_masuk' => 2022,
                'kelas' => 'A',
                'id_prodi' => 1,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            // [
            //     'nim' => '201511051',
            //     'tahun_masuk' => 2020,
            //     'kelas' => 'A',
            //     'id_prodi' => 1,
            //     'status_ta' => 'mahasiswa_non_ta',
            //     'id_kota' => null,
            //     'tahun_ta' => 2025
            // ],
            // D4
            [
                'nim' => '201524017',
                'tahun_masuk' => 2020,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            // Untuk keperluan Demo
            // [
            //     'nim' => '211524001',
            //     'tahun_masuk' => 2021,
            //     'kelas' => 'A',
            //     'id_prodi' => 2,
            //     'status_ta' => 'mahasiswa_non_ta',
            //     'id_kota' => null,                
            //     'tahun_ta' => 2025
            // ],
            [
                'nim' => '211524003',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524002',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524026',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524004',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524027',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524005',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524029',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524006',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524017',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524009',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524030',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524010',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524016',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524012',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524032',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524015',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524013',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524018',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524024',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524007',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524019',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524021',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524022',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524025',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524028',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524008',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524031',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524033',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524060',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524034',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524044',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524036',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524041',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524037',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524057',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524038',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524048',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524039',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524047',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524042',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524062',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524056',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524043',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524045',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524051',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524046',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524054',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524049',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524050',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524052',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524063',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524053',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524061',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524059',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524055',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524064',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524058',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524011',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524023',
                'tahun_masuk' => 2021,
                'kelas' => 'A',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ],
            [
                'nim' => '211524040',
                'tahun_masuk' => 2021,
                'kelas' => 'B',
                'id_prodi' => 2,
                'status_ta' => 'mahasiswa_non_ta',
                'id_kota' => null,
                'tahun_ta' => 2025
            ]

        ];

        foreach ($data as $mahasiswa) {
            Mahasiswa::create($mahasiswa);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}