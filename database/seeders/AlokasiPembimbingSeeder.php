<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\AlokasiPembimbing;

class AlokasiPembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('alokasi_pembimbing')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('alokasi_pembimbing')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'id_pengajuan_pembimbing' => 2,
                'nip' => '197312271999031003',
                'urutan_prioritas_terpilih' => 1,
            ],
            [
                'id_pengajuan_pembimbing' => 2,
                'nip' => '198502102015042001',
                'urutan_prioritas_terpilih' => 2,
            ],
            [
                'id_pengajuan_pembimbing' => 2,
                'nip' => '197201061999031002',
                'urutan_prioritas_terpilih' => 3,
            ],
            [
                'id_pengajuan_pembimbing' => 1,
                'nip' => '197604182001121004',
                'urutan_prioritas_terpilih' => 1,
            ],
            [
                'id_pengajuan_pembimbing' => 1,
                'nip' => '197201061999031002',
                'urutan_prioritas_terpilih' => 2,
            ],
        ];

        foreach ($data as $item) {
            AlokasiPembimbing::create($item);
        }
    }
}
