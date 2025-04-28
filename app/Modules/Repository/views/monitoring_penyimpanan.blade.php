@extends('adminlte::page')

@section('title', 'Monitoring Penyimpanan')

@section('content_header')
    <h1>Monitoring Penyimpanan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <!-- Filter Search only -->
                <div class="d-flex align-items-center mb-3">
                    <!-- Search Input -->
                    <input type="text" id="searchInput" class="form-control me-2" placeholder="Cari Kategori..." onkeyup="filterTable()" style="width: auto; min-width: 150px;">
                </div>

                <div>
                    <a href="{{ url('/log-aktivitas') }}" class="btn btn-secondary">Log Aktivitas</a>
                    <a href="{{ url('/repository') }}" class="btn btn-secondary">Akses Dokumen</a>
                </div>
            </div>

            <!-- Tabel Data -->
            <table class="table table-bordered mt-3" id="penyimpananTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Subkategori</th>
                        <th>Penggunaan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penyimpanan as $index => $item)
                        <tr data-index="{{ $index + 1 }}">
                            <td class="row-number">{{ $index + 1 }}</td>
                            <td>{{ $item->kategori }}</td>
                            <td>{{ $item->subkategori ? $item->subkategori->nama_subkategori : 'No Subkategori' }}</td>
                            <td>{{ number_format($item->total_ukuran / 1024, 2) }} MB</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                <p>
                    Penyimpanan Tersedia:
                    {{ number_format(1 - $penyimpanan->sum('total_ukuran') / (1024 * 1024), 2) }} GB
                </p>
                <p>
                    Penyimpanan Terpakai:
                    {{ number_format($penyimpanan->sum('total_ukuran') / 1024, 2) }} MB
                </p>
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
        let visibleCount = 0;

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName("td")[1]; // Kolom Kategori
            if (td) {
                let textValue = td.textContent || td.innerText;
                let isVisible = textValue.toUpperCase().indexOf(input) > -1;
                tr[i].style.display = isVisible ? "" : "none";
                
                // Update nomor baris yang terlihat
                if (isVisible) {
                    visibleCount++;
                    tr[i].getElementsByClassName("row-number")[0].textContent = visibleCount;
                }
            }
        }
    }
</script>
@stop