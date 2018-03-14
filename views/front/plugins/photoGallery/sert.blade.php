<div class="larrock-gallery gallery-sert uk-grid uk-grid-width-small-1-2 uk-grid-width-medium-1-3">
    @foreach($images as $image)
        <div class="gallery-sert-item uk-margin-large-bottom">
            <div class="uk-grid uk-grid-small">
                <div class="uk-width-1-1 uk-width-large-1-2">
                    <a data-fancybox="fancybox-{{ $image->getCustomProperty('gallery') }}"
                       href="{{ $image->getUrl() }}" title="{{ strip_tags($image->getCustomProperty('alt', 'фото')) }}">
                        <img src="{{ $image->getUrl() }}" alt="{{ $image->getCustomProperty('alt', 'фото') }}" class="max-width">
                    </a>
                </div>
                <div class="uk-width-1-1 uk-width-large-1-2">
                    <a class="uk-margin-top uk-display-inline-block" data-fancybox="2fancybox-{{ $image->getCustomProperty('gallery') }}"
                       href="{{ $image->getUrl() }}" title="{{ strip_tags($image->getCustomProperty('alt', 'фото')) }}">
                        {!! $image->getCustomProperty('alt') !!}
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="uk-clearfix"></div>