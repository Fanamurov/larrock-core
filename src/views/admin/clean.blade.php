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
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,500,500italic,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
</head>
<body id="clean_template">
<section id="top_menu">
    @if(isset($top_menu)) {!! $top_menu !!} @endif
</section>
<section id="content" class="uk-container uk-container-center uk-margin-large-top">
    @foreach($errors->all() as $error)
        <div class="uk-alert uk-alert-danger">
            {{ $error }}
        </div>
    @endforeach
    @if(Session::has('message') && is_array(Session::get('message')))
        @foreach(Session::get('message') as $type => $messages)
            @foreach($messages as $message)
                <div class="uk-alert uk-alert-{{ $type }} alert-dismissable">
                    @if($type === 'danger') <i class="uk-icon-bug"></i> @else <i class="uk-icon-check"></i> @endif
                    {{ $message }} <a href="" class="uk-alert-close uk-close uk-float-right"></a>
                </div>
            @endforeach
        @endforeach
        @php(\Illuminate\Support\Facades\Session::forget('message'))
    @endif
    <div class="uk-grid">
        <div class="uk-width-1-1">
            @yield('content')
        </div>
    </div>
</section>

<script src="/_assets/bower_components/uikit/js/uikit.js"></script>
<script src="/_assets/bower_components/uikit/js/components/notify.min.js"></script>
<script src="{{asset('_assets/_admin/_js/back_core.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
@if(isset($validator)) {!! $validator !!} @endif
@stack('scripts')
</body>
</html>