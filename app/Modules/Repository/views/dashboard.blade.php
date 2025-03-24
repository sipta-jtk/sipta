@extends('adminlte::page')

@section('title', 'RepositoryTA')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>RepositoryTA</h1>
    <span class="text-muted">
        {{ $kota->nama_kota ?? 'Kota Tidak Diketahui' }} - {{ $kota->id_kota }}
    </span>
</div>
@stop

@section('content')
<table class="table table-hover">
    <tbody>
        @foreach($data as $kategori => $subkategori)
            <tr data-widget="expandable-table" aria-expanded="false">
                <td>
                    <strong><i class="expandable-table-caret fas fa-caret-right fa-fw"></i> {{ $kategori }}</strong>
                </td>
            </tr>
            <tr class="expandable-body">
                <td>
                    <div class="p-0">
                        <table class="table table-hover">
                            <tbody>
                                @foreach($subkategori as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ $item['url'] }}" style="text-decoration: none; color: black;">
                                                {{ $item['label'] }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{--
<link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop