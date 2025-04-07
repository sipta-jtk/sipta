@extends('adminlte::page')

@section('title', 'PENILAIAN SEMINAR 1')

@section('content_header')
    <div class="container-fluid p-3">
        <!-- Breadcrumb -->
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => '', 'label' => 'Penilaian Seminar 1']
            ]
        ])
        @endcomponent

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
                <span>{{ $kotaInfo->nama_kota }}</span>
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

        <!-- Usulan Topik Tugas Akhir -->
        <div class="row mt-4">
            <div class="col-md-12">
                <strong>Topik Tugas Akhir</strong> <br>
                <span>{{ $kotaInfo->judul_ta }}</span>
            </div>
        </div>

        <h3 class="heading-spacing text-center">EVALUASI</h3>

        <!-- Form -->
        <form action="{{ url('/kelola-penilaian-ta') }}"> <!-- route('feedback.store') method="POST" -->
            @csrf

            <!-- Deskripsi Topik -->
            <div class="form-group">
                <label for="deskripsi_topik">Evaluasi untuk Deskripsi Topik</label>
                <input id="deskripsi_topik" type="hidden" name="deskripsi_topik">
                <trix-editor input="deskripsi_topik"></trix-editor>
            </div>

            <!-- Problem Definition -->
            <div class="form-group">
                <label for="problem_definition">Evaluasi untuk Problem Definition</label>
                <input id="problem_definition" type="hidden" name="problem_definition">
                <trix-editor input="problem_definition"></trix-editor>
            </div>

            <!-- Metodologi Penyelesaian TA -->
            <div class="form-group">
                <label for="metodologi">Evaluasi untuk Metodologi Penyelesaian TA</label>
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
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/pemberian_nilai_dan_feedback.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@stop

@section('js')
    <!-- Trix Editor Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>
@stop
