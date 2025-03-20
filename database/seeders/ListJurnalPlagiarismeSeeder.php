<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ListJurnalPlagiarisme;

class ListJurnalPlagiarismeSeeder extends Seeder
{
    public function run()
    {
        // Matikan sementara foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('list_jurnal_plagiarisme')->truncate(); // Kosongkan tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data dummy untuk seed
        ListJurnalPlagiarisme::create([
            'link_jurnal' => 'https://openlibrarypublications.telkomuniversity.ac.id/index.php/engineering/article/view/12376/0',
            'judul' => 'Pengembangan Sistem Deteksi Occupancy Menggunakan Computer Vision Untuk Smart Building Dan Automation',
            'persentase_kemunculan' => 4.00
        ]);

        ListJurnalPlagiarisme::create([
            'link_jurnal' => 'https://ojs.uajy.ac.id/index.php/SENASTI/article/view/8013',
            'judul' => 'Perancangan Model Konveyor Pemilah Produk Defect Berbasis Computer Vision untuk Flexible Manufacturing System   ',
            'persentase_kemunculan' => 3.00
        ]);

        ListJurnalPlagiarisme::create([
            'link_jurnal' => 'https://journal.umtas.ac.id/index.php/produktif/article/view/386',
            'judul' => 'Penerapan Teknologi Blockchain Dalam Lingkungan Pendidikan',
            'persentase_kemunculan' => 7.00
        ]);

        ListJurnalPlagiarisme::create([
            'link_jurnal' => 'https://jurnal.polban.ac.id/ialj/article/view/3516',
            'judul' => 'Audit Tata Kelola IT dan Process Investasi Digital Library Menggunakan Pendekatan Framework Cobit 4.1',
            'persentase_kemunculan' => 3.50
        ]);
        
        ListJurnalPlagiarisme::create([
            'link_jurnal' => 'https://jurnal.polban.ac.id/jaief/article/view/3871',
            'judul' => 'Perancangan Aplikasi Sistem Informasi Keuangan Berbasis Web: Studi Kasus di Lembaga Keuangan Mikro Syariah Al-Falah POLBAN',
            'persentase_kemunculan' => 5.00
        ]);
    }
}
