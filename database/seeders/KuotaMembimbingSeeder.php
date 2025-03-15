<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\KuotaMembimbing;

class KuotaMembimbingSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kuota_membimbing')->truncate(); // Membersihkan tabel sebelum seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'nip' => '197312271999031003',
                'id_prodi' => 1,
                'jumlah' => 2
            ],
            [
                'nip' => '198502102015042001',
                'id_prodi' => 1,
                'jumlah' => 3
            ],
        ];

        foreach ($data as $item) {
            KuotaMembimbing::create($item);
        }
    }
}