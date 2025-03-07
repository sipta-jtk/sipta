<?php

namespace Database\Seeders;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PreferensiNotifikasi;

class PreferensiNotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        PreferensiNotifikasi::create
        ([
            'username' => 'admin123',
            'tipe_notifikasi' => 'pengajuan_pembimbing',
            'whatsapp' => 1,
            'in_app' => 1,
            'email' => 1
        ]);

        PreferensiNotifikasi::create
        ([
            'username' => 'mahasiswa001',
            'tipe_notifikasi' => 'pengajuan_pembimbing',
            'whatsapp' => 1,
            'in_app' => 0,
            'email' => 0
        ]);
    }
}
