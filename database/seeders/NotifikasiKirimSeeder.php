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
            // 'id_notifikasi' => 1,
            'username' => 'admin123',
            'kanal' => 'email',
            'status' => 'terkirim',
            'waktu_kirim' => Carbon::now(),
            'respon_log' => 'Pesan berhasil dikirim.'
        ]);

        NotifikasiKirim::create([
            // 'id_notifikasi' => 2,
            'username' => 'mahasiswa001',
            'kanal' => 'sms',
            'status' => 'gagal',
            'waktu_kirim' => Carbon::now(),
            'respon_log' => 'Nomor tidak valid.'
        ]);
    }
}
