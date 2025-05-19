@extends('adminlte::page')

@section('title', 'Repository Mahasiswa - ' . $mahasiswa->nama)

@section('content_header')
    <h1>Repository Tugas Akhir - {{ $mahasiswa->nama }}</h1>
@stop

@section('content')
<div class="container">
    <h4>Dokumen yang telah diunggah oleh mahasiswa {{ $mahasiswa->nama }}</h4>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Versi</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kategoriDokumen as $kategori)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ strtoupper($kategori) }}</td>
                    <td>
                        @foreach ($dokumen->where('kategori', $kategori) as $doc)
                            <a href="{{ route('Repository.index', ['kategori' => $kategori]) }}?nim={{ $mahasiswa->nim }}">
                                {{ $doc->judul }}
                            </a>
                            <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($dokumen->where('kategori', $kategori) as $doc)
                            V{{ $doc->versi }}
                        @endforeach
                    </td>
                    <td>
                        @foreach ($dokumen->where('kategori', $kategori) as $doc)
                            <a href="{{ route('Repository.mahasiswa.download', [$mahasiswa->nim, $kategori, $doc->id_dokumen]) }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-download"></i> Download
                            </a>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-start mb-3">
        <a href="{{ route('Repository.list_kelompok_ta') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke List Kelompok TA
        </a>
    </div>
</div>
@stop

@section('css')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
</style>
@stop
