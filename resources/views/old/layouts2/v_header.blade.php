<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @stack('meta')
    <meta name="keywords" content="sugar glider, sugar glider indonesia, sugar glider genetik, classic grey, white face, mosaic, platinum, leucistic, creamino, albino, caramel, combo morph, ruby leucistic, ruby platinum">

    <title>@yield('title') - {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link href="{{ asset('assets/images/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/images/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts: Nunito + DM Serif Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons (tetap dipakai untuk ikon) -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- GLightbox -->
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">

    <!-- Tailwind (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CYDZGQKYFF"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-CYDZGQKYFF');
    </script>
</head>
