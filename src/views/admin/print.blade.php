<!DOCTYPE html>
<html lang="ru" id="print_template">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="generator" content="Mart Larrock CMS" />
    <meta name="author" content="MartDS">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Admin') - Larrock Admin</title>
    <link href="/_assets/_admin/_images/logo-hand-black.png" rel="shortcut icon" />
    <link rel="stylesheet" href="/_assets/_front/_css/_min/uikit.min.css"/>
    <link rel="stylesheet" href="/_assets/_admin/_css/min/admin.min.css"/>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,500,500italic,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
</head>
<body>
<section id="print_content">
    @yield('content')
</section>

<script src="/_assets/bower_components/uikit/js/uikit.js"></script>
@stack('scripts')
</body>
</html>