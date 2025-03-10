@extends('adminlte::page')

@section('title', 'Informasi Detail Formulir Penilaian')

@section('content_header')
    <h1>Informasi Detail Formulir Penilaian</h1>
@stop

@section('content')
    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ url('KelolaPenilaianTA/formulir-penilaian') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="kodeFTA">Kode FTA</label>
                    <input type="text" class="form-control" id="kodeFTA" name="kodeFTA" value="FTA.011" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="namaFTA">Nama FTA</label>
                    <input type="text" class="form-control" id="namaFTA" name="namaFTA" value="PENILAIAN SEMINAR III" readonly>
                </div>
            </div>
        </div>

        <h6><strong>Aspek Penilaian</strong></h6>
        <div class="table-container">
            <table class="table text-center">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th style="min-width: 200px;" rowspan="2">Kriteria Penilaian Penguji</th>
                        <th style="min-width: 100px;" rowspan="2">Bobot (%)</th>
                        <th style="min-width: 200px;" rowspan="2">Detail Kriteria</th>
                        <th style="min-width: 200px;" colspan="6">Rentang Penilaian</th>
                    </tr>
                    <tr class="bg-dark text-white">
                        <th style="min-width: 200px;">≥ 80 (A)</th>
                        <th style="min-width: 200px;">75 - 79.99 (AB)</th>
                        <th style="min-width: 200px;">70 - 74.99 (B)</th>
                        <th style="min-width: 200px;">65 - 69.99 (BC)</th>
                        <th style="min-width: 200px;">60 - 64.99 (C)</th>
                        <th style="min-width: 200px;">< 60 (CD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="9" class="bg-light text-left"><strong>1. Dokumen</strong></td>
                    </tr>
                    <tr>
                        <td>Dokumen</td>
                        <td>35</td>
                        <td>Kejelasan kaitan antar bab/ sub kajian (hubungan sebab akibat/ reasoning, rasionalitas)</td>
                        <td>Dokumen yang dibuat memiliki konten yang sangat lengkap dan keterkaitan antar bab/ sub kajian sangat erat, serta dapat dipertanggung jawabkan.</td>
                        <td>Dokumen yang dibuat memiliki konten yang  lengkap dan keterkaitan antar bab/ sub kajian kurang erat, serta dapat dipertanggung jawabkan.</td>
                        <td>Dokumen yang dibuat memiliki konten yang  lengkap dan keterkaitan antar bab/ sub kajian tidak erat, serta dapat dipertanggung jawabkan.</td>
                        <td>Dokumen yang dibuat memiliki konten yang  tidak lengkap dan keterkaitan antar bab/ sub kajian erat, serta dapat dipertanggung jawabkan.</td>
                        <td>Dokumen yang dibuat memiliki konten yang  tidak lengkap dan keterkaitan antar bab/ sub kajian tidak erat, serta kurang dapat dipertanggung jawabkan.</td>
                        <td>Dokumen yang dibuat memiliki konten yang  tidak lengkap dan keterkaitan antar bab/ sub kajian tidak erat, serta tidak dapat dipertanggung jawabkan.</td>
                    </tr>
                    <tr>
                        <td>Dokumen</td>
                        <td>35</td>
                        <td>Kesesuaian dan ketepatan penggunaan metodologi dan modelling tools.</td>
                        <td>Mahasiswa menguasai serta memahami penerapan cara-cara/ metoda pengembangan aplikasi dan modelling tools pada pengembangan aplikasi.</td>
                        <td>Mahasiswa menguasai penerapan cara-cara/ metoda pengembangan aplikasi tetapi tidak menguasai modelling tools pada pengembangan aplikasi.</td>
                        <td>Mahasiswa tidak menguasai penerapan metoda pengembangan aplikasi. tetapi  menguasai penggunaan modelling tools pada pengembangan aplikasi.</td>
                        <td>Mahasiswa menguasai  penerapan cara-cara/metoda pengembangan aplikasi tetapi tidak menguasai modelling tools pada pengembangan aplikasi.</td>
                        <td>Mahasiswa tidak menguasai atau penerapan cara-cara/metoda pengembangan aplikasi dan modelling tools pada pengembangan aplikasi.</td>
                        <td>Tidak ada metodologi dan modelling tools yang digunakan pada pengembangan aplikasi.</td>
                    </tr>
                    <tr>
                        <td>Dokumen</td>
                        <td>35</td>
                        <td>Kesesuaian studi pustaka dan daftar pustaka yang digunakan.</td>
                        <td>Pustaka yang digunakan sangat sesuai dengan penelitian dan kualitas pustaka yang sangat baik.</td>
                        <td>Pustaka yang digunakan sesuai dengan penelitian dan kualitas pustaka yang baik.</td>
                        <td>Pustaka yang digunakan sesuai dengan penelitian tetapi kualitas pustaka hanya cukup baik.</td>
                        <td>Pustaka yang digunakan cukup sesuai dengan penelitian dan kualitas yang cukup baik.</td>
                        <td>Pustaka yang digunakan cukup sesuai dengan penelitian tetapi kualitas pustaka hanya kurang baik.</td>
                        <td>Pustaka yang digunakan tidak sesuai dengan penelitian dan kualitas pustaka hanya kurang baik.</td>
                    </tr>
                    <tr>
                        <td>Dokumen</td>
                        <td>35</td>
                        <td>Tata tulis laporan
                            a) Dokumen dapat dibaca dengan baik.
                            b) Sistematika Penulisan sesuai dengan penulisan TA.
                            c) Kedalaman konten yang disajikan dapat terlihat
                        </td>
                        <td>Dokumen yang dibuat memenuhi kriteria a, b, dan c dengan sangat baik/jelas.</td>
                        <td>Dokumen yang dibuat memenuhi kriteria a, b, dan c dengan baik/jelas.</td>
                        <td>Dokumen yang dibuat memenuhi kriteria a dan c dengan baik/jelas.</td>
                        <td>Dokumen yang dibuat memenuhi kriteria a dan c dengan cukup baik/jelas.</td>
                        <td>Dokumen yang dibuat memenuhi kriteria a dan b dengan baik/jelas.</td>
                        <td>Dokumen yang dibuat memenuhi kriteria b saja dengan baik/jelas</td>
                    </tr>
                    <tr>
                        <td colspan="9" class="bg-light text-left"><strong>2. Presentasi</strong></td>
                    </tr>
                    <tr>
                        <td>Presentasi</td>
                        <td>15</td>
                        <td>Materi presentasi
                            a) Penguasaan domain TA.
                            b) Penguasaan pelaksanaan cara-cara/metoda pengembangan aplikasi.
                            c) Penguasaan pelaksanaan cara-cara /metoda penggunaan tools pengembangan aplikasi.
                        </td>
                        <td>Mahasiswa menguasai serta memahami domain TA yang dikerjakan, penerapan cara-cara/metoda pengembangan aplikasi dan cara/metoda penggunaan tools pengembangan aplikasi (kriteria a, b, c).</td>
                        <td>Mahasiswa menguasai domain TA yang dikerjakan dan penerapan cara-cara/metoda pengembangan aplikasi (kriteria a, b) tetapi tidak menguasai atau memahami cara/metoda penggunaan tools pengembangan aplikasi (kriteria c).</td>
                        <td>Mahasiswa menguasai domain TA yang dikerjakan dan cara/metoda penggunaan tools pengembangan aplikasi (kriteria a, c) tetapi tidak menguasai atau memahami penerapan cara-cara/metoda pengembangan aplikasi (kriteria b).</td>
                        <td>Mahasiswa menguasai  penerapan cara-cara/metoda pengembangan aplikasi dan cara/metoda penggunaan tools pengembangan aplikasi (kriteria b, c) tetapi tidak menguasai atau memahami domain TA yang dikerjakan (kriteria a).</td>
                        <td>Mahasiswa menguasai atau memahami salah satu dari: domain TA yang dikerjakan, penerapan cara-cara/metoda pengembangan aplikasi dan cara/metoda penggunaan tools pengembangan aplikasi (Salah satu dari kriteria a, b, c).</td>
                        <td>Mahasiswa Tidak Menguasai atau memahami seluruh kriteria berikut: domain TA yang dikerjakan, penerapan cara-cara/metoda pengembangan aplikasi dan cara/metoda penggunaan tools pengembangan aplikasi (kriteria a, b, c).</td>
                    </tr>
                    <tr>
                        <td>Presentasi</td>
                        <td>15</td>
                        <td>Kejelasan presentasi dan kemampuan membangkitkan minat pemirsa.</td>
                        <td>Mahasiswa menjelaskan dengan sangat baik dan menyeluruh serta membangkitkan antusiasme pemirsa untuk menyimak dari awal hingga akhir presentasi.</td>
                        <td>Mahasiswa menjelaskan dengan  baik dan menyeluruh serta membangkitkan antusiasme pemirsa untuk menyimak presentasi.</td>
                        <td>Mahasiswa menjelaskan dengan kurang baik dan menyeluruh serta kurang membangkitkan antusiasme pemirsa untuk menyimak presentasi.</td>
                        <td>Mahasiswa menjelaskan dengan kurang baik dan kurang menyeluruh serta kurang membangkitkan antusiasme pemirsa untuk menyimak presentasi.</td>
                        <td>Mahasiswa menjelaskan dengan kurang baik dan kurang menyeluruh serta tidak membangkitkan antusiasme pemirsa untuk menyimak presentasi.</td>
                        <td>Mahasiswa menjelaskan dengan tidak baik dan tidak menyeluruh serta tidak membangkitkan antusiasme pemirsa untuk menyimak presentasi.</td>
                    </tr>
                    <tr>
                        <td>Presentasi</td>
                        <td>15</td>
                        <td>Kebebasan dari catatan</td>
                        <td>Mahasiswa menjelaskan berdasarkan poin - poin penting pada bahan presentasi.</td>
                        <td>Mahasiswa menjelaskan berdasarkan poin - poin penting dengan uraiannya  pada bahan presentasi.</td>
                        <td>Mahasiswa menjelaskan dengan membaca poin - poin penting dengan uraiannya  pada bahan presentasi.</td>
                        <td>Mahasiswa hanya membaca poin - poin penting dengan uraiannya  pada bahan presentasi.</td>
                        <td>Mahasiswa tidak menjelaskan poin - poin penting dan uraiannya pada bahan presentasi.</td>
                        <td>Tidak ada poin penting pada uraian pembahasan pada bahan presentasi.</td>
                    </tr>
                    <tr>
                        <td colspan="9" class="bg-light text-left"><strong>3. Tanya Jawab</strong></td>
                    </tr>
                    <tr>
                        <td>Tanya Jawab</td>
                        <td>35</td>
                        <td>Tanya Jawab (Penguasaan materi terkait tugas yang dikerjakan).</td>
                        <td>Mahasiswa dapat menjawab dengan sangat baik beserta reasoning dan rasionalitas yang tinggi.</td>
                        <td>Mahasiswa dapat menjawab dengan baik beserta reasoning dan rasionalitas yang cukup.</td>
                        <td>Mahasiswa dapat menjawab dengan baik beserta reasoning dan rasionalitas yang kurang.</td>
                        <td>Mahasiswa dapat menjawab dengan kurang baik beserta reasoning dan rasionalitas yang kurang.</td>
                        <td>Mahasiswa dapat menjawab dengan kurang baik beserta tidak ada reasoning dan rasionalitas.</td>
                        <td>Mahasiswa tidak dapat menjawab.</td>
                    </tr>
                    <tr>
                        <td colspan="9" class="bg-light text-left"><strong>4. Prototipe yang dihasilkan</strong></td>
                    </tr>
                    <tr>
                        <td>Prototipe yang dihasilkan</td>
                        <td>15</td>
                        <td>Prototipe yang dihasilkan</td>
                        <td>Produk yang dihasilkan sesuai dengan target Seminar III, memenuhi spesifikasi, dan rancangan yang sesuai spesifikasi (sufficient).</td>
                        <td>Produk yang dihasilkan sesuai dengan target Seminar III, memenuhi spesifikasi, dan rancangan yang kurang sesuai spesifikasi (less sufficient).</td>
                        <td>Produk yang dihasilkan kurang dari target Seminar III, tidak memenuhi spesifikasi, dan rancangan yang tidak sesuai spesifikasi (not sufficient).</td>
                        <td>Produk yang dihasilkan kurang dari target Seminar III, tidak memenuhi spesifikasi, dan rancangan yang tidak sesuai spesifikasi (not sufficient).</td>
                        <td>Produk yang dihasilkan kurang dari target Seminar III, tidak memenuhi spesifikasi, dan tidak ada rancangan.</td>
                        <td>Produk yang dihasilkan tidak memenuhi target Seminar III dan tidak memenuhi spesifikasi.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tanggalTenggat">Tanggal Tenggat Pengisian</label>
                    <input type="text" class="form-control" id="tanggalTenggat" name="tanggalTenggat" value="05/03/2025" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="waktuTenggat">Waktu Tenggat</label>
                    <input type="text" class="form-control" id="waktuTenggat" name="waktuTenggat" value="23:59" readonly>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .table-container {
            max-height: 500px;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #ddd;
            position: relative;
            scrollbar-width: thin;
            -ms-overflow-style: auto;
        }
        .table-container::-webkit-scrollbar {
            height: 12px;
        }
        .table-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc !important;
            padding: 10px;
            text-align: center;
        }
        thead {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(0, 0, 0, 0.9);
            color: white;
        }
        thead th {
            position: sticky;
            top: 0;
            z-index: 1001;
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            text-align: center;
            padding: 12px;
            border-bottom: 2px solid #fff;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tbody tr:hover {
            background-color: #e2e6ea;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@stop