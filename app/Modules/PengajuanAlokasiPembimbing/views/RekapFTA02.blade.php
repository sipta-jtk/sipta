@extends('adminlte::page')

@section('title', 'Rekap FTA02')

@section('content_header')
<h1>Rekap Formulir TA</h1>
@stop

@section('content')
<div class="p-4">
    <div class="d-flex justify-content-between mb-2">
        <div id="dataTableControls"></div>
        <div id="searchBox"></div>
    </div>

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
                    @for ($i = 1; $i <= 5; $i++) <th style="width: 4%;">{{ $i }}</th>
                        @endfor
                        <th style="width: 7%;">1</th>
                        <th style="width: 7%;">2</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $row)
                <tr class="bg-light">
                    <td class="align-middle">{{ $index + 1 }}</td>
                    <td class="align-middle position-relative">
                        <span class="text-primary cursor-pointer" onmouseover="showTooltip('tooltip-kota-{{ $index }}')" onmouseout="hideTooltip('tooltip-kota-{{ $index }}')">
                            {{ $row['kode'] }}
                        </span>
                        <div id="tooltip-kota-{{ $index }}" class="tooltip-box d-none position-absolute bg-white border p-3 shadow rounded text-left">
                            <strong>Anggota Kelompok:</strong>
                            <ul class="pl-3 m-0">
                                @foreach ($row['anggota'] as $anggota)
                                <li>{{ $anggota['nama'] }} ({{ $anggota['nim'] }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </td>
                    <td class="align-middle">{{ $row['jumlahMahasiswa'] }}</td>
                    <td class="align-middle">{{ $row['bidang'] }}</td>
                    <td class="align-middle">{{ $row['judul'] }}</td>
                    @foreach ($row['usulanDosen'] as $i => $dosen)
                    <td class="align-middle position-relative">
                        <span class="text-success cursor-pointer" onmouseover="showTooltip('tooltip-dosen-{{ $index }}-{{ $i }}')" onmouseout="hideTooltip('tooltip-dosen-{{ $index }}-{{ $i }}')">
                            {{ $dosen }}
                        </span>
                        <div id="tooltip-dosen-{{ $index }}-{{ $i }}" class="tooltip-box d-none position-absolute bg-white border p-3 shadow rounded text-left">
                            <strong>Nama Dosen: {{ $dosen }}</strong>
                            <p class="m-0">Sisa Kuota D4: <strong>2</strong></p>
                            <p class="m-0">Sisa Kuota D3: <strong>1</strong></p>
                        </div>
                    </td>
                    @endforeach
                    <td class="align-middle">{{ $row['pembimbing1'] }}</td>
                    <td class="align-middle">{{ $row['pembimbing2'] }}</td>
                    @endforeach
            </tbody>
        </table>
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

    thead th {
        position: sticky;
        top: 0;
        z-index: 1050;
        background-color: rgba(0, 0, 0, 0.95);
        color: white;
        display: table-cell;
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
    $(document).ready(function() {
        var table = $('#alokasiTable').DataTable({
            "paging": true
            , "lengthMenu": [10, 25, 50, 100]
            , "pageLength": 10
            , "searching": true
            , "ordering": true
            , "info": true
            , "autoWidth": false
            , "scrollY": "400px", // Menetapkan tinggi tetap untuk scroll
            "scrollCollapse": true
            , "columnDefs": [{
                "orderable": false
                , "targets": [5, 6, 7, 8, 9, 10, 11]
            }]
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
