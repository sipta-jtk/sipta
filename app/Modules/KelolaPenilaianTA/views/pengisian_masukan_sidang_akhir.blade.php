@extends('adminlte::page')

@section('title', 'PENILAIAN SIDANG D3')

@section('content_header')
    <div class="container-fluid p-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-3">
                <li class="breadcrumb-item">
                    <a href="{{ url('/KelolaPenilaianTA') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/KelolaPenilaianTA/nilai-sidang-akhir') }}">
                        Penilaian Sidang D3
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Catatan Perbaikan Laporan
                </li>
            </ol>
        </nav>

        <!-- Judul Halaman -->
        <h1 class="mb-0">CATATAN PERBAIKAN LAPORAN</h1>
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

        <h3 class="heading-spacing text-center">CATATAN PERBAIKAN LAPORAN</h3>

        <!-- Form -->
        <form action="{{ url('/KelolaPenilaianTA') }}"> <!-- route('feedback.store') method="POST" -->
            @csrf

            <!-- Dokumen -->
            <div class="form-group">
                <input id="dokumen" type="hidden" name="dokumen">
                <trix-editor input="dokumen"></trix-editor>
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
            margin-top: 50px;
            margin-bottom: 30px;
            font-weight: bold;
        }
    </style>
@stop

@section('js')
    <!-- Trix Editor Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>
@stop
