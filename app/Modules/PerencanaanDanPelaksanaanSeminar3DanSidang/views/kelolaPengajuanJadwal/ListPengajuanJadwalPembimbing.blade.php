@extends('adminlte::page')

@section('title', 'Daftar Pengajuan Jadwal')

@section('content_header')
    <h1 class="mb-3">Daftar Pengajuan Jadwal {{ $tipe === 'seminar-3' ? 'Seminar 3' : 'Sidang Akhir' }}</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', ['links'=> [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => url('/sipta-dev/kelola-pengajuan-jadwal-pembimbing/'.$tipe), 'label' => 'Daftar Pengajuan Jadwal Pembimbing ' . ($tipe === 'seminar-3' ? 'Seminar 3' : 'Sidang Akhir')],
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
                <a class="nav-link {{ request()->routeIs('kelola-pembimbing.jadwal.list') ? 'active' : '' }}" 
                href="{{ route('kelola-pembimbing.jadwal.list', ['tipe' => $tipe]) }}">
                    Pembimbing
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kelola-penguji.jadwal.list') ? 'active' : '' }}" 
                href="{{ route('kelola-penguji.jadwal.list', ['tipe' => $tipe]) }}">
                    Penguji
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
                    <th>Kelompok TA</th>
                    <th>Agenda</th>
                    <th>Tanggal Kegiatan</th>
                    <th>Ruangan</th>
                    <th>Sesi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal Detail Pengajuan -->
<x-adminlte-modal id="modalPengajuan" title="Detail Pengajuan" theme="blue" size="lg" scrollable>

    <x-slot name="footerSlot">
        <form id="formVerifikasi" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="inputId">
            <input type="hidden" name="status_verifikasi" id="inputStatus">
            <div class="d-flex pt-3 justify-content-end">
                <x-adminlte-button class="btn-tutup mx-1" label="Tutup" theme="secondary" data-dismiss="modal" type="button"/>
                <x-adminlte-button class="btn-tolak mx-1" label="Tolak" theme="danger" data-dismiss="modal" type="submit"/>
                <x-adminlte-button class="btn-setuju mx-1" label="Setuju" theme="success" data-dismiss="modal" type="submit"/>
            </div>
        </form>
    </x-slot>

    <div class="px-3 mb-2">
        <div class="col-md-6 mb-2">
            <p><strong>Kelompok:</strong> <span id="modalKelompok"></span></p>
        </div>
        <div class="col-md-6 mb-2">
            <p><strong>Judul TA:</strong> <span id="modalJudul"></span></p>
        </div>
        <div class="col-md-6 mb-2">
            <p><strong>Agenda:</strong> <span id="modalAgenda"></span></p>
        </div>
        <div class="col-md-6 mb-2">
            <p><strong>Tanggal:</strong> <span id="modalTanggal"></span></p>
        </div>
        <div class="col-md-6 mb-2">
            <p><strong>Sesi:</strong> <span id="modalSesi"></span></p>
        </div>
        <div class="col-md-6 mb-2">
            <p><strong>Status Verifikasi:</strong> <span id="modalStatus"></span></p>
        </div>
    </div>

</x-adminlte-modal>


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
            let data = @json($dataPengajuan);
            console.log(data);

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
                    { 
                        data: "id_kota",
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `koTA ${data}`; // Menambahkan prefix "KoTA" sebelum angka
                        }
                    },
                    { 
                        data: "agenda",
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (data === "seminar_3") {
                                return "Seminar 3";
                            } else if (data === "sidang") {
                                return "Sidang Akhir";
                            }
                            return data; // Jika tidak sesuai dengan kondisi di atas, tampilkan apa adanya
                        }
                    },
                    { data: "tanggal", className: 'text-center',},
                    { data: "id_ruangan", className: 'text-center',},
                    { data: "sesi", className: 'text-center',},
                    { 
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<button class="btn btn-primary btn-proses" '
                                        data-idpenjadwalan="${row.id_penjadwalan}"
                                        data-kelompok="${row.id_kota}" 
                                        data-judul="${row.judul_ta}" 
                                        data-agenda="${row.agenda}"
                                        data-tanggal="${row.tanggal}" 
                                        data-ruangan="${row.id_ruangan}" 
                                        data-sesi="${row.sesi}"
                                        data-status="Menunggu Verifikasi">
                                        Proses
                                    </button>`;
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

        $(document).on('click', '.btn-proses', function() {
            let tipe = @json($tipe);
            console.log(tipe);
            let id_penjadwalan = $(this).data('idpenjadwalan');
            let id = $(this).data('kelompok');
            let judul = $(this).data('judul');
            let tanggal = $(this).data('tanggal');
            let ruangan = $(this).data('ruangan');
            let sesi = $(this).data('sesi');
            let status = $(this).data('status');

            if (agenda === "seminar_3") {
                agenda = "Seminar 3";
            } else if (data === "sidang") {
                agenda = "Sidang Akhir";
            }

            // Isi data ke dalam modal
            $('#modalKelompok').text(id);
            $('#modalJudul').text(judul);
            $('#modalTanggal').text(tanggal);
            $('#modalRuangan').text(ruangan);
            $('#modalSesi').text(sesi);
            $('#modalStatus').text(status);

            // Set ID untuk form
            $('#inputId').val(id);

            // Set action form
            $('#formVerifikasi').attr('action', `/kelola-pengajuan-jadwal-pembimbing/${tipe}/verifikasi/${id_penjadwalan}`);

            // Tampilkan modal
            $('#modalPengajuan').modal('show');
        });

        $(document).on('click', '.btn-setuju', function() {
            // Set status menjadi Disetujui
            $('#inputStatus').val('Disetujui');

            // Submit form setelah setuju
            $('#formVerifikasi').submit(); // Mengirimkan form dengan status Disetujui
        });

        $(document).on('click', '.btn-tolak', function() {
            // Set status menjadi Ditolak
            $('#inputStatus').val('Ditolak');

            // Submit form setelah tolak
            $('#formVerifikasi').submit(); // Mengirimkan form dengan status Ditolak
        });

    </script>
@stop