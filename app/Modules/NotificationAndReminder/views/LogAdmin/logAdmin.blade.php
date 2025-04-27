@extends('adminlte::page')

@section('title', 'Log Notifikasi Admin')

@section('content_header')
    <h1 class="mb-3">Log Notifikasi Admin</h1>
    <!-- <h1>Log Notifikasi Admin</h1> -->
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Notifikasi dan Reminder'],
                ['url' => '', 'label' => 'Log Notifikasi Admin']
            ]
        ])
        @endcomponent
    </div>
@stop

@section('content')
    <div class="card">
        <!-- <div class="card-header">
            <h3 class="card-title">Daftar Log Notifikasi Admin</h3>
        </div> -->
        <div class="card-body">
            <table id="table-log" class="table table-striped" width="100%">
                <thead class="sticky-header">
                    <tr class="bg-dark text-white">
                        <th style="width: 5%">No</th>
                        <th style="width: 20%">Tanggal & Waktu</th>
                        <th style="width: 25%">Judul Notifikasi</th>
                        <th>Isi Notifikasi</th>
                        <th style="width: 15%">Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logNotifikasi as $index => $notif)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $notif->waktu_kirim }}</td>
                            <td>{{ $notif->judul }}</td>
                            <td>{{ $notif->isi_notifikasi }}</td>
                            <td>{{ $notif->user ? $notif->user->nama : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table-log').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari total _MAX_ data)",
                    paginate: {
                        first: "<<",
                        last: ">>",
                        next: ">",
                        previous: "<"
                    }
                }
            });

            $('#notificationBell').on('click', function() {
                $('#myModal').modal('show');
            });
        });
    </script>
@stop