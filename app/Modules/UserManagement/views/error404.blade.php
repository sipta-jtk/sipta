@extends('adminlte::page')

@section('title', 'Error 404 : Not Found')

@section('content_header')
<h1></h1>
@stop

@section('content')
<div class="error-page text-center mt-5">
    <h2 class="headline text-warning"> 404</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Not Found</h3>
        <p>
            Halaman yang anda cari tidak dapat ditemukan<br>
            Silakan kembali ke <a href="/sipta">halaman utama</a>
        </p>
                <img src="{{ url('storage/image/error-404.png') }}" alt="Error 403" class="img-fluid mt-3" style="max-width: 400px;">

    </div>
</div>
@endsection

