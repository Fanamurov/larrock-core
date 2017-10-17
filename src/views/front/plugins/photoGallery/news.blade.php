<div class="gallery-news uk-grid-small">
    @foreach($images as $image)
        <div class="gallery-news-item @if($loop->first) uk-width-1-1 @else uk-width-1-3 uk-width-medium-1-5 @endif">
            <a data-fancybox="fancybox-{{ $image->getCustomProperty('gallery') }}"
               href="{{ $image->getUrl() }}" title="{{ $image->getCustomProperty('alt', 'фото') }}">
                <img src="@if($loop->first) {{ $image->getUrl() }} @else {{ $image->getUrl('140x140') }} @endif" alt="{{ $image->getCustomProperty('alt', 'фото') }}" class="max-width">
            </a>
        </div>
    @endforeach
</div>