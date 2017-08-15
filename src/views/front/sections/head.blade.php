<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="generator" content="Mart Larrock CMS" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>@yield('title')</title>
<meta name="description" content="@yield('title')">
<meta name="author" content="MartDS">

<link rel="apple-touch-icon" sizes="57x57" href="/_assets/_front/_images/favicons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/_assets/_front/_images/favicons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/_assets/_front/_images/favicons/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/_assets/_front/_images/favicons/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/_assets/_front/_images/favicons/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/_assets/_front/_images/favicons/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/_assets/_front/_images/favicons/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/_assets/_front/_images/favicons/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/_assets/_front/_images/favicons/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/_assets/_front/_images/favicons/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/_assets/_front/_images/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/_assets/_front/_images/favicons/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/_assets/_front/_images/favicons/favicon-16x16.png">
<link rel="manifest" href="/_assets/_front/_images/favicons/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/_assets/_front/_images/favicons/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

<link rel="stylesheet" href="/_assets/_front/_css/_min/uikit.min.css"/>
<link rel="stylesheet" href="/_assets/_front/_css/_min/front.min.css"/>
<link rel="stylesheet" href="/_assets/_fonts/flaticon.css"/>
<link rel="stylesheet" href="/_assets/bower_components/fancybox/source/jquery.fancybox.css"/>
<link rel="stylesheet" href="/_assets/bower_components/fancybox/source/helpers/jquery.fancybox-buttons.css"/>
<link rel="stylesheet" href="/_assets/bower_components/fancybox/source/helpers/jquery.fancybox-thumbs.css"/>
<link rel="stylesheet" href="/_assets/bower_components/pickadate/lib/compressed/themes/classic.css"/>
<link rel="stylesheet" href="/_assets/bower_components/pickadate/lib/compressed/themes/classic.date.css"/>
<link rel="stylesheet" href="/_assets/bower_components/noty/lib/noty.css"/>

<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500,500i,700&amp;subset=cyrillic" rel="stylesheet">
@yield('styles')
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="http://code.jquery.com/jquery-2.2.4.min.js"></script>

<link rel="alternate" hreflang="ru" type="rss" href="{!! URL::to('/feed.rss') !!}" title="RSS feed">
{{--
<script src="/_assets/bower_components/jquery-validation/dist/jquery.validate.min.js"></script>
<script src="/_assets/bower_components/jquery-validation/dist/additional-methods.min.js"></script>--}}
