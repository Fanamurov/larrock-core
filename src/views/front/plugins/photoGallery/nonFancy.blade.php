<div class="gallery-nonFancy uk-grid-small">
    @foreach($images as $image)
        <div class="gallery-nonFancy-item @if($loop->first) uk-width-1-1 @else uk-width-1-3 uk-width-medium-1-5 @endif">
            <img src="@if($loop->first) {{ $image->getUrl() }} @else {{ $image->getUrl('140x140') }} @endif" alt="{{ $image->getCustomProperty('alt', 'фото') }}" class="max-width">
        </div>
    @endforeach
</div>