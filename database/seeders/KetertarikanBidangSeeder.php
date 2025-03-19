<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\KetertarikanBidang;

class KetertarikanBidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('ketertarikan_bidang')->truncate();

        $ketertarikanBidang = [
            ['id_ketertarikan_bidang' => 1, 'nip' => '197312271999031003', 'id_bidang' => 13],
            ['id_ketertarikan_bidang' => 2, 'nip' => '196101141992021001', 'id_bidang' => 12],
            ['id_ketertarikan_bidang' => 3, 'nip' => '198009162009122001', 'id_bidang' => 5],
            ['id_ketertarikan_bidang' => 4, 'nip' => '198502102015042001', 'id_bidang' => 4],
            ['id_ketertarikan_bidang' => 5, 'nip' => '196610181995121001', 'id_bidang' => 2],
            ['id_ketertarikan_bidang' => 6, 'nip' => '196312131992012001', 'id_bidang' => 6],
            ['id_ketertarikan_bidang' => 7, 'nip' => '197109031999032001', 'id_bidang' => 9],
            ['id_ketertarikan_bidang' => 8, 'nip' => '196303161995121001', 'id_bidang' => 4],
            ['id_ketertarikan_bidang' => 9, 'nip' => '196904041998031001', 'id_bidang' => 1],
            ['id_ketertarikan_bidang' => 10, 'nip' => '196111091993032001', 'id_bidang' => 12],
            ['id_ketertarikan_bidang' => 11, 'nip' => '199312282019031013', 'id_bidang' => 13],
        ];

        DB::table('ketertarikan_bidang')->insert($ketertarikanBidang);

        Schema::enableForeignKeyConstraints();
    }
}
