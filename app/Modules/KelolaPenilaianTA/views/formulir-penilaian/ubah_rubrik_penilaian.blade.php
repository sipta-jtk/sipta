@extends('adminlte::page')

@section('title', 'Pengubahan Rubrik Penilaian')

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
        <h1 class="mb-0">Pengubahan Rubrik Penilaian</h1>
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
                        <label for="nama_fta">Kode FTA</label>
                        <input type="text" class="form-control" id="kode_fta" name="kode_fta"
                        value="{{ $data->kode_fta ?? '' }}" readonly>
                    </div>
                </div>

                <!-- Nama FTA -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_fta">Nama FTA</label>
                        <input type="text" class="form-control" id="nama_fta" name="nama_fta" 
                        value="{{ $data->nama_fta ?? '' }}"readonly>
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
                            @foreach ($rentangNilai as $nilai)
                                <th style="min-width: 200px;">≥ {{ $nilai->batas_bawah }} - {{ $nilai->batas_atas }} ({{ $nilai->id_nilai }})</th>
                            @endforeach
                            <th style="min-width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="rubrikPenilaianTable">
                        @foreach ($rubrikList as $kriteria)
                            @foreach ($kriteria->rubrik as $rubrik)
                                <tr>
                                    <td>
                                        <select class="form-control id_kriteria" name="nama_kriteria[]" required>
                                            <option value="{{ $kriteria->id_kriteria }}" selected>
                                                {{ $kriteria->nama_kriteria }}
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <p class="form-control-plaintext bobot">{{ $kriteria->bobot_kriteria }}%</p>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="detail[]" value="{{ $rubrik->nama_rubrik }}" required>
                                    </td>
                                    @foreach ($rentangNilai as $nilai)
                                        @php
                                            $deskripsi = $rubrik->detail->firstWhere('id_nilai', $nilai->id_nilai);
                                        @endphp
                                        <td>
                                            <input type="text" class="form-control" name="nilai_{{ $nilai->id_nilai }}[]" 
                                                value="{{ $deskripsi->detail_rubrik_penilaian ?? '-' }}" required>
                                        </td>
                                    @endforeach
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
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

    <script>
        $(document).ready(function () {
            $(document).on('change', '.id_kriteria', function () {
                var selectedOption = $(this).find(':selected'); // Ambil opsi yang dipilih
                var bobot = selectedOption.data('bobot'); // Ambil nilai dari atribut data-bobot
                $(this).closest('tr').find('.bobot').text(bobot ? bobot + "%" : "-"); // Tampilkan bobot di kolom
                console.log("Data Bobot:", bobot); // ✅ Console log di sini
            });
        });
    </script>
@stop
