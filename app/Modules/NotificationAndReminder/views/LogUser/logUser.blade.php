@extends('adminlte::page')

@section('title', 'Log Notifikasi Mahasiswa')

@section('content_header')
    <h1>Log Notifikasi Mahasiswa</h1>
    @include('NotificationAndReminder::modals.log-modal')
    @include('NotificationAndReminder::modals.preferences-modal')
@stop

@section('content')

    {{-- Daftar Log Notifikasi --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Log Notifikasi Mahasiswa</h3>
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal & Waktu</th>
                        <th>Judul Notifikasi</th>
                        <th>Sumber</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data Log Notifikasi akan dimuat dengan jQuery --}}
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk Detail Notifikasi -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="detailModalLabel"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Tanggal:</strong> <span id="modalDate"></span></p>
                    <p><strong>Sumber:</strong> <span id="modalSource"></span></p>
                    <p><strong>Isi Notifikasi:</strong></p>
                    <p id="modalContent"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Memuat data notifikasi dari API
            $.get('/api/mahasiswa/notifications', function(data) {
                $('tbody').empty();

                // Iterasi data dan menambahkan ke tabel
                data.forEach(function(notif, index) {
                    let row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${notif.created_at}</td>
                            <td>${notif.judul}</td>
                            <td>${notif.sumber_notifikasi}</td>
                            <td>
                                <button class="btn btn-info btn-sm btn-detail" 
                                    data-title="${notif.judul}"
                                    data-content="${notif.isi_notifikasi}"
                                    data-date="${notif.created_at}"
                                    data-source="${notif.sumber_notifikasi}">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    `;
                    $('tbody').append(row);
                });

                // Menangani klik tombol "Detail"
                $('.btn-detail').on('click', function() {
                    let title = $(this).data('title');
                    let content = $(this).data('content');
                    let date = $(this).data('date');
                    let source = $(this).data('source');

                    $('#detailModalLabel').text(title);
                    $('#modalDate').text(date);
                    $('#modalSource').text(source);
                    $('#modalContent').text(content);

                    $('#detailModal').modal('show');
                });
            });
        });
    </script>
@stop
