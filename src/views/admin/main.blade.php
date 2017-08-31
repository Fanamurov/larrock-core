<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="generator" content="Mart Larrock CMS" />
        <meta name="author" content="MartDS">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>@yield('title', 'Admin') - Larrock Admin</title>
        <link href="{{asset('ico.png?2v')}}" rel="shortcut icon" />
        <link rel="stylesheet" href="/_assets/_front/_css/_min/uikit.min.css"/>
        <link rel="stylesheet" href="/_assets/_admin/_css/min/admin.min.css"/>
        <link rel="stylesheet" href="/_assets/bower_components/noty/lib/noty.css"/>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,500,500italic,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script src="http://code.jquery.com/jquery-2.2.4.min.js"></script>
        <link rel="stylesheet" href="/_assets/bower_components/selectize/dist/css/selectize.bootstrap3.css"/>
        <script src="/_assets/bower_components/selectize/dist/js/standalone/selectize.min.js"></script>
    </head>
    <body>
        <section id="top_menu">
            @if(isset($top_menu)) @include('larrock::admin.sectionmenu.top', $top_menu) @endif
        </section>
        <section id="content" class="uk-container uk-container-center uk-margin-large-top">
            @if(isset($errors))
                @foreach($errors->all() as $error)
                    <div class="uk-alert uk-alert-danger">
                        <i class="uk-icon-bug"></i> {{ $error }}
                    </div>
                @endforeach
            @endif
            @foreach (Alert::getMessages() as $type => $messages)
                @foreach ($messages as $message)
                    @php($type = str_replace('Admin', '', $type))
                    <div class="uk-alert uk-alert-{{ $type }} @if($type === 'error') uk-alert-danger @endif">
                        @if($type === 'error') <i class="uk-icon-bug"></i> @else <i class="uk-icon-check"></i> @endif {{ $message }}
                    </div>
                @endforeach
            @endforeach
            @yield('content')
        </section>

        <footer class="uk-container uk-container-center uk-margin-large-top">
            <span>Larrock-core:</span>
            <a href="https://github.com/Fanamurov/larrock-core" target="_blank">
                <img src="https://poser.pugx.org/fanamurov/larrock-core/version" alt="Latest Stable Version">
                <img src="https://poser.pugx.org/fanamurov/larrock-core/downloads" alt="Total downloads">
                <img src="https://poser.pugx.org/fanamurov/larrock-core/license" alt="License">
            </a><br/>
            <span>Project wiki <a href="https://github.com/Fanamurov/larrock-core/wiki" target="_blank">available on GitHub</a></span>.<br/>
            <span>Commercial development and support of sites on LarrockCMS - <a href="http://martds.ru" target="_blank">MartDS</a>.</span>
        </footer>

        <!-- Mainly scripts -->
        <script src="/_assets/bower_components/uikit/js/uikit.js"></script>
        <script src="/_assets/bower_components/uikit/js/components/accordion.min.js"></script>
        <script src="/_assets/bower_components/uikit/js/components/tooltip.min.js"></script>
        <script src="/_assets/bower_components/uikit/js/components/notify.min.js"></script>
        <script src="/_assets/bower_components/uikit/js/core/modal.min.js"></script>

        <link href="/_assets/bower_components/jquery.filer/css/jquery.filer.css" type="text/css" rel="stylesheet" />
        <link href="/_assets/bower_components/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css" type="text/css" rel="stylesheet" />

        <script src="/_assets/bower_components/jquery.filer/js/jquery.filer.min.js"></script>
        <script src="/_assets/bower_components/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
        <script src="{{asset('_assets/_admin/_js/back_core.min.js')}}"></script>
        @if(isset($validator)) {!! $validator !!} @endif
        @stack('scripts')
    </body>
</html>