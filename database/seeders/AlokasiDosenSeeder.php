<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlokasiDosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('alokasi_dosen')->insert([
        //     [
        //         'id_alokasi' => 1, 
        //         'id_pengajuan_pembimbing' => 1, 
        //         'nip' => '197312271999031003', 
        //         'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'belum_fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 2, 
        //         'id_pengajuan_pembimbing' => 1, 
        //         'nip' => '196101141992021001', 'urutan_prioritas_terpilih' => 2, 
        //         'status_alokasi' => 'belum_fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 3, 
        //         'id_pengajuan_pembimbing' => 1, 
        //         'nip' => '196210211993031002', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'penguji'
        //     ],
        //     [
        //         'id_alokasi' => 4, 
        //         'id_pengajuan_pembimbing' => 2, 
        //         'nip' => '196610181995121001', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 5, 
        //         'id_pengajuan_pembimbing' => 2, 
        //         'nip' => '196303161995121001', 'urutan_prioritas_terpilih' => 2, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 6, 
        //         'id_pengajuan_pembimbing' => 3, 
        //         'nip' => '197407182001121002', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'belum_fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 7, 
        //         'id_pengajuan_pembimbing' => 3, 
        //         'nip' => '198705172019031004', 'urutan_prioritas_terpilih' => 2, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 8, 
        //         'id_pengajuan_pembimbing' => 3, 
        //         'nip' => '199312282019031013', 'urutan_prioritas_terpilih' => 3, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 9, 
        //         'id_pengajuan_pembimbing' => 4, 
        //         'nip' => '196009281994031001', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'belum_fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'penguji'
        //     ],
        //     [
        //         'id_alokasi' => 10, 
        //         'id_pengajuan_pembimbing' => 4, 
        //         'nip' => '196303161995121001', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 11, 
        //         'id_pengajuan_pembimbing' => 5, 
        //         'nip' => '198009162009122001', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'belum_fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'penguji'
        //     ],
        //     [
        //         'id_alokasi' => 12, 
        //         'id_pengajuan_pembimbing' => 5, 
        //         'nip' => '198502102015042001', 'urutan_prioritas_terpilih' => 2, 
        //         'status_alokasi' => 'belum_fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'penguji'
        //     ],
        //     [
        //         'id_alokasi' => 13, 
        //         'id_pengajuan_pembimbing' => 5, 
        //         'nip' => '198012122008122001', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 14, 
        //         'id_pengajuan_pembimbing' => 6, 
        //         'nip' => '196210211993031002', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'belum_fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 15, 
        //         'id_pengajuan_pembimbing' => 6, 
        //         'nip' => '198104072006041001', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'penguji'
        //     ],
        //     [
        //         'id_alokasi' => 16, 
        //         'id_pengajuan_pembimbing' => 7, 
        //         'nip' => '196208151990031001', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 17, 
        //         'id_pengajuan_pembimbing' => 7, 
        //         'nip' => '198004192005011002', 'urutan_prioritas_terpilih' => 2, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 18, 
        //         'id_pengajuan_pembimbing' => 8, 
        //         'nip' => '198012122008122001', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 19, 
        //         'id_pengajuan_pembimbing' => 9, 
        //         'nip' => '196101141992021001', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'belum_fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'pembimbing'
        //     ],
        //     [
        //         'id_alokasi' => 20, 
        //         'id_pengajuan_pembimbing' => 2, 
        //         'nip' => '198104072006041001', 'urutan_prioritas_terpilih' => 1, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'penguji'
        //     ],
        //     [
        //         'id_alokasi' => 21, 
        //         'id_pengajuan_pembimbing' => 2, 
        //         'nip' => '196904041998031001', 'urutan_prioritas_terpilih' => 2, 
        //         'status_alokasi' => 'fix', 
        //         'catatan' => null, 
        //         'tipe_alokasi' => 'penguji'
                
        //     ]
        // ]);
    }
}