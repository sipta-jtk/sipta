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
        $this->call(KategoriPenilaianSeeder::class);
        $this->call(KbkSeeder::class);
        $this->call(KehadiranSeeder::class);
        $this->call(KetertarikanBidangSeeder::class);
        $this->call(KonfirmasiSeeder::class);
        $this->call(MahasiswaDosenDokumenSeeder::class);
        $this->call(MahasiswaSeeder::class);
        $this->call(NotifikasiKirimSeeder::class);
        $this->call(NotifikasiSeeder::class);
        $this->call(PengajuanPembimbingSeeder::class);
        $this->call(RuangFasilitasSeeder::class);
        $this->call(RubrikPenilaianSeeder::class);
        $this->call(SubKategoriSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProdiSeeder::class);
        $this->call(PreferensiNotifikasiSeeder::class);
        $this->call(PrioritasPembimbingSeeder::class);
        $this->call(RuanganSeeder::class);
        $this->call(DosenSeeder::class);
        $this->call(AlokasiPembimbingSeeder::class);
        $this->call(AmbangBatasSeeder::class);
        $this->call(BidangSeeder::class);
        $this->call(DokumenSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
