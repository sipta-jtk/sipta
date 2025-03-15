@extends('adminlte::page')

@section('title', 'Ubah Aspek Penilaian')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => url('/kelola-penilaian-ta/formulir-penilaian'), 'label' => 'Formulir Penilaian'],
                ['url' => '', 'label' => 'Ubah Aspek Penilaian']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Ubah Aspek Penilaian</h1>
    </div>
@stop

@section('content')
    <div class="p-4">
        <form action="{{ url('/kelola-penilaian-ta/formulir-penilaian/update-aspek-penilaian/' . $aspek->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Kode FTA -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kodeFTA">Kode FTA</label>
                        <input type="text" class="form-control" id="kodeFTA" name="kodeFTA" value="{{ $aspek->kodeFTA }}" required>
                    </div>
                </div>

                <!-- Nama FTA -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="namaFTA">Nama FTA</label>
                        <input type="text" class="form-control" id="namaFTA" name="namaFTA" value="{{ $aspek->namaFTA }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jenisForm">Jenis Formulir</label>
                        <select class="form-control" id="jenisForm" name="jenisForm" required>
                            <option value="" disabled>Pilih Jenis Formulir</option>
                            <option value="Penilaian" {{ $aspek->jenisForm == 'Penilaian' ? 'selected' : '' }}>Penilaian</option>
                            <option value="Feedback" {{ $aspek->jenisForm == 'Feedback' ? 'selected' : '' }}>Feedback</option>
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
                <table class="table text-center" id="tablePenilaian" style="{{ $aspek->jenisForm == 'Penilaian' ? '' : 'display: none;' }}">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white">
                            <th style="min-width: 200px;">Kriteria Penilaian Penguji</th>
                            <th style="min-width: 100px;">Bobot (%)</th>
                            <th style="min-width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="aspekPenilaianTable">
                        @foreach($aspek->penilaian as $penilaian)
                        <tr>
                            <td><input type="text" class="form-control" name="kriteria[]" value="{{ $penilaian->kriteria }}" required></td>
                            <td><input type="number" class="form-control" name="bobot[]" value="{{ $penilaian->bobot }}" required></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="table text-center" id="tableFeedback" style="{{ $aspek->jenisForm == 'Feedback' ? '' : 'display: none;' }}">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white">
                            <th style="min-width: 200px;">Kriteria Penilaian Penguji</th>
                            <th style="min-width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="aspekFeedbackTable">
                        @foreach($aspek->feedback as $feedback)
                        <tr>
                            <td><input type="text" class="form-control" name="kriteria[]" value="{{ $feedback->kriteria }}" required></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <!-- Tanggal Tenggat -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggalTenggat">Tanggal Tenggat Pengisian</label>
                        <input type="date" class="form-control" id="tanggalTenggat" name="tanggalTenggat" value="{{ $aspek->tanggalTenggat }}" required>
                    </div>
                </div>

                <!-- Waktu Tenggat -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="waktuTenggat">Waktu Tenggat</label>
                        <input type="time" class="form-control" id="waktuTenggat" name="waktuTenggat" value="{{ $aspek->waktuTenggat }}" required>
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