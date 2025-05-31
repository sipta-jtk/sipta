@extends('layouts.app') <!-- Sesuaikan jika layout kamu beda -->

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold">Daftar Log Aktivitas</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Alamat IP</th>
                            <th>Aktivitas</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logAktivitas as $log)
                            <tr>
                                <td>{{ $log->user->nip ?? '-' }}</td>
                                <td>{{ $log->user->nama ?? '-' }}</td>
                                <td>{{ $log->ip_address ?? '-' }}</td>
                                <td>{{ $log->aktivitas ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->waktu_aktivitas)->format('Y-m-d \p\a\d\a H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data aktivitas ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
