@extends('adminlte::page')

@section('title', 'Form Cerai KoTA')

@section('content_header')
    <h1>Form Cerai KoTA</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @csrf
            
        <!-- Unggah FTA 20 -->
        <div class="mb-3">
            <label class="form-label">Unggah FTA 20 (PDF)</label>
            <input type="file" class="form-control" name="fta_20" accept=".pdf" required readonly>
        </div>

        <!-- Nama Mahasiswa -->
        <div class="mb-3">
            <label class="form-label">Nama Mahasiswa</label>
            <input type="text" class="form-control" value="{{ $item->nama }}" readonly>
        </div>

        <!-- Info KoTA -->

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Judul TA</label>
                <input type="text" class="form-control" value="{{ $item->judul_ta }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tahun TA</label>
                <input type="text" class="form-control" value="{{ $item->tahun_ta }}" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nama KoTA</label>
                <input type="text" class="form-control" value="{{ $item->kelompok_ta }}" readonly>
            </div>
        </div>


        <!-- Tombol Kembali -->
        <div class="d-flex justify-content-end">
            <a href="{{ route('pengajuan.cerai.kota') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-success mx-2">
                <i class="fas fa-check"></i> Terima Cerai
            </button>
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-times"></i> Ajukan Cerai
            </button>
        </div>
    </div>
</div>
@stop
