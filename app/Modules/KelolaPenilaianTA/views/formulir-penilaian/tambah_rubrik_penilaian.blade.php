@extends('adminlte::page')

@section('title', 'Penambahan Rubrik Penilaian')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => url('/kelola-penilaian-ta/formulir-penilaian'), 'label' => 'Formulir Penilaian'],
                ['url' => '', 'label' => 'Tambah Rubrik Penilaian']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Penambahan Rubrik Penilaian</h1>
    </div>
@stop

@section('content')
    <div class="p-4">
        <form action="{{ url('/kelola-penilaian-ta/formulir-penilaian/tambah-rubrik-penilaian') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Kode FTA -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kode_fta">Kode FTA</label>
                        <select class="form-control" id="kode_fta" name="kode_fta" required>
                            <option value="" disabled selected>Pilih Kode FTA</option>
                            @foreach ($formPenilaianList as $formPenilaian)
                                <option value="{{ $formPenilaian->kode_fta }}">{{ $formPenilaian->kode_fta }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Nama FTA -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_fta">Nama FTA</label>
                        <input type="text" class="form-control" id="nama_fta" name="nama_fta" readonly>
                    </div>
                </div>
            </div>

            <!-- Aspek Penilaian -->
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h6><strong>Rubrik Penilaian</strong></h6>
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
                            <th style="min-width: 200px;">Detail Kriteria</th>
                            <th style="min-width: 200px;">≥ 80 (A)</th>
                            <th style="min-width: 200px;">75 - 79.99 (AB)</th>
                            <th style="min-width: 200px;">70 - 74.99 (B)</th>
                            <th style="min-width: 200px;">65 - 69.99 (BC)</th>
                            <th style="min-width: 200px;">60 - 64.99 (C)</th>
                            <th style="min-width: 200px;">< 60 (CD)</th>
                            <th style="min-width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="rubrikPenilaianTable">
                        <tr>
                            <td>
                                <select class="form-control kriteria" name="kriteria[]" required>
                                    <option value="" disabled selected>Pilih Kriteria</option>
                                    @foreach ($kriteriaList as $kriteria)
                                        <option value="{{ $kriteria->nama_kriteria }}">{{ $kriteria->nama_kriteria }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><p class="form-control-plaintext bobot">35</p></td>
                            <td><input type="text" class="form-control" name="detail[]" required></td>
                            <td><input type="text" class="form-control" name="lebih80[]" required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluhLima[]" required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluh[]" required></td>
                            <td><input type="text" class="form-control" name="enamPuluhLima[]" required></td>
                            <td><input type="text" class="form-control" name="enamPuluh[]" required></td>
                            <td><input type="text" class="form-control" name="kurang60[]" required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                        </tr>
                    </tbody>
                </table>
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
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/tambah_rubrik_penilaian.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/tambah_rubrik_penilaian.js') }}"></script>
    <script> const formPenilaianList = @json($formPenilaianList);</script>
    <script> const kriteriaList = @json($kriteriaList);</script>
@stop