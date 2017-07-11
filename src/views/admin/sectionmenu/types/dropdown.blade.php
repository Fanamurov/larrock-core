<li class="uk-parent @if(str_contains(\URL::current(), $url)) uk-active @endif"
    data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
    <a href="{{ $url }}">{{ $app->title }} @if(isset($count))<span class="badge">[{{ $count }}]</span>@endif <i class="uk-icon-caret-down"></i></a>

    <div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom @if(str_contains(\URL::current(), $url)) active @endif"
         aria-hidden="true" style="top: 40px; left: 0px;" tabindex="">
        <ul class="uk-nav uk-nav-navbar">
            <li><a href="{{ $url }}">Все материалы</a></li>
            @foreach($dropdown->take(10) as $item)
                <li @if(\URL::current() === $url .'/'. $item->id) class="uk-active" @endif><a href="{{ $url }}/{{ $item->id }}@if($item->getTable() !== 'category'){!! '/edit' !!}@endif">{{ $item->title }}</a></li>
            @endforeach
            @if(count($dropdown) > 10)
                <li><a href="{{ $url }}">...</a></li>
            @endif
            @if(isset($push))
                <li class="uk-nav-header">Дополнительно</li>
                @foreach($push as $key => $item)
                    <li><a href="{{ $item }}">{{ $key }}</a></li>
                @endforeach
            @endif
        </ul>
    </div>
</li>