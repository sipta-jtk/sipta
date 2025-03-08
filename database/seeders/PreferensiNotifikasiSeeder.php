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
            'username' => '221524049',
            'tipe_notifikasi' => 'pengajuan_pembimbing',
            'whatsapp' => 1,
            'in_app' => 1,
            'email' => 1
        ]);

        PreferensiNotifikasi::create
        ([
            'username' => '221524059',
            'tipe_notifikasi' => 'pengajuan_pembimbing',
            'whatsapp' => 1,
            'in_app' => 0,
            'email' => 0
        ]);
    }
}
