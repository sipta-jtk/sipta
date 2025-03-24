@extends('adminlte::page')

@section('title', 'Log Notifikasi Mahasiswa')

@section('content_header')
    <h1>Log Notifikasi Mahasiswa</h1>
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
                    </tr>
                </thead>
                <tbody id="notifications-table-body">
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
<script>
    $(document).ready(function () {
        // Data dummy notifikasi
        const dummyNotifications = [
            {
                judul: 'Pemberitahuan Jadwal Seminar',
                isi_notifikasi: 'Anda telah berhasil mendaftarkan jadwal seminar pada tanggal 25 Oktober 2023.',
                sumber_notifikasi: 'Admin Sistem',
                created_at: '2023-10-25 10:30:00',
            },
            {
                judul: 'Pengumpulan Artefak Diterima',
                isi_notifikasi: 'Artefak yang Anda kumpulkan telah diterima dan diverifikasi oleh dosen pembimbing.',
                sumber_notifikasi: 'Dosen Pembimbing',
                created_at: '2023-10-24 14:45:00',
            },
            {
                judul: 'Pengingat Sidang Akhir',
                isi_notifikasi: 'Sidang akhir akan dilaksanakan pada tanggal 30 Oktober 2023 pukul 09:00 WIB.',
                sumber_notifikasi: 'Koordinator TA',
                created_at: '2023-10-23 08:15:00',
            },
        ];

        const $tbody = $('#notifications-table-body');
        $tbody.empty();

        // Jika data kosong, tampilkan pesan
        if (!Array.isArray(dummyNotifications) || dummyNotifications.length === 0) {
            $tbody.append('<tr><td colspan="4" class="text-center">Tidak ada notifikasi.</td></tr>');
            return;
        }

        // Iterasi data dan menambahkan ke tabel
        dummyNotifications.forEach(function (notif, index) {
            let row = `
                <tr class="clickable-row"
                    data-title="${encodeURIComponent(notif.judul)}"
                    data-content="${encodeURIComponent(notif.isi_notifikasi)}"
                    data-date="${notif.created_at}"
                    data-source="${notif.sumber_notifikasi}">
                    <td>${index + 1}</td>
                    <td>${notif.created_at}</td>
                    <td>${notif.judul}</td>
                    <td>${notif.sumber_notifikasi}</td>
                </tr>
            `;
            $tbody.append(row);
        });

        // Delegasi event untuk baris tabel yang dapat diklik
        $(document).on('click', '.clickable-row', function () {
            let title = decodeURIComponent($(this).data('title'));
            let content = decodeURIComponent($(this).data('content'));
            let date = $(this).data('date');
            let source = $(this).data('source');

            // Update modal content dynamically
            $('#detailModalLabel').text(title);
            $('#modalDate').text(date);
            $('#modalSource').text(source);
            $('#modalContent').text(content);

            // Show the modal
            $('#detailModal').modal('show');
        });
    });
</script>
@stop