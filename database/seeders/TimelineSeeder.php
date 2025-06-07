<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timelines = [
            [
                'nama_kegiatan' => 'Seminar 1',
                'tanggal_mulai' => '2024-02-25',
                'tanggal_selesai' => '2024-03-14',
                'deskripsi' => 'Seminar 1 dilaksanakan pada minggu pertama pada bulan maret dengan evaluator dari koordinator TA',
            ],
            [
                'nama_kegiatan' => 'Seminar 2',
                'tanggal_mulai' => '2024-03-05',
                'tanggal_selesai' => '2024-03-14',
                'deskripsi' => 'Seminar 2 dilaksanakan pada minggu pertama pada bulan april dengan evaluator dari koordinator TA',
            ],
        ];

        DB::table('timeline')->insert($timelines);
    }
}
