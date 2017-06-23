<div class="gallery-news gallery-sert row">
    @foreach($images as $image)
        <div class="gallery-news-item gallery-sert-item col-xs-12">
            <div class="row">
                <div class="col-xs-8">
                    <a class="fancybox" rel="fancybox-{{ $image->getCustomProperty('gallery') }}"
                       href="{{ $image->getUrl() }}" title="{{ $image->getCustomProperty('alt', 'фото') }}">
                        <img src="{{ $image->getUrl() }}" alt="{{ $image->getCustomProperty('alt', 'фото') }}" class="max-width">
                    </a>
                </div>
                <div class="col-xs-16">
                    <a class="fancybox" rel="fancybox-{{ $image->getCustomProperty('gallery') }}"
                       href="{{ $image->getUrl() }}" title="{{ $image->getCustomProperty('alt', 'фото') }}">
                        {{ $image->getCustomProperty('alt') }}
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="clearfix"></div>