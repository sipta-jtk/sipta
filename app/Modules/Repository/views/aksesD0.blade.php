@extends('adminlte::page')

@section('title', 'Dashboard Laporan TA')

@section('content_header')
    <h1 class="text-center">Bimbingan Tugas Akhir</h1>
@stop

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-box">
            <input type="text" class="form-control" id="searchInput" placeholder="Search here...">
        </div>
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Kelompok TA</th>
                <th>Judul TA</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <tr class="clickable-row" data-url="{{ url('/repository/kategori') }}">
                <td>1</td>
                <td>KWDA</td>
                <td>Sistem Manajemen Tugas Akhir</td>
                <td class="catatan-cell"></td>
            </tr>
            <tr class="clickable-row" data-url="{{ url('/kelompok/cemut') }}">
                <td>2</td>
                <td>CEMUT</td>
                <td>Pengembangan Fitur Notifikasi</td>
                <td class="catatan-cell"></td>
            </tr>
            <tr class="clickable-row" data-url="{{ url('/kelompok/syapy') }}">
                <td>3</td>
                <td>SYAPY</td>
                <td>Integrasi dengan API</td>
                <td class="catatan-cell"></td>
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
    .clickable-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .clickable-row:hover {
        background-color: #f1f1f1;
    }
    .search-box input {
        width: 250px;
    }
</style>
@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        console.log("Dashboard Laporan TA loaded.");

        // Membuat setiap baris daftar kelompok bisa diklik untuk menuju laman berikutnya
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function() {
                let url = this.getAttribute('data-url');
                if (url) {
                    window.location.href = url;
                }
            });
        });

        // Fitur pencarian pada tabel
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