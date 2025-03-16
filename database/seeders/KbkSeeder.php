<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Kbk;

class KbkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('kbk')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::disableForeignKeyConstraints();
        DB::table('kbk')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Schema::enableForeignKeyConstraints();

        $data = [
            ['kbk' => 'Rekayasa Perangkat Lunak'],
            ['kbk' => 'Database'],
            ['kbk' => 'Sistem Informasi'],
            ['kbk' => 'Multimedia'],
        ];

        foreach ($data as $item) {
            Kbk::create($item);
        }
    }
}
