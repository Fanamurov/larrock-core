<header>
    <div class="uk-container uk-container-center">
        <div class="uk-grid">
            <div class="uk-width-1-1 uk-width-medium-2-3 uk-width-large-1-2">
                <p class="logo-name">Фотоцентр цифровых услуг</p>
                <a href="/"><img class="logo" src="/_assets/_front/_images/logo.png" srcset="/_assets/_front/_images/logo@2x.png 2x"></a>
                <address class="uk-hidden-small">
                    @if(isset($telefony_v_shapke))
                        @include('larrock::front.modules.html.text', ['data' => $telefony_v_shapke])
                    @endif
                </address>
            </div>
            <div class="uk-width-1-1 uk-width-medium-1-3 uk-width-large-1-2 header-links">
                <a href="/user"><span class="flaticon flaticon-profile"></span> <span class="text">Личный кабинет</span></a>
                {{--@include('larrock::front.modules.cart.moduleSplash')--}}
            </div>
        </div>
    </div>
</header>