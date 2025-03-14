<?php

namespace Database\Seeders;

use App\Models\KotaUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KotaUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KotaUser::create([
            'id_kota' => 1,
            'username' => '221524059',
        ]);

        KotaUser::create([
            'id_kota' => 2,
            'username' => '221524049',
        ]);
    }
}
