<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include('layouts.v_backend_header')
</head>

<body>
    <script src="{{ asset('assets/js/initTheme.js') }}"></script>
    <div id="app">
        <div id="sidebar" class="active">
            @include('layouts.v_backend_sidebar')
        </div>
        <div id="main" class="layout-navbar">
            <header class="mb-3">
                @include('layouts.v_backend_navbar')
            </header>

            <div id="main-content">
                <div class="page-heading">
                    @yield('content')
                </div>

                <footer>
                    <div class="footer clearfix mb-0 text-muted small">
                        <div class="float-start">
                            <p>2022-2023 &copy; {{ config('app.name') }} - v{{ config('app.version') }} </p>
                        </div>
                        <div class="float-end">
                            <p>Developed by <a href="https://athoria.me">AthoRia.me</a></p>
                        </div>
                    </div>
                </footer>
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @stack('scripts')
</body>

</html>
