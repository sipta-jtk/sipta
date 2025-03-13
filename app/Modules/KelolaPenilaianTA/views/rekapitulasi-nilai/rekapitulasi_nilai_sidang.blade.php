@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai Sidang')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Monitoring Penilaian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Rekapitulasi Nilai Sidang</li>
        </ol>
    </nav>
    <h1>Rekapitulasi Nilai Sidang</h1>
@stop

@section('content')
    <div class="p-4">
        {{-- Filter Section --}}
        <div class="d-flex mb-3">
            <select id="filterProdi" class="form-control mr-2" style="width: 200px;">
                <option value="">Prodi</option>
                <option value="D3-Teknik Informatika">D3-Teknik Informatika</option>
                <option value="D4-Teknik Informatika">D4-Teknik Informatika</option>
            </select>

            <select id="filterKelas" class="form-control" style="width: 150px;">
                <option value="">Kelas</option>
                <option value="4A">4A</option>
                <option value="4B">4B</option>
            </select>
        </div>
        {{-- DataTables Controls (Jumlah data & Search) --}}
        <div class="d-flex justify-content-between mb-2">
            <div id="dataTableControls"></div> <!-- Placeholder untuk jumlah data -->
            <div id="searchBox"></div> <!-- Placeholder untuk pencarian -->
        </div>

        {{-- Tabel Scrollable --}}
        <div class="table-container">
            <table id="nilaiTable" class="table text-center">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th rowspan="2" class="align-middle" style="width: 1%;">No</th>
                        <th rowspan="2" class="align-middle" style="width: 3%;">NIM</th>
                        <th rowspan="2" class="align-middle" style="width: 20%;">Nama</th>
                        <th rowspan="2" class="align-middle" style="width: 5%;">Prodi</th>
                        <th rowspan="2" class="align-middle" style="width: 2%;">Kelas</th>
                        <th rowspan="2" class="align-middle" style="width: 4%;">Kelompok</th>
                        <th colspan="4" style="width: 10%;">Seminar 2</th>
                        <th colspan="4" style="width: 10%;">Seminar 3</th>
                        <th colspan="4" style="width: 10%;">Sidang Akhir</th>
                        <th colspan="3" style="width: 10%;">Dosen Pembimbing</th>
                    </tr>
                    <tr class="bg-secondary text-white">
                        <th style="width: 2%;">P1</th>
                        <th style="width: 2%;">P2</th>
                        <th style="width: 2%;">P3</th>
                        <th style="width: 2%;">Rata-rata</th>
                        <th style="width: 2%;">P1</th>
                        <th style="width: 2%;">P2</th>
                        <th style="width: 2%;">P3</th>
                        <th style="width: 2%;">Rata-rata</th>
                        <th style="width: 2%;">P1</th>
                        <th style="width: 2%;">P2</th>
                        <th style="width: 2%;">P3</th>
                        <th style="width: 2%;">Rata-rata</th>
                        <th style="width: 2%;">PM1</th>
                        <th style="width: 2%;">PM2</th>
                        <th style="width: 2%;">Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr class="bg-light">
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">{{ $row['nim'] }}</td>
                            <td class="align-middle">{{ $row['nama'] }}</td>
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

        <form id="exportForm" action="{{ route('rekapitulasi-nilai.export') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="data" id="exportData">
        </form>

        <div class="d-flex justify-content-end mt-3">
            <button id="exportExcel" type="button" class="btn btn-success"">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/rekapitulasi_nilai.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/rekapitulasi_nilai.js') }}"></script>
@stop
