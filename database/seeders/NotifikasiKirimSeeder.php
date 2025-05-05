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
            'username' => '198009162009122001',
            'kanal' => 'email',
            'status' => 'terkirim',
            'waktu_kirim' => Carbon::now(),
            'respon_log' => 'gagal terkirim karena terjadi pembatasan pengiriman pesan'
        ]);

        NotifikasiKirim::create([
            'id_notifikasi' => 2,
            'username' => '198604122014041001',
            'kanal' => 'whatsapp',
            'status' => 'gagal terkirim',
            'waktu_kirim' => Carbon::now(),
            'respon_log' => 'gagal terkirim karena terjadi pembatasan pengiriman pesan'
        ]);

        NotifikasiKirim::create([
            'id_notifikasi' => 3,
            'username' => '198502102015042001',
            'kanal' => 'email',
            'status' => 'gagal terkirim',
            'waktu_kirim' => Carbon::now(),
            'respon_log' => 'gagal terkirim karena terjadi pembatasan pengiriman pesan'
        ]);
    }
}