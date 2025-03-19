<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\VerifikasiBerkasPengajuan;

class VerifikasiBerkasPengajuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('verifikasi_berkas_pengajuan')->truncate();

        DB::table('verifikasi_berkas_pengajuan')->insert([
            [
                'id_pengajuan' => 1,
                'nip' => '196610181995121001',
                'status_konfirmasi' => 'disetujui',
                'catatan' => 'Berkas lengkap dan sesuai persyaratan.',
                'tanggal_pengajuan' => '2025-01-05 00:00:00',
                'tanggal_verifikasi' => '2025-01-12 00:00:00',
                'jenis_pengajuan' => 'seminar_3',
                'id_kota' => 2,
            ],
            [
                'id_pengajuan' => 2,
                'nip' => '196610181995121001',
                'status_konfirmasi' => 'tidak_disetujui',
                'catatan' => 'Format dokumen tidak sesuai ketentuan.',
                'tanggal_pengajuan' => '2025-01-10 00:00:00',
                'tanggal_verifikasi' => '2025-04-17 00:00:00',
                'jenis_pengajuan' => 'sidang_akhir',
                'id_kota' => 6,
            ],
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
