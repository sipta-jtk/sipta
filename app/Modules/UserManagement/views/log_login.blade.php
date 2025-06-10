@extends('adminlte::page')

@section('title', 'Log Login')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold">Daftar Log Login</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th>ID (NIP/NIM)</th>
                            <th>Nama</th>
                            <th>IP Address</th>
                            <th>Login Time</th>
                            <th>Logout Time</th>
                            <th>Durasi Online</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logLogin as $log)
                            <tr>
                                <td>{{ $log->user->username ?? '-' }}</td>
                                <td>{{ $log->user->nama ?? '-' }}</td>
                                <td>{{ $log->ip_address ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->waktu_aktivitas)->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($log->status === 'online')
                                        <span class="badge badge-success">Masih Online</span>
                                    @else
                                        {{ $log->waktu_logout ? \Carbon\Carbon::parse($log->waktu_logout)->format('Y-m-d H:i') : '-' }}
                                    @endif
                                </td>
                                <td>{{ $log->durasi_login }}</td>
                                <td>
                                    @if($log->status === 'online')
                                        <span class="badge badge-success">{{ $log->status_aktif }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $log->status_aktif }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data aktivitas ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $logLogin->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
