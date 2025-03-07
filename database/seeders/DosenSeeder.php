<?php

namespace Database\Seeders;

use App\Models\Dosen;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('dosen')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        Dosen::create([
            'nip' => '1987654321',
            'maks_bimbingan_d4' => 1,
            'maks_bimbingan_d3' => 3,
            'id_dosen' => "K1",
            'kode_dosen' => "AMBTKM", 
            'status_dosen' => 'aktif',
            'role_dosen' => 'dosen'
        ]);

        Dosen::create([
            'nip' => '1987654322',
            'maks_bimbingan_d4' => 2,
            'maks_bimbingan_d3' => 4,
            'id_dosen' => "K2",
            'kode_dosen' => "AMBTMM", 
            'status_dosen' => 'aktif',
            'role_dosen' => 'dosen'
        ]);

    }
}
