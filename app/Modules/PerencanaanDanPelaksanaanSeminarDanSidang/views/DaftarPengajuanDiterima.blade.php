@extends('adminlte::page')

@section('title', 'Daftar Pengajuan Mahasiswa')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
@stop

@section('content_header')
    <h1>Daftar Pengajuan Mahasiswa</h1>
@stop

@section('content')
<div class="container-fluid text-center">
    <!-- Area filter berdasarkan proses -->
    <div class="d-flex justify-content-center gap-2 mb-5">
        <a href="{{ route('perencanaan.kelola-pengajuan.list') }}" class="btn btn-secondary w-50">Belum Verifikasi</a>
        <a href="{{ route('perencanaan.kelola-pengajuan.ditolak') }}" class="btn btn-secondary w-50">Pengajuan Ditolak</a>
        <a href="{{ route('perencanaan.kelola-pengajuan.diterima') }}" class="btn btn-success w-50">Pengajuan Diterima</a>
    </div>
</div>

<div class="container-fluid">
    <!-- Area filter berdasarkan status -->
    <div class="d-flex justify-content-start mb-3">
        <button type="button" class="btn btn-secondary filter-status mx-2" data-filter="">Semua</button>
        <button type="button" class="btn btn-secondary filter-status mx-2" data-filter="Seminar 3">Seminar 3</button>
        <button type="button" class="btn btn-secondary filter-status mx-2" data-filter="Sidang TA">Sidang TA</button>
    </div>

    <!-- Tabel daftar pengajuan -->
    <div class="container-fluid">
        <table id="pengajuanTable" class="table table-striped text-center" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kelompok TA</th>
                    <th>Judul TA</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@stop

@section('js')
    <!-- Load jQuery & Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Load DataTables -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Data JSON Statis
            let dataSeminarTA = [
                { id: 1, tanggal: "2025-03-10", kelompok: "Kelompok TA 001", judul: "Analisis AI dalam Fintech", status: "Seminar 1" },
                { id: 2, tanggal: "2025-03-11", kelompok: "Kelompok TA 002", judul: "Implementasi IoT di Smart Home", status: "Sidang TA" },
                { id: 3, tanggal: "2025-03-12", kelompok: "Kelompok TA 003", judul: "Machine Learning untuk Prediksi Cuaca", status: "Seminar 2" },
                { id: 4, tanggal: "2025-03-13", kelompok: "Kelompok TA 004", judul: "Blockchain dalam Supply Chain", status: "Sidang TA" },
                { id: 5, tanggal: "2025-03-14", kelompok: "Kelompok TA 005", judul: "Penerapan Deep Learning pada Medis", status: "Seminar 3" },
                { id: 6, tanggal: "2025-03-15", kelompok: "Kelompok TA 006", judul: "Keamanan Siber dalam Era Digital", status: "Sidang TA" }
            ];

            // Inisialisasi DataTables
            let table = $('#pengajuanTable').DataTable({
                data: dataSeminarTA,
                columns: [
                    { 
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Auto indexing mulai dari 1
                        },
                        orderable: false
                    },
                    { data: "tanggal" },
                    { data: "kelompok" },
                    { data: "judul" },
                    { data: "status" }
                ],
                "language": {
                    "search": "Cari dalam semua kolom:"
                }
            });

            // Fungsi untuk filter status
            $('.filter-status').on('click', function() {
                let filterValue = $(this).data('filter');

                if (filterValue === "") {
                    table.column(4).search("").draw(); // Tampilkan semua
                } else {
                    table.column(4).search(filterValue).draw(); // Filter sesuai status
                }
            });

            // Fungsi pencarian berdasarkan urutan huruf yang sesuai di seluruh kolom
            function filterTable() {
                let inputValue = $('#search-input').val().toLowerCase();

                table.rows().every(function() {
                    let rowData = this.data();
                    let allFields = `${rowData.tanggal} ${rowData.kelompok} ${rowData.judul} ${rowData.status}`.toLowerCase();

                    // Cek apakah inputValue muncul dengan urutan yang benar dalam salah satu kolom
                    if (inputValue.length < 3 || allFields.indexOf(inputValue) !== -1) {
                        $(this.node()).show();
                    } else {
                        $(this.node()).hide();
                    }
                });
            }

            // Debounce pencarian untuk menghindari spam saat mengetik
            let delayTimer;
            $('#search-input').on('keyup', function() {
                clearTimeout(delayTimer);
                delayTimer = setTimeout(() => {
                    filterTable();
                }, 300);
            });

            // Event untuk tombol "Search"
            $('#search-btn').on('click', function() {
                let inputValue = $('#search-input').val();
                if (inputValue.length >= 3) {
                    filterTable();
                } else {
                    alert("Masukkan minimal 3 karakter untuk mencari!");
                }
            });

            $('.filter-status[data-filter=""]').removeClass('btn-secondary').addClass('btn-success');

            // Fungsi untuk mengatur warna tombol filter status yang aktif
            $('.filter-status').on('click', function() {
                $('.filter-status').removeClass('btn-success').addClass('btn-secondary'); // Reset semua tombol ke warna default
                $(this).removeClass('btn-secondary').addClass('btn-success'); // Ubah warna tombol yang diklik
            });

            // Fungsi untuk menandai tombol navigasi yang aktif berdasarkan halaman
            let currentPage = "Belum Verifikasi"; // Gantilah ini dengan cara mendeteksi halaman aktif
            $('.filter-proses').each(function() {
                if ($(this).data('filter') === currentPage) {
                    $(this).removeClass('btn-secondary').addClass('btn-success'); // Set tombol yang sesuai dengan halaman menjadi hijau
                }
            });
        });
    </script>
@stop
