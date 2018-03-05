<nav uk-navbar="mode: click" class="uk-navbar-container uk-navbar uk-navbar-transparent">
    <div class="uk-navbar-left">
        <a class="uk-navbar-item uk-logo uk-visible@m" href="/admin">
            <img width="36" src="/_assets/_admin/_images/logo-hand-black.png" alt="Larrock">Larrock</a>
        <a class="uk-navbar-toggle uk-hidden@m" uk-navbar-toggle-icon href="#" uk-toggle="target: #offcanvas-usage"></a>
        <ul class="uk-navbar-nav uk-visible@m">
            @foreach($menu as $item)
                {!! $item !!}
            @endforeach
            <li class="uk-parent">
                <a href="#">Прочее <span uk-icon="chevron-down"></span></a>
                <div class="uk-navbar-dropdown">
                    <ul class="uk-nav uk-navbar-dropdown-nav">
                        @foreach($menu_other as $item)
                            {!! $item !!}
                        @endforeach
                        <li class="uk-nav-header">Система</li>
                        @if(file_exists(public_path(). '/external/adminer.php'))
                            <li><a target="_blank" href="/external/adminer.php">DB Adminer</a></li>
                        @endif
                        <li>
                            <a href="#" onclick="clear_cache(); UIkit.dropdown('uk-navbar').hide(); return false">Очистить кэш</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

    <div class="uk-navbar-right">
        @if(isset($searchSiteAdmin))
            {!! $searchSiteAdmin !!}
        @endif
        <ul class="uk-navbar-nav">
            <li><a href="/"><span class="uk-margin-small-right" uk-icon="home"></span><span class="uk-visible@m">К сайту</span></a></li>
            <li><a href="{{ url('/logout') }}"><span class="uk-margin-small-right" uk-icon="sign-out"></span><span class="uk-visible@m">Выйти</span></a></li>
        </ul>
    </div>
</nav>

<div class="uk-offcanvas-content">
    <div id="offcanvas-usage" uk-offcanvas>
        <div class="uk-offcanvas-bar">
            <button class="uk-offcanvas-close" type="button" uk-close></button>
            <ul class="uk-nav">
                @foreach($menu as $item)
                    {!! $item !!}
                @endforeach
                <li class="uk-parent">
                    <a href="#">Прочее <span uk-icon="chevron-down"></span></a>
                    <div class="uk-navbar-dropdown">
                        <ul class="uk-nav uk-navbar-dropdown-nav">
                            @foreach($menu_other as $item)
                                {!! $item !!}
                            @endforeach
                            <li class="uk-nav-header">Система</li>
                            @if(file_exists(public_path(). '/external/adminer.php'))
                                <li><a target="_blank" href="/external/adminer.php">DB Adminer</a></li>
                            @endif
                            <li>
                                <a href="#" onclick="clear_cache(); UIkit.dropdown('uk-navbar').hide(); return false">Очистить кэш</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>