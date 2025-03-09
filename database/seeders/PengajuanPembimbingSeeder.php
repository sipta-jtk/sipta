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
            'id_kota' => 10,
            'status_pengajuan' => 'diproses',
            'created_at' => Carbon::now()
        ]);

        PengajuanPembimbing::create([
            'id_kota' => 12,
            'status_pengajuan' => 'disetujui',
            'created_at' => Carbon::now()
        ]);
    }
}
