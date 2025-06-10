@extends('adminlte::page')

@section('title', 'Log Aktivitas')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold">Daftar Log Aktivitas</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID (NIP/NIM)</th>
                            <th>Nama</th>
                            <th>IP Address</th>
                            <th>Timestamp</th>
                            <th>Status Aktif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logAktivitas as $log)
                            <tr>
                                <td>{{ $log->id ?? '-' }}</td>
                                <td>{{ $log->nama ?? '-' }}</td>
                                <td>{{ $log->ip_address ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->timestamp)->format('Y-m-d H:i') }}</td>
                                <td>
                                    @php
                                        $diffMinutes = \Carbon\Carbon::parse($log->timestamp)->diffInMinutes(now());
                                        if ($diffMinutes < 60) {
                                            echo $diffMinutes . ' menit yang lalu';
                                        } elseif ($diffMinutes < 1440) {
                                            echo round($diffMinutes / 60) . ' jam yang lalu';
                                        } else {
                                            echo round($diffMinutes / 1440) . ' hari yang lalu';
                                        }
                                    @endphp
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data aktivitas ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $logAktivitas->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
