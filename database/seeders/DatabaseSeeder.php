<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(FasilitasSeeder::class);
        $this->call(FormPenilaianSeeder::class);
        $this->call(JadwalDosenPembimbingSeeder::class);
        $this->call(KaprodiSeeder::class);
        $this->call(GedungSeeder::class);
    }
}
