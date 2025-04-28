@extends('adminlte::page')

@section('title', 'Daftar Pengajuan Mahasiswa')

@section('content_header')
    <h1 class="mb-3">Daftar Pengajuan Verifikasi Berkas {{ $tipe === 'seminar-3' ? 'Seminar 3' : 'Sidang Akhir' }}</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', ['links'=> [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => url('/sipta-dev/kelola-pengajuan-berkas/'.$tipe), 'label' => 'Daftar Pengajuan Verifikasi Berkas ' . ($tipe === 'seminar-3' ? 'Seminar 3' : 'Sidang Akhir')],
            ]])
        @endcomponent
    </div>
@stop


@section('content')
<div class="container-fluid">
    <div class="d-flex mb-3">
        <ul class="nav nav-tabs">
            @php
                $tipe = request()->route('tipe'); // Ambil tipe dari parameter route (seminar atau sidang)
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kelola.berkas.list') ? 'active' : '' }}" 
                href="{{ route('kelola.berkas.list', ['tipe' => $tipe]) }}">
                    Belum Verifikasi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kelola.berkas.ditolak') ? 'active' : '' }}" 
                href="{{ route('kelola.berkas.ditolak', ['tipe' => $tipe]) }}">
                    Pengajuan Ditolak
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kelola.berkas.diterima') ? 'active' : '' }}" 
                href="{{ route('kelola.berkas.diterima', ['tipe' => $tipe]) }}">
                    Pengajuan Diterima
                </a>
            </li>
        </ul>
    </div>



    <!-- Tabel daftar pengajuan -->
    <div class="container-fluid card">
        <table id="pengajuanTable" class="table table-striped text-center" style="width:100%">
            <thead class="sticky-header">
                <tr class="bg-dark text-white">
                    <th>No</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Kelompok TA</th>
                    <th>Judul TA</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">

    <style>
        .judul-ta-column {
            max-width: 400px; /* Sesuaikan lebar kolom */
        }
        .judul-ta-scroll {
            max-width: 100%; 
            white-space: nowrap;
            overflow-x: auto; 
        }
    </style>

@stop

@section('js')
    <!-- Load DataTables -->
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            let data = @json($dataKota);
            let detailUrl = "{{ route('kelola.berkas.detail', ['id' => '__ID__', 'tipe' => $tipe]) }}";

            let table = $('#pengajuanTable').DataTable({
                data: data,
                columns: [
                    { 
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Auto indexing mulai dari 1
                        },
                        orderable: false
                    },
                    { data: "tanggal_pengajuan", className: 'text-center', },
                    { 
                        data: "id_kota",
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `KoTA ${data}`; // Menambahkan prefix "KoTA" sebelum angka
                        }
                    },
                    { 
                        data: "judul_ta",
                        className: "judul-ta-column",
                        render: function(data, type) {
                            return `<div class="judul-ta-scroll">${data}</div>`; // Bungkus dalam div
                        }
                    },
                    { 
                        data: null,
                        render: function(data, type, row) {
                            let detailLink = detailUrl.replace('__ID__', row.id_pengajuan);
                            return `<a href="${detailLink}" class="btn btn-primary btn-sm text-center">Proses</a>`;
                        },
                        orderable: false
                    }
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari total _MAX_ data)",
                    paginate: {
                        first: "<<",  // Tombol pertama
                        last: ">>",   // Tombol terakhir
                        next: ">",    // Tombol berikutnya
                        previous: "<" // Tombol sebelumnya
                    }
                }
            });

             // Tambahkan event listener untuk scroll horizontal menggunakan mouse
            $('#pengajuanTable tbody').on('wheel', '.judul-ta-scroll', function(event) {
                if (event.originalEvent.deltaY !== 0) {
                    event.preventDefault();
                    this.scrollLeft += event.originalEvent.deltaY; // Ubah scroll vertical menjadi horizontal
                }
            });
        });
    </script>
@stop
