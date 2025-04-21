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
            <i class="fas fa-filter"></i>
        </button>
        <input type="text" id="searchInput" class="form-control ms-2 w-25" placeholder="Cari Pengguna atau Aktivitas..." style="display: none;">
        <div>
            <a href="{{ url('/monitoring-penyimpanan') }}" class="btn btn-secondary">Monitoring Penyimpanan</a>
            <a href="{{ url('/repository') }}" class="btn btn-secondary">Akses Dokumen</a>
        </div>
    </div>

    
    

    <!-- Form Filter -->
    <div id="filterOptions" class="card p-3 shadow-sm mb-3" style="display: none;">
        <form>
            <div class="row g-2">
                <!-- Input Pencarian -->
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Cari Pengguna atau Aktivitas">
                </div>

                <!-- Dropdown Filter Pengguna -->
                <div class="col-md-3">
                    <select class="form-control">
                        <option value="">-- Pilih Pengguna --</option>
                        <option value="1">User 1</option>
                        <option value="2">User 2</option>
                        <option value="3">User 3</option>
                    </select>
                </div>

                <!-- Dropdown Filter Jenis Aktivitas -->
                <div class="col-md-3">
                    <select class="form-control">
                        <option value="">-- Pilih Aktivitas --</option>
                        <option value="Login">Login</option>
                        <option value="Download">Download</option>
                        <option value="Edit">Edit</option>
                        <option value="Hapus">Hapus</option>
                    </select>
                </div>

                <!-- Input Filter Rentang Tanggal -->
                <div class="col-md-3">
                    <label>Dari:</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Sampai:</label>
                    <input type="date" class="form-control">
                </div>

                <!-- Tombol Filter & Reset -->
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-dark">Filter</button>
                    <button type="button" class="btn btn-secondary ms-2">Reset</button>
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
            <tr>
                <td>1</td>
                <td>2025-03-13 10:00:00</td>
                <td>User 1</td>
                <td>101</td>
                <td>Login</td>
            </tr>
            <tr>
                <td>2</td>
                <td>2025-03-13 10:15:00</td>
                <td>User 2</td>
                <td>102</td>
                <td>Download</td>
            </tr>
            <tr>
                <td>3</td>
                <td>2025-03-13 10:30:00</td>
                <td>User 3</td>
                <td>103</td>
                <td>Edit</td>
            </tr>
        </tbody>
    </table>
</div>
@stop

@section('css')
@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var toggleFilter = document.getElementById("toggleFilter");
        var filterOptions = document.getElementById("filterOptions");

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
