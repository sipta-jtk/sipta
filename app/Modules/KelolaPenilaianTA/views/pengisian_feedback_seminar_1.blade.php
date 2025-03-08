@extends('adminlte::page')

@section('title', 'PENILAIAN SEMINAR 1')

@section('content_header')
    <div class="container-fluid p-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0 mb-3">
                <li class="breadcrumb-item">
                    <a href="{{ url('/KelolaPenilaianTA') }}">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Penilaian Seminar 1
                </li>
            </ol>
        </nav>

        <!-- Judul Halaman -->
        <h1 class="mb-0">SEMINAR 1</h1>
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

            <!-- Tanggal, Waktu, ID Kota -->
            <div class="col-md-2 mt-3">
                <strong>Pada Hari/Tanggal</strong> <br>
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

        <!-- Usulan Topik Tugas Akhir -->
        <div class="row mt-4">
            <div class="col-md-12">
                <strong>Usulan Topik Tugas Akhir</strong> <br>
                <span>{{ $mahasiswa['topik_ta'] }}</span>
            </div>
        </div>

        <h3 class="heading-spacing text-center">EVALUASI</h3>

        <!-- Form -->
        <form action="{{ route('feedback.store') }}"> <!-- method="POST" -->
            @csrf

            <!-- Deskripsi Topik -->
            <div class="form-group">
                <label for="deskripsi_topik">Deskripsi Topik</label>
                <input id="deskripsi_topik" type="hidden" name="deskripsi_topik">
                <trix-editor input="deskripsi_topik"></trix-editor>
            </div>

            <!-- Problem Definition -->
            <div class="form-group">
                <label for="problem_definition">Problem Definition</label>
                <input id="problem_definition" type="hidden" name="problem_definition">
                <trix-editor input="problem_definition"></trix-editor>
            </div>

            <!-- Metodologi Penyelesaian TA -->
            <div class="form-group">
                <label for="metodologi">Metodologi Penyelesaian TA</label>
                <input id="metodologi" type="hidden" name="metodologi">
                <trix-editor input="metodologi"></trix-editor>
            </div>

            <!-- Tombol Simpan -->
            <div class="text-right mt-3">
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <!-- Trix Editor Styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
    <style>
        /* Styling untuk Trix Editor */
        trix-editor {
            min-height: 150px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 12px;
            font-size: 14px;
        }
        .trix-button-group {
            background: white;
        }

        .heading-spacing {
            margin-top: 30px;
            margin-bottom: 30px;
        }
    </style>
@stop

@section('js')
    <!-- Trix Editor Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>
@stop
