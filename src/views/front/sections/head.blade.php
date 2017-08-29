<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="generator" content="Mart Larrock CMS" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>@yield('title')</title>
<meta name="description" content="@yield('title')">
<meta name="author" content="MartDS">
<link rel="icon" type="image/png" sizes="16x16" href="/_assets/_front/_images/favicons/favicon-16x16.png?v=2">
<link rel="stylesheet" href="/_assets/_front/_css/_min/uikit.min.css"/>
<link rel="stylesheet" href="/_assets/_front/_css/_min/libs.min.css"/>
<link rel="stylesheet" href="/_assets/_front/_css/_min/front.min.css"/>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700|Roboto+Condensed:400,700|Roboto:400,500,700&amp;subset=cyrillic" rel="stylesheet">

@yield('styles')
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="http://code.jquery.com/jquery-2.2.4.min.js"></script>

{{--<link rel="alternate" hreflang="ru" type="rss" href="{!! URL::to('/feed.rss') !!}" title="RSS feed">--}}
