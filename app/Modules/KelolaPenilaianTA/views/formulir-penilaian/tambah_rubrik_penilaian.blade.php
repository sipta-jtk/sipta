@extends('adminlte::page')

@section('title', 'Penambahan Rubrik Penilaian')

@section('content_header')
<div class="container-fluid p-3">
    <!-- Judul Halaman -->
    <h1 class="mb-0">Penambahan Rubrik Penilaian</h1>

    @component('KelolaPenilaianTA.views.components.breadcrumb', [
        'links' => [
            ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
            ['url' => route('formulir-penilaian.index'), 'label' => 'Formulir Penilaian'],
            ['url' => '', 'label' => 'Tambah Rubrik Penilaian']
        ]
    ])
    @endcomponent
</div>
@stop

@section('content')
<div class="card p-4">
    <div class="p-2">
        <form action="{{ route('formulir-penilaian.store-rubrik') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Kode FTA -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="kode_fta">Kode FTA</label>
                        <input type="text" class="form-control" id="kode_fta" name="kode_fta"
                        value="{{ $data->kode_fta ?? '' }}" readonly>
                    </div>
                </div>

                <!-- Nama FTA -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nama_fta">Nama FTA</label>
                        <input type="text" class="form-control" id="nama_fta" name="nama_fta" 
                        value="{{ $data->nama_fta ?? '' }}"readonly>
                    </div>
                </div>

                <!-- Program Studi -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nama_prodi">Program Studi</label>
                        <input type="text" class="form-control" id="nama_prodi" name="nama_prodi" 
                            value="{{ $data->nama_prodi ?? '' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Aspek Penilaian -->
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h6><strong>Rubrik Penilaian</strong></h6>
                <button type="button" class="btn btn-primary btn-md my-1" id="addRow" title="Tambah Rubrik Penilaian">
                    <i class="fas fa-plus text-white"></i>
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
                                <th style="min-width: 200px;">{{ $nilai->batas_bawah }} - {{ $nilai->batas_atas }} ({{ $nilai->id_nilai }})</th>
                            @endforeach
                            <th style="min-width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="rubrikPenilaianTable">
                        <tr>
                            <td>
                                <select class="form-control id_kriteria" name="nama_kriteria[]" required>
                                    <option value="" disabled selected>Pilih Kriteria</option>
                                    @foreach ($kriteriaList as $kriteriaPenilaian)
                                        <option value="{{ $kriteriaPenilaian->id_kriteria }}" 
                                            data-bobot="{{ $kriteriaPenilaian->bobot_kriteria }}">
                                            {{ $kriteriaPenilaian->nama_kriteria }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <p class="form-control-plaintext bobot">-</p> 
                            </td>                            
                            <td>
                                <textarea class="form-control textarea-rubrik" name="detail[]" rows="6" required></textarea>
                            </td>
                            @foreach ($rentangNilai as $nilai)
                                <td>
                                    <textarea class="form-control textarea-rubrik" name="nilai_{{ $nilai->id_nilai }}[]" rows="6" required></textarea>
                                </td>
                            @endforeach
                            <td><button type="button" class="btn btn-danger btn-md remove-row" title="Hapus Baris Rubrik Penilaian"><i class="fa-solid fa-minus"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tombol Submit -->
            <div class="d-flex justify-content-end px-3">
                <button type="submit" class="btn btn-primary btn-md my-1">
                    Simpan <i class="fa-solid fa-floppy-disk"></i>
                </button>
            </div>
        </form>
    </div>
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
        const rentangNilai = @json($rentangNilai);
        const kriteriaList = @json($kriteriaList);
    </script>
@stop
