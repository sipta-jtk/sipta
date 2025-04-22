@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai Sidang')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/'), 'label' => 'Home'],
                ['url' => '', 'label' => 'Rekapitulasi Nilai Sidang']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Rekapitulasi Nilai Sidang</h1>
    </div>
@stop

@section('content')
    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                {{-- Filter Toggle Button --}}
                <div class="d-flex justify-content-start mb-3">
                    <button id="toggleFilter" class="btn btn-outline-secondary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>

                {{-- Filter Section --}}
                <div id="filterSection" class="mb-3" style="display: none;">
                    <div class="d-flex flex-wrap gap-2">
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
                                <th rowspan="2" class="bg-dark align-middle" style="width: 1%;">No</th>
                                <th rowspan="2" class="bg-dark align-middle" style="width: 3%;">NIM</th>
                                <th rowspan="2" class="bg-dark align-middle" style="width: 15%;">Nama</th>
                                <th rowspan="2" class="bg-dark align-middle" style="width: 5%;">Prodi</th>
                                <th rowspan="2" class="bg-dark align-middle" style="width: 2%;">Kelas</th>
                                <th rowspan="2" class="bg-dark align-middle" style="width: 4%;">Kelompok</th>
                                <th colspan="4" class="bg-dark" style="width: 10%;">Seminar 2</th>
                                <th colspan="4" class="bg-dark" style="width: 10%;">Seminar 3</th>
                                <th colspan="4" class="bg-dark" style="width: 10%;">Sidang Akhir</th>
                                <th colspan="3" class="bg-dark" style="width: 10%;">Dosen Pembimbing</th>
                            </tr>
                            <tr class="bg-secondary text-white">
                                <th class="bg-dark" style="width: 2%;">P1</th>
                                <th class="bg-dark" style="width: 2%;">P2</th>
                                <th class="bg-dark" style="width: 2%;">P3</th>
                                <th class="bg-dark" style="width: 2%;">Rata-rata</th>
                                <th class="bg-dark" style="width: 2%;">P1</th>
                                <th class="bg-dark" style="width: 2%;">P2</th>
                                <th class="bg-dark" style="width: 2%;">P3</th>
                                <th class="bg-dark" style="width: 2%;">Rata-rata</th>
                                <th class="bg-dark" style="width: 2%;">P1</th>
                                <th class="bg-dark" style="width: 2%;">P2</th>
                                <th class="bg-dark" style="width: 2%;">P3</th>
                                <th class="bg-dark" style="width: 2%;">Rata-rata</th>
                                <th class="bg-dark" style="width: 2%;">PM1</th>
                                <th class="bg-dark" style="width: 2%;">PM2</th>
                                <th class="bg-dark" style="width: 2%;">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $index => $row)
                                <tr class="bg-light">
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/rekapitulasi_nilai.js') }}"></script>
@stop
