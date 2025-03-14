@extends('adminlte::page')

@section('title', 'Penambahan Aspek Formulir Penilaian')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => url('/kelola-penilaian-ta/formulir-penilaian'), 'label' => 'Formulir Penilaian'],
                ['url' => '', 'label' => 'Tambah Formulir Penilaian']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Penambahan Aspek Formulir Penilaian</h1>
    </div>
@stop

@section('content')
    <div class="p-4">
        <form action="{{ url('/kelola-penilaian-ta/formulir-penilaian/tambah-aspek-formulir') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Kode FTA -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kodeFTA">Kode FTA</label>
                        <input type="text" class="form-control" id="kodeFTA" name="kodeFTA" required>
                    </div>
                </div>

                <!-- Nama FTA -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="namaFTA">Nama FTA</label>
                        <input type="text" class="form-control" id="namaFTA" name="namaFTA" required>
                    </div>
                </div>
            </div>

            <!-- Aspek Penilaian -->
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h6><strong>Aspek Penilaian</strong></h6>
                <button type="button" class="btn btn-dark btn-sm" id="addRow">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>

            <div class="table-container mb-3">
                <table class="table text-center">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white">
                            <th style="min-width: 200px;">Kriteria Penilaian Penguji</th>
                            <th style="min-width: 100px;">Bobot (%)</th>
                            <th style="min-width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="aspekFormulirTable">
                        <tr>
                            <td><input type="text" class="form-control" name="kriteria[]" required></td>
                            <td><input type="number" class="form-control" name="bobot[]" required></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <!-- Tanggal Tenggat -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggalTenggat">Tanggal Tenggat Pengisian</label>
                        <input type="date" class="form-control" id="tanggalTenggat" name="tanggalTenggat" required>
                    </div>
                </div>

                <!-- Waktu Tenggat -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="waktuTenggat">Waktu Tenggat</label>
                        <input type="time" class="form-control" id="waktuTenggat" name="waktuTenggat" required>
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/tambah_aspek_formulir_penilaian.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/tambah_aspek_formulir_penilaian.js') }}"></script>
@stop