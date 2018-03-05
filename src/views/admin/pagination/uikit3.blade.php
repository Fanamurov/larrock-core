@if ($paginator->hasPages())
    <ul class="uk-pagination uk-flex-right">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="uk-disabled"><span class="uk-button uk-button-default uk-button-small">&laquo;</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev"><span class="uk-button uk-button-default uk-button-small">&laquo;</span></a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="uk-disabled"><span class="uk-button uk-button-default uk-button-small">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="uk-active"><span class="uk-button uk-button-default uk-button-small">{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}"><span class="uk-button uk-button-primary uk-button-small">{{ $page }}</span></a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><span class="uk-button uk-button-default uk-button-small"><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></span></li>
        @else
            <li class="uk-disabled"><span class="uk-button uk-button-default uk-button-small">&raquo;</span></li>
        @endif
    </ul>
@endif
