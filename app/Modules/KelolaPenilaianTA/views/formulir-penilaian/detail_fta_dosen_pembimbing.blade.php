@extends('adminlte::page')

@section('title', 'Informasi Detail Formulir Penilaian - Dosen')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => route('formulir-penilaian.index'), 'label' => 'Formulir Penilaian'],
                ['url' => '', 'label' => 'Detail Formulir Penilaian - Dosen Pembimbing']   
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Informasi Detail Formulir Penilaian - Dosen Pembimbing</h1>
    </div>
@stop

@section('content')
<div class="card p-4">
    <div class="p-4">
        <!-- Informasi Kategori -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="kodeFTA">Kode FTA</label>
                    <input type="text" class="form-control" id="kodeFTA" name="kodeFTA" 
                           value="{{ $kategori->kode_fta ?? '' }}" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="namaFTA">Nama FTA</label>
                    <input type="text" class="form-control" id="namaFTA" name="namaFTA" 
                           value="{{ $kategori->nama_fta ?? '' }}" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="namaProdi">Program Studi</label>
                    <input type="text" class="form-control" id="namaProdi" name="namaProdi" 
                        value="{{ $kategori->nama_prodi ?? '' }}" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="jenisFormulir">Jenis Formulir</label>
                    <input type="text" class="form-control" id="jenisFormulir" name="jenisFormulir" 
                        value="{{ $kategori->jenis_form ?? '' }}" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="jenisTA">Jenis TA</label>
                    <input type="text" class="form-control" id="jenisTA" name="jenisTA" 
                        value="{{ $kategori->jenis_ta ?? '' }}" readonly>
                </div>
            </div>
        </div>

        <!-- Tabel Aspek Penilaian -->
        <h6><strong>Aspek Penilaian</strong></h6>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table text-center">
                        <thead class="sticky-header">
                            <tr class="bg-dark text-white">
                                <th style="min-width: 200px;">Kriteria Penilaian</th>
                                <th style="min-width: 100px;">Bobot (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($aspekPenilaian as $data_aspek => $aspekList)
                                <!-- Nama Kategori -->
                                <tr class="bg-light">
                                    <td colspan="2" class="text-left">
                                        <strong>{{ $data_aspek }}</strong>
                                    </td>
                                </tr>

                                <!-- Aspek Penilaian -->
                                @foreach ($aspekList as $aspek)
                                    <tr class="bg-white">
                                        <td class="text-left">{{ $aspek['nama'] }}</td>
                                        <td class="text-center">{{ $aspek['bobot'] }}%</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Informasi Tenggat -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tanggalTenggat">Tanggal Tenggat Pengisian</label>
                    <input type="text" class="form-control" id="tanggalTenggat" name="tanggalTenggat" 
                    value="{{ $kategori->tanggal_tenggat_pengisian ?? '' }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="waktuTenggat">Waktu Tenggat</label>
                    <input type="text" class="form-control" id="waktuTenggat" name="waktuTenggat" 
                    value="{{ $kategori->waktu_tenggat_pengisian ?? '' }}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/formulir_penilaian.css') }}">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/detail_fta.js') }}"></script>
@stop