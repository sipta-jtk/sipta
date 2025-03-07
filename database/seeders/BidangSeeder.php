<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    var $bidang = [
        "Penerapan dan pengkajian teknik dan best practice dalam pengembangan perangkat lunak (improvement pada seluruh / sebagian tahap SDLC) apapun platform nya",
        "Machine Learning (Information Retrieval, Data Mining, Text Mining, AI, dll)",
        "Pengembangan perangkat lunak / sistem berbasis IOT",
        "Data and Information Management (cakupan ; operasional, transaksional, menengah (tactical), strategic)",
        "Business Intelligent",
        "Knowledge Management",
        "Data Warehouse",
        "Sistem Rekomendasi",
        "Image Processsing",
        "Computer Graphic",
        "Computer Vision",
        "Game and Simulator",
        "Robotics",
        "Sound Processing",
        "Math Modelling",
    ];

    public function run(): void
    {
        foreach ($this->bidang as $item) {
            Bidang::create([
                'bidang' => $item
            ]);
        }

    }
}
