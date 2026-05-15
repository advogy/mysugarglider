<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include('layouts.v_backend_header')
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="{{ route('index') }}"><img src="{{ asset('assets/images/logo/logo.svg') }}"
                                alt="Logo"></a>
                    </div>

                    @yield('content')
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <picture class="picture">
                        <img itemprop="image" srcset="//picsum.photos/700/600/?random" alt="" class="h-100">
                    </picture>
                </div>
            </div>
        </div>
    </div>
    @stack('modals')
    @stack('scripts')
</body>

</html>
