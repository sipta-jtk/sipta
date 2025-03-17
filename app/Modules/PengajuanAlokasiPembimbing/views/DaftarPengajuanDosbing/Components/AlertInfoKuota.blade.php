@php
$alertClasses = [
'success' => 'alert alert-success',
'error' => 'alert alert-danger',
'warning' => 'alert alert-warning',
'info' => 'alert alert-info',
];
@endphp

<div class="{{ $alertClasses[$type] ?? 'alert alert-info' }}" role="alert">
    {!! $message !!}
</div>
