@extends('adminlte::page')

@section('title', 'Log Login')

@section('content_header')
    <h1>Log Login</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Log Login']
            ]
        ])
        @endcomponent
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="log-table" class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th>No</th>
                            <th>ID (NIP/NIM)</th>
                            <th>Nama</th>
                            <th>IP Address</th>
                            <th>Login Time</th>
                            <th>Logout Time</th>
                            <th>Durasi Online</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logLogin as $index => $log)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $log->user->username ?? '-' }}</td>
                                <td>{{ $log->user->nama ?? '-' }}</td>
                                <td>{{ $log->ip_address ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->waktu_aktivitas)->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($log->status === 'online')
                                        <span class="badge badge-success">Masih Online</span>
                                    @else
                                        {{ $log->waktu_logout ? \Carbon\Carbon::parse($log->waktu_logout)->format('Y-m-d H:i') : '-' }}
                                    @endif
                                </td>
                                <td>{{ $log->durasi_login }}</td>
                                <td>
                                    @if($log->status === 'online')
                                        <span class="badge badge-success">{{ $log->status_aktif }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $log->status_aktif }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data aktivitas ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
            $('#log-table').DataTable({
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
                order: [[0, 'asc']],
                columnDefs: [
                    {
                        targets: [0],
                        width: '5%'
                    }
                ]
            });
        });
    </script>
@stop
