<li @if(str_contains(\URL::current(), $url)) class="uk-active" @endif title="{{ $app->description }}">
    <a href="{{ $url }}">{{ $app->title }} @if(isset($count))<span class="badge">[{{ $count }}]</span>@endif</a>
</li>