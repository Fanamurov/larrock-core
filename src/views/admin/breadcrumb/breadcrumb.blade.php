@if ($breadcrumbs)
    <ol class="breadcrumb" data-count="{{ $count = count($breadcrumbs) }}">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->url && !$loop->last)
                <li><h2><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></h2></li>
            @else
                <li class="active"><h1>{{ $breadcrumb->title }}</h1></li>
            @endif
        @endforeach
    </ol>

    @if(isset(collect($breadcrumbs)->last()->current_level))
        <div class="uk-button-dropdown dropdown-breadcrumbs" data-uk-dropdown="{mode: 'click'}">
            <button class="uk-button"><i class="uk-icon-caret-down"></i></button>
            <div class="uk-dropdown uk-dropdown-bottom">
                <ul class="uk-nav uk-nav-dropdown">
                    <li class="uk-text-center uk-text-bold">15 материалов раздела:</li>
                    <li class="uk-nav-divider"></li>
                    @foreach(collect($breadcrumbs)->last()->current_level as $item)
                        <li><a href="/admin/{{ $app->name }}/{{ $item->id }}/edit">{{ $item->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@endif
