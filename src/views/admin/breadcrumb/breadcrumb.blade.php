@if ($breadcrumbs)
    <ul class="uk-breadcrumb" data-count="{{ $count = count($breadcrumbs) }}">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->url && !$loop->last)
                <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="uk-active"><p>{{ $breadcrumb->title }}</p></li>
            @endif
        @endforeach
    </ul>

    @if(isset(collect($breadcrumbs)->last()->current_level))
        <span class="uk-inline uk-breadcrumb-more">
            <button class="uk-button uk-button-small uk-button-default" type="button"><span uk-icon="list"></span></button>
            <div uk-dropdown class="uk-dropdown">
                <ul class="uk-nav uk-nav-default">
                    @foreach(collect($breadcrumbs)->last()->current_level as $item)
                        <li><a href="/admin/{{ $app->name }}/{{ $item->id }}/edit">{{ $item->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </span>
    @endif
@endif
