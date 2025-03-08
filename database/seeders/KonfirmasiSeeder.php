<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Konfirmasi;

class KonfirmasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure table is empty
        // Schema::disableForeignKeyConstraints();
        DB::table('konfirmasi')->truncate();
        // Schema::enableForeignKeyConstraints();

        // Create sample konfirmasi records
        $konfirmasiData = [
            [
            'id_penjadwalan' => 1,
            'nip' => '19731227 199903 1 003', // AD
            'status_konfirmasi' => 'disetujui',
            ],
            [
            'id_penjadwalan' => 2,
            'nip' => '19850210 201504 2 001', // AN
            'status_konfirmasi' => 'tidak_disetujui',
            ]
        ];

        foreach ($konfirmasiData as $data) {
            Konfirmasi::create($data);
        }
    }
}
