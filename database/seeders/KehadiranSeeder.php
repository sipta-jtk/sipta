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
            'username' => '221524059',
            'status_hadir' => 'hadir',
            ],
            [
            'id_penjadwalan' => 2,
            'username' => '221524049',
            'status_hadir' => 'tidak_hadir',
            ],
        ];

        // Insert all data into the database
        foreach ($kehadiran as $k) {
            Kehadiran::create($k);
        }
    }
}
