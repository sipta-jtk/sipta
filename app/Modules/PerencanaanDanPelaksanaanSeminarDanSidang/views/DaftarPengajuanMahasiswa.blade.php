@extends('adminlte::page')

@section('title', 'Daftar Pengajuan Mahasiswa')

@section('content_header')
<h1 class="text-center mb-5">Daftar Pengajuan {{$tipe}}</h1>
@stop

@section('content')
<div class="container-fluid text-center">
    <!-- Area filter berdasarkan proses -->
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
    <div class="container-fluid ">
        <table id="pengajuanTable" class="table table-striped text-center" style="width:100%">
            <thead>
                <tr>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

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
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js"></script>

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
                        data: "kelompok",
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `KoTA ${data}`; // Menambahkan prefix "KoTA" sebelum angka
                        }
                    },
                    { 
                        data: "judul_ta",
                        className: "judul-ta-column",
                        render: function(data, type, row) {
                            return `<div class="judul-ta-scroll">${data}</div>`; // Bungkus dalam div
                        }
                    },
                    { 
                        data: null,
                        render: function(data, type, row) {
                            let detailLink = detailUrl.replace('__ID__', row.id);
                            return `<a href="${detailLink}" class="btn btn-primary btn-sm text-center">Proses</a>`;
                        },
                        orderable: false
                    }
                ],
                language: {
                    search: "Cari dalam semua kolom:"
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
