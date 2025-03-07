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
        $this->call(PengajuanPisahKotaSeeder::class);
        $this->call(PenilaianKategoriSeeder::class);
        $this->call(PenilaianRubrikSeeder::class);
        $this->call(PenjadwalanSeeder::class);
        $this->call(PeriodePengajuanSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
