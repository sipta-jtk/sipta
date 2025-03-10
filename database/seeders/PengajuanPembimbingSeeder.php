<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PengajuanPembimbing;
use Carbon\Carbon;

class PengajuanPembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PengajuanPembimbing::create([
            'id_kota' => 1,
            'status_pengajuan' => 'pending',
            'created_at' => Carbon::now()
        ]);

        PengajuanPembimbing::create([
            'id_kota' => 2,
            'status_pengajuan' => 'diterima',
            'created_at' => Carbon::now()
        ]);
    }
}
