@extends('adminlte::page')

@section('title', 'Rekapitulasi Nilai')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Monitoring Penilaian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Rekapitulasi Nilai</li>
        </ol>
    </nav>
    <h1>Rekapitulasi Nilai</h1>
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
            <table id="alokasiTable" class="table text-center">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th rowspan="2" class="align-middle" style="width: 3%;">No</th>
                        <th rowspan="2" class="align-middle" style="width: 5%;">NIM</th>
                        <th rowspan="2" class="align-middle" style="width: 20%;">Nama</th>
                        <th rowspan="2" class="align-middle" style="width: 5%;">Prodi</th>
                        <th rowspan="2" class="align-middle" style="width: 2%;">Kelas</th>
                        <th rowspan="2" class="align-middle" style="width: 4%;">Kelompok</th>
                        <th colspan="3" style="width: 10%;">Seminar 2</th>
                        <th colspan="3" style="width: 10%;">Seminar 3</th>
                        <th colspan="3" style="width: 10%;">Sidang Akhir</th>
                        <th colspan="2" style="width: 10%;">Dosen Pembimbing</th>
                        <th rowspan="2" class="align-middle" style="width: 2%;">UTS</th>
                        <th rowspan="2" class="align-middle" style="width: 2%;">UAS</th>
                        <th rowspan="2" class="align-middle" style="width: 2%;">Lain-Lain</th>
                        <th rowspan="2" class="align-middle" style="width: 2%;">Nilai Akhir</th>
                        <th rowspan="2" class="align-middle" style="width: 2%;">Predikat</th>
                    </tr>
                    <tr class="bg-secondary text-white">
                        <th style="width: 2%;">P1</th>
                        <th style="width: 2%;">P2</th>
                        <th style="width: 2%;">P3</th>
                        <th style="width: 2%;">P1</th>
                        <th style="width: 2%;">P2</th>
                        <th style="width: 2%;">P3</th>
                        <th style="width: 2%;">P1</th>
                        <th style="width: 2%;">P2</th>
                        <th style="width: 2%;">P3</th>
                        <th style="width: 2%;">PM1</th>
                        <th style="width: 2%;">PM2</th>
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
                            @foreach (['seminar2_penguji1', 'seminar2_penguji2', 'seminar2_penguji3', 'seminar3_penguji1', 'seminar3_penguji2', 'seminar3_penguji3', 'sidang_penguji1', 'sidang_penguji2', 'sidang_penguji3', 'pembimbing1', 'pembimbing2'] as $field)
                                <td class="align-middle position-relative">
                                    @if ($row[$field] == -1)
                                        <span class="text-danger cursor-pointer" onmouseover="showTooltip('tooltip-{{ $field }}-{{ $index }}')" onmouseout="hideTooltip('tooltip-{{ $field }}-{{ $index }}')">
                                            {{ $row[$field] }}
                                        </span>
                                        <div id="tooltip-{{ $field }}-{{ $index }}" class="tooltip-box d-none position-absolute bg-white border p-3 shadow rounded text-left">
                                            <strong>Perhatian!</strong> Dosen belum menginput nilai.
                                        </div>
                                    @else
                                        {{ $row[$field] }}
                                    @endif
                                </td>
                            @endforeach
                            <td class="align-middle">{{ $row['uts'] }}</td>
                            <td class="align-middle">{{ $row['uas'] }}</td>
                            <td class="align-middle">{{ $row['lain_lain'] }}</td>
                            <td class="align-middle">{{ $row['nilai_akhir'] }}</td>
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
            <button id="exportExcel" type="button" class="btn btn-success"">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
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
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .table-container::-webkit-scrollbar {
            display: none; /* Untuk Chrome, Safari, dan Opera */
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
            });
            $('#filterProdi').on('change', function () {
                table.column(3).search(this.value).draw();
            });

            // Filter Kelas
            $('#filterKelas').on('change', function () {
                table.column(4).search(this.value).draw();
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

        document.getElementById("exportExcel").addEventListener("click", function () {
            let filterProdi = document.getElementById("filterProdi").value;
            let filterKelas = document.getElementById("filterKelas").value;
            
            let url = "{{ route('rekapitulasi.export') }}?prodi=" + filterProdi + "&kelas=" + filterKelas;
            window.location.href = url;
        });

    </script>
@stop
