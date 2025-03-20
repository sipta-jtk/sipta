@extends('adminlte::page')

@section('title', 'DosenTabelPenilaian')

@section('content_header')
    <h1>Tabel Penilaian & Feedback</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Penjadwalan</h3>
        </div>
        <div class="card-body">
            <table id="penjadwalanTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Sesi</th>
                        <th>Agenda</th>
                        <th>Tanggal</th>
                        <th>Judul</th>
                        <th>Kota</th>
                        <th>Penilaian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjadwalan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['sesi'] }}</td>
                            <td>{{ $item['agenda'] }}</td>
                            <td>{{ $item['tanggal'] }}</td>
                            <td>{{ $item['judul'] }}</td>
                            <td>{{ $item['kota'] }}</td>
                            <td>
                                @if ($item['status'] === 'Sudah dinilai')
                                    @if ($item['status_penilaian'] === 'dipublikasikan')
                                    <a class="btn btn-success btn-sm">
                                        Lihat Nilai
                                    </a>
                                    @elseif ($item['status_penilaian'] === 'draf')
                                    <a class="btn btn-warning btn-sm">
                                        Edit Nilai
                                    </a>
                                    <a class="btn btn-primary btn-sm">
                                        Publikasikan
                                    </a>
                                    @endif
                                @else
                                <a class="btn btn-primary btn-sm">
                                    Isi Nilai
                                </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <!-- CDN untuk DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <!-- CDN untuk jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- CDN untuk DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <!-- Inisialisasi DataTables -->
    <script>
        $(document).ready(function() {
            $('#penjadwalanTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@stop