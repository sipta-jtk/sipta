<div class="container-fluid d-flex justify-content-center mb-3 p-0">
    @for ($i = 1; $i <= $number; $i++)
        @if ($i == $active)
            <a href="{{ $hrefs[$i - 1] }}" type="button"
                class="btn btn-{{ $activeColor }} rounded-circle pt-1 pb-1">{{ $i }}</a>
        @else
            <a href="{{ $hrefs[$i - 1] }}" type="button"
                class="btn btn-{{ $inactiveColor }} rounded-circle pt-1 pb-1">{{ $i }}</a>
        @endif
        @if ($i != $number)
            <div class="w-25" style="height: 1px; background-color: gray; align-self: center;"></div>
        @endif
    @endfor
</div>
