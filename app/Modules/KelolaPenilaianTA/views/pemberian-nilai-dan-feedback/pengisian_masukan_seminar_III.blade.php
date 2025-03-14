@extends('adminlte::page')

@section('title', 'PENILAIAN SEMINAR III')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => url('/kelola-penilaian-ta/nilai-seminar/3'), 'label' => 'Penilaian Seminar III'],
                ['url' => '', 'label' => 'Masukan Seminar III']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">MASUKAN SEMINAR III</h1>
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

        <h3 class="heading-spacing text-center">ISI MASUKAN</h3>

        <!-- Form -->
        <form action="{{ url('/kelola-penilaian-ta') }}"> <!-- route('feedback.store') method="POST" -->
            @csrf

            <!-- Dokumen -->
            <div class="form-group">
                <label for="dokumen">Masukan untuk Dokumen</label>
                <input id="dokumen" type="hidden" name="dokumen">
                <trix-editor input="dokumen"></trix-editor>
            </div>

            <!-- Presentasi -->
            <div class="form-group">
                <label for="presentasi">Masukan untuk Presentasi</label>
                <input id="presentasi" type="hidden" name="presentasi">
                <trix-editor input="presentasi"></trix-editor>
            </div>

            <!-- Penguasaan Topik -->
            <div class="form-group">
                <label for="penguasaan">Masukan untuk Penguasaan Topik</label>
                <input id="penguasaan" type="hidden" name="penguasaan">
                <trix-editor input="penguasaan"></trix-editor>
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
