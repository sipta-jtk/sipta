@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to sipta.</p>
    <!-- Include modal preferences-modal.blade.php -->
    @include('NotificationAndReminder::modals.log-modal')
    @include('NotificationAndReminder::modals.preferences-modal')
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Menangani klik pada ikon lonceng
            $('#notificationBell').on('click', function() {
                // Tampilkan modal log saat ikon lonceng diklik
                $('#myModal').modal('show');
            });

            // Klik ikon pengaturan untuk membuka modal preferensi
            $('#openPreferences').on('click', function(e) {
                e.preventDefault();  // Menghindari aksi default
                $('#myModal').modal('hide');  // Menutup modal log
                $('#myModals').modal('show');  // Menampilkan modal preferensi
            });
        });
    </script>
    
@stop