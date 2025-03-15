@extends('adminlte::page')

@section('title', '403 Forbidden')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="error-page text-center justify-content-center mt-5">
        <h2 class="headline text-warning">403</h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Access Forbidden</h3>
            <p>
                Anda tidak memiliki izin untuk mengakses halaman ini.<br>
                Silakan kembali ke <a href="/">halaman utama</a> atau hubungi administrator.
            </p>
        </div>
    </div>
@stop

@section('css')
    {{-- Tambahkan CSS tambahan di sini jika diperlukan --}}
@stop

@section('js')
    <script> console.log("403 Forbidden Page Loaded"); </script>
@stop