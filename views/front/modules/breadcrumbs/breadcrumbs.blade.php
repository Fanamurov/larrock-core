@if ($breadcrumbs)
    <ul class="uk-breadcrumb" data-count="{{ $count = count($breadcrumbs) }}">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->url && !$loop->last)
                <li><h2><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></h2></li>
            @else
                <li class="uk-active"><h1>{{ $breadcrumb->title }}</h1></li>
            @endif
        @endforeach
    </ul>
@endif
