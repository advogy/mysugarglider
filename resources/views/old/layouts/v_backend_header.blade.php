<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
@stack('meta')

<title>@yield('title') - {{ config('app.name') }}</title>

<!-- Styles -->
@stack('styles')
<link rel="stylesheet" href="{{ asset('assets/css/style-app.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/css/style-app-dark.css') }}" type="text/css" />

<!-- Favicons -->
<link href="{{ asset('assets/images/favicon.png') }}" rel="icon">
<link href="{{ asset('assets/images/apple-touch-icon.png') }}" rel="apple-touch-icon">

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-CYDZGQKYFF"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-CYDZGQKYFF');
</script>
