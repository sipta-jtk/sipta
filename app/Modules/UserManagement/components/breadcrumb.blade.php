<ol class="breadcrumb">
    @foreach ($links as $link)
        @if ($link['url'])
            <li class="breadcrumb-item">
                <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
            </li>
        @else
            <li class="breadcrumb-item active" aria-current="page">{{ $link['label'] }}</li>
        @endif
    @endforeach
</ol>
