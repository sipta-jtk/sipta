@extends('adminlte::page')

@section('title', 'Log Notifikasi User')

@section('content_header')
    <h1 class="mb-3">Log Notifikasi User</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Notifikasi dan Reminder'],
                ['url' => '', 'label' => 'Log Notifikasi User']
            ]
        ])
        @endcomponent
    </div>
@stop

@section('content')
    <div class="card">
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
                    @foreach($logUserNotifikasi as $index => $notif)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $notif->waktu_kirim }}</td>
                            <td>{{ $notif->judul }}</td>
                            <td>{{ $notif->isi_notifikasi }}</td>
                            <td>{{ $notif->user->nama ?? $notif->username ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Tambahkan paginasi -->
            <div class="mt-3">
                {{ $logUserNotifikasi->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.min.js"></script>

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
                },
                // Nonaktifkan fitur yang sudah ditangani oleh Laravel pagination
                paging: false,
                serverSide: false
            });
        });
    </script>
@stop