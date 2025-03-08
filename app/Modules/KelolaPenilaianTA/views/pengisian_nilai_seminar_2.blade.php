@extends('adminlte::page')

@section('title', 'PENILAIAN SEMINAR 2')

@section('content_header')
    <div class="container-fluid p-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-3">
                <li class="breadcrumb-item">
                    <a href="{{ url('/KelolaPenilaianTA') }}">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Penilaian Seminar 2
                </li>
            </ol>
        </nav>

        <!-- Judul Halaman -->
        <h1 class="mb-0">PENILAIAN SEMINAR 2</h1>
    </div>
@stop

@section('content')
    <div class="card p-4">
        <div class="row">
            <!-- Kode FTA -->
            <div class="col-md-12">
                <strong>Kode FTA</strong> <br>
                <span>{{ $mahasiswa['kode_fta'] }}</span>
            </div>

            <!-- Tanggal, Waktu, ID KoTA -->
            <div class="col-md-2 mt-3">
                <strong>Pada hari/tanggal</strong> <br>
                <span>{{ $mahasiswa['tanggal'] }}</span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>Waktu</strong> <br>
                <span>{{ $mahasiswa['waktu'] }}</span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>ID KoTA</strong> <br>
                <span>{{ $mahasiswa['id_kota'] }}</span>
            </div>
        </div>

        <!-- Data Mahasiswa dalam Tabel -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mahasiswa['list_mahasiswa'] as $key => $mhs)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $mhs['nim'] }}</td>
                                    <td>{{ $mhs['nama'] }}</td>
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
                <span>{{ $mahasiswa['topik_ta'] }}</span>
            </div>
        </div>

        <!-- Form Penilaian -->
        <form action="{{ url('/KelolaPenilaianTA/nilai-seminar-2/masukan-seminar-2') }}" > <!-- method="POST" -->
            @csrf

            <div class="row mt-3 p-4 col-md-12">
                <table class="table table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Kriteria Penilaian Penguji</th>
                            <th rowspan="2">Detail Kriteria</th>
                            <th colspan="6">Rentang Nilai</th>
                            <th rowspan="2">Bobot Nilai</th>
                            <th rowspan="2">Rentang Nilai</th>
                            <th colspan="{{ count($mahasiswa['list_mahasiswa']) }}">Nilai Perorangan</th>
                        </tr>
                        <tr>
                        <th>≥ 80 (A)</th>
                            <th>75 - 79.99 (AB)</th>
                            <th>70 - 74.99 (B)</th>
                            <th>65 - 69.99 (BC)</th>
                            <th>60 - 64.99 (C)</th>
                            <th>< 60 (CD)</th>
                            @foreach($mahasiswa['list_mahasiswa'] as $key => $mhs)
                                <th>{{ $key + 1 }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penilaian as $p)
                            <tr>
                                <td>{{ $p['no'] }}</td>
                                <td>{{ $p['kriteria'] }}</td>
                                <td>
                                    <ul class="text-left">
                                        @foreach ($p['detail_kriteria'] as $detail)
                                            <li>{{ $detail }}</li>
                                        @endforeach
                                    </ul>
                                </td>

                                <!-- Rentang Nilai per Kategori -->
                                <td>
                                    @if(isset($p['nilai']['≥ 80 (A)']))
                                        <ul class="text-left">
                                            @foreach ($p['nilai']['≥ 80 (A)'] as $nilai)
                                                <li>{{ $nilai }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($p['nilai']['75 - 79.99 (AB)']))
                                        <ul class="text-left">
                                            @foreach ($p['nilai']['75 - 79.99 (AB)'] as $nilai)
                                                <li>{{ $nilai }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($p['nilai']['70 - 74.99 (B)']))
                                        <ul class="text-left">
                                            @foreach ($p['nilai']['70 - 74.99 (B)'] as $nilai)
                                                <li>{{ $nilai }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($p['nilai']['65 - 69.99 (BC)']))
                                        <ul class="text-left">
                                            @foreach ($p['nilai']['65 - 69.99 (BC)'] as $nilai)
                                                <li>{{ $nilai }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($p['nilai']['60 - 64.99 (C)']))
                                        <ul class="text-left">
                                            @foreach ($p['nilai']['60 - 64.99 (C)'] as $nilai)
                                                <li>{{ $nilai }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($p['nilai']['< 60 (CD)']))
                                        <ul class="text-left">
                                            @foreach ($p['nilai']['< 60 (CD)'] as $nilai)
                                                <li>{{ $nilai }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>

                                <td>{{ $p['bobot'] }}</td>
                                <td>{{ $p['rentang_nilai'] }}</td>

                                <!-- Input Nilai Perorangan -->
                                @foreach($mahasiswa['list_mahasiswa'] as $mhs)
                                    <td>
                                        <input type="number" class="form-control" name="nilai[{{ $mhs['nim'] }}][{{ $p['no'] }}]" min="0" max="100">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tombol Simpan dan Selanjutnya -->
            <div class="row mt-0">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Selanjutnya</button>
                </div>
            </div>
        </form>
    </div>
@stop
