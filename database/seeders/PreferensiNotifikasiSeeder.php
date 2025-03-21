<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PreferensiNotifikasi;

class PreferensiNotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('preferensi_notifikasi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        PreferensiNotifikasi::create
        ([
            'username' => '197312271999031003',
            'whatsapp' => 1,
            'reminder_h5' => 1,
            'email' => 1
        ]);

        PreferensiNotifikasi::create
        ([
            'username' => '196810141993032002',
            'whatsapp' => 1,
            'reminder_h5' => 1,
            'email' => 0
        ]);

        PreferensiNotifikasi::create
        ([
            'username' => '197201061999031002',
            'whatsapp' => 0,
            'reminder_h5' => 1,
            'email' => 1
        ]);
    }
}
