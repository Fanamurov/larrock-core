<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="generator" content="Mart LarrockCMS" />
    <meta name="author" content="MartDS. Alexandr Fanamurov">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Admin') - Larrock Admin</title>
    <link href="/_assets/_admin/_images/logo-hand-black.png" rel="shortcut icon" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,500,500italic,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/_assets/_admin/_css/uikit.min.css"/>
    <link rel="stylesheet" href="/_assets/bower_components/selectize/dist/css/selectize.bootstrap3.css"/>
    <link rel="stylesheet" href="/_assets/bower_components/pickadate/lib/compressed/themes/default.css">
    <link rel="stylesheet" href="/_assets/bower_components/pickadate/lib/compressed/themes/default.date.css">
    <link rel="stylesheet" href="/_assets/_admin/_css/min/admin.min.css"/>
    @stack('css')
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="/_assets/bower_components/selectize/dist/js/standalone/selectize.min.js"></script>
</head>
<body>
    <section id="top_menu">
        @if(isset($top_menu)) @include('larrock::admin.sectionmenu.top', $top_menu) @endif
    </section>

    <section id="content" class="uk-container uk-margin-large">
        @if(isset($errors))
            @foreach($errors->all() as $error)
                <div uk-alert class="uk-alert-danger">
                    <a class="uk-alert-close uk-close" uk-close></a>
                    <span uk-icon="warning"></span> {{ $error }}
                </div>
            @endforeach
        @endif
            @if(Session::has('message') && is_array(Session::get('message')))
                @foreach(Session::get('message') as $type => $messages)
                    @foreach($messages as $message)
                        @if($type === 'dangerDestroy')
                            <div uk-alert class="uk-alert-danger">
                                <span uk-icon="warning"></span>
                                {{ $message }}
                                <form class="uk-form" action="/admin/{{ Session::get('destroyCategory')[0] }}" method="post">
                                    <input name="_method" type="hidden" value="DELETE">
                                    <input type="hidden" name="allowDestroy" value="true">
                                    {{ csrf_field() }}
                                    <button type="submit" class="uk-button uk-button-small uk-button-danger please_conform uk-margin-top">Продолжить удаление</button>
                                </form>
                                <a href="" class="uk-alert-close uk-close uk-float-right"></a>
                            </div>
                        @else
                            <div uk-alert class="uk-alert-{{ $type }}">
                                <a class="uk-alert-close uk-close" uk-close></a>
                                @if($type === 'danger') <span uk-icon="warning"></span> @else <span uk-icon="info"></span> @endif
                                {{ $message }}
                            </div>
                        @endif
                    @endforeach
                @endforeach
                @php(Session::forget('destroyCategory'))
                @php(Session::forget('message'))
            @endif
            @yield('content')
    </section>

    <footer class="uk-container uk-margin-bottom">
        <p class="uk-text-muted uk-text-small">
            LarrockCMS 2017-{{ date('Y') }}. Распространяется под лицензией CC-BY-4.0. <br/>
            <a href="https://packagist.org/packages/fanamurov/larrock-core"><img src="https://camo.githubusercontent.com/4995869bf897e3f0ecbada3a719f3ce06217c3a5/68747470733a2f2f706f7365722e707567782e6f72672f66616e616d75726f762f6c6172726f636b2d636f72652f762f737461626c65" alt="Latest Stable Version" data-canonical-src="https://poser.pugx.org/fanamurov/larrock-core/v/stable"></a> |
            <a href="http://larrock-cms.ru/" target="_blank">larrock-cms.ru</a> |
            <a href="https://github.com/Fanamurov/larrock-core" target="_blank">Github</a> |
            <a href="mailto:fanamurov@ya.ru">Разработчик</a> |
            <a href="http://martds.ru" target="_blank">Платная тех.поддержка</a>
        </p>
    </footer>

    <!-- Mainly scripts -->
    <script src="/_assets/_admin/_js/uikit.min.js"></script>
    <script src="/_assets/_admin/_js/uikit-icons.min.js"></script>
    <script src="/_assets/_admin/_js/min/back_core.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    @if(isset($validator)) {!! $validator !!} @endif
    @stack('scripts')
</body>
</html>