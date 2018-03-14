<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="generator" content="Mart Larrock CMS" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>@yield('title')</title>
<meta name="description" content="@yield('title')">
<meta name="author" content="MartDS">
@yield('meta')
<link href="/_assets/_admin/_images/logo-hand-black.png" rel="shortcut icon" />
<link rel="stylesheet" href="/_assets/_front/_css/_min/uikit.min.css"/>
<link rel="stylesheet" href="/_assets/_front/_css/_min/libs.min.css"/>
<link rel="stylesheet" href="/_assets/_front/_css/_min/front.min.css"/>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700|Roboto+Condensed:400,700,700i|Roboto:400,500,700&amp;subset=cyrillic" rel="stylesheet">
@yield('styles')
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>