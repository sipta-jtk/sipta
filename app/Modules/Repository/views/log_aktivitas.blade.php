@extends('adminlte::page')

@section('title', 'Log Aktivitas')

@section('content_header')
    <h1>Log Aktivitas</h1>
@stop

@section('content')
<div class="container">
    <!-- Tombol Filter dan Search -->
    <div class="d-flex justify-content-between">
        <button id="toggleFilter" class="btn btn-outline-secondary">
            <i class="fas fa-filter"></i> Filter
        </button>
        <div>
            <a href="{{ url('/monitoring-penyimpanan') }}" class="btn btn-secondary">Monitoring Penyimpanan</a>
            <a href="{{ url('/repository') }}" class="btn btn-secondary">Akses Dokumen</a>
        </div>
    </div>

    <!-- Form Filter -->
    <div id="filterOptions" class="card p-3 shadow-sm mb-3 mt-2" style="display: none;">
        <form id="logFilterForm" action="{{ url('/log-aktivitas') }}" method="GET">
            <div class="row g-2">
                <!-- Input Pencarian -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kata Kunci:</label>
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan aktivitas..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Dropdown Filter Pengguna -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pengguna:</label>
                        <select name="user_id" class="form-control">
                            <option value="">-- Semua Pengguna --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Dropdown Filter ID KoTA -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>ID KoTA:</label>
                        <select name="kota_id" class="form-control">
                            <option value="">-- Semua KoTA --</option>
                            @foreach($kotas as $kota)
                                <option value="{{ $kota->id }}" {{ request('kota_id') == $kota->id ? 'selected' : '' }}>
                                    {{ $kota->nama_kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Dropdown Filter Jenis Aktivitas -->
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

                <!-- Input Filter Rentang Tanggal -->
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

                <!-- Tombol Filter & Reset -->
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
    <table class="table table-bordered mt-3">
        <thead>
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
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log->waktu_aktivitas }}</td>
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

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $logAktivitas->appends(request()->query())->links() }}
    </div>
</div>
@stop

@section('css')
@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var toggleFilter = document.getElementById("toggleFilter");
        var filterOptions = document.getElementById("filterOptions");

        // Check if any filter is applied to show the filter section
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
    });
</script>
@stop