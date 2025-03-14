<?php

namespace Database\Seeders;

use App\Models\KategoriArtefak;
use App\Models\Timeline;
use Illuminate\Database\Seeder;

class TimelineArtefakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timelines = Timeline::all();
        $artefaks = KategoriArtefak::all();

        $data = [
            [
                'id_timeline' => $timelines->get(0)->id_timeline,
                'id_kategori_artefak' => $artefaks->get(0)->id_kategori_artefak,
            ],
            [
                'id_timeline' => $timelines->get(0)->id_timeline,
                'id_kategori_artefak' => $artefaks->get(1)->id_kategori_artefak,
            ],
            [
                'id_timeline' => $timelines->get(0)->id_timeline,
                'id_kategori_artefak' => $artefaks->get(2)->id_kategori_artefak,
            ],
            [
                'id_timeline' => $timelines->get(0)->id_timeline,
                'id_kategori_artefak' => $artefaks->get(3)->id_kategori_artefak,
            ],
            [
                'id_timeline' => $timelines->get(0)->id_timeline,
                'id_kategori_artefak' => $artefaks->get(4)->id_kategori_artefak,
            ],
            [
                'id_timeline' => $timelines->get(0)->id_timeline,
                'id_kategori_artefak' => $artefaks->get(23)->id_kategori_artefak,
            ],
            [
                'id_timeline' => $timelines->get(1)->id_timeline,
                'id_kategori_artefak' => $artefaks->get(5)->id_kategori_artefak,
            ],
            [
                'id_timeline' => $timelines->get(1)->id_timeline,
                'id_kategori_artefak' => $artefaks->get(6)->id_kategori_artefak,
            ],
            [
                'id_timeline' => $timelines->get(1)->id_timeline,
                'id_kategori_artefak' => $artefaks->get(7)->id_kategori_artefak,
            ],
            [
                'id_timeline' => $timelines->get(1)->id_timeline,
                'id_kategori_artefak' => $artefaks->get(8)->id_kategori_artefak,
            ],
            [
                'id_timeline' => $timelines->get(1)->id_timeline,
                'id_kategori_artefak' => $artefaks->get(25)->id_kategori_artefak,
            ],
        ];

        foreach ($data as $entry) {
            \App\Models\TimelineArtefak::create($entry);
        }
    }
}
