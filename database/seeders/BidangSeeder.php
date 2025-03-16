<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Bidang;

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
        "Computer Vision"
    ];

    public function run(): void
    {
        if (!Schema::hasTable('ambang_batas')) 
        {
            return;
        }
        
        foreach ($this->bidang as $item) 
        {
            Bidang::create([
                'bidang' => $item
            ]);
        }
    }
}
