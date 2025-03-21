@extends('adminlte::page')

@section('title', 'Subkategori Artefak')

@section('content_header')
<h1 class="text-center">SUBKATEGORI ARTEFAK</h1>
@stop

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="search-box">
            <input type="text" class="form-control" id="searchInput" placeholder="Search here...">
        </div>
        <div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addSubkategoriModal">
                + Add Subkategori
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Subkategori</th>
            </tr>
        </thead>
        <tbody>
            @if ($subkategoris->isEmpty())
            <tr>
                <td colspan="2" class="text-center">
                    <p class="text-muted">List Subkategori Kosong, Belum Ada Subkategori Yang Ditambahkan</p>
                </td>
            </tr>
            @else
            @foreach ($subkategoris as $index => $subkategori)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $subkategori->nama_subkategori }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

    <div class="mt-3">
        <a href="{{ route('Repository.index', $kategori) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- Modal Tambah Subkategori -->
<div class="modal fade" id="addSubkategoriModal" tabindex="-1" aria-labelledby="addSubkategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubkategoriModalLabel">Tambah Subkategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Subkategori.store', $kategori) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_subkategori" class="form-label">Nama Subkategori:</label>
                        <input type="text" class="form-control" id="nama_subkategori" name="nama_subkategori" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Back</button>
                        <button type="submit" class="btn btn-dark">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection