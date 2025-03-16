@extends('adminlte::page')

@section('title', 'Daftar Program Studi')

@section('content_header')
    <h1>Program Studi</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Program Studi</h3>
        <div class="card-tools">
            <a href="{{ route('program-studi.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
    </div> 
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="col-1 text-center">No</th>
                    <th>Nama Prodi</th>
                    <th>Ketua Prodi</th>
                    <th class="col-2 text-center">Maksimal Mahasiswa Bimbingan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($programStudi as $index => $prodi)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $prodi->nama_prodi }}</td>
                        <td>{{ $prodi->ketua_prodi ?? '-' }}</td>
                        <td class="text-center">{{ $prodi->maksimal_mahasiswa_bimbingan }}</td>
                        <td class="text-center"> 
                            <a href="{{ route('program-studi.edit', $prodi->id_prodi) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('program-studi.destroy', $prodi->id_prodi) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
