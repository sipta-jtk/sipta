@extends('adminlte::page')

@section('title', 'PENILAIAN SIDANG D3')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => url('/kelola-penilaian-ta/nilai-sidang/akhir'), 'label' => 'Penilaian Sidang D3'],
                ['url' => '', 'label' => 'Catatan Perbaikan Laporan']
            ]
        ])
        @endcomponent

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

        <!-- Input Tanggal dan Waktu Sebelum Catatan Perbaikan Laporan -->
        <div class="row mt-4">
            <div class="col-md-3">
                <label for="tanggal_catatan"><strong>Tanggal dan Waktu Perbaikan</strong></label>
                <input type="date" id="tanggal_catatan" name="tanggal_catatan" class="form-control date">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <p>
                    Diharuskan untuk mengadakan perbaikan laporan sidang tugas akhir dengan batas akhir perbaikan laporan pada  
                    hari <u id="hari_perbaikan">________</u> tanggal <u id="tanggal_perbaikan">________</u>.  
                    Apabila kelompok tersebut tidak dapat menyelesaikan sesuai dengan waktu yang telah ditentukan tersebut di atas,  
                    maka kelompok tersebut dinyatakan <strong>tidak lulus</strong>, dan nilai hasil sidang dinyatakan <strong>batal</strong>.
                </p>
            </div>
        </div>

        <h3 class="heading-spacing text-center">CATATAN PERBAIKAN LAPORAN</h3>

        <!-- Form -->
        <form action="{{ url('/kelola-penilaian-ta') }}">
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
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/pemberian_nilai_dan_feedback.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@stop

@section('js')
    <!-- Trix Editor Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/pemberian_nilai_dan_feedback.js') }}"></script>
@stop
