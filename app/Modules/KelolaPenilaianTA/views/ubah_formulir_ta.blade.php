@extends('adminlte::page')

@section('title', 'Ubah Formulir Penilaian')

@section('content_header')
    <h1>Ubah Formulir Penilaian</h1>
@stop

@section('content')
    <div class="p-4">
        <form action="{{ route('formulir-penilaian.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kodeFTA">Kode FTA</label>
                        <input type="text" class="form-control" id="kodeFTA" name="kodeFTA" value="FTA.011" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="namaFTA">Nama FTA</label>
                        <input type="text" class="form-control" id="namaFTA" name="namaFTA" value="PENILAIAN SEMINAR III" required>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-1">
                <h6><strong>Aspek Penilaian</strong></h6>
                <button type="button" class="btn btn-dark btn-sm" id="addRow"><i class="fa-solid fa-plus"></i></button>
            </div>
            <div class="table-container mb-3">
                <table class="table text-center">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white">
                            <th style="min-width: 200px;" rowspan="2">Kriteria Penilaian Penguji</th>
                            <th style="min-width: 100px;" rowspan="2">Bobot (%)</th>
                            <th style="min-width: 200px;" rowspan="2">Detail Kriteria</th>
                            <th style="min-width: 200px;" colspan="6">Rentang Penilaian</th>
                            <th style="min-width: 100px;" rowspan="2">Aksi</th>
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
                    <tbody id="aspekPenilaianTable">
                        <tr>
                            <td colspan="9" class="bg-light text-left"><strong>1. Dokumen</strong></td>
                        </tr>
                        <tr>
                            <td>Dokumen</td>
                            <td><input type="number" class="form-control" name="bobot[]" value="35" required></td>
                            <td><input type="text" class="form-control" name="detail[]" value="Kejelasan kaitan antar bab/sub bab/kaitan (hubungan sebab akibat/ reasoning, rasionalitas)" required></td>
                            <td><input type="text" class="form-control" name="lebih80[]" value="Dokumen yang dibuat memiliki konten yang sangat lengkap dan keterkaitan antar bab/ sub kajian sangat erat, serta dapat dipertanggung jawabkan." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluhLima[]" value="Dokumen yang dibuat memiliki konten yang  lengkap dan keterkaitan antar bab/ sub kajian kurang erat, serta dapat dipertanggung jawabkan." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluh[]" value="Dokumen yang dibuat memiliki konten yang  lengkap dan keterkaitan antar bab/ sub kajian tidak erat, serta dapat dipertanggung jawabkan." required></td>
                            <td><input type="text" class="form-control" name="enamPuluhLima[]" value="Dokumen yang dibuat memiliki konten yang  tidak lengkap dan keterkaitan antar bab/ sub kajian erat, serta dapat dipertanggung jawabkan." required></td>
                            <td><input type="text" class="form-control" name="enamPuluh[]" value="Dokumen yang dibuat memiliki konten yang  tidak lengkap dan keterkaitan antar bab/ sub kajian tidak erat, serta kurang dapat dipertanggung jawabkan." required></td>
                            <td><input type="text" class="form-control" name="kurang60[]" value="Dokumen yang dibuat memiliki konten yang  tidak lengkap dan keterkaitan antar bab/ sub kajian tidak erat, serta tidak dapat dipertanggung jawabkan." required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                        </tr>
                        <tr>
                            <td>Dokumen</td>
                            <td><input type="number" class="form-control" name="bobot[]" value="35" required></td>
                            <td><input type="text" class="form-control" name="detail[]" value="Kesesuaian dan ketepatan penggunaan metodologi dan modelling tools." required></td>
                            <td><input type="text" class="form-control" name="lebih80[]" value="Mahasiswa menguasai serta memahami penerapan cara-cara/ metoda pengembangan aplikasi dan modelling tools pada pengembangan aplikasi." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluhLima[]" value="Mahasiswa menguasai penerapan cara-cara/ metoda pengembangan aplikasi tetapi tidak menguasai modelling tools pada pengembangan aplikasi." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluh[]" value="Mahasiswa tidak menguasai penerapan metoda pengembangan aplikasi. tetapi  menguasai penggunaan modelling tools pada pengembangan aplikasi." required></td>
                            <td><input type="text" class="form-control" name="enamPuluhLima[]" value="Mahasiswa menguasai  penerapan cara-cara/metoda pengembangan aplikasi tetapi tidak menguasai modelling tools pada pengembangan aplikasi." required></td>
                            <td><input type="text" class="form-control" name="enamPuluh[]" value="Mahasiswa tidak menguasai atau penerapan cara-cara/metoda pengembangan aplikasi dan modelling tools pada pengembangan aplikasi." required></td>
                            <td><input type="text" class="form-control" name="kurang60[]" value="Tidak ada metodologi dan modelling tools yang digunakan pada pengembangan aplikasi." required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                        </tr>
                        <tr>
                            <td>Dokumen</td>
                            <td><input type="number" class="form-control" name="bobot[]" value="35" required></td>
                            <td><input type="text" class="form-control" name="detail[]" value="Kesesuaian studi pustaka dan daftar pustaka yang digunakan." required></td>
                            <td><input type="text" class="form-control" name="lebih80[]" value="Pustaka yang digunakan sangat sesuai dengan penelitian dan kualitas pustaka yang sangat baik." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluhLima[]" value="Pustaka yang digunakan sesuai dengan penelitian dan kualitas pustaka yang baik." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluh[]" value="Pustaka yang digunakan sesuai dengan penelitian tetapi kualitas pustaka hanya cukup baik." required></td>
                            <td><input type="text" class="form-control" name="enamPuluhLima[]" value="Pustaka yang digunakan cukup sesuai dengan penelitian dan kualitas yang cukup baik." required></td>
                            <td><input type="text" class="form-control" name="enamPuluh[]" value="Pustaka yang digunakan cukup sesuai dengan penelitian tetapi kualitas pustaka hanya kurang baik." required></td>
                            <td><input type="text" class="form-control" name="kurang60[]" value="Pustaka yang digunakan tidak sesuai dengan penelitian dan kualitas pustaka hanya kurang baik." required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                        </tr>
                        <tr>
                            <td>Dokumen</td>
                            <td><input type="number" class="form-control" name="bobot[]" value="35" required></td>
                            <td><input type="text" class="form-control" name="detail[]" value="Tata tulis laporan a) Dokumen dapat dibaca dengan baik. b) Sistematika Penulisan sesuai dengan penulisan TA. c) Kedalaman konten yang disajikan dapat terlihat" required></td>
                            <td><input type="text" class="form-control" name="lebih80[]" value="Dokumen yang dibuat memenuhi kriteria a, b, dan c dengan sangat baik/jelas." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluhLima[]" value="Dokumen yang dibuat memenuhi kriteria a, b, dan c dengan baik/jelas." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluh[]" value="Dokumen yang dibuat memenuhi kriteria a dan c dengan baik/jelas." required></td>
                            <td><input type="text" class="form-control" name="enamPuluhLima[]" value="Dokumen yang dibuat memenuhi kriteria a dan c dengan cukup baik/jelas." required></td>
                            <td><input type="text" class="form-control" name="enamPuluh[]" value="Dokumen yang dibuat memenuhi kriteria a dan b dengan baik/jelas." required></td>
                            <td><input type="text" class="form-control" name="kurang60[]" value="Dokumen yang dibuat memenuhi kriteria b saja dengan baik/jelas" required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                        </tr>
                        <tr>
                            <td colspan="9" class="bg-light text-left"><strong>2. Presentasi</strong></td>
                        </tr>
                        <tr>
                            <td>Presentasi</td>
                            <td><input type="number" class="form-control" name="bobot[]" value="15" required></td>
                            <td><input type="text" class="form-control" name="detail[]" value="Materi presentasi a) Penguasaan domain TA. b) Penguasaan pelaksanaan cara-cara/metoda pengembangan aplikasi. c) Penguasaan pelaksanaan cara-cara /metoda penggunaan tools pengembangan aplikasi." required></td>
                            <td><input type="text" class="form-control" name="lebih80[]" value="Mahasiswa menguasai serta memahami domain TA yang dikerjakan, penerapan cara-cara/metoda pengembangan aplikasi dan cara/metoda penggunaan tools pengembangan aplikasi (kriteria a, b, c)." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluhLima[]" value="Mahasiswa menguasai domain TA yang dikerjakan dan penerapan cara-cara/metoda pengembangan aplikasi (kriteria a, b) tetapi tidak menguasai atau memahami cara/metoda penggunaan tools pengembangan aplikasi (kriteria c)." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluh[]" value="Mahasiswa menguasai domain TA yang dikerjakan dan cara/metoda penggunaan tools pengembangan aplikasi (kriteria a, c) tetapi tidak menguasai atau memahami penerapan cara-cara/metoda pengembangan aplikasi (kriteria b)." required></td>
                            <td><input type="text" class="form-control" name="enamPuluhLima[]" value="Mahasiswa menguasai  penerapan cara-cara/metoda pengembangan aplikasi dan cara/metoda penggunaan tools pengembangan aplikasi (kriteria b, c) tetapi tidak menguasai atau memahami domain TA yang dikerjakan (kriteria a)." required></td>
                            <td><input type="text" class="form-control" name="enamPuluh[]" value="Mahasiswa menguasai atau memahami salah satu dari: domain TA yang dikerjakan, penerapan cara-cara/metoda pengembangan aplikasi dan cara/metoda penggunaan tools pengembangan aplikasi (Salah satu dari kriteria a, b, c)." required></td>
                            <td><input type="text" class="form-control" name="kurang60[]" value="Mahasiswa Tidak Menguasai atau memahami seluruh kriteria berikut: domain TA yang dikerjakan, penerapan cara-cara/metoda pengembangan aplikasi dan cara/metoda penggunaan tools pengembangan aplikasi (kriteria a, b, c)." required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                        </tr>
                        <tr>
                            <td>Presentasi</td>
                            <td><input type="number" class="form-control" name="bobot[]" value="15" required></td>
                            <td><input type="text" class="form-control" name="detail[]" value="Kejelasan presentasi dan kemampuan membangkitkan minat pemirsa." required></td>
                            <td><input type="text" class="form-control" name="lebih80[]" value="Mahasiswa menjelaskan dengan sangat baik dan menyeluruh serta membangkitkan antusiasme pemirsa untuk menyimak dari awal hingga akhir presentasi." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluhLima[]" value="Mahasiswa menjelaskan dengan  baik dan menyeluruh serta membangkitkan antusiasme pemirsa untuk menyimak presentasi." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluh[]" value="Mahasiswa menjelaskan dengan kurang baik dan menyeluruh serta kurang membangkitkan antusiasme pemirsa untuk menyimak presentasi." required></td>
                            <td><input type="text" class="form-control" name="enamPuluhLima[]" value="Mahasiswa menjelaskan dengan kurang baik dan kurang menyeluruh serta kurang membangkitkan antusiasme pemirsa untuk menyimak presentasi." required></td>
                            <td><input type="text" class="form-control" name="enamPuluh[]" value="Mahasiswa menjelaskan dengan kurang baik dan kurang menyeluruh serta tidak membangkitkan antusiasme pemirsa untuk menyimak presentasi." required></td>
                            <td><input type="text" class="form-control" name="kurang60[]" value="Mahasiswa menjelaskan dengan tidak baik dan tidak menyeluruh serta tidak membangkitkan antusiasme pemirsa untuk menyimak presentasi." required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                        </tr>
                        <tr>
                            <td>Presentasi</td>
                            <td><input type="number" class="form-control" name="bobot[]" value="15" required></td>
                            <td><input type="text" class="form-control" name="detail[]" value="Kebebasan dari catatan" required></td>
                            <td><input type="text" class="form-control" name="lebih80[]" value="Mahasiswa menjelaskan berdasarkan poin - poin penting pada bahan presentasi." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluhLima[]" value="Mahasiswa menjelaskan berdasarkan poin - poin penting dengan uraiannya  pada bahan presentasi." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluh[]" value="Mahasiswa menjelaskan dengan membaca poin - poin penting dengan uraiannya  pada bahan presentasi." required></td>
                            <td><input type="text" class="form-control" name="enamPuluhLima[]" value="Mahasiswa hanya membaca poin - poin penting dengan uraiannya  pada bahan presentasi." required></td>
                            <td><input type="text" class="form-control" name="enamPuluh[]" value="Mahasiswa tidak menjelaskan poin - poin penting dan uraiannya pada bahan presentasi." required></td>
                            <td><input type="text" class="form-control" name="kurang60[]" value="Tidak ada poin penting pada uraian pembahasan pada bahan presentasi." required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                        </tr>
                        <tr>
                            <td colspan="9" class="bg-light text-left"><strong>3. Tanya Jawab</strong></td>
                        </tr>
                        <tr>
                            <td>Tanya Jawab</td>
                            <td><input type="number" class="form-control" name="bobot[]" value="35" required></td>
                            <td><input type="text" class="form-control" name="detail[]" value="Tanya Jawab (Penguasaan materi terkait tugas yang dikerjakan)." required></td>
                            <td><input type="text" class="form-control" name="lebih80[]" value="Mahasiswa dapat menjawab dengan sangat baik beserta reasoning dan rasionalitas yang tinggi." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluhLima[]" value="Mahasiswa dapat menjawab dengan baik beserta reasoning dan rasionalitas yang cukup." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluh[]" value="Mahasiswa dapat menjawab dengan baik beserta reasoning dan rasionalitas yang kurang." required></td>
                            <td><input type="text" class="form-control" name="enamPuluhLima[]" value="Mahasiswa dapat menjawab dengan kurang baik beserta reasoning dan rasionalitas yang kurang." required></td>
                            <td><input type="text" class="form-control" name="enamPuluh[]" value="Mahasiswa dapat menjawab dengan kurang baik beserta tidak ada reasoning dan rasionalitas." required></td>
                            <td><input type="text" class="form-control" name="kurang60[]" value="Mahasiswa tidak dapat menjawab." required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                        </tr>
                        <tr>
                            <td colspan="9" class="bg-light text-left"><strong>4. Prototipe yang dihasilkan</strong></td>
                        </tr>
                        <tr>
                            <td>Prototipe yang dihasilkan</td>
                            <td><input type="number" class="form-control" name="bobot[]" value="15" required></td>
                            <td><input type="text" class="form-control" name="detail[]" value="Prototipe yang dihasilkan" required></td>
                            <td><input type="text" class="form-control" name="lebih80[]" value="Produk yang dihasilkan sesuai dengan target Seminar III, memenuhi spesifikasi, dan rancangan yang sesuai spesifikasi (sufficient)." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluhLima[]" value="Produk yang dihasilkan sesuai dengan target Seminar III, memenuhi spesifikasi, dan rancangan yang kurang sesuai spesifikasi (less sufficient)." required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluh[]" value="Produk yang dihasilkan kurang dari target Seminar III, tidak memenuhi spesifikasi, dan rancangan yang tidak sesuai spesifikasi (not sufficient)." required></td>
                            <td><input type="text" class="form-control" name="enamPuluhLima[]" value="Produk yang dihasilkan kurang dari target Seminar III, tidak memenuhi spesifikasi, dan rancangan yang tidak sesuai spesifikasi (not sufficient)." required></td>
                            <td><input type="text" class="form-control" name="enamPuluh[]" value="Produk yang dihasilkan kurang dari target Seminar III, tidak memenuhi spesifikasi, dan tidak ada rancangan." required></td>
                            <td><input type="text" class="form-control" name="kurang60[]" value="Produk yang dihasilkan tidak memenuhi target Seminar III dan tidak memenuhi spesifikasi." required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggalTenggat">Tanggal Tenggat Pengisian</label>
                        <input type="date" class="form-control" id="tanggalTenggat" name="tanggalTenggat" value="2025-03-05" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="waktuTenggat">Waktu Tenggat</label>
                        <input type="time" class="form-control" id="waktuTenggat" name="waktuTenggat" value="23:59" required>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary mr-2" onclick="window.location='{{ url('KelolaPenilaianTA/formulir-penilaian') }}'">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
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
    <script>
        $(document).ready(function () {
            $('#addRow').on('click', function () {
                var newRow = `<tr>
                    <td><input type="text" class="form-control" name="kriteria[]" required></td>
                    <td><input type="number" class="form-control" name="bobot[]" required></td>
                    <td><input type="text" class="form-control" name="detail[]" required></td>
                    <td><input type="text" class="form-control" name="lebih80[]" required></td>
                    <td><input type="text" class="form-control" name="tujuhPuluhLima[]" required></td>
                    <td><input type="text" class="form-control" name="tujuhPuluh[]" required></td>
                    <td><input type="text" class="form-control" name="enamPuluhLima[]" required></td>
                    <td><input type="text" class="form-control" name="enamPuluh[]" required></td>
                    <td><input type="text" class="form-control" name="kurang60[]" required></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                </tr>`;
                $('#aspekPenilaianTable').append(newRow);
            });

            $(document).on('click', '.remove-row', function () {
                $(this).closest('tr').remove();
            });
        });
    </script>
@stop