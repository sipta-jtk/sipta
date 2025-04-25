<!-- Modules/KelolaPenilaianTA/views/components/breadcrumb.blade.php -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent p-0 mb-3">
        @foreach ($links as $link)
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $link['label'] }}</li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>