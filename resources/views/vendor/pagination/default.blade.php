@if ($paginator->hasPages())
    <ul class="artshop-pagination d-none d-sm-block">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>&lt;</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lt;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&gt;</a></li>
        @else
            <li class="disabled"><span>&gt;</span></li>
        @endif
    </ul>

    <ul class="artshop-pagination d-sm-none">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>&lt; PREV</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lt; PREV</a></li>
        @endif
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">NEXT &gt;</a></li>
        @else
            <li class="disabled"><span>NEXT &gt;</span></li>
        @endif
    </ul>

@endif
