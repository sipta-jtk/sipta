@extends('adminlte::page')

@section('title', 'Kelola KBK')

@section('content_header')
    <h1>Daftar Kelompok Bidang Keahlian</h1>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <button class="btn btn-primary" data-toggle="modal" data-target="#addKBKModal">+ Add</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th class="text-left">Kelompok Bidang Keahlian</th>
                    <th style="width: 20%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kbkList as $index => $kbk)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $kbk->kbk }}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning" 
                            data-toggle="modal" 
                            data-target="#editKBKModal{{ $kbk->id_kbk }}">
                            <i class="fas fa-edit"></i>
                        </button>

                        <form action="{{ route('kelola-kbk.destroy', $kbk->id_kbk) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit KBK -->
                <div class="modal fade" id="editKBKModal{{ $kbk->id_kbk }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit KBK</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('kelola-kbk.update', $kbk->id_kbk) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Nama KBK</label>
                                        <input type="text" name="kbk" class="form-control" value="{{ $kbk->kbk }}">
                                    </div>
                                    <button type="submit" class="btn btn-success">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add KBK -->
<div class="modal fade" id="addKBKModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah KBK</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kelola-kbk.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama KBK</label>
                        <input type="text" name="kbk" class="form-control" placeholder="Masukkan nama KBK">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 700);
</script>

@stop
