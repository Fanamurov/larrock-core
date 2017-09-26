<div class="file-gallery-default">
    @foreach($files as $file)
        <div class="file-gallery-default-item">
            <a href="{{ $file->getUrl() }}">
                @if( !empty($file->getCustomProperty('alt')))
                    <span>{{ $file->getCustomProperty('alt') }}</span>
                @else
                    <span>{{ $file->file_name }}</span>
                @endif
            </a>
        </div>
    @endforeach
</div>
<div class="uk-clearfix"></div>