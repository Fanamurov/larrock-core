<nav class="uk-navbar">
    <a href="/admin" class="uk-navbar-brand">
        <img src="/_assets/_admin/_images/hand-with-white-outline-forming-a-rock-on-symbol.png"> L!ROCK
    </a>
    <ul class="uk-navbar-nav uk-hidden-small">
        @foreach($menu as $item)
            {!! $item !!}
        @endforeach
        <li class="uk-parent" data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
            <a href="#">Прочее <i class="uk-icon-caret-down"></i></a>

            <div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" aria-hidden="true" style="top: 40px; left: 0px;">
                <ul class="uk-nav uk-nav-navbar">
                    @foreach($menu_other as $item)
                        {!! $item !!}
                    @endforeach
                    <li class="uk-nav-header">Система</li>
                    @if(file_exists(public_path(). '/external/adminer.php'))
                        <li><a target="_blank" href="/external/adminer.php">DB</a></li>
                    @endif
                    <li>
                        <a href="#" onclick="clear_cache(); return false">Очистить кэш</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    @if(isset($searchSiteAdmin))
        <div class="uk-navbar-content uk-hidden-small">
            {!! $searchSiteAdmin !!}
        </div>
    @endif
    <div class="uk-navbar-content uk-navbar-flip uk-text-right">
        <a href="/" target="_blank" class="uk-button">К сайту</a>
        <a class="uk-button" href="{{ url('/logout') }}">Выйти</a>
    </div>

    <a href="#my-id" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
    <div id="my-id" class="uk-offcanvas">
        <div class="uk-offcanvas-bar">
            <form class="uk-form uk-margin uk-margin-top uk-margin-left uk-margin-right" action="/admin/search" method="get">
                <input type="text" class="uk-form-controls uk-width-1-1 uk-form-large" placeholder="Что ищем?" name="text" value="{{ Request::get('text') }}">
                <button type="submit" class="uk-button uk-button-primary uk-width-1-1" title="Поиск по сайту"><i class="uk-icon-search"></i> Поиск</button>
            </form>
            <ul class="uk-nav">
                @foreach($menu as $item)
                    {!! $item !!}
                @endforeach
                <li class="uk-parent" data-uk-dropdown aria-haspopup="true" aria-expanded="false">
                    <a href="">Прочее <i class="uk-icon-caret-down"></i></a>

                    <div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" aria-hidden="true" style="top: 40px; left: 0px;" tabindex="">
                        <ul class="uk-nav uk-nav-navbar">
                            @foreach($menu_other as $item)
                                {!! $item !!}
                            @endforeach
                            <li class="uk-nav-header">Система</li>
                            @if(file_exists(base_path(). '/vendor/fanamurov/larrock-sitemap'))
                                <li><a href="#" onclick="rebuild_sitemap(); return false">Перегенерировать /sitemap.xml</a></li>
                                <li><a href="#" onclick="rebuild_rss(); return false">Перегенерировать /feed.rss</a></li>
                            @endif
                            @if(file_exists(public_path(). '/external/adminer.php'))
                                <li><a target="_blank" href="/external/adminer.php">DB</a></li>
                            @endif
                            <li>
                                <a href="#" onclick="clear_cache(); return false">Очистить кэш</a>
                            </li>
                        </ul>
                    </div>

                </li>
            </ul>
        </div>
    </div>
</nav>