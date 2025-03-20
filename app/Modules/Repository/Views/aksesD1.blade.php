@extends('adminlte::page')

@section('title', 'Laporan TA')

@section('content_header')
    <h1 class="text-center">Laporan Tugas Akhir</h1>
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
                <th>Kategori</th>
                <th>Last Update</th>
            </tr>
        </thead>
        <tbody>
            <tr class="clickable-row" data-url="{{ url('/repository/dokumen') }}">
                <td>1</td>
                <td>Laporan TA</td>
                <td>Sistem Manajemen Tugas Akhir</td>
            </tr>
            <tr class="clickable-row" data-url="{{ url('/kategori/revisi-ta') }}">
                <td>2</td>
                <td></td>
                <td></td>
            </tr>
            <tr class="clickable-row" data-url="{{ url('/kategori/publikasi-ta') }}">
                <td>3</td>
                <td></td>
                <td></td>
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
        console.log("Laporan TA Page Loaded");

        // Membuat setiap baris kategori bisa diklik untuk menuju laman berikutnya
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