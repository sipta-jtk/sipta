@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai Sidang')

@section('content_header')
<div class="container-fluid">
    <!-- Judul Halaman -->
    <h1 class="mb-0">Rekapitulasi Nilai Sidang</h1>

    @component('KelolaPenilaianTA.views.components.breadcrumb',
    ['links' => [
    ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
    ['url' => '', 'label' => 'Rekapitulasi Nilai Sidang']]
    ])
    @endcomponent


</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body p-3">
            {{-- Filter Toggle Button --}}
            <div class="d-flex justify-content-between mb-3">
                <button id="toggleFilter" class="btn btn-primary btn-md">
                    <i class="fas fa-filter"></i>
                </button>
            </div>

            {{-- Filter Section --}}
            <div id="filterSection" class="mt-3 mb-3" style="display: none;">
                <div class="card mt-3">
                    <div class="card-header">
                        <i class="fas fa-filter"></i> Filter Data
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filterProdi"><i class="fas fa-university"></i> Prodi</label>
                                <select id="filterProdi" class="form-control">
                                    <option value="">Semua Prodi</option>
                                    <option value="D3-Teknik Informatika">D3-Teknik Informatika</option>
                                    <option value="D4-Teknik Informatika">D4-Teknik Informatika</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filterKelas"><i class="fas fa-graduation-cap"></i> Kelas</label>
                                <select id="filterKelas" class="form-control">
                                    <option value="">Semua Kelas</option>
                                    <option value="4A">4A</option>
                                    <option value="4B">4B</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button id="applyFilter" class="btn btn-primary">Terapkan</button>
                    </div>
                </div>
            </div>

            {{-- DataTables Controls (Jumlah data & Search) --}}
            <div class="d-flex justify-content-between mb-3">
                <div id="dataTableControls"></div> <!-- Placeholder untuk jumlah data -->
                <div id="searchBox"></div> <!-- Placeholder untuk pencarian -->
            </div>

            {{-- Tabel Scrollable --}}
            <div class="table-container">
                <table id="nilaiTable" class="table table-striped text-center" width="100%">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white text-center">
                            <th rowspan="2" class="align-middle" style="width: 4%">No</th>
                            <th rowspan="2" class="align-middle" style="width: 6%;">NIM</th>
                            <th rowspan="2" class="align-middle" style="width: 20%;">Nama</th>
                            <th rowspan="2" class="align-middle" style="width: 6%;">Prodi</th>
                            <th rowspan="2" class="align-middle" style="width: 4%;">Kelas</th>
                            <th rowspan="2" class="align-middle" style="width: 6%;">Kelompok</th>
                            <th colspan="4" style="width: 12%;">Seminar 2</th>
                            <th colspan="4" style="width: 12%;">Seminar 3</th>
                            <th colspan="4" style="width: 12%;">Sidang Akhir</th>
                            <th colspan="3" style="width: 10%;">Dosen Pembimbing</th>

                        </tr>
                        <tr class="bg-dark text-white">
                            <th style="width: 3%;">P1</th>
                            <th style="width: 3%;">P2</th>
                            <th style="width: 3%;">P3</th>
                            <th style="width: 3%;">Rata-rata</th>
                            <th style="width: 3%;">P1</th>
                            <th style="width: 3%;">P2</th>
                            <th style="width: 3%;">P3</th>
                            <th style="width: 3%;">Rata-rata</th>
                            <th style="width: 3%;">P1</th>
                            <th style="width: 3%;">P2</th>
                            <th style="width: 3%;">P3</th>
                            <th style="width: 3%;">Rata-rata</th>
                            <th style="width: 3%;">PM1</th>
                            <th style="width: 3%;">PM2</th>
                            <th style="width: 4%;">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $row)
                        <tr>
                            <td class="align-middle"></td>
                            <td class="align-middle">{{ $row['nim'] }}</td>
                            <td class="align-middle" style="text-align: left;">{{ $row['nama'] }}</td>
                            <td class="align-middle">{{ $row['prodi'] }}</td>
                            <td class="align-middle">{{ $row['kelas'] }}</td>
                            <td class="align-middle">{{ $row['kelompok'] }}</td>
                            <td class="align-middle">{{ $row['seminar2Penguji1'] }}</td>
                            <td class="align-middle">{{ $row['seminar2Penguji2'] }}</td>
                            <td class="align-middle">{{ $row['seminar2Penguji3'] }}</td>
                            <td class="align-middle">{{ $row['rataSeminar2'] }}</td>
                            <td class="align-middle">{{ $row['seminar3Penguji1'] }}</td>
                            <td class="align-middle">{{ $row['seminar3Penguji2'] }}</td>
                            <td class="align-middle">{{ $row['seminar3Penguji3'] }}</td>
                            <td class="align-middle">{{ $row['rataSeminar3'] }}</td>
                            <td class="align-middle">{{ $row['sidangPenguji1'] }}</td>
                            <td class="align-middle">{{ $row['sidangPenguji2'] }}</td>
                            <td class="align-middle">{{ $row['sidangPenguji3'] }}</td>
                            <td class="align-middle">{{ $row['rataSidang'] }}</td>
                            <td class="align-middle">{{ $row['pembimbing1'] }}</td>
                            <td class="align-middle">{{ $row['pembimbing2'] }}</td>
                            <td class="align-middle">{{ $row['rataPembimbing'] }}</td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-1">
                <div id="infoControls"></div> <!-- Placeholder untuk info -->
                <div id="paginationControls"></div> <!-- Placeholder untuk pagination -->
            </div>


            <form id="exportForm" action="{{ route('rekapitulasi-nilai.export') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="data" id="exportData">
            </form>

            <div class="d-flex justify-content-end mt-3">
                <button id="exportExcel" type="button" class="btn btn-success"
                    data-url="{{ route('rekapitulasi.export') }}">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/rekapitulasi_nilai.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('KelolaPenilaianTA/js/rekapitulasi_nilai.js') }}"></script>
@stop