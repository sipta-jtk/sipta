<?php

namespace Database\Seeders;

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
        NotifikasiKirim::create([
            'id_notifikasi' => 1,
            'username' => '221524049',
            'kanal' => 'email',
            'status' => 'terkirim',
            'waktu_kirim' => Carbon::now(),
            'respon_log' => 'Pesan berhasil dikirim.'
        ]);

        NotifikasiKirim::create([
            'id_notifikasi' => 2,
            'username' => '221524059',
            'kanal' => 'sms',
            'status' => 'gagal',
            'waktu_kirim' => Carbon::now(),
            'respon_log' => 'Nomor tidak valid.'
        ]);
    }
}
