@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai Sidang')

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
                        <th rowspan="2" class="align-middle" style="width: 1%;">No</th>
                        <th rowspan="2" class="align-middle" style="width: 3%;">NIM</th>
                        <th rowspan="2" class="align-middle" style="width: 20%;">Nama</th>
                        <th rowspan="2" class="align-middle" style="width: 5%;">Prodi</th>
                        <th rowspan="2" class="align-middle" style="width: 2%;">Kelas</th>
                        <th rowspan="2" class="align-middle" style="width: 4%;">Kelompok</th>
                        <th colspan="1" style="width: 5%;">UTS</th>
                        <th colspan="1" style="width: 5%;">UAS</th>
                        <th colspan="1" style="width: 5%;">Lain-Lain</th>
                        <th colspan="1" style="width: 5%;">Nilai Akhir</th>
                        <th colspan="1" style="width: 5%;">Predikat</th>
                    </tr>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr class="bg-light">
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">{{ $row['nim'] }}</td>
                            <td class="align-middle">{{ $row['nama'] }}</td>
                            <td class="align-middle">{{ $row['prodi'] }}</td>
                            <td class="align-middle">{{ $row['kelas'] }}</td>
                            <td class="align-middle">{{ $row['kelompok'] }}</td>
                            <td class="align-middle">{{ $row['nilaiUts'] }}</td>
                            <td class="align-middle">{{ $row['nilaiUas'] }}</td>
                            <td class="align-middle">{{ $row['nilaiLainLain'] }}</td>
                            <td class="align-middle">{{ $row['nilaiAkhir'] }}</td>
                            <td class="align-middle">{{ $row['predikat'] }}</td>
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
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/rekapitulasi_nilai.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.noConflict(); // Hindari konflik dengan jQuery versi lain
    </script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#nilaiAkhirTable').DataTable({
                "paging": true,
                "lengthMenu": [10, 25, 50, 100],
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });

            $('#filterProdi').on('change', function () {
                table.column(3).search(this.value).draw();
            });

            $('#filterKelas').on('change', function () {
                table.column(4).search(this.value).draw();
            });

            $('#dataTableControls').html($('.dataTables_length'));
            $('#searchBox').html($('.dataTables_filter'));
        });
    </script>
@stop

