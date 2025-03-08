<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Kehadiran;

class KehadiranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table first
        // Schema::disableForeignKeyConstraints();
        Kehadiran::truncate();
        // Schema::enableForeignKeyConstraints();

        // Create sample attendance data
        $kehadiran = [
            [
            'id_penjadwalan' => 1,
            'username' => 'mahasiswa101',
            'status_hadir' => 'hadir',
            ],
            [
            'id_penjadwalan' => 1,
            'username' => 'mahasiswa102',
            'status_hadir' => 'tidak_hadir',
            ],
            [
            'id_penjadwalan' => 2,
            'username' => 'mahasiswa101',
            'status_hadir' => 'tidak_hadir',
            ],
            [
            'id_penjadwalan' => 2,
            'username' => 'mahasiswa103',
            'status_hadir' => 'tidak_hadir',
            ],
            [
            'id_penjadwalan' => 3,
            'username' => 'mahasiswa102',
            'status_hadir' => 'hadir',
            ],
            [
            'id_penjadwalan' => 3,
            'username' => 'mahasiswa104',
            'status_hadir' => 'hadir',
            ],
            [
            'id_penjadwalan' => 4,
            'username' => 'mahasiswa105',
            'status_hadir' => 'tidak_hadir',
            ],
            [
            'id_penjadwalan' => 5,
            'username' => 'mahasiswa101',
            'status_hadir' => 'hadir',
            ],
            [
            'id_penjadwalan' => 5,
            'username' => 'mahasiswa106',
            'status_hadir' => 'tidak_hadir',
            ],
        ];

        // Insert all data into the database
        foreach ($kehadiran as $k) {
            Kehadiran::create($k);
        }
    }
}
