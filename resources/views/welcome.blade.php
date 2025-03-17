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
            $('#notificationBell').on('click', function() {
                $('#myModal').modal('show');
            });
            $('#openPreferences').on('click', function(e) {
                e.preventDefault();  
                $('#myModal').modal('hide');  
                $('#myModals').modal('show');  
            });
        });
    </script>
    
@stop