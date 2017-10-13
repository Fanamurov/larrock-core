<div class="larrock-gallery gallery-newsDescription uk-grid uk-grid-width-small-1-2 uk-grid-width-large-1-4">
    @foreach($images as $image)
        <div class="gallery-newsDescription-item uk-text-center uk-margin-large-bottom">
            <a class="fancybox" rel="fancybox-{{ $image->getCustomProperty('gallery') }}"
               href="{{ $image->getUrl() }}" title="{{ strip_tags($image->getCustomProperty('alt', 'фото')) }}">
                <img src="{{ $image->getUrl() }}" alt="{{ $image->getCustomProperty('alt', 'фото') }}" class="max-width">
            </a>
            <a class="fancybox uk-margin-top uk-display-inline-block" rel="fancybox-{{ $image->getCustomProperty('gallery') }}_link"
               href="{{ $image->getUrl() }}" title="{{ strip_tags($image->getCustomProperty('alt', 'фото')) }}">
                {!! $image->getCustomProperty('alt') !!}
            </a>
        </div>
    @endforeach
</div>
<div class="uk-clearfix"></div>