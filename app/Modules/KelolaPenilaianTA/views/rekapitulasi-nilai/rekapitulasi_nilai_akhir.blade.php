@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai Akhir')

@section('content_header')
<div class="container-fluid p-3">
    <!-- Judul Halaman -->
    <h1 class="mb-0">Rekapitulasi Nilai Akhir</h1>
    @component('KelolaPenilaianTA.views.components.breadcrumb', [
    'links' => [
    ['url' => route('beranda.get'), 'label' => 'Home'],
    ['url' => '', 'label' => 'Rekapitulasi Nilai Akhir']
    ]
    ])
    @endcomponent
</div>
@stop

@section('content')
<div class="p-2">
    <div class="card shadow-sm">
        <div class="card-body">
            {{-- Filter Toggle Button --}}
            <div class="d-flex justify-content-between px-3 pt-3">
                <button id="toggleFilter" class="btn btn-primary btn-md">
                    <i class="fas fa-filter"></i>
                </button>
            </div>

            {{-- Filter Section --}}
            <div id="filterSection" class="mt-3" style="display: none;">
                <div class="card mx-3 mt-3">
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
                        <div class="card-footer text-right">
                            <button id="applyFilter" class="btn btn-primary">Terapkan</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- DataTables Controls (Jumlah data & Search) --}}
            <div class="d-flex justify-content-between mb-2">
                <div id="dataTableControls"></div> <!-- Placeholder untuk jumlah data -->
                <div id="searchBox"></div> <!-- Placeholder untuk pencarian -->
            </div>

            {{-- Tabel Scrollable --}}
            <div class="table-container">
                <table id="nilaiAkhirTable" class="table table-striped table-bordered text-center" width="100%">
                    <thead class="sticky-header">
                    <tr class="bg-dark text-white text-center">
                    <th rowspan="1" class="align-middle" style="width: 1%;">No</th>
                    <th rowspan="1" class="align-middle" style="width: 3%;">NIM</th>
                    <th rowspan="1" class="align-middle" style="width: 15%;">Nama</th>
                    <th rowspan="1" class="align-middle" style="width: 5%;">Prodi</th>
                    <th rowspan="1" class="align-middle" style="width: 2%;">Kelas</th>
                    <th rowspan="1" class="align-middle" style="width: 4%;">Kelompok</th>
                    <th rowspan="1" class="align-middle" style="width: 5%;">UTS (Teori)</th>
                    <th rowspan="1" class="align-middle" style="width: 5%;">Praktikum ETS</th>
                    <th rowspan="1" class="align-middle" style="width: 5%;">Lain-lain ETS</th>
                    <th rowspan="1" class="align-middle" style="width: 5%;">UAS (Teori)</th>
                    <th rowspan="1" class="align-middle" style="width: 5%;">Praktikum EAS</th>
                    <th rowspan="1" class="align-middle" style="width: 5%;">Lain-lain EAS</th>
                    <th rowspan="1" class="align-middle" style="width: 5%;">PjBL</th>
                    <th rowspan="1" class="align-middle" style="width: 5%;">Partisipatif</th>
                    <th rowspan="1" class="align-middle" style="width: 5%;">Nilai Akhir</th>
                    <th rowspan="1" class="align-middle" style="width: 5%;">Predikat</th>
                </tr>
                    </thead>
                    <tbody>
                        @if(empty($data) || count($data) == 0)
                        <tr>
                            <td colspan="11" class="text-center">Data tidak tersedia</td>
                        </tr>
                        @else
                        @foreach ($data as $index => $row)
                        <tr>
                            <td class="align-middle"></td>
                            <td class="align-middle">{{ $row['nim'] }}</td>
                            <td class="align-middle" style="text-align: left;">{{ $row['nama'] }}</td>
                            <td class="align-middle">{{ $row['prodi'] }}</td>
                            <td class="align-middle">{{ $row['kelas'] }}</td>
                            <td class="align-middle">{{ $row['kelompok'] }}</td>
                            <td class="align-middle nilai-cell">
                                @if ($row['nilaiUtsTeori'] == null)
                                <span class="text-danger nilai-tooltip">-1
                                    <div class="tooltip-box">Dosen belum melakukan penilaian</div>
                                </span>
                                @else
                                {{ $row['nilaiUtsTeori'] }}
                                @endif
                            </td>

                            <td class="align-middle nilai-cell">
                                @if ($row['nilaiPraktikumETS'] == null)
                                <span class="text-danger nilai-tooltip">-1
                                    <div class="tooltip-box">Dosen belum melakukan penilaian</div>
                                </span>
                                @else
                                {{ $row['nilaiPraktikumETS'] }}
                                @endif
                            </td>

                            <td class="align-middle nilai-cell">
                                @if ($row['nilaiLainLainETS'] == null)
                                <span class="text-danger nilai-tooltip">-1
                                    <div class="tooltip-box">Dosen belum melakukan penilaian</div>
                                </span>
                                @else
                                {{ $row['nilaiLainLainETS'] }}
                                @endif
                            </td>

                            <td class="align-middle nilai-cell">
                                @if ($row['nilaiUasTeori'] == null)
                                <span class="text-danger nilai-tooltip">-1
                                    <div class="tooltip-box">Dosen belum melakukan penilaian</div>
                                </span>
                                @else
                                {{ $row['nilaiUasTeori'] }}
                                @endif
                            </td>

                            <td class="align-middle nilai-cell">
                                @if ($row['nilaiPraktikumEAS'] == null)
                                <span class="text-danger nilai-tooltip">-1
                                    <div class="tooltip-box">Dosen belum melakukan penilaian</div>
                                </span>
                                @else
                                {{ $row['nilaiPraktikumEAS'] }}
                                @endif
                            </td>

                            <td class="align-middle nilai-cell">
                                @if ($row['nilaiLainLainEAS'] == null)
                                <span class="text-danger nilai-tooltip">-1
                                    <div class="tooltip-box">Dosen belum melakukan penilaian</div>
                                </span>
                                @else
                                {{ $row['nilaiLainLainEAS'] }}
                                @endif
                            </td>

                            <td class="align-middle nilai-cell">
                                @if ($row['nilaiPjBL'] == null)
                                <span class="text-danger nilai-tooltip">-1
                                    <div class="tooltip-box">Dosen belum melakukan penilaian</div>
                                </span>
                                @else
                                {{ $row['nilaiPjBL'] }}
                                @endif
                            </td>

                            <td class="align-middle nilai-cell">
                                @if ($row['nilaiPartisipatif'] == null)
                                <span class="text-danger nilai-tooltip">-1
                                    <div class="tooltip-box">Dosen belum melakukan penilaian</div>
                                </span>
                                @else
                                {{ $row['nilaiPartisipatif'] }}
                                @endif
                            </td>
                            

                            <td class="align-middle nilai-cell">
                                @if ($row['nilaiAkhir'] == 0)
                                <span class="text-danger nilai-tooltip">-1
                                    <div class="tooltip-box">Nilai akhir belum tersedia</div>
                                </span>
                                @else
                                {{ $row['nilaiAkhir'] }}
                                @endif
                            </td>

                            <td class="align-middle nilai-cell">
                                @if ($row['nilaiAkhir'] == 0)
                                <span>T</span>
                                @else
                                {{ $row['predikat'] }}
                                @endif
                            </td>

                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between mt-1">
                <div id="infoControls"></div> <!-- Placeholder untuk info -->
                <div id="paginationControls"></div> <!-- Placeholder untuk pagination -->
            </div>
            <form id="exportForm" action="{{ route('rekapitulasi-nilai-akhir.export') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="data" id="exportData">
            </form>

            <div class="d-flex justify-content-end mt-3">
                <button id="exportExcel" type="button" class="btn btn-success"
                    data-url="{{ route('rekapitulasi-akhir.export') }}">
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('KelolaPenilaianTA/js/rekapitulasi_nilai_akhir.js') }}"></script>
@stop