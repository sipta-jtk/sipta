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
        $this->call(MahasiswaDosenDokumenSeeder::class);
        $this->call(MahasiswaSeeder::class);
        $this->call(NotifikasiKirimSeeder::class);
        $this->call(NotifikasiSeeder::class);
        $this->call(PengajuanPembimbingSeeder::class);
        $this->call(RuangFasilitasSeeder::class);
        $this->call(RubrikPenilaianSeeder::class);
        $this->call(SubKategoriSeeder::class);
        $this->call(UserSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
