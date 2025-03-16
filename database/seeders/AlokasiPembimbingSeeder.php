<?php

namespace Database\Seeders;

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
                'id_pengajuan_pembimbing' => 1,
                'nip' => '197312271999031003',
                'urutan_prioritas_terpilih' => 1,
                'status_alokasi' => 'fix', // Sesuai ENUM dalam SQL
                'catatan' => 'Pembimbing utama',
            ],
            [
                'id_pengajuan_pembimbing' => 2,
                'nip' => '198502102015042001',
                'urutan_prioritas_terpilih' => 2,
                'status_alokasi' => 'belum_fix',
                'catatan' => 'Menunggu konfirmasi',
            ],
            [
                'id_pengajuan_pembimbing' => 2,
                'nip' => '197201061999031002',
                'urutan_prioritas_terpilih' => 3,
                'status_alokasi' => 'fix',
                'catatan' => 'Pembimbing alternatif',
            ],
            [
                'id_pengajuan_pembimbing' => 1,
                'nip' => '197604182001121004',
                'urutan_prioritas_terpilih' => 1,
                'status_alokasi' => 'fix',
                'catatan' => 'Dosen pembimbing utama',
            ],
            [
                'id_pengajuan_pembimbing' => 1,
                'nip' => '197201061999031002',
                'urutan_prioritas_terpilih' => 2,
                'status_alokasi' => 'belum_fix',
                'catatan' => 'Pembimbing kedua',
            ],
        ];

        foreach ($data as $item) {
            AlokasiPembimbing::create($item);
        }
    }
}
