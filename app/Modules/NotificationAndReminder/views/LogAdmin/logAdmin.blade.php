@extends('adminlte::page')

@section('title', 'Log Notifikasi Admin')

@section('content_header')
    <h1>Log Notifikasi Admin</h1>
    @include('NotificationAndReminder::modals.log-modal')
    @include('NotificationAndReminder::modals.preferences-modal')
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Log Notifikasi Admin</h3>
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
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
                    {{-- Data Log Notifikasi akan diisi dengan jQuery --}}
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.get('/api/notifications', function(data) {
                $('tbody').empty();

                // Iterasi data dan tambahkan ke tabel
                data.forEach(function(notif, index) {
                    let row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${notif.created_at}</td>
                            <td>${notif.judul}</td>
                            <td>${notif.isi_notifikasi}</td>
                            <td>${notif.username}</td> 
                        </tr>
                    `;
                    $('tbody').append(row);
                });
            });
        });
    </script>
@stop
