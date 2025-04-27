@extends('adminlte::page')

@section('title')
@if ($kategori === 'seminar1')
Dokumen Seminar 1
@elseif ($kategori === 'seminar2')
Dokumen Seminar 2
@elseif ($kategori === 'seminar3')
Dokumen Seminar 3
@else
Daftar Dokumen
@endif
@stop

@section('content_header')
@php
$prefix = env('PREFIX_URL', ''); // Tarik prefix dari env
@endphp

<div class="mb-3">
    <h1 class="mb-2">
        @if ($kategori === 'seminar1')
        Dokumen Seminar 1
        @elseif ($kategori === 'seminar2')
        Dokumen Seminar 2
        @elseif ($kategori === 'seminar3')
        Dokumen Seminar 3
        @else
        Daftar Dokumen
        @endif
    </h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ url($prefix . '/') }}">Beranda</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url($prefix . '/repository/mahasiswa/kota/' . request()->route('id_kota')) }}">Repository</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                @if ($kategori === 'seminar1')
                Dokumen Seminar 1
                @elseif ($kategori === 'seminar2')
                Dokumen Seminar 2
                @elseif ($kategori === 'seminar3')
                Dokumen Seminar 3
                @else
                Daftar Dokumen
                @endif
            </li>
        </ol>
    </nav>
</div>
@stop

@section('content')
@if ($kategori === 'seminar1')
@include('Repository.views.cardSeminar1')
@elseif ($kategori === 'seminar2')
@include('Repository.views.seminar2')
@elseif ($kategori === 'seminar3')
@include('Repository.views.seminar3')
@endif
@stop

@section('css')
@stop

@section('js')
@stop