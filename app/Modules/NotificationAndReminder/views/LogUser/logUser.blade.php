@extends('adminlte::page')

@section('title', 'Log Notifikasi Mahasiswa')

@section('content_header')
    <h1>Log Notifikasi Mahasiswa</h1>
    @include('NotificationAndReminder::modals.log-modal')
    @include('NotificationAndReminder::modals.preferences-modal')
@stop

@section('content')

    {{-- Data Dummy Log Notifikasi --}}
    @php
        $notifikasi = [
            (object) [
                'tipe_notifikasi' => 'Notifikasi',
                'judul' => 'Deadline Reminder: Pengumpulan Berkas',
                'isi_notifikasi' => 'Diberitahukan kepada seluruh mahasiswa yang sedang menempuh Tugas Akhir bahwa batas waktu pengumpulan seluruh dokumen terkait Tugas Akhir adalah 3 (tiga) hari dari sekarang. Mohon segera melengkapi dan menyerahkan dokumen-dokumen yang diperlukan termasuk naskah final, lembar pengesahan, lampiran pendukung, dan berkas administrasi lainnya sesuai dengan panduan yang telah diberikan. Pengumpulan dokumen dilakukan melalui sistem akademik dan dalam bentuk hardcopy di Sekretariat Program Studi. Keterlambatan pengumpulan akan mempengaruhi proses evaluasi dan penilaian akhir. Untuk informasi lebih lanjut, silakan menghubungi dosen pembimbing atau Sekretariat Program Studi.',
                'sumber_notifikasi' => 'Sistem',
                'created_at' => \Carbon\Carbon::now(),
            ],
            (object) [
                'tipe_notifikasi' => 'Reminder',
                'judul' => 'Pengumuman: Seminar Proposal',
                'isi_notifikasi' => 'Diberitahukan kepada mahasiswa yang telah mendaftarkan diri untuk Seminar Proposal bahwa acara tersebut akan dilaksanakan pada hari Senin, 15 Maret 2025 di Aula Gedung Utama. Mohon hadir tepat waktu dan membawa semua persyaratan yang diperlukan. Untuk informasi lebih lanjut, silakan menghubungi panitia Seminar Proposal.',
                'sumber_notifikasi' => 'Sistem',
                'created_at' => \Carbon\Carbon::now()->subDay(),
            ],
        ];
    @endphp

    {{-- Daftar Log Notifikasi dalam Satu Baris --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Log Notifikasi Mahasiswa</h3>
        </div>
        <div class="card-body">
            @foreach ($notifikasi as $notif)
                <div class="notification-item" style="padding: 10px; border-bottom: 1px solid #ddd;" data-content="{{ $notif->isi_notifikasi }}" data-date="{{ $notif->created_at->toDateTimeString() }}" data-source="{{ $notif->sumber_notifikasi }}">
                    <strong>{{ $notif->judul }}</strong>
                    <small class="text-muted" style="position: absolute; bottom: 10px; right: 10px;">{{ $notif->created_at->toDateTimeString() }}</small>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal untuk Detail Notifikasi -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Header Modal -->
                <div class="modal-header">
                    <h3 class="modal-title" id="detailModalLabel"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Isi Modal -->
                <div class="modal-body" id="detailModalContent">
                    <p id="detailModalDate"></p>
                    <p id="detailModalSource"></p>
                    <p id="detailModalText"></p>
                </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Menangani klik pada notifikasi untuk menampilkan detail
            $('.notification-item').on('click', function() {
                var title = $(this).find('strong').text();
                var content = $(this).data('content');
                var date = $(this).data('date');
                var source = $(this).data('source');
                $('#detailModalLabel').text(title);
                $('#detailModalDate').text("Tanggal: " + date);
                $('#detailModalSource').text("Sumber: " + source);
                $('#detailModalText').text(content);
                $('#detailModal').modal('show');
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Menangani klik pada ikon lonceng
            $('#notificationBell').on('click', function() {
                // Tampilkan modal log saat ikon lonceng diklik
                $('#myModal').modal('show');
            });

            // Klik ikon pengaturan untuk membuka modal preferensi
            $('#openPreferences').on('click', function(e) {
                e.preventDefault();  // Menghindari aksi default
                $('#myModal').modal('hide');  // Menutup modal log
                $('#myModals').modal('show');  // Menampilkan modal preferensi
            });
        });
    </script>
    
@stop
