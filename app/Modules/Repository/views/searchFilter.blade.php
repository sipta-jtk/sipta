@extends('adminlte::page')

@section('title', 'Laporan TA')

@section('content_header')
    <h1 class="text-center">LAPORAN TA</h1>
@stop

@section('content')
<div class="container">
    <!-- Tombol Filter dan Search -->
    <div class="d-flex align-items-center mb-3">
    <button id="toggleFilter" class="btn btn-outline-secondary">
        <i class="fas fa-filter"></i>
    </button>
    <input type="text" id="searchInput" class="form-control ms-2 w-25" placeholder="Cari Judul atau Versi..." onkeyup="filterTable()" style="display: none;">
    </div>

    <!-- Form Filter (Hidden by Default) -->
    <div id="filterOptions" class="card p-3 shadow-sm mb-3" style="display: none;">
    <form action="{{ route('repository.list') }}" method="GET">
        <div class="row g-2">
            <!-- Input Pencarian -->
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari Judul atau Versi" value="{{ request('search') }}">
            </div>

                <!-- Dropdown Filter Versi -->
                <select name="versi" class="form-control w-25">
                    <option value="">-- Pilih Versi --</option>
                    <option value="V1.0" {{ request('versi') == 'V1.0' ? 'selected' : '' }}>V1.0</option>
                    <option value="V1.1" {{ request('versi') == 'V1.1' ? 'selected' : '' }}>V1.1</option>
                    <option value="V2.0" {{ request('versi') == 'V2.0' ? 'selected' : '' }}>V2.0</option>
                </select>

                <!-- Input Filter Tanggal Dibuat -->
                <input type="date" name="tanggal_dibuat" class="form-control w-25" value="{{ request('tanggal_dibuat') }}">

                <div class="col-md-3 d-flex">
                <button type="submit" class="btn btn-dark w-50 me-2">Filter</button>
                <a href="{{ route('repository.list') }}" class="btn btn-secondary w-50 me-2">Reset</a>
            </div>
            </div>
        </form>
    </div>

    <!-- Tabel Data Laporan -->
    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Versi</th>
                <th>Judul</th>
                <th>Tanggal Dibuat</th>
                <th>Terakhir Diedit</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $index => $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->versi }}</td>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->tanggal_dibuat }}</td>
                <td>{{ $item->updated_at }}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editDocumentModal" data-filename="{{ $item->judul }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-success">
                        <i class="fas fa-download"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada laporan ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tambahkan Pagination -->
    <div class="d-flex justify-content-center">
        {{ $laporan->links() }}
    </div>
</div>
@stop

@section('js')
<script>
    document.getElementById("toggleFilter").addEventListener("click", function () {
        var filterDiv = document.getElementById("filterOptions");
        var searchInput = document.getElementById("searchInput");
        if (filterDiv.style.display === "none") {
            filterDiv.style.display = "block";
            searchInput.style.display = "block";
        } else {
            filterDiv.style.display = "none";
            searchInput.style.display = "none";
        }
    });

    function filterTable() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }
</script>
@stop

<!-- @section('css')
<style>
    /* Agar tombol Filter dan Reset sejajar */
    .btn {
        height: 38px;
        font-size: 14px;
    }

    /* Atur lebar tombol agar lebih proporsional */
    .btn-dark, .btn-secondary {
        width: 100px;
    }

    /* Efek hover untuk tombol */
    .btn-dark:hover {
        background-color: #2c2c2c;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    /* Agar filter terlihat lebih modern */
    #filterOptions {
        border-radius: 8px;
    }

    /* Supaya form lebih responsif */
    @media (max-width: 768px) {
        .col-md-3 {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>
@stop -->
