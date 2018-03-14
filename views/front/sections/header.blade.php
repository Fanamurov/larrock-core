<header>
    <div class="uk-container uk-container-center">
        <div class="uk-grid">
            <div class="uk-width-1-1 uk-width-small-1-2 uk-width-medium-2-3 uk-width-large-1-3">
                <a href="/"><img class="logo" src="/_assets/_front/_images/logo.png" srcset="/_assets/_front/_images/logo@2x.png 2x" alt="{{ env('SITE_NAME') }}"></a>
                <address class="uk-hidden-small">
                    @renderBlock(telefony_v_shapke)
                </address>
            </div>
            <div class="uk-width-1-1 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-2 header-links">
                <a href="/user" class="profile-link uk-text-nowrap uk-hidden"><img class="user-icon" src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDM1MCAzNTAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDM1MCAzNTA7IiB4bWw6c3BhY2U9InByZXNlcnZlIiB3aWR0aD0iNTEycHgiIGhlaWdodD0iNTEycHgiPgo8Zz4KCTxwYXRoIGQ9Ik0xNzUsMTcxLjE3M2MzOC45MTQsMCw3MC40NjMtMzguMzE4LDcwLjQ2My04NS41ODZDMjQ1LjQ2MywzOC4zMTgsMjM1LjEwNSwwLDE3NSwwcy03MC40NjUsMzguMzE4LTcwLjQ2NSw4NS41ODcgICBDMTA0LjUzNSwxMzIuODU1LDEzNi4wODQsMTcxLjE3MywxNzUsMTcxLjE3M3oiIGZpbGw9IiNlMjAwMGYiLz4KCTxwYXRoIGQ9Ik00MS45MDksMzAxLjg1M0M0MS44OTcsMjk4Ljk3MSw0MS44ODUsMzAxLjA0MSw0MS45MDksMzAxLjg1M0w0MS45MDksMzAxLjg1M3oiIGZpbGw9IiNlMjAwMGYiLz4KCTxwYXRoIGQ9Ik0zMDguMDg1LDMwNC4xMDRDMzA4LjEyMywzMDMuMzE1LDMwOC4wOTgsMjk4LjYzLDMwOC4wODUsMzA0LjEwNEwzMDguMDg1LDMwNC4xMDR6IiBmaWxsPSIjZTIwMDBmIi8+Cgk8cGF0aCBkPSJNMzA3LjkzNSwyOTguMzk3Yy0xLjMwNS04Mi4zNDItMTIuMDU5LTEwNS44MDUtOTQuMzUyLTEyMC42NTdjMCwwLTExLjU4NCwxNC43NjEtMzguNTg0LDE0Ljc2MSAgIHMtMzguNTg2LTE0Ljc2MS0zOC41ODYtMTQuNzYxYy04MS4zOTUsMTQuNjktOTIuODAzLDM3LjgwNS05NC4zMDMsMTE3Ljk4MmMtMC4xMjMsNi41NDctMC4xOCw2Ljg5MS0wLjIwMiw2LjEzMSAgIGMwLjAwNSwxLjQyNCwwLjAxMSw0LjA1OCwwLjAxMSw4LjY1MWMwLDAsMTkuNTkyLDM5LjQ5NiwxMzMuMDgsMzkuNDk2YzExMy40ODYsMCwxMzMuMDgtMzkuNDk2LDEzMy4wOC0zOS40OTYgICBjMC0yLjk1MSwwLjAwMi01LjAwMywwLjAwNS02LjM5OUMzMDguMDYyLDMwNC41NzUsMzA4LjAxOCwzMDMuNjY0LDMwNy45MzUsMjk4LjM5N3oiIGZpbGw9IiNlMjAwMGYiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" /> <span class="text">Личный кабинет</span></a>
                @if(file_exists(base_path(). '/vendor/fanamurov/larrock-cart'))
                    @include('larrock::front.modules.cart.moduleSplash')
                @endif
                @if(file_exists(base_path(). '/vendor/fanamurov/larrock-catalog'))
                    @include('larrock::front.modules.search.catalog-autocomplite')
                @endif
            </div>
        </div>
    </div>
    @if(isset($menu_default))
        <section id="top_menu" class="uk-container uk-container-center">
            @include('larrock::front.modules.menu.top', ['menu' => $menu_default])
        </section>
    @endif
</header>