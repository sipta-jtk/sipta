@extends('adminlte::page')

@section('title', 'RepositoryTA')

@section('content_header')
@php
$prefix = env('PREFIX_URL', ''); // Tarik prefix dari env
@endphp
@foreach($data as $kategori => $subkategori)
<div class="d-flex justify-content-between align-items-center">
    <h1>RepositoryTA</h1>
    <span class="text-muted">
        {{ $kota->nama_kota ?? 'Kota Tidak Diketahui' }} - {{ $kota->id_kota }}
    </span>
</div>
<nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ url($prefix . '/') }}">Beranda</a>
            </li>
           
            <li class="breadcrumb-item active" aria-current="page">
                @if ($kategori === 'seminar1')
                Dokumen Seminar 1
                @elseif ($kategori === 'seminar2')
                Dokumen Seminar 2
                @elseif ($kategori === 'seminar3')
                Dokumen Seminar 3
                @elseif ($kategori === 'sidang')
                Dokumen Sidang Akhir
                @elseif ($kategori === 'yudisium')
                Dokumen Yudisium
                @else
                Repository
                @endif
            </li>
        </ol>
    </nav>
@stop

@section('content')
<div class="px-4"> {{-- padding kiri-kanan agar tidak terlalu mepet --}}

    <div class="mb-4">
        <h5 class="mb-4"><i class="fas fa-folder-open mr-2"></i>{{ $kategori }}</h5>

        @php
            $chunks = array_chunk($subkategori, 3);
        @endphp

        {{-- Baris pertama --}}
        <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap" style="gap: 1.5rem;">
            @foreach($chunks[0] as $item)
            <a href="{{ $item['url'] }}" class="text-decoration-none text-dark" style="width: 320px;">
                <div class="rounded-pill border border-secondary shadow-sm">
                    <div class="text-center py-3">
                        <strong>{{ $item['label'] }}</strong>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Baris kedua --}}
        @if(isset($chunks[1]))
        <div class="d-flex justify-content-center gap-3 flex-wrap"  style="gap: 1.5rem;">
            @foreach($chunks[1] as $item)
            <a href="{{ $item['url'] }}" class="text-decoration-none text-dark" style="width: 320px;">
                <div class="rounded-pill border border-secondary shadow-sm ">
                    <div class="text-center py-3">
                        <strong>{{ $item['label'] }}</strong>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
    @endforeach
</div>
@stop


@section('css')
<style>
    /* Normal state */
    a .rounded-pill {
        transition: all 0.2s ease-in-out;
        background-color: #f5f6f7;
        color: #343a40; /* text-dark */
    }

    /* Hover state */
    a:hover .rounded-pill {
        background-color: #007bff !important; /* Bootstrap primary */
        border-color: #007bff;
        color: white;
        transform: scale(1.02);
    }

    a:hover .rounded-pill strong {
        color: white;
    }
</style>
@stop



@section('js')
<script>
    console.log("Hi, I'm using the Laravel-AdminLTE package!");
</script>
@stop