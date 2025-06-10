@extends('adminlte::page')

@php
    $prefix = $prefix ?? env('PREFIX_URL', 'sipta-dev');
@endphp

@section('title', 'Log Login')

@section('content_header')
    <h1 class="mb-3">Log Login</h1>
    <div>
        @component('UserManagement.components.breadcrumb', [
            'links' => [
                ['url' => url('/' . $prefix . '/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Log Login'],
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
                <table id="log-table" class="table table-bordered table-striped table-hover w-100">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th class="text-center" style="width: 5%">No</th>
                            <th>ID (NIP/NIM)</th>
                            <th>Nama</th>
                            <th>IP Address</th>
                            <th>Login Time</th>
                            <th>Logout Time</th>
                            <th>Durasi Online</th>
                            <th class="text-center">Status</th>
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
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">
    <style>
        #log-table thead th {
            vertical-align: middle;
            text-align: center;
        }
        .dataTables_wrapper .dataTables_processing {
            background: rgba(255,255,255,0.9);
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .badge {
            min-width: 80px;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
@stop

@section('js')
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#log-table').DataTable({
                processing: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari total _MAX_ data)",
                    processing: '<i class="fa fa-spinner fa-spin fa-fw"></i> Loading...',
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        previous: '<i class="fas fa-angle-left"></i>',
                        next: '<i class="fas fa-angle-right"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>'
                    }
                },
                responsive: true,
                order: [[0, 'asc']],
                pageLength: 10,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                columnDefs: [
                    {
                        targets: [0, -1], // No dan Status
                        className: 'text-center'
                    },
                    {
                        targets: 0,
                        width: '50px'
                    }
                ]
            });
        });
    </script>
@stop
