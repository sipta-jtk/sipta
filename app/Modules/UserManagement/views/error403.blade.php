@extends('adminlte::page')

@section('title', 'Error 403 : Forbidden')

@section('content_header')
<h1></h1>
@stop

@section('content')
<div class="error-page text-center mt-5">
    <h2 class="headline text-warning"> 403</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Akses Dilarang</h3>
        <p>
            Anda tidak memiliki izin untuk mengakses halaman ini.<br>
            Silakan kembali ke <a href="/sipta">halaman utama</a> atau hubungi administrator.
        </p>
                <img src="{{ asset('error-image/error-403.png') }}" alt="Error 403" class="img-fluid mt-3" style="max-width: 400px;">

    </div>
</div>
@endsection

{{--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body class="hold-transition sidebar-mini">

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html> --}}