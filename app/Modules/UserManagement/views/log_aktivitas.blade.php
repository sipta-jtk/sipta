@extends('adminlte::page')

@section('title', 'Log Aktivitas')

@section('content')
<div class="container">
    <h1 class="mb-4">Log Aktivitas - Login</h1>

    {{-- Filter --}}
    <div class="mb-4">
        <form method="GET" action="{{ route('log-aktivitas.index') }}" class="flex items-center gap-2">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Nama" 
                class="border rounded p-2"
            >
            <button type="submit" class="bg-blue-500 text-white rounded px-4 py-2">Search</button>
            <a href="{{ route('log-aktivitas.index') }}" class="bg-gray-500 text-white rounded px-4 py-2">Clear</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">NIP</th>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">IP Address</th>
                    <th class="border px-4 py-2">Timestamp</th>
                    <th class="border px-4 py-2">Status Aktif</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logins as $login)
                    <tr>
                        <td class="border px-4 py-2">{{ $login->user->username }}</td>
                        <td class="border px-4 py-2">{{ $login->user->nama }}</td>
                        <td class="border px-4 py-2">{{ $login->ip_address }}</td>
                        <td class="border px-4 py-2">{{ $login->waktu_login->format('d-m-Y H:i') }}</td> <!-- Tanggal dan jam -->
                        <td class="border px-4 py-2">
                            @php
                                $diffMinutes = now()->diffInMinutes($login->waktu_login);
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
                        <td colspan="5" class="border px-4 py-2 text-center">Tidak ada data login</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@stop
