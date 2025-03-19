@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai Akhir')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => '', 'label' => 'Rekapitulasi Nilai Akhir']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">Rekapitulasi Nilai Akhir</h1>
    </div>
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
            <table id="nilaiAkhirTable" class="table text-center">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th rowspan="1" class="align-middle" style="width: 1%;">No</th>
                        <th rowspan="1" class="align-middle" style="width: 3%;">NIM</th>
                        <th rowspan="1" class="align-middle" style="width: 20%;">Nama</th>
                        <th rowspan="1" class="align-middle" style="width: 5%;">Prodi</th>
                        <th rowspan="1" class="align-middle" style="width: 2%;">Kelas</th>
                        <th rowspan="1" class="align-middle" style="width: 4%;">Kelompok</th>
                        <th colspan="1" style="width: 5%;">UTS</th>
                        <th colspan="1" style="width: 5%;">UAS</th>
                        <th colspan="1" style="width: 5%;">Lain-Lain</th>
                        <th colspan="1" style="width: 5%;">Nilai Akhir</th>
                        <th colspan="1" style="width: 5%;">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @if(empty($data) || count($data) == 0)
                        <tr><td colspan="11" class="text-center">Data tidak tersedia</td></tr>
                    @else
                        @foreach ($data as $index => $row)
                            <tr class="bg-light">
                                <td class="align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle">{{ $row['nim'] }}</td>
                                <td class="align-middle">{{ $row['nama'] }}</td>
                                <td class="align-middle">{{ $row['prodi'] }}</td>
                                <td class="align-middle">{{ $row['kelas'] }}</td>
                                <td class="align-middle">{{ $row['kelompok'] }}</td>
                                <td class="align-middle nilai-cell">
                                @if ($row['nilaiUts'] == 0)
                                    <span class="text-danger nilai-tooltip">-1
                                        <div class="tooltip-box">Dosen belum melakukan penilaian</div>
                                    </span>
                                @else
                                    {{ $row['nilaiUts'] }}
                                @endif
                            </td>

                            <td class="align-middle nilai-cell">
                                @if ($row['nilaiUas'] == 0)
                                    <span class="text-danger nilai-tooltip">-1
                                        <div class="tooltip-box">Dosen belum melakukan penilaian</div>
                                    </span>
                                @else
                                    {{ $row['nilaiUas'] }}
                                @endif
                            </td>

                            <td class="align-middle nilai-cell">
                                @if ($row['nilaiLainLain'] == 0)
                                    <span class="text-danger nilai-tooltip">-1
                                        <div class="tooltip-box">Dosen belum melakukan penilaian</div>
                                    </span>
                                @else
                                    {{ $row['nilaiLainLain'] }}
                                @endif
                            </td>

                                </td>
                                <td class="align-middle">{{ $row['nilaiAkhir'] }}</td>
                                <td class="align-middle">{{ $row['predikat'] }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
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
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/rekapitulasi_nilai.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/rekapitulasi_nilai_akhir.js') }}"></script>
@stop

