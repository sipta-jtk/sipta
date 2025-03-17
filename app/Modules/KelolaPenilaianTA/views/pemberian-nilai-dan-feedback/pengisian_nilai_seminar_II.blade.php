@extends('adminlte::page')

@section('title', 'PENILAIAN SEMINAR II')

@section('content_header')
    <div class="container-fluid p-3">
        <!-- Breadcrumb -->
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => '', 'label' => 'Penilaian Seminar II']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">PENILAIAN SEMINAR II</h1>
    </div>
@stop

@section('content')
    <div class="card p-4">
        <div class="row">
            <!-- Kode FTA -->
            <div class="col-md-12">
                <strong>Kode FTA</strong> <br>
                <span>{{ $data['nama_fta'] }}</span>
            </div>

            <!-- Tanggal, Waktu, ID KoTA -->
            <div class="col-md-2 mt-3">
                <strong>Pada hari/tanggal</strong> <br>
                <span>{{ $data['tanggal'] }}</span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>Waktu</strong> <br>
                <span>{{ $data['start'] }}</span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>ID KoTA</strong> <br>
                <span>{{ $data['kota'] }}</span>
            </div>
        </div>

        <!-- Data Mahasiswa dalam Tabel -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mahasiswaList as $key => $mhs)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $mhs->nim }}</td>
                                    <td>{{ $mhs->user->nama }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Topik Tugas Akhir -->
        <div class="row mt-4">
            <div class="col-md-12">
                <strong>Topik Tugas Akhir</strong> <br>
                <span>{{ $data['judul_ta'] }}</span>
            </div>
        </div>

        <!-- Form Penilaian -->
        <form action="{{ url('/kelola-penilaian-ta/nilai-seminar/2/masukan') }}" > <!-- method="POST" -->
            @csrf

            <div class="row mt-4">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Kriteria Penilaian Penguji</th>
                                <th>Bobot Nilai</th>
                                <th>Rentang Nilai</th>
                                <th style="min-width: 100px;" colspan="{{ count($mahasiswaList) }}">Nilai Perorangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    Kejelasan isi dokumen : 
                                    <ul>
                                        <li>kejelasan kaitan antar bab/sub kajian (hubungan sebab akibat/reasoning, rasionalitas),</li>
                                        <li>kesesuaian dan ketepatan penggunaan metodologi dan modelling tools,</li>
                                        <li>kejelasan dan kesesuaian studi pustaka beserta daftar pustakanya,</li>
                                        <li>tata tulis laporan.</li>
                                    </ul>
                                </td>
                                <td>40 %</td>
                                <td>0 - 100</td>
                                @foreach($mahasiswaList as $key => $mhs)
                                    <td><input type="number" class="form-control" name="nilai[]" min="0" max="100"></td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    Presentasi : 
                                    <ul>
                                        <li>materi presentasi,</li>
                                        <li>kejelasan presentasi & kemampuan membangkitkan minat pemirsa,</li>
                                        <li>penggunaan alat bantu,</li>
                                        <li>ketepatan waktu,</li>
                                        <li>kebebasan dari catatan.</li>
                                    </ul>
                                </td>
                                <td>20 %</td>
                                <td>0 - 100</td>
                                @foreach($mahasiswaList as $key => $mhs)
                                    <td><input type="number" class="form-control" name="nilai[]" min="0" max="100"></td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Tanya Jawab (penguasaan materi terkait tugas yang dikerjakan)</td>
                                <td>40 %</td>
                                <td>0 - 100</td>
                                @foreach($mahasiswaList as $key => $mhs)
                                    <td><input type="number" class="form-control" name="nilai[]" min="0" max="100"></td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="row mt-0">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/pemberian_nilai_dan_feedback.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
@stop