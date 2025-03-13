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
                    <th>Jenis Pengajuan</th>
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
            let data = @json($dataKota);

            // Inisialisasi DataTables
            let table = $('#pengajuanTable').DataTable({
                data: data,
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
                    { data: "judul_ta" },
                    { data: "jenis_pengajuan" }
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
