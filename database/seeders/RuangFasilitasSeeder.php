<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuangFasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('ruang_fasilitas')) {
            return;
        }

        DB::table('ruang_fasilitas')->truncate();

        $data = [
            ['id_ruangan' => 1, 'id_fasilitas' => 1, 'jumlah_fasilitas' => 10],
            ['id_ruangan' => 1, 'id_fasilitas' => 2, 'jumlah_fasilitas' => 5],
            ['id_ruangan' => 2, 'id_fasilitas' => 1, 'jumlah_fasilitas' => 8],
            ['id_ruangan' => 2, 'id_fasilitas' => 3, 'jumlah_fasilitas' => 12],
        ];

        foreach ($data as $item) {
            RuangFasilitas::create($item);
        }
    }
}
