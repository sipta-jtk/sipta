@extends('adminlte::page')

@section('title', 'Log Notifikasi Admin')

@section('content_header')
    <h1>Log Notifikasi Admin</h1>
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
                    {{-- Iterasi data dan tambahkan ke tabel --}}
                    @foreach($logNotifikasi as $index => $notif)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $notif->waktu_kirim }}</td>
                            <td>{{ $notif->judul }}</td>
                            <td>{{ $notif->isi_notifikasi }}</td>
                            <td>{{ $notif->user ? $notif->user->nama : 'undefined' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
