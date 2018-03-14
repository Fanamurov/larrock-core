<!DOCTYPE html>
<html lang="ru">
<head>
    @include('larrock::front.sections.head')
</head>
<body class="@yield('body_class') @if(auth()->user() && auth()->user()->level() === 3) adminMode @endif">
<div class="body-container">
    <div class="uk-container uk-container-center">
        @include('larrock::front.sections.header')
        <section id="main">
            <div class="uk-container uk-container-center">
                <div class="uk-grid">
                    <div id="content" class="uk-width-8-11 left_colomn">
                        <div class="uk-width-1-1 uk-width-large-9-10 content-container">
                            @include('larrock::front.errors')
                            @yield('content')
                            @yield('contentBottom')
                        </div>

                        @if(isset($seofish) && count($seofish) > 0)
                            <div class="uk-width-1-1 uk-width-large-9-10 uk-margin-large-bottom uk-margin-large-top">
                                @include('larrock::front.modules.seofish.item')
                            </div>
                        @endif
                    </div>
                    <section id="right_colomn" class="uk-width-3-11">
                        <div class="uk-padding-top uk-padding-bottom">
                            <span class="devider_top"></span>
                            <div class="modules-list">
                                @yield('front.modules.list.catalog')
                                @if(isset($anons) && count($anons) > 0)
                                    @include('vendor.larrock.front.modules.list.news', ['data' => $anons])
                                @endif
                                @if(isset($RandomCatalogItems))
                                    @include('larrock::front.modules.list.random_catalog_items', $RandomCatalogItems)
                                @endif
                            </div>

                            @if(env('SMARTBANNERS') === true)
                                {!! $smartbanners !!}
                            @endif
                            <span class="devider_bottom"></span>
                        </div>
                    </section>
                </div>
            </div>
        </section>
        @include('larrock::front.sections.footer')
    </div>
</div>
<div id="modalNotify" class="uk-modal" style="overflow-y: scroll;background: rgba(0,0,0,.2);"></div>
<div id="modalProgress" class="uk-modal" style="width: 136px; border-radius: 10px;">
    <div class="uk-modal-dialog">
        Загрузка...
    </div>
</div>
@include('larrock::front.sections.bottom_scripts')
</body>
</html>