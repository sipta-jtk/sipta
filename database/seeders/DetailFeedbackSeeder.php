<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\DetailFeedback;

class DetailFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('detail_feedback')->truncate();

        DetailFeedback::create([
            'id_detail_feedback' => 1,
            'id_feedback' => 1,
            'id_kota' => 5,
            'nip' => '199312282019031013',
            'status_penilaian' => 'draf',
            'isi_feedback' => "Struktur laporan sudah sistematis, mencakup pendahuluan, metode, hasil, dan kesimpulan.
        Penjelasan algoritma Content-Based Filtering cukup jelas dan menggunakan referensi yang relevan.
        Hasil evaluasi model sudah ditampilkan dengan metrik seperti Precision dan Recall, Penjelasan dataset perlu lebih rinci, misalnya jumlah data, sumber data, dan preprocessing yang dilakukan.
        Harus ada analisis lebih dalam terhadap kelebihan dan kekurangan metode yang digunakan.

        Penjelasan mengenai proses rekomendasi cukup jelas dan runtut.
        Pemaparan hasil uji coba sistem dilakukan dengan baik dan didukung dengan grafik/visualisasi.

        Durasi presentasi perlu diperhatikan, bagian penjelasan metode agak terlalu panjang dibandingkan hasil analisis."
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
