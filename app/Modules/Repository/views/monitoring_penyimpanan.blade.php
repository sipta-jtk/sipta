@extends('adminlte::page')

@section('title', 'Monitoring Penyimpanan')

@section('content_header')
    <h1>Monitoring Penyimpanan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <!-- Filter Search & Sorting -->
                <div class="d-flex align-items-center mb-3">
                    <!-- Search Input -->
                    <input type="text" id="searchInput" class="form-control me-2" placeholder="Cari Kategori..." onkeyup="filterTable()" style="width: auto; min-width: 150px;">

                    <!-- Sorting Kategori -->
                    <select id="sortKategori" class="form-control me-2" onchange="sortTable()" style="width: auto; min-width: 150px;">
                        <option value="">Sortir Kategori</option>
                        <option value="asc">A-Z</option>
                        <option value="desc">Z-A</option>
                    </select>

                    <!-- Sorting Penggunaan -->
                    <select id="sortPenggunaan" class="form-control" onchange="sortTable()" style="width: auto; min-width: 150px;">
                        <option value="">Sortir Penggunaan</option>
                        <option value="high">Teratas</option>
                        <option value="low">Terbawah</option>
                    </select>
                </div>


                <div>
                    <a href="{{ url('/log-aktifitas') }}" class="btn btn-secondary">Log Aktivitas</a>
                    <a href="{{ url('/repository') }}" class="btn btn-secondary">Akses Dokumen</a>
                </div>


            </div>

            <!-- Tabel Data -->
            <table class="table table-bordered mt-3" id="penyimpananTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Penggunaan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1.</td><td>Laporan TA</td><td>1 GB</td></tr>
                    <tr><td>2.</td><td>Poster</td><td>10 GB</td></tr>
                    <tr><td>3.</td><td>Dataset</td><td>5 GB</td></tr>
                    <tr><td>4.</td><td>Dokumen Penting</td><td>3 GB</td></tr>
                </tbody>
            </table>

            <div class="mt-3">
                <p>Penyimpanan Tersedia: 86 GB</p>
                <p>Penyimpanan Terpakai: 14 GB</p>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
<script>
    function filterTable() {
        let input = document.getElementById("searchInput").value.toUpperCase();
        let table = document.getElementById("penyimpananTable");
        let tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName("td")[1]; // Kolom Kategori
            if (td) {
                let textValue = td.textContent || td.innerText;
                tr[i].style.display = textValue.toUpperCase().indexOf(input) > -1 ? "" : "none";
            }
        }
    }

    function sortTable() {
        let table = document.getElementById("penyimpananTable");
        let rows = Array.from(table.getElementsByTagName("tr")).slice(1);
        let sortKategori = document.getElementById("sortKategori").value;
        let sortPenggunaan = document.getElementById("sortPenggunaan").value;

        rows.sort((a, b) => {
            let kategoriA = a.cells[1].textContent.toLowerCase();
            let kategoriB = b.cells[1].textContent.toLowerCase();
            let penggunaanA = parseInt(a.cells[2].textContent);
            let penggunaanB = parseInt(b.cells[2].textContent);

            // Sorting berdasarkan Kategori (A-Z / Z-A)
            if (sortKategori) {
                return sortKategori === "asc" ? kategoriA.localeCompare(kategoriB) : kategoriB.localeCompare(kategoriA);
            }

            // Sorting berdasarkan Penggunaan (Teratas / Terbawah)
            if (sortPenggunaan) {
                return sortPenggunaan === "high" ? penggunaanB - penggunaanA : penggunaanA - penggunaanB;
            }

            return 0;
        });

        // Update tampilan tabel
        let tbody = table.getElementsByTagName("tbody")[0];
        tbody.innerHTML = "";
        rows.forEach(row => tbody.appendChild(row));
    }
</script>
@stop
