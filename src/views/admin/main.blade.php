<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="generator" content="Mart LarrockCMS" />
        <meta name="author" content="MartDS">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>@yield('title', 'Admin') - Larrock Admin</title>
        <link href="/_assets/_admin/_images/logo-hand-black.png" rel="shortcut icon" />
        <link rel="stylesheet" href="/_assets/_front/_css/_min/uikit.min.css"/>
        <link rel="stylesheet" href="/_assets/_admin/_css/min/admin.min.css"/>
        <link rel="stylesheet" href="/_assets/bower_components/uikit/css/components/sortable.min.css"/>
        <link rel="stylesheet" href="/_assets/bower_components/uikit/css/components/sortable.almost-flat.min.css"/>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,500,500italic,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <link rel="stylesheet" href="/_assets/bower_components/selectize/dist/css/selectize.bootstrap3.css"/>
        <script src="/_assets/bower_components/selectize/dist/js/standalone/selectize.min.js"></script>
        <link rel="stylesheet" href="/_assets/bower_components/fancybox/dist/jquery.fancybox.css">
        <link rel="stylesheet" href="/_assets/bower_components/pickadate/lib/compressed/themes/default.css">
        <link rel="stylesheet" href="/_assets/bower_components/pickadate/lib/compressed/themes/default.date.css">
        <link rel="stylesheet" href="/_assets/bower_components/pickadate/lib/compressed/themes/default.time.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.25/jquery.fancybox.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.25/jquery.fancybox.min.js"></script>
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
            @if(Session::has('message') && is_array(Session::get('message')))
                @foreach(Session::get('message') as $type => $messages)
                    @foreach($messages as $message)
                        @if($type === 'dangerDestroy')
                            <div class="uk-alert uk-alert-danger alert-dismissable">
                                <i class="uk-icon-bug"></i>
                                {{ $message }}
                                <form action="/admin/{{ Session::get('destroyCategory')[0] }}" method="post">
                                    <input name="_method" type="hidden" value="DELETE">
                                    <input type="hidden" name="allowDestroy" value="true">
                                    {{ csrf_field() }}
                                    <button type="submit" class="uk-button uk-button-small uk-button-danger please_conform uk-margin-top">Продолжить удаление</button>
                                </form>
                                <a href="" class="uk-alert-close uk-close uk-float-right"></a>
                            </div>
                        @else
                            <div class="uk-alert uk-alert-{{ $type }} alert-dismissable">
                                @if($type === 'danger') <i class="uk-icon-bug"></i> @else <i class="uk-icon-check"></i> @endif
                                {{ $message }} <a href="" class="uk-alert-close uk-close uk-float-right"></a>
                            </div>
                        @endif
                    @endforeach
                @endforeach
                @php(\Illuminate\Support\Facades\Session::forget('destroyCategory'))
                @php(\Illuminate\Support\Facades\Session::forget('message'))
            @endif
            @yield('content')
        </section>

        <footer class="uk-container uk-container-center uk-margin-large-top uk-margin-large-bottom uk-text-muted">
            <span>Larrock-core: {{ Cache::get('coreVersionInstall') }}</span>
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
        <script src="/_assets/bower_components/uikit/js/components/sortable.min.js"></script>
        <script src="/_assets/bower_components/uikit/js/components/notify.min.js"></script>
        <script src="/_assets/bower_components/uikit/js/components/grid.min.js"></script>
        <script src="/_assets/bower_components/uikit/js/core/modal.min.js"></script>
        <script src="/_assets/bower_components/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
        <script src="/_assets/_admin/_js/min/back_core.min.js"></script>
        @if(isset($validator)) {!! $validator !!} @endif
        <script>
            window.FileAPI = {
                debug: false   // debug mode, see Console
                , cors: false    // if used CORS, set `true`
                , media: false   // if used WebCam, set `true`
                , staticPath: '/_assets/bower_components/fileapi/dist/' // path to '*.swf'
            }
        </script>
        <script src="/_assets/bower_components/fileapi/dist/FileAPI.min.js"></script>
        @stack('scripts')
    </body>
</html>