<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fasilitas;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fasilitas::create([
            'nama_fasilitas' => 'Proyektor',
            'jumlah_total_fasilitas' => 2
        ]);

        Fasilitas::create([
            'nama_fasilitas' => 'Terminal',
            'jumlah_total_fasilitas' => 6
        ]);
    }
}
