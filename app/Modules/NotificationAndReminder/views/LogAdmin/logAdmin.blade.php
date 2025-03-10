@extends('adminlte::page')

@section('title', 'Log Notifikasi Admin')

@section('content_header')
    <h1>Log Notifikasi Admin</h1>
    @include('NotificationAndReminder::modals.log-modal')
    @include('NotificationAndReminder::modals.preferences-modal')

@stop

@section('content')

    {{-- Data Dummy Log Notifikasi --}}
    @php
        $notifikasi = [
            (object) [
                'tipe_notifikasi' => 'Notifikasi',
                'judul' => 'Jadwal Sidang Mahasiswa A 3 hari lagi',
                'isi_notifikasi' => 'Jadwal Sidang mahasiswa A.....3 hari lagi, harap menyiapkan keperluan untuk sidang',
                'penerima_notif' => 'Mahasiswa',
                'created_at' => \Carbon\Carbon::now(),
            ],
            (object) [
                'tipe_notifikasi' => 'Notifikasi',
                'judul' => 'Jadwal Sidang Mahasiswa A 3 hari lagi',
                'isi_notifikasi' => 'Jadwal Sidang mahasiswa A.....3 hari lagi, harap menyiapkan keperluan untuk sidang',
                'penerima_notif' => 'Mahasiswa',
                'created_at' => \Carbon\Carbon::now(),
            ],
        ];
    @endphp

    {{-- Tabel Daftar Log Notifikasi --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Log Notifikasi Admin</h3>
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;" data-title="{{ $notifikasi[0]->judul }}" data-content="{{ $notifikasi[0]->isi_notifikasi }}">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal & Waktu</th>
                        <th>Judul Notifikasi</th>
                        <th>Isi Notifikasi</th>
                        <th>Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notifikasi as $index => $notif)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $notif->created_at->toDateTimeString() }}</td>
                            <td>{{ $notif->judul }}</td>
                            <td>{{ $notif->isi_notifikasi }}</td>
                            <td>{{ $notif->penerima_notif }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk Detail Notifikasi -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- Header Modal -->
                <div class="modal-header">
                    <h3 class="modal-title" id="detailModalLabel"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Isi Modal -->
                <div class="modal-body" id="detailModalContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    {{-- Tambahkan stylesheet tambahan di sini --}}
@stop

@section('js')
    <script>
        $(document).ready(function() {
            console.log("Hi, I'm using the Laravel-AdminLTE package!");

            // Menangani klik pada card body untuk menampilkan detail
            $('.card-body').on('click', function() {
                var title = $(this).data('title');
                var content = $(this).data('content');
                $('#detailModalLabel').text(title);
                $('#detailModalContent').text(content);
                $('#detailModal').modal('show');
            });
        });
    </script>
@stop
