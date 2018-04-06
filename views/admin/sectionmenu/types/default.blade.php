<li @if(str_contains(\URL::current(), $url)) class="uk-active" @endif title="{{ $package->description }}">
    <a href="{{ $url }}">{{ $package->title }} @if(isset($count))<span class="count">[{{ $count }}]</span>@endif</a>
</li>