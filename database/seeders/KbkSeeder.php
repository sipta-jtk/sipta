<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Kbk;

class KbkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('kbk')->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            ['kbk' => 'Rekayasa Perangkat Lunak'],
            ['kbk' => 'Database'],
            ['kbk' => 'Sistem Informasi'],
            ['kbk' => 'Multimedia'],
        ];

        foreach ($data as $item) {
            DB::table('kbk')->insert($item);
        }
    }
}
