<!DOCTYPE html>
<html lang="ru">
<head>
    @include('larrock::front.sections.head')
</head>
<body class="@yield('body_class')">
@include('larrock::front.sections.header')
<section id="top_menu">
    <div class="uk-container uk-container-center">
        <button class="uk-button uk-button-large uk-button-primary uk-width-1-1 uk-hidden-medium uk-hidden-large" data-uk-toggle="{target:'#top_menu_block', cls:'uk-hidden-small'}">Меню</button>
        @include('larrock::front.modules.menu.top', $menu)
    </div>
</section>
<section id="main">
    <div class="uk-container uk-container-center body-container">
        <div class="uk-grid">
            <div id="content" class="uk-width-8-11 left_colomn">
                <div class="uk-width-1-1 uk-width-large-9-10 content-container">
                    @include('larrock::front.errors')
                    @yield('content')
                    @yield('contentBottom')
                </div>

                @if(isset($seofish) && count($seofish) > 0)
                    <div class="uk-width-1-1 uk-width-large-9-10 uk-margin-large-bottom">
                        @include('larrock::front.modules.seofish.item')
                    </div>
                @endif
            </div>
            <section id="right_colomn" class="uk-width-3-11">
                <div class="uk-padding-top uk-padding-bottom">
                    <span class="devider_top"></span>
                    <div class="uk-grid grid-right-colomn">
                        {{--@if(isset($anons) && count($anons) > 0)
                            @include('larrock::front.modules.list.news', ['data' => $anons])
                        @endif--}}

                        {{--@if(isset($banner_v_kolonke_sprava) && count($banner_v_kolonke_sprava) > 0)
                            @include('larrock::front.modules.list.banner', ['data' => $banner_v_kolonke_sprava])
                        @endif--}}
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
@include('larrock::front.sections.bottom_scripts')
</body>
</html>