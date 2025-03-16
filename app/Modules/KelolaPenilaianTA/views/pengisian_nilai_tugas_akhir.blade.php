@extends('adminlte::page')

@section('title', 'PENILAIAN PELAKSANAAN TUGAS AKHIR')

@section('content_header')
    <div class="container-fluid p-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-3">
                <li class="breadcrumb-item">
                    <a href="{{ url('/KelolaPenilaianTA') }}">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Penilaian Pelaksanaan Tugas Akhir
                </li>
            </ol>
        </nav>

        <!-- Judul Halaman -->
        <h1 class="mb-0">PENILAIAN PELAKSANAAN TUGAS AKHIR</h1>
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
        <form action="{{ url('/KelolaPenilaianTA') }}"> <!-- route('feedback.store') method="POST" -->
            @csrf

            <div class="row mt-3 p-4 col-md-12">
                <table class="table table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Materi Penilaian</th>
                            <th rowspan="2">Bobot Nilai</th>
                            <th colspan="{{ count($mahasiswa['list_mahasiswa']) }}">Nilai Perorangan</th>
                        </tr>
                        <tr>
                            @foreach($mahasiswa['list_mahasiswa'] as $mhs)
                                <th>{{ $loop->iteration }}</th> <!-- Nomor Mahasiswa -->
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penilaian as $kategori)
                            <!-- Row untuk kategori utama -->
                            <tr class="fw-bold">
                                <td>{{ $kategori['kategori'] }}</td>
                                <td class="text-start bg-light text-white" colspan="{{ 3 + count($mahasiswa['list_mahasiswa']) }}">
                                    {{ $kategori['judul'] }}
                                </td>
                            </tr>

                            @foreach ($kategori['sub_kriteria'] as $sub)
                                <!-- Row untuk sub-kriteria -->
                                <tr>
                                    <td>{{ $sub['kode'] }}</td>
                                    <td class="text-start">{{ $sub['kriteria'] }}</td>
                                    <td>{{ $sub['bobot'] }}</td>
                                    @foreach($mahasiswa['list_mahasiswa'] as $mhs)
                                        <td>
                                            <input type="number" class="form-control" 
                                                name="nilai[{{ $mhs['nim'] }}][{{ $sub['kode'] }}]" 
                                                min="0" max="100">
                                        </td>
                                    @endforeach
                                </tr>

                                <!-- Jika ada deskripsi tambahan, tambahkan row baru -->
                                @if ($sub['deskripsi'])
                                <tr>
                                    <td colspan="{{ 3 + count($mahasiswa['list_mahasiswa']) }}" class="text-start fst-italic">{{ $sub['deskripsi'] }}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tombol Simpan dan Selanjutnya -->
            <div class="row mt-0">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
@stop
