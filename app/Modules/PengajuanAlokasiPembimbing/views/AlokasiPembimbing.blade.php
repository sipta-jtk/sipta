@extends('adminlte::page')

@section('title', 'Alokasi Dosen Pembimbing')

@section('content_header')
    <h1>Alokasi Dosen Pembimbing</h1>
@stop

@section('content')
    <div class="p-4">
        {{-- DataTables Controls (Jumlah data & Search) --}}
        <div class="d-flex justify-content-between mb-2">
            <div id="dataTableControls"></div> <!-- Placeholder untuk jumlah data -->
            <div id="searchBox"></div> <!-- Placeholder untuk pencarian -->
        </div>

        {{-- Tabel Scrollable --}}
        <div class="table-container">
            <table id="alokasiTable" class="table text-center">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th rowspan="2" class="align-middle" style="width: 5%;">No</th>
                        <th rowspan="2" class="align-middle" style="width: 15%;">Kelompok</th>
                        <th rowspan="2" class="align-middle" style="width: 10%;">Jumlah Mahasiswa</th>
                        <th rowspan="2" class="align-middle" style="width: 15%;">Topik</th>
                        <th rowspan="2" class="align-middle" style="width: 20%;">Judul</th>
                        <th colspan="5" style="width: 20%;">Alokasi</th>
                        <th colspan="2" class="align-middle" style="width: 15%;">Pembimbing</th>
                    </tr>
                    <tr class="bg-secondary text-white">
                        <th style="width: 4%;">1</th>
                        <th style="width: 4%;">2</th>
                        <th style="width: 4%;">3</th>
                        <th style="width: 4%;">4</th>
                        <th style="width: 4%;">5</th>
                        <th style="width: 7%;">1</th>
                        <th style="width: 7%;">2</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr class="bg-light">
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle position-relative">
                                <span class="text-primary cursor-pointer"
                                    onmouseover="showTooltip('tooltip-kota-{{ $index }}')"
                                    onmouseout="hideTooltip('tooltip-kota-{{ $index }}')">
                                    {{ $row['kota'] }}
                                </span>
                                <div id="tooltip-kota-{{ $index }}"
                                    class="tooltip-box d-none position-absolute bg-white border p-3 shadow rounded text-left">
                                    <strong>Anggota Kelompok:</strong>
                                    <ul class="pl-3 m-0">
                                        @foreach ($row['anggota'] as $anggota)
                                            <li>{{ $anggota }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td class="align-middle">{{ $row['jumlahMahasiswa'] }}</td>
                            <td class="align-middle">{{ $row['topik'] }}</td>
                            <td class="align-middle">{{ $row['judul'] }}</td>
                            @foreach ($row['usulanDosen'] as $i => $dosen)
                                <td class="align-middle position-relative">
                                    <span class="text-success cursor-pointer"
                                        onmouseover="showTooltip('tooltip-dosen-{{ $index }}-{{ $i }}')"
                                        onmouseout="hideTooltip('tooltip-dosen-{{ $index }}-{{ $i }}')">
                                        {{ $dosen }}
                                    </span>
                                    <div id="tooltip-dosen-{{ $index }}-{{ $i }}"
                                        class="tooltip-box d-none position-absolute bg-white border p-3 shadow rounded text-left">
                                        <strong>Nama Dosen: {{ $dosen }}</strong>
                                        <p class="m-0">Sisa Kuota D4: <strong>2</strong></p>
                                        <p class="m-0">Sisa Kuota D3: <strong>1</strong></p>
                                        <p class="m-0">Kuota Maksimal D4: <strong>5</strong></p>
                                        <p class="m-0">Kuota Maksimal D3: <strong>4</strong></p>
                                    </div>
                                </td>
                            @endforeach
                            <td class="align-middle">
                                <input type="text" class="form-control text-center" value="{{ $row['pembimbing1'] }}">
                            </td>
                            <td class="align-middle">
                                <input type="text" class="form-control text-center" value="{{ $row['pembimbing2'] }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex justify-content-end mt-3">
            <button type="button" class="btn btn-dark mr-2">Cek Rekap</button>
            <button type="button" class="btn btn-secondary mr-2">Simpan</button>
            <button type="button" class="btn btn-primary" id="openConfirmModal">Submit</button>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        .table-container {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #ddd;
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc !important; /* Tambahkan border untuk outline */
            padding: 10px;
            text-align: center;
        }

        thead {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(0, 0, 0, 0.9);
            color: white;
        }

        thead th {
            position: sticky;
            top: 0;
            z-index: 1001;
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            text-align: center;
            padding: 12px;
            border-bottom: 2px solid #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa; /* Warna striping */
        }

        tbody tr:hover {
            background-color: #e2e6ea;
        }

        .tooltip-box {
            width: 280px;
            padding: 10px;
            border-radius: 6px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 14px;
            line-height: 1.5;
            left: 0;
            transform: translateX(0);
            top: 130%;
            z-index: 10;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#alokasiTable').DataTable({
                "paging": true,
                "lengthMenu": [10, 25, 50, 100],
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "columnDefs": [
                    { "orderable": false, "targets": [5, 6, 7, 8, 9, 10, 11] }
                ]
            });

            $('#dataTableControls').html($('.dataTables_length'));
            $('#searchBox').html($('.dataTables_filter'));
        });

        function showTooltip(id) {
            document.getElementById(id).classList.remove('d-none');
        }

        function hideTooltip(id) {
            document.getElementById(id).classList.add('d-none');
        }
    </script>
@stop
