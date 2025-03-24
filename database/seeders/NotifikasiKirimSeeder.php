<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NotifikasiKirim;
use Carbon\Carbon;

class NotifikasiKirimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('notifikasi_kirim')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        NotifikasiKirim::create([
            'id_notifikasi' => 1,
            'username' => '196610181995121001',
            'kanal' => 'email',
            'status' => 'terkirim',
            'waktu_kirim' => Carbon::now(),
            'respon_log' => 'gagal terkirim karena terjadi pembatasan pengiriman pesan'
        ]);

        NotifikasiKirim::create([
            'id_notifikasi' => 2,
            'username' => '198706302019031011',
            'kanal' => 'whatsapp',
            'status' => 'gagal terkirim',
            'waktu_kirim' => Carbon::now(),
            'respon_log' => 'gagal terkirim karena terjadi pembatasan pengiriman pesan'
        ]);

        NotifikasiKirim::create([
            'id_notifikasi' => 3,
            'username' => '197109031999032001',
            'kanal' => 'email',
            'status' => 'gagal terkirim',
            'waktu_kirim' => Carbon::now(),
            'respon_log' => 'gagal terkirim karena terjadi pembatasan pengiriman pesan'
        ]);
    }
}
