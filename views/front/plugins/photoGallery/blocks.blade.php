<div class="gallery-blocks uk-grid">
    @foreach($images as $image)
        <div class="gallery-news-item uk-width-1-2 uk-width-medium-1-3 uk-width-large-1-4">
            <a data-fancybox="fancybox-{{ $image->getCustomProperty('gallery') }}"
               href="{{ $image->getUrl() }}" title="{{ $image->getCustomProperty('alt', 'фото') }}">
                <img src="{{ $image->getUrl('140x140') }}" alt="{{ $image->getCustomProperty('alt', 'фото') }}" class="max-width">
            </a>
        </div>
    @endforeach
</div>