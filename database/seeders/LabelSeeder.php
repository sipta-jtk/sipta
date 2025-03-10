<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Label;

class LabelSeeder extends Seeder
{
    public function run()
    {
        // Matikan foreign key checks agar bisa truncate tabel tanpa error
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('label')->truncate(); // Kosongkan tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            ['nama_label' => 'Label A', 'id_kota' => 1],
            ['nama_label' => 'Label B', 'id_kota' => 2],
            ['nama_label' => 'Label C', 'id_kota' => 3],
            ['nama_label' => 'Label D', 'id_kota' => 4],
        ];

        foreach ($data as $item) {
            Label::create($item);
        }
    }
}