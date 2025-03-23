@extends('adminlte::page')

@section('title', 'Setting Notification')

@section('content_header')
    <h1>Pengaturan Notifikasi</h1>
    @include('NotificationAndReminder.views.modals.log-modal')
    @include('NotificationAndReminder.views.modals.preferences-modal')
@stop

@section('content')
    {{-- Formulir Input Notifikasi --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Template Notifikasi</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('notifikasi.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="judul_notifikasi">Judul Notifikasi</label>
                    <x-adminlte-input name="judul_notifikasi" id="judul_notifikasi" placeholder="Masukkan judul notifikasi" required />
                </div>
                <div class="form-group">
                    <label for="jenis_notifikasi">Jenis Notifikasi</label>
                    <x-adminlte-select name="jenis_notifikasi" id="jenis_notifikasi" required>
                        <option value="" disabled selected>Pilih Jenis Notifikasi</option>
                        <option value="pemberitahuan">Pemberitahuan</option>
                        <option value="reminder">Reminder</option>
                    </x-adminlte-select>
                </div>
                <div class="form-group">
                    <label for="isi_in_apps">Template Notifikasi In Apps</label>
                    <x-adminlte-textarea name="isi_in_apps" id="isi_in_apps" rows=4 placeholder="Masukkan isi template untuk notifikasi In Apps" required></x-adminlte-textarea>
                </div>
                <div class="form-group">
                    <label for="isi_in_email">Template Notifikasi In Email</label>
                    <x-adminlte-textarea name="isi_in_email" id="isi_in_email" rows=4 placeholder="Masukkan isi template untuk notifikasi In Email" required></x-adminlte-textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Tabel Daftar Notifikasi --}}
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Daftar Template Notifikasi</h3>
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Notifikasi</th>
                        <th>Jenis Notifikasi</th>
                        <th>Template In Apps</th>
                        <th>Template In Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($notifikasis as $key => $notifikasi)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $notifikasi->judul_notifikasi }}</td>
                    <td>{{ ucfirst($notifikasi->jenis_notifikasi) }}</td>
                    <td>{{ Str::limit($notifikasi->isi_in_apps, 50) }}</td>
                    <td>{{ Str::limit($notifikasi->isi_in_email, 50) }}</td>
                    <td>
                        @include('NotificationAndReminder.views.modals.edit-notifikasi', ['notifikasi' => $notifikasi])

                        <!-- Tombol untuk menampilkan alert konfirmasi -->
                        <button type="button" class="btn btn-danger btn-sm" onclick="$('#alert-delete-{{ $notifikasi->id_template_notifikasi }}').show()">Hapus</button>

                        <!-- AdminLTE Alert untuk konfirmasi hapus -->
                        <div id="alert-delete-{{ $notifikasi->id_template_notifikasi }}" class="alert-hapus" style="display: none;">
                            <x-adminlte-alert theme="warning" title="Konfirmasi Hapus" dismissable>
                                <p>Apakah Anda yakin ingin menghapus template notifikasi ini?</p>
                                <div class="d-flex justify-content-end">
                                    <form action="{{ route('notifikasi.delete', $notifikasi->id_template_notifikasi) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm mr-2">Iya, Hapus</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="$('#alert-delete-{{ $notifikasi->id_template_notifikasi }}').hide()">Batal</button>
                                </div>
                            </x-adminlte-alert>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .alert-dismissible {
            position: relative;
        }
        .alert-hapus {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            z-index: 1050; /* Pastikan di atas elemen lain */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Menangani klik pada ikon lonceng
            $('#notificationBell').on('click', function() {
                $('#myModal').modal('show');
            });

            // Klik ikon pengaturan untuk membuka modal preferensi
            $('#openPreferences').on('click', function(e) {
                e.preventDefault();
                $('#myModal').modal('hide');
                $('#myModals').modal('show');
            });
        });
    </script>
@stop
