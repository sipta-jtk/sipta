@extends('adminlte::page')

@section('title', 'SettingNotification')

@section('content_header')
    <h1>Pengaturan Notifikasi</h1>
    @include('NotificationAndReminder::modals.log-modal')
    @include('NotificationAndReminder::modals.preferences-modal')
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
                    <label for="judul">Judul Notifikasi</label>
                    <x-adminlte-input name="judul" id="judul" placeholder="Masukkan judul notifikasi" required />
                </div>
                <div class="form-group">
                    <label for="isi">Pesan Notifikasi</label>
                    <x-adminlte-textarea name="isi" id="isi" rows=4 placeholder="Masukkan isi notifikasi" required></x-adminlte-textarea>
                </div>
                <div class="form-group">
                    <label for="trigger">Trigger</label>
                    <x-adminlte-input name="trigger" id="trigger" placeholder="Masukkan trigger notifikasi" required />
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
                        <th>Isi Notifikasi</th>
                        <th>Trigger</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
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
