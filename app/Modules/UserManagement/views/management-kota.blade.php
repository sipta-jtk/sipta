@extends('adminlte::page')

@section('title', 'Kelompok Tugas Akhir')

@section('content_header')
    <h1>Kelompok Tugas Akhir</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kelompok Tugas Akhir</h3>
    </div>
    <div class="card-body my-4">
        <table class="table table-bordered table-hover" id="tabel-kota">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kelompok TA</th>
                    <th>Tahun</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelompokList as $index => $kelompok)
                <tr class="clickable-row" data-href="{{ route('detail.kota', ['id' => $kelompok->id_kota]) }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $kelompok->nama_kota }}</td>
                    <td>{{ $kelompok->tahun_kota }}</td>
                    <td>
                    @switch($kelompok->status_kota)
                            @case('pra_kota')
                                <span class="badge badge-warning">Pra-KoTA</span>
                                @break
                            @case('aktif')
                                <span class="badge badge-success">Aktif</span>
                                @break
                            @case('lulus')
                                <span class="badge badge-primary">Lulus</span>
                                @break
                            @case('bubar')
                                <span class="badge badge-danger">Bubar</span>
                                @break
                            @default
                                <span class="badge badge-secondary">{{ $kelompok->status_kota }}</span>
                        @endswitch
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
<style>
    .clickable-row {
        cursor: pointer;
    }
    .clickable-row:hover {
        background-color: rgba(0, 0, 0, .075);
    }
</style>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() 
    {
        // Menangani klik pada baris tabel
        $(".clickable-row").click(function() 
        {
            let targetUrl = $(this).data("href");
            if (targetUrl) 
            {
                window.location = targetUrl;
            }
        });
    });
</script>
@stop