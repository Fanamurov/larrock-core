<div class="file-gallery-default">
    @foreach($files as $file)
        <div class="file-gallery-default-item">
            <a href="{{ $file->getUrl() }}">
                @if( !empty($file->getCustomProperty('alt')))
                    <img src="/_assets/_front/_images/icons/icon_docs_64.png" alt="file attach"><span>{{ $file->getCustomProperty('alt') }}</span>
                @else
                    <img src="/_assets/_front/_images/icons/icon_docs_64.png" alt="file attach"><span>{{ $file->file_name }}</span>
                @endif
            </a>
        </div>
    @endforeach
</div>
<div class="uk-clearfix"></div>