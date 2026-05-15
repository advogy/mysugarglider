@include('layouts.v_header')

<body class="bg-cream">

<!-- ======= Navbar ======= -->
<nav id="main-navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="navbar-inner max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">

        <!-- Logo -->
        <a href="{{ route('index') }}" class="flex items-center gap-2 flex-shrink-0">
            <img src="{{ asset('assets/images/logo/logo.svg') }}" alt="MySugarGlider" class="h-8 nav-logo transition-all duration-300">
        </a>

        <!-- Desktop Nav -->
        <ul class="hidden lg:flex items-center gap-8">
            <li><a href="{{ route('home') }}"
                   class="nav-link-public {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
            <li>
                <div class="relative group">
                    <button class="nav-link-public flex items-center gap-1">
                        Sugar Glider <i class="bi bi-chevron-down text-xs"></i>
                    </button>
                    <div class="absolute top-full left-1/2 -translate-x-1/2 pt-3 hidden group-hover:block">
                        <div class="bg-white rounded-2xl shadow-card p-2 min-w-[160px]">
                            <a href="{{ route('collections') }}"
                               class="block px-4 py-2.5 text-sm text-bark-light hover:text-sage hover:bg-sage-50 rounded-xl transition-colors duration-150">
                                <i class="bi bi-collection mr-2"></i>Koleksi
                            </a>
                            <a href="{{ route('shelters') }}"
                               class="block px-4 py-2.5 text-sm text-bark-light hover:text-sage hover:bg-sage-50 rounded-xl transition-colors duration-150">
                                <i class="bi bi-house mr-2"></i>Kandang
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            <li><a href="{{ route('pedigree') }}"
                   class="nav-link-public {{ request()->routeIs('pedigree') || request()->routeIs('pedigree.show') ? 'active' : '' }}">Silsilah</a></li>
            <li><a href="{{ route('about') }}"
                   class="nav-link-public {{ request()->routeIs('about') ? 'active' : '' }}">Tentang</a></li>
            <li><a href="{{ route('home') }}#contact" class="nav-link-public">Kontak</a></li>
        </ul>

        <!-- CTA + Auth -->
        <div class="hidden lg:flex items-center gap-3">
            @if (Auth::check())
                <span class="text-sm nav-auth-name">Halo, <strong>{{ Auth::user()->name }}</strong></span>
                <a href="{{ route('dashboard.index') }}" class="btn-primary py-2 px-4 text-xs">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm nav-logout-btn transition-colors duration-200">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link-public">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary py-2 px-4 text-xs">
                    Daftar Gratis <i class="bi bi-arrow-right"></i>
                </a>
            @endif
        </div>

        <!-- Mobile toggle -->
        <button id="mobile-menu-toggle" class="lg:hidden p-2 rounded-xl hover:bg-white/20 transition-colors duration-200">
            <i class="bi bi-list text-2xl nav-toggle-icon"></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-cream-dark shadow-card">
        <div class="max-w-6xl mx-auto px-6 py-4 space-y-1">
            <a href="{{ route('home') }}"
               class="block px-4 py-3 text-sm font-medium text-bark-light hover:text-sage hover:bg-sage-50 rounded-xl transition-colors duration-150">
                <i class="bi bi-house mr-2"></i>Beranda
            </a>
            <a href="{{ route('collections') }}"
               class="block px-4 py-3 text-sm font-medium text-bark-light hover:text-sage hover:bg-sage-50 rounded-xl transition-colors duration-150">
                <i class="bi bi-collection mr-2"></i>Koleksi
            </a>
            <a href="{{ route('shelters') }}"
               class="block px-4 py-3 text-sm font-medium text-bark-light hover:text-sage hover:bg-sage-50 rounded-xl transition-colors duration-150">
                <i class="bi bi-house-heart mr-2"></i>Kandang
            </a>
            <a href="{{ route('pedigree') }}"
               class="block px-4 py-3 text-sm font-medium text-bark-light hover:text-sage hover:bg-sage-50 rounded-xl transition-colors duration-150">
                <i class="bi bi-diagram-3 mr-2"></i>Silsilah
            </a>
            <a href="{{ route('about') }}"
               class="block px-4 py-3 text-sm font-medium text-bark-light hover:text-sage hover:bg-sage-50 rounded-xl transition-colors duration-150">
                <i class="bi bi-info-circle mr-2"></i>Tentang
            </a>
            <div class="pt-2 border-t border-cream-dark mt-2">
                @if (Auth::check())
                    <a href="{{ route('dashboard.index') }}" class="btn-primary w-full justify-center mb-2">
                        <i class="bi bi-grid-fill"></i> Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-center text-sm text-bark-muted py-2">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="btn-primary w-full justify-center mb-2">Daftar Gratis</a>
                    <a href="{{ route('login') }}" class="btn-secondary w-full justify-center">Masuk</a>
                @endif
            </div>
        </div>
    </div>
</nav>

<style>
    /* Default: transparent with white text (hero pages) */
    #main-navbar { background: transparent; }
    #main-navbar .nav-link-public { color: rgba(255,255,255,0.9); }
    #main-navbar .nav-link-public:hover,
    #main-navbar .nav-link-public.active { color: #FAD96E; }
    #main-navbar .nav-toggle-icon { color: white; }
    #main-navbar .nav-auth-name { color: rgba(255,255,255,0.7); }
    #main-navbar .nav-auth-name strong { color: white; }
    #main-navbar .nav-logout-btn { color: rgba(255,255,255,0.7); }
    #main-navbar .nav-logout-btn:hover { color: white; }
    #main-navbar .nav-logo { filter: brightness(0) invert(1); }

    /* Scrolled / non-hero pages: glass morphism */
    #main-navbar.navbar-scrolled {
        background: rgba(250, 247, 242, 0.95);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 2px 20px rgba(45, 45, 45, 0.08);
    }
    #main-navbar.navbar-scrolled .nav-link-public { color: #555555; }
    #main-navbar.navbar-scrolled .nav-link-public:hover,
    #main-navbar.navbar-scrolled .nav-link-public.active { color: #5C8A6E; }
    #main-navbar.navbar-scrolled .nav-toggle-icon { color: #2D2D2D; }
    #main-navbar.navbar-scrolled .nav-auth-name { color: #888888; }
    #main-navbar.navbar-scrolled .nav-auth-name strong { color: #2D2D2D; }
    #main-navbar.navbar-scrolled .nav-logout-btn { color: #888888; }
    #main-navbar.navbar-scrolled .nav-logout-btn:hover { color: #2D2D2D; }
    #main-navbar.navbar-scrolled .nav-logo { filter: none; }
</style>

@yield('content')
@stack('modals')

@include('layouts.v_footer')
