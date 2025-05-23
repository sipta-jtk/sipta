@extends('adminlte::page')

@section('title', 'Log Aktivitas')

@section('content_header')
    <h1 class="mb-3">Log Aktivitas</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Log Aktivitas']
            ]
        ])
        @endcomponent
    </div>
@stop

@section('content')
<div class="container">
    <!-- Tombol Filter dan Search -->
    <div class="d-flex justify-content-between">
        <button id="toggleFilter" class="btn btn-outline-secondary">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>

    <!-- Form Filter -->
    <div id="filterOptions" class="card p-3 shadow-sm mb-3 mt-2" style="display: none;">
        <form id="logFilterForm" action="{{ url('/log-aktivitas') }}" method="GET">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kata Kunci:</label>
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan aktivitas..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pengguna:</label>
                        <select name="username" class="form-control">
                            <option value="">-- Semua Pengguna --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->username }}" {{ request('username') == $user->username ? 'selected' : '' }}>
                                    {{ $user->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>ID KoTA:</label>
                        <select name="id_kota" class="form-control">
                            <option value="">-- Semua KoTA --</option>
                            @foreach($kotas as $kota)
                                <option value="{{ $kota->id_kota }}" {{ request('id_kota') == $kota->id_kota ? 'selected' : '' }}>
                                    {{ $kota->nama_kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenis Aktivitas:</label>
                        <select name="action" class="form-control">
                            <option value="">-- Semua Aktivitas --</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ $action }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Dari Tanggal:</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Sampai Tanggal:</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                </div>

                <div class="col-md-12 d-flex mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Terapkan Filter
                    </button>
                    <a href="{{ url('/log-aktivitas') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Log Aktivitas -->
    <div class="card mt-3">
        <div class="card-body">
            <table id="logAktivitasTable" class="table table-striped table-bordered">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>No</th>
                        <th>Timestamp</th>
                        <th>Pengguna</th>
                        <th>ID KoTA</th>
                        <th>Jenis Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logAktivitas as $index => $log)
                        <tr>
                            <td>{{ $logAktivitas->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->waktu_aktivitas)->translatedFormat('H:i d F Y') }}</td>
                            <td>{{ $log->user ? $log->user->nama : 'No user' }}</td>
                            <td>{{ $log->kota ? $log->kota->nama_kota : 'No KoTA' }}</td>
                            <td>{{ $log->action }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data log aktivitas yang ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $logAktivitas->appends(request()->query())->links() }}
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
document.addEventListener("DOMContentLoaded", function() {
    var toggleFilter = document.getElementById("toggleFilter");
    var filterOptions = document.getElementById("filterOptions");

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.toString()) {
        filterOptions.style.display = "block";
    }

    toggleFilter.addEventListener("click", function() {
        if (filterOptions.style.display === "none" || filterOptions.style.display === "") {
            filterOptions.style.display = "block";
        } else {
            filterOptions.style.display = "none";
        }
    });

    $('#logAktivitasTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
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
});
</script>
@stop
