@extends('adminlte::page')

@section('title', 'Penambahan Aspek Penilaian')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => url('/kelola-penilaian-ta/formulir-penilaian'), 'label' => 'Formulir Penilaian'],
                ['url' => '', 'label' => 'Tambah Aspek Penilaian']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Penambahan Aspek Penilaian</h1>
    </div>
@stop

@section('content')
    <div class="p-4">
        <form id="aspekFormulirForm" action="{{ route('formulir-penilaian.simpan') }}" method="POST">
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
                        <select class="form-control" id="namaFTA" name="namaFTA" required>
                            <option value="" disabled selected>Pilih Nama FTA</option>
                            <option value="Seminar I">Seminar I</option>
                            <option value="Seminar II">Seminar II</option>
                            <option value="Seminar III">Seminar III</option>
                            <option value="Sidang Akhir">Sidang Akhir</option>
                            <option value="Dosen Pembimbing">Dosen Pembimbing</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jenisForm">Jenis Formulir</label>
                        <select class="form-control" id="jenisForm" name="jenisForm" required>
                            <option value="" disabled selected>Pilih Jenis Formulir</option>
                            <option value="Penilaian">Penilaian</option>
                            <option value="Feedback">Feedback</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="namaProdi">Nama Prodi</label>
                        <select class="form-control" id="namaProdi" name="namaProdi" required>
                            <option value="" disabled selected>Pilih Nama Prodi</option>
                            @foreach ($prodiList as $prodi)
                                <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                            @endforeach
                        </select>
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
                <table class="table text-center" id="tablePenilaian">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white">
                            <th style="min-width: 200px;">Kriteria Penilaian Penguji</th>
                            <th style="min-width: 100px;">Bobot (%)</th>
                            <th style="min-width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="aspekPenilaianTable">
                        <tr>
                            <td><input type="text" class="form-control" name="nama_kriteria[]" required></td>
                            <td><input type="number" class="form-control" name="bobot_kriteria[]" required></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="table text-center" id="tableFeedback" style="display: none;">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white">
                            <th style="min-width: 200px;">Kriteria Penilaian Penguji</th>
                            <th style="min-width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="aspekFeedbackTable">
                        <tr>
                            <td><input type="text" class="form-control" name="nama_aspek_feedback[]" required></td>
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
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/tambah_aspek_formulir.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/tambah_aspek_formulir.js') }}"></script>
@stop