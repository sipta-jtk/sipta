@extends('adminlte::page')

@section('title', 'Log Notifikasi Mahasiswa')

@section('content_header')
    <h1>Log Notifikasi Mahasiswa</h1>
@stop

@section('content')

    {{-- Data Dummy Log Notifikasi --}}
    @php
        $notifikasi = [
            (object) [
                'judul' => 'Deadline Reminder: Pengumpulan Berkas',
                'isi_notifikasi' => 'Diberitahukan kepada seluruh mahasiswa yang sedang menempuh Tugas Akhir bahwa batas waktu pengumpulan seluruh dokumen terkait Tugas Akhir adalah 3 (tiga) hari dari sekarang. Mohon segera melengkapi dan menyerahkan dokumen-dokumen yang diperlukan termasuk naskah final, lembar pengesahan, lampiran pendukung, dan berkas administrasi lainnya sesuai dengan panduan yang telah diberikan.',
                'sumber_notifikasi' => 'Sistem',
                'created_at' => \Carbon\Carbon::now(),
            ],
            (object) [
                'judul' => 'Pengumuman: Seminar Proposal',
                'isi_notifikasi' => 'Diberitahukan kepada mahasiswa yang telah mendaftarkan diri untuk Seminar Proposal bahwa acara tersebut akan dilaksanakan pada hari Senin, 15 Maret 2025 di Aula Gedung Utama.',
                'sumber_notifikasi' => 'Sistem',
                'created_at' => \Carbon\Carbon::now()->subDay(),
            ],
        ];
    @endphp

    {{-- Daftar Log Notifikasi dalam Satu Baris --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Log Notifikasi Mahasiswa</h3>
            <div class="ml-auto">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTemplateModal">Tambah Template</button>
            </div>
        </div>
        <div class="card-body">
            @foreach ($notifikasi as $notif)
                <div class="notification-item" style="padding: 10px; border-bottom: 1px solid #ddd;">
                    <strong>{{ $notif->judul }}</strong>
                    <p>{{ $notif->isi_notifikasi }}</p>
                    <small class="text-muted" style="position: absolute; bottom: 10px; right: 10px;">{{ $notif->created_at->toDateTimeString() }}</small>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal untuk Tambah Template -->
    <div class="modal fade" id="addTemplateModal" tabindex="-1" role="dialog" aria-labelledby="addTemplateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTemplateModalLabel">Tambah Template Notifikasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="judul">Judul Notifikasi</label>
                            <input type="text" class="form-control" id="judul" placeholder="Masukkan judul notifikasi">
                        </div>
                        <div class="form-group">
                            <label for="isi">Isi Notifikasi</label>
                            <textarea class="form-control" id="isi" rows="4" placeholder="Masukkan isi notifikasi"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="sumber">Sumber Notifikasi</label>
                            <input type="text" class="form-control" id="sumber" placeholder="Masukkan sumber notifikasi">
                        </div>
                        <div class="form-group">
                            <label for="tipe_notifikasi">Tipe Notifikasi</label>
                            <select class="form-control" id="tipe_notifikasi">
                                <option value="Notifikasi">Notifikasi</option>
                                <option value="Reminder">Reminder</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary">Simpan Template</button>
                    </form>
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
            // Optional: Additional JS functionality can be added here
        });
    </script>
@stop
