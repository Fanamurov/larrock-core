@if ($paginator->hasPages())
    <ul class="uk-pagination uk-pagination-right">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="uk-disabled"><span>&laquo;</span></li>
        @else
            <li class="uk-pagination-previous"><a href="{{ $paginator->previousPageUrl() }}">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="uk-disabled"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="uk-active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="uk-pagination-next"><a href="{{ $paginator->nextPageUrl() }}">&raquo;</a></li>
        @else
            <li class="uk-disabled"><span>&raquo;</span></li>
        @endif
    </ul>
@endif
