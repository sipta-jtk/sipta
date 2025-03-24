@extends('adminlte::page')

@section('title', 'Detail Plagiarism Check')

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Detail Plagiarism Check</h1>
    <nav aria-label="breadcrumb" class="me-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/cek-plagiarisme') }}">Plagiarism Checking</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Laporan</li>
        </ol>
    </nav>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div>
        <form id="form2-ws" action="/cek-plagiarisme/cek-tugas-akhir" enctype="multipart/form-data" method="POST">
            @csrf
            <input id="id_docfile" required type="file" name="docfile" />
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Tampilkan hasil jika ada -->
    @if(isset($percent) && isset($link))
    <div class="mt-4">
        <h3>Hasil Pengecekan:</h3>
        <p><strong>Persentase Plagiarisme:</strong> {{ $percent }}%</p>
        <p><strong>Referensi:</strong></p>
        <pre>{{ print_r($link, true) }}</pre>
    </div>
    @endif

    <!-- Menampilkan error jika terjadi masalah -->
    @if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div> 
@stop
