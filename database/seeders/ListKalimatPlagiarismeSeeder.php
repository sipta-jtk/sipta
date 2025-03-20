<?php

namespace Database\Seeders; 

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ListKalimatPlagiarisme;

class ListKalimatPlagiarismeSeeder extends Seeder
{
    public function run()
    {
        // Matikan sementara foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('list_kalimat_plagiarisme')->truncate(); // Kosongkan tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data dummy untuk seed
        ListKalimatPlagiarisme::create([
            'id_dokumen' => 1,
            'id_jurnal' => 1,
            'kalimat_plagiat' => 'Internet of Things adalah suatu konsep yang terkait dengan obyek tertentu yang memilikikemampuan untuk mentransfer data lewat jaringan tanpa memerlukan adanya interaksi dari manusia ke manusia ataupun dari manusia ke perangkat komputer'
        ]);

        ListKalimatPlagiarisme::create([
            'id_dokumen' => 1,
            'id_jurnal' => 1,
            'kalimat_plagiat' => 'Seiring dengan perkembangan teknologi, sudah banyak sistem otomatis yang sangat efektif untuk digunakan dan memiliki dampak yang positif. '
        ]);

        ListKalimatPlagiarisme::create([
            'id_dokumen' => 2,
            'id_jurnal' => 2,
            'kalimat_plagiat' => 'Flexible Manufacturing System (FMS) merupakan sebuah sistem manufaktur yang terdiri dari mesin perkakas otomatis yang terintegrasi dengan sistem penanganan material aktivitasnya dikendalikan oleh sistem kontrol komputer.'
        ]);

        ListKalimatPlagiarisme::create([
            'id_dokumen' => 2,
            'id_jurnal' => 2,
            'kalimat_plagiat' => 'Sistem tersebut menggunakan metode computer visiondengan Convolutional  Neural  Network (CNN).'
        ]);

        ListKalimatPlagiarisme::create([
            'id_dokumen' => 3,
            'id_jurnal' => 3,
            'kalimat_plagiat' => 'Blockchain adalah teknologi informasi terkini, dan saat ini sudah mulai banyak diterapkan dalam kebutuhan sehari-hari di berbagai  bidang  terapan.'
        ]);

        ListKalimatPlagiarisme::create([
            'id_dokumen' => 3,
            'id_jurnal' => 3,
            'kalimat_plagiat' => 'Blockchain adalah record (basis data) yang terus berkembang, disebut block, yang terhubung dan diamankan menggunakan teknik kriptografi. '
        ]);

        ListKalimatPlagiarisme::create([
            'id_dokumen' => 4,
            'id_jurnal' => 4,
            'kalimat_plagiat' => 'Teknologi Informasi adalah suatu bentuk teknologi yang membantu pekerjaan suatu organisasi dalam memproses,menyimpan,dan mendistribusikan informasii–iinformasi organisasi.'
        ]);

        ListKalimatPlagiarisme::create([
            'id_dokumen' => 4,
            'id_jurnal' => 4,
            'kalimat_plagiat' => 'Investasi teknologi informasi adalah suatu keputusan organisasi dalam melakukan investasi yang berkaitandengan teknologi informasi.'
        ]);

        ListKalimatPlagiarisme::create([
            'id_dokumen' => 5,
            'id_jurnal' => 5,
            'kalimat_plagiat' => 'Basis data atau database adalah kumpulan data yang disusun dalam bentuk  tabel yang saling berhubunganatau berdiri sendiri yang diatur menurut skema atau struktur tertentu dan dapat dimanipulasi oleh perangkat lunak untuk tujuan tertentu.'
        ]);

        ListKalimatPlagiarisme::create([
            'id_dokumen' => 5,
            'id_jurnal' => 5,
            'kalimat_plagiat' => 'Bahasa pemrograman  adalah  bahasa  yang  digunakan  untuk  membuat suatu program komputer. Bahasa pemrograman yang  digunakan  untuk  membuat  aplikasi  ini  adalah Java, produkopen source dari Sun Microsystems.'
        ]);
    }
}