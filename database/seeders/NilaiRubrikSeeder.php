<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\NilaiRubrik;

class NilaiRubrikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('nilai_kriteria')->truncate();

        $data = [
            [
                'nim' => '221524036',
                'nip' => '198104072006041001',
                'id_rubrik' => 1,
                'nilai_rubrik' => 75.00,
                'status_penilaian_dosen' => 'belum_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524036',
                'nip' => '198104072006041001',
                'id_rubrik' => 1,
                'nilai_rubrik' => 78.00,
                'status_penilaian_dosen' => 'belum_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524036',
                'nip' => '198104072006041001',
                'id_rubrik' => 1,
                'nilai_rubrik' => 77.00,
                'status_penilaian_dosen' => 'belum_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524036',
                'nip' => '198104072006041001',
                'id_rubrik' => 1,
                'nilai_rubrik' => 82.43,
                'status_penilaian_dosen' => 'belum_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524036',
                'nip' => '198104072006041001',
                'id_rubrik' => 5,
                'nilai_rubrik' => 76.35,
                'status_penilaian_dosen' => 'sudah_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524036',
                'nip' => '198104072006041001',
                'id_rubrik' => 5,
                'nilai_rubrik' => 68.93,
                'status_penilaian_dosen' => 'sudah_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524036',
                'nip' => '198104072006041001',
                'id_rubrik' => 8,
                'nilai_rubrik' => 76.55,
                'status_penilaian_dosen' => 'sudah_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524039',
                'nip' => '198104072006041001',
                'id_rubrik' => 1,
                'nilai_rubrik' => 85.43,
                'status_penilaian_dosen' => 'belum_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524039',
                'nip' => '198104072006041001',
                'id_rubrik' => 1,
                'nilai_rubrik' => 78.66,
                'status_penilaian_dosen' => 'belum_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524039',
                'nip' => '198104072006041001',
                'id_rubrik' => 1,
                'nilai_rubrik' => 76.45,
                'status_penilaian_dosen' => 'belum_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524039',
                'nip' => '198104072006041001',
                'id_rubrik' => 1,
                'nilai_rubrik' => 77.43,
                'status_penilaian_dosen' => 'belum_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524039',
                'nip' => '198104072006041001',
                'id_rubrik' => 5,
                'nilai_rubrik' => 78.45,
                'status_penilaian_dosen' => 'sudah_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524039',
                'nip' => '198104072006041001',
                'id_rubrik' => 5,
                'nilai_rubrik' => 74.55,
                'status_penilaian_dosen' => 'sudah_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '221524039',
                'nip' => '198104072006041001',
                'id_rubrik' => 8,
                'nilai_rubrik' => 72.55,
                'status_penilaian_dosen' => 'sudah_dinilai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        foreach ($data as $item) {
            NilaiRubrik::create($item);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
