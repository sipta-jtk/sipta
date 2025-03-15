@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@section('adminlte_css')
@stack('css')
@yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            // title: 'Success',
            title: "{{ session('success') }}",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    });
</script>
@endif
@if (session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            // title: 'Error',
            title: "{{ session('error') }}",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    });
</script>
@endif
@if ($errors->any())
@php
$errorList = '<ul>';
    foreach ($errors->all() as $error) {
    $errorList .= '<li>' . $error . '</li>';
    }
    $errorList .= '</ul>';
@endphp
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            // title: 'Kesalahan Validasi',
            title: `{!! $errorList !!}`,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 7000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    });
</script>
@endif
<div class="wrapper">

    {{-- Preloader Animation (fullscreen mode) --}}
    @if($preloaderHelper->isPreloaderEnabled())
    @include('adminlte::partials.common.preloader')
    @endif

    {{-- Top Navbar --}}
    @if($layoutHelper->isLayoutTopnavEnabled())
    @include('adminlte::partials.navbar.navbar-layout-topnav')
    @else
    @include('adminlte::partials.navbar.navbar')
    @endif

    {{-- Left Main Sidebar --}}
    @if(!$layoutHelper->isLayoutTopnavEnabled())
    @include('adminlte::partials.sidebar.left-sidebar')
    @endif

    {{-- Content Wrapper --}}
    @empty($iFrameEnabled)
    @include('adminlte::partials.cwrapper.cwrapper-default')
    @else
    @include('adminlte::partials.cwrapper.cwrapper-iframe')
    @endempty

    {{-- Footer --}}
    @hasSection('footer')
    @include('adminlte::partials.footer.footer')
    @endif

    {{-- Right Control Sidebar --}}
    @if($layoutHelper->isRightSidebarEnabled())
    @include('adminlte::partials.sidebar.right-sidebar')
    @endif

</div>
@stop

@section('adminlte_js')
@stack('js')
@yield('js')
@stop