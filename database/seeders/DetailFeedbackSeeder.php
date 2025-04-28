<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\DetailFeedback;
use Carbon\Carbon;

class DetailFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('detail_feedback')->truncate();

        // Data untuk id_feedback = 4 (Dokumen)
        DetailFeedback::create([
            'id_detail_feedback' => 1,
            'id_feedback' => 4,
            'id_kota' => 2,
            'nip' => '196610181995121001',
            'status_penilaian_dosen' => 'dipublikasikan',
            'isi_feedback' => "<ol><li><!--block-->Struktur laporan sudah sistematis, mencakup pendahuluan, metode, hasil, dan kesimpulan.</li><li><!--block-->Penjelasan algoritma Content-Based Filtering cukup jelas dan menggunakan referensi yang relevan.</li><li><!--block-->Hasil evaluasi model sudah ditampilkan dengan metrik seperti Precision dan Recall. Penjelasan dataset perlu lebih rinci, misalnya jumlah data, sumber data, dan preprocessing yang dilakukan.</li><li><!--block-->Harus ada analisis lebih dalam terhadap kelebihan dan kekurangan metode yang digunakan.</li></ol>"
        ]);

        // Data untuk id_feedback = 5 (Presentasi)
        DetailFeedback::create([
            'id_detail_feedback' => 2,
            'id_feedback' => 5,
            'id_kota' => 2,
            'nip' => '196610181995121001',
            'status_penilaian_dosen' => 'dipublikasikan',
            'isi_feedback' => "<div><!--block--><strong>Presentasi cukup jelas, namun perlu lebih banyak visualisasi untuk mendukung penjelasan.</strong></div><div><!--block--><strong>Durasi presentasi sudah sesuai, tetapi perlu lebih fokus pada hasil analisis dibandingkan penjelasan teori.</strong></div><div><!--block--><strong>Pemaparan hasil uji coba sistem dilakukan dengan baik dan didukung dengan grafik/visualisasi.</strong></div>"
        ]);

        // Data untuk id_feedback = 6 (Penguasaan Materi)
        DetailFeedback::create([
            'id_detail_feedback' => 3,
            'id_feedback' => 6,
            'id_kota' => 2,
            'nip' => '196610181995121001',
            'status_penilaian_dosen' => 'dipublikasikan',
            'isi_feedback' => "<div><!--block--><em>Penguasaan materi sangat baik, dengan penjelasan yang mendalam dan runtut.</em></div><div><!--block--><em>Mahasiswa mampu menjawab pertanyaan dengan baik dan menunjukkan pemahaman yang kuat terhadap topik yang dibahas.</em></div><div><!--block--><em>Namun, perlu lebih banyak analisis terhadap kelebihan dan kekurangan metode yang digunakan.</em></div>"
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
