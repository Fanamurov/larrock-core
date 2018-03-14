<li @if(str_contains(\URL::current(), $url)) class="uk-active" @endif title="{{ $app->description }}">
    <a href="{{ $url }}">{{ $app->title }} @if(isset($count))<span class="count">[{{ $count }}]</span>@endif <span uk-icon="chevron-down"></span></a>
    <div class="uk-navbar-dropdown">
        <ul class="uk-nav uk-navbar-dropdown-nav">
            <li><a href="{{ $url }}" class="uk-text-bold">Все материалы</a></li>
            @foreach($dropdown->take(10) as $item)
                <li @if(\URL::current() === $url .'/'. $item->id) class="uk-active" @endif>
                    <a href="{{ $url }}/{{ $item->id }}@if($item->getTable() !== 'category'){!! '/edit' !!}@endif">{{ $item->title }}</a>
                </li>
            @endforeach
            @if(count($dropdown) > 10)
                <li><a href="{{ $url }}">...</a></li>
            @endif
            @if(isset($push) && count($push) > 0)
                <li class="uk-nav-header">Дополнительно</li>
                @foreach($push as $key => $item)
                    <li><a href="{{ $item }}">{{ $key }}</a></li>
                @endforeach
            @endif
        </ul>
    </div>
</li>