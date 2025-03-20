@extends('adminlte::page')

@section('title', 'Penguji TA')

@section('content_header')
    <h1 class="text-center">Pengujian Tugas Akhir</h1>
@stop

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-box">
            <input type="text" class="form-control" id="searchInput" placeholder="Search kelompok atau judul...">
        </div>
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Kelompok TA</th>
                <th>Judul TA</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>KWDA</td>
                <td>Sistem Manajemen Tugas Akhir</td>
                <td>
                    <button class="btn btn-secondary dropdown-toggle status-btn" data-toggle="dropdown">
                        Tidak Ada Status
                    </button>
                    <div class="dropdown-menu">
                        <button class="dropdown-item status-option" data-status="tidak-ada">Tidak Ada Status</button>
                        <button class="dropdown-item status-option text-danger" data-status="belum-diuji">Belum Diuji</button>
                        <button class="dropdown-item status-option text-warning" data-status="sedang-diuji">Sedang Diuji</button>
                        <button class="dropdown-item status-option text-success" data-status="sudah-diuji">Sudah Diuji</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>CEMUT</td>
                <td>Pengembangan Fitur Notifikasi</td>
                <td>
                    <button class="btn btn-secondary dropdown-toggle status-btn" data-toggle="dropdown">
                        Tidak Ada Status
                    </button>
                    <div class="dropdown-menu">
                        <button class="dropdown-item status-option" data-status="tidak-ada">Tidak Ada Status</button>
                        <button class="dropdown-item status-option text-danger" data-status="belum-diuji">Belum Diuji</button>
                        <button class="dropdown-item status-option text-warning" data-status="sedang-diuji">Sedang Diuji</button>
                        <button class="dropdown-item status-option text-success" data-status="sudah-diuji">Sudah Diuji</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>SYAPY</td>
                <td>Integrasi dengan API</td>
                <td>
                    <button class="btn btn-secondary dropdown-toggle status-btn" data-toggle="dropdown">
                        Tidak Ada Status
                    </button>
                    <div class="dropdown-menu">
                        <button class="dropdown-item status-option" data-status="tidak-ada">Tidak Ada Status</button>
                        <button class="dropdown-item status-option text-danger" data-status="belum-diuji">Belum Diuji</button>
                        <button class="dropdown-item status-option text-warning" data-status="sedang-diuji">Sedang Diuji</button>
                        <button class="dropdown-item status-option text-success" data-status="sudah-diuji">Sudah Diuji</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@stop

@section('css')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .search-box input {
        width: 300px;
    }
    /* Warna tombol berdasarkan status */
    .status-btn.tidak-ada {
        background-color: #6c757d; /* Abu-abu */
        color: white;
    }
    .status-btn.belum-diuji {
        background-color: #dc3545; /* Merah */
        color: white;
    }
    .status-btn.sedang-diuji {
        background-color: #ffc107; /* Kuning */
        color: black;
    }
    .status-btn.sudah-diuji {
        background-color: #28a745; /* Hijau */
        color: white;
    }
</style>
@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        console.log("Pengujian Tugas Akhir Page Loaded");

        // Mengubah warna tombol berdasarkan status yang dipilih
        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function() {
                let selectedStatus = this.getAttribute('data-status');
                let button = this.closest('td').querySelector('.status-btn');

                // Perbarui teks tombol dengan status yang dipilih
                button.textContent = this.textContent;

                // Hapus semua class status lama
                button.classList.remove("tidak-ada", "belum-diuji", "sedang-diuji", "sudah-diuji");

                // Tambahkan class status baru
                button.classList.add(selectedStatus);
            });
        });

        // Fitur pencarian berdasarkan kelompok dan judul TA
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    });
</script>
@stop