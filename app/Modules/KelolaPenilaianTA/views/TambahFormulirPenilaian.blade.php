@extends('adminlte::page')

@section('title', 'Penambahan Formulir Penilaian')

@section('content_header')
    <h1>Penambahan Formulir Penilaian</h1>
@stop

@section('content')
    <div class="p-4">
        <form action="{{ route('formulir-penilaian.create') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kodeFTA">Kode FTA</label>
                        <input type="text" class="form-control" id="kodeFTA" name="kodeFTA" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="namaFTA">Nama FTA</label>
                        <input type="text" class="form-control" id="namaFTA" name="namaFTA" required>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-1">
                <h6><strong>Aspek Penilaian</strong></h6>
                <button type="button" class="btn btn-dark btn-sm" id="addRow"><i class="fa-solid fa-plus"></i></button>
            </div>
            <div class="table-container mb-3">
                <table class="table text-center">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white">
                            <th style="min-width: 200px;">Kriteria Penilaian Penguji</th>
                            <th style="min-width: 100px;">Bobot (%)</th>
                            <th style="min-width: 200px;">Detail Kriteria</th>
                            <th style="min-width: 200px;">≥ 80 (A)</th>
                            <th style="min-width: 200px;">75 - 79.99 (AB)</th>
                            <th style="min-width: 200px;">70 - 74.99 (B)</th>
                            <th style="min-width: 200px;">65 - 69.99 (BC)</th>
                            <th style="min-width: 200px;">60 - 64.99 (C)</th>
                            <th style="min-width: 200px;">< 60 (CD)</th>
                            <th style="min-width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="aspekPenilaianTable">
                        <tr>
                            <td><input type="text" class="form-control" name="kriteria[]" required></td>
                            <td><input type="number" class="form-control" name="bobot[]" required></td>
                            <td><input type="text" class="form-control" name="detail[]" required></td>
                            <td><input type="text" class="form-control" name="lebih80[]" required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluhLima[]" required></td>
                            <td><input type="text" class="form-control" name="tujuhPuluh[]" required></td>
                            <td><input type="text" class="form-control" name="enamPuluhLima[]" required></td>
                            <td><input type="text" class="form-control" name="enamPuluh[]" required></td>
                            <td><input type="text" class="form-control" name="kurang60[]" required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggalTenggat">Tanggal Tenggat Pengisian</label>
                <input type="date" class="form-control" id="tanggalTenggat" name="tanggalTenggat" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="waktuTenggat">Waktu Tenggat</label>
                        <input type="time" class="form-control" id="waktuTenggat" name="waktuTenggat" required>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary mr-2" onclick="window.location='{{ url('KelolaPenilaianTA/formulir-penilaian') }}'">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .table-container {
            max-height: 500px;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #ddd;
            position: relative;
            scrollbar-width: thin;
            -ms-overflow-style: auto;
        }
        .table-container::-webkit-scrollbar {
            height: 12px;
        }
        .table-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc !important;
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
            background-color: #f8f9fa;
        }
        tbody tr:hover {
            background-color: #e2e6ea;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#addRow').on('click', function () {
                var newRow = `<tr>
                    <td><input type="text" class="form-control" name="kriteria[]" required></td>
                    <td><input type="number" class="form-control" name="bobot[]" required></td>
                    <td><input type="text" class="form-control" name="detail[]" required></td>
                    <td><input type="text" class="form-control" name="lebih80[]" required></td>
                    <td><input type="text" class="form-control" name="tujuhPuluhLima[]" required></td>
                    <td><input type="text" class="form-control" name="tujuhPuluh[]" required></td>
                    <td><input type="text" class="form-control" name="enamPuluhLima[]" required></td>
                    <td><input type="text" class="form-control" name="enamPuluh[]" required></td>
                    <td><input type="text" class="form-control" name="kurang60[]" required></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                </tr>`;
                $('#aspekPenilaianTable').append(newRow);
            });

            $(document).on('click', '.remove-row', function () {
                $(this).closest('tr').remove();
            });
        });
    </script>
@stop