@extends('adminlte::page')

@section('title', 'Daftar Pengajuan Mahasiswa')

@section('content_header')
<h1 class="text-center mb-5">Daftar Pengajuan {{$tipe}}</h1>
@stop

@section('content')
<div class="container-fluid text-center">
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
    <div class="container-fluid ">
        <table id="pengajuanTable" class="table table-striped text-center" style="width:100%">
            <thead>
                <tr>
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
<div class="modal fade" id="modalPengajuan" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Detail Pengajuan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Kelompok:</strong> <span id="modalKelompok"></span></p>
        <p><strong>Judul TA:</strong> <span id="modalJudul"></span></p>
        <p><strong>Tanggal Kegiatan:</strong> <span id="modalTanggal"></span></p>
        <p><strong>Ruangan:</strong> <span id="modalRuangan"></span></p>
        <p><strong>Status Verifikasi:</strong> <span id="modalStatus"></span></p>
      </div>
      <div class="modal-footer">
        <form id="formVerifikasi" method="POST">
          @csrf
          @method('PUT')
          <input type="hidden" name="id" id="inputId">
          <input type="hidden" name="status_verifikasi" id="inputStatus">
          <button type="submit" class="btn btn-danger btn-tolak">Tolak</button>
          <button type="submit" class="btn btn-success btn-setuju">Setuju</button>
        </form>
      </div>
    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
            $('#inputStatus').val('Disetujui'); // Set status menjadi Disetujui
        });

        $(document).on('click', '.btn-tolak', function() {
            $('#inputStatus').val('Ditolak'); // Set status menjadi Ditolak
        });
    </script>
@stop