@extends('adminlte::page')

@section('title', 'DosenTabelPenilaian')

@section('content_header')
    <h1>Tabel Penilaian & Feedback</h1>
@stop

@section('content')
    <div class="container">
    <h2>Penilaian & Feedback</h2>
    <table id="seminarTable" class="table table-bordered" width="100%">
        <thead>
            <th>No</th>
            <th>Seminar/Sidang</th>
            <th>KoTA</th>
            <th>Judul</th>
            <th>Tanggal</th>
            <th>Sesi</th>
            <th>Penilaian & Feedback</th>
        </thead>
        <tbody>
            @foreach($seminars as $index => $seminar)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>@if($seminar->agenda == 'seminar_3')
                    Seminar 3
                    @elseif($seminar->agenda == 'sidang')
                    Sidang
                    @endif
                </td>
                <td>{{ $seminar->id_kota }}</td>
                <td>{{ $seminar->kota_judul}}</td>
                <td>{{ $seminar->tanggal }}</td>
                <td>{{ $seminar->sesi }}</td>
            </tr>
            @endforeach
            <!-- <tr>
                <td>1</td>
                <td>Seminar 3</td>
                <td>KoTA-001</td>
                <td>Penelitian Singkong Keju</td>
                <td>22 Februari 2025</td>
                <td>1</td>
                <td><button class="btn btn-danger">Beri Penilaian</button></td>
                <td><button class="btn btn-danger">Beri Feedback</button></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Seminar 3</td>
                <td>KoTA-002</td>
                <td>Penelitian Pisang Keju</td>
                <td>23 Februari 2025</td>
                <td>2</td>
                <td><button class="btn btn-success">Lihat Penilaian</button></td>
                <td><button class="btn btn-success">Lihat Feedback</button></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Seminar 3</td>
                <td>KoTA-003</td>
                <td>Penelitian Martabak Keju</td>
                <td>24 Februari 2025</td>
                <td>3</td>
                <td><button class="btn btn-warning">Lanjut Penilaian</button></td>
                <td><button class="btn btn-warning">Lanjut Feedback</button></td>
            </tr> -->
        </tbody>
    </table>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js">
@stop

@section('js')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#seminarTable').DataTable();
        });
    </script>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop