@include('layouts.v_header')

<body class="bg-white font-body">

{{-- ═══════════ NAVBAR ═══════════ --}}
<nav id="navbar" class="fixed top-0 inset-x-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between h-18 py-4">

            {{-- Logo --}}
            <a href="{{ route('index') }}" class="flex items-center gap-2 flex-shrink-0">
                <img src="{{ asset('assets/images/logo/logo.svg') }}"
                     alt="{{ config('app.name') }}"
                     id="nav-logo"
                     class="h-8 transition-all duration-300">
            </a>

            {{-- Desktop links --}}
            <ul class="hidden lg:flex items-center gap-1">
                <li>
                    <a href="{{ route('home') }}"
                       class="nav-link px-4 py-2 rounded-full {{ request()->routeIs('home') ? 'nav-active' : '' }}">
                        Beranda
                    </a>
                </li>
                <li class="relative group">
                    <button class="nav-link px-4 py-2 rounded-full flex items-center gap-1">
                        Sugar Glider <i class="bi bi-chevron-down text-[10px] transition-transform duration-200 group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute top-full left-1/2 -translate-x-1/2 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 translate-y-1 group-hover:translate-y-0">
                        <div class="bg-white rounded-2xl shadow-hover border border-cream-dark p-2 min-w-[180px]">
                            <a href="{{ route('collections') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-bark-light hover:text-sage hover:bg-sage-50 transition-all duration-150">
                                <i class="bi bi-collection text-sage"></i> Koleksi
                            </a>
                            <a href="{{ route('shelters') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-bark-light hover:text-sage hover:bg-sage-50 transition-all duration-150">
                                <i class="bi bi-house-heart text-sage"></i> Kandang
                            </a>
                            <a href="{{ route('pedigree') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-bark-light hover:text-sage hover:bg-sage-50 transition-all duration-150">
                                <i class="bi bi-diagram-3 text-sage"></i> Silsilah
                            </a>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="{{ route('about') }}"
                       class="nav-link px-4 py-2 rounded-full {{ request()->routeIs('about') ? 'nav-active' : '' }}">
                        Tentang
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}#contact" class="nav-link px-4 py-2 rounded-full">
                        Kontak
                    </a>
                </li>
            </ul>

            {{-- CTA & Auth --}}
            <div class="hidden lg:flex items-center gap-3">
                @if (Auth::check())
                    <span id="nav-greeting" class="text-sm font-semibold transition-colors duration-300">
                        Halo, <strong>{{ Auth::user()->name }}</strong>
                    </span>
                    <a href="{{ route('dashboard.index') }}" class="btn-primary py-2 px-5 text-xs">
                        <i class="bi bi-grid-fill"></i> Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" id="nav-logout"
                                class="text-sm font-semibold transition-colors duration-200 hover:text-sage">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" id="nav-login" class="nav-link px-4 py-2 rounded-full font-bold">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary py-2.5 px-5 text-sm">
                        Daftar Gratis <i class="bi bi-arrow-right"></i>
                    </a>
                @endif
            </div>

            {{-- Mobile toggle --}}
            <button id="mobile-toggle"
                    class="lg:hidden p-2 rounded-xl transition-colors duration-200"
                    aria-label="Menu">
                <i class="bi bi-list text-2xl" id="mobile-icon"></i>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu"
         class="hidden lg:hidden bg-white/95 backdrop-blur-lg border-t border-cream-dark shadow-card">
        <div class="max-w-7xl mx-auto px-6 py-4 space-y-1">
            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-bark-light hover:bg-cream hover:text-sage transition-all duration-150">
                <i class="bi bi-house text-sage"></i> Beranda
            </a>
            <a href="{{ route('collections') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-bark-light hover:bg-cream hover:text-sage transition-all duration-150">
                <i class="bi bi-collection text-sage"></i> Koleksi
            </a>
            <a href="{{ route('shelters') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-bark-light hover:bg-cream hover:text-sage transition-all duration-150">
                <i class="bi bi-house-heart text-sage"></i> Kandang
            </a>
            <a href="{{ route('pedigree') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-bark-light hover:bg-cream hover:text-sage transition-all duration-150">
                <i class="bi bi-diagram-3 text-sage"></i> Silsilah
            </a>
            <a href="{{ route('about') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-bark-light hover:bg-cream hover:text-sage transition-all duration-150">
                <i class="bi bi-info-circle text-sage"></i> Tentang
            </a>

            <div class="pt-3 border-t border-cream-dark mt-2 flex flex-col gap-2">
                @if (Auth::check())
                    <a href="{{ route('dashboard.index') }}" class="btn-primary justify-center">
                        <i class="bi bi-grid-fill"></i> Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-center text-sm font-semibold text-bark-muted py-2 hover:text-bark transition-colors">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="btn-primary justify-center">
                        Daftar Gratis
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary justify-center">
                        Masuk
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>

<style>
    /* ── Transparent state (hero pages) ── */
    #navbar { background: transparent; }
    #navbar .nav-link { color: rgba(255,255,255,0.85); font-weight: 700; }
    #navbar .nav-link:hover,
    #navbar .nav-link.nav-active { color: #FAD96E; background: rgba(255,255,255,0.10); }
    #navbar #mobile-icon { color: white; }
    #navbar #nav-greeting { color: rgba(255,255,255,0.80); }
    #navbar #nav-greeting strong { color: #fff; }
    #navbar #nav-logout { color: rgba(255,255,255,0.70); }
    #navbar #nav-login { color: rgba(255,255,255,0.85); }
    #navbar #nav-logo { filter: brightness(0) invert(1); }

    /* ── Scrolled / no-hero state ── */
    #navbar.is-scrolled {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    }
    #navbar.is-scrolled .nav-link { color: #555555; }
    #navbar.is-scrolled .nav-link:hover,
    #navbar.is-scrolled .nav-link.nav-active { color: #5C8A6E; background: #F0F7F3; }
    #navbar.is-scrolled #mobile-icon { color: #2D2D2D; }
    #navbar.is-scrolled #nav-greeting { color: #888888; }
    #navbar.is-scrolled #nav-greeting strong { color: #2D2D2D; }
    #navbar.is-scrolled #nav-logout { color: #888888; }
    #navbar.is-scrolled #nav-login { color: #555555; }
    #navbar.is-scrolled #nav-logo { filter: none; }
</style>

{{-- Page content --}}
@yield('content')
@stack('modals')

{{-- ═══════════ FOOTER ═══════════ --}}
<footer class="bg-bark text-white">
    <div class="max-w-7xl mx-auto px-6 pt-16 pb-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 mb-14">

            {{-- Brand --}}
            <div class="lg:col-span-5">
                <a href="{{ route('index') }}" class="inline-block mb-5">
                    <img src="{{ asset('assets/images/logo/logo.svg') }}"
                         alt="{{ config('app.name') }}"
                         class="h-8 brightness-0 invert">
                </a>
                <p class="text-white/50 text-sm leading-relaxed max-w-xs mb-6">
                    Platform komunitas peternak sugar glider Indonesia. Catat data, lacak silsilah, dan temukan adopsi terbaik.
                </p>
                <div class="flex items-center gap-3">
                    <a href="https://www.instagram.com/mysugarglider.id/" target="_blank" rel="noopener"
                       class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center hover:bg-sage transition-all duration-200">
                        <i class="bi bi-instagram text-sm"></i>
                    </a>
                    <a href="https://wa.me/6285755333232" target="_blank" rel="noopener"
                       class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center hover:bg-sage transition-all duration-200">
                        <i class="bi bi-whatsapp text-sm"></i>
                    </a>
                    <a href="mailto:info@mysugarglider.id"
                       class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center hover:bg-sage transition-all duration-200">
                        <i class="bi bi-envelope text-sm"></i>
                    </a>
                </div>
            </div>

            {{-- Nav links --}}
            <div class="lg:col-span-3">
                <h5 class="text-xs font-bold text-white/40 uppercase tracking-[0.15em] mb-5">Navigasi</h5>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}"        class="text-white/60 hover:text-honey text-sm font-semibold transition-colors duration-200">Beranda</a></li>
                    <li><a href="{{ route('collections') }}" class="text-white/60 hover:text-honey text-sm font-semibold transition-colors duration-200">Koleksi</a></li>
                    <li><a href="{{ route('shelters') }}"    class="text-white/60 hover:text-honey text-sm font-semibold transition-colors duration-200">Kandang</a></li>
                    <li><a href="{{ route('pedigree') }}"    class="text-white/60 hover:text-honey text-sm font-semibold transition-colors duration-200">Silsilah</a></li>
                    <li><a href="{{ route('about') }}"       class="text-white/60 hover:text-honey text-sm font-semibold transition-colors duration-200">Tentang</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="lg:col-span-4">
                <h5 class="text-xs font-bold text-white/40 uppercase tracking-[0.15em] mb-5">Kontak</h5>
                <ul class="space-y-3.5">
                    <li class="flex items-start gap-3 text-white/60 text-sm">
                        <i class="bi bi-geo-alt text-sage-light mt-0.5 flex-shrink-0"></i>
                        <span>Kota Surabaya, Jawa Timur, Indonesia</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <i class="bi bi-envelope text-sage-light flex-shrink-0"></i>
                        <a href="mailto:info@mysugarglider.id"
                           class="text-white/60 hover:text-honey font-semibold transition-colors duration-200">
                            info@mysugarglider.id
                        </a>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <i class="bi bi-whatsapp text-sage-light flex-shrink-0"></i>
                        <a href="https://wa.me/6285755333232" target="_blank" rel="noopener"
                           class="text-white/60 hover:text-honey font-semibold transition-colors duration-200">
                            +62 857 5533 3232
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-white/30 text-xs">
                &copy; 2022–{{ date('Y') }} <span class="text-white/50 font-semibold">{{ config('app.name') }}</span>. All rights reserved.
            </p>
            <p class="text-white/30 text-xs">
                Developed by
                <a href="https://athoria.me" target="_blank" rel="noopener"
                   class="text-sage-light hover:text-honey font-semibold transition-colors duration-200">
                    AthoRia.me
                </a>
            </p>
        </div>
    </div>
</footer>

{{-- Back to top --}}
<button id="back-to-top"
        class="fixed bottom-6 right-6 w-11 h-11 bg-sage text-white rounded-2xl shadow-hover
               hidden items-center justify-center hover:bg-sage-dark
               transition-all duration-200 z-50">
    <i class="bi bi-arrow-up"></i>
</button>

<script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>

<script>
(function() {
    // Lightbox
    if (typeof GLightbox !== 'undefined') GLightbox({ selector: '.glightbox' });

    // PureCounter
    if (typeof PureCounter !== 'undefined') new PureCounter();

    // Navbar scroll logic
    const navbar  = document.getElementById('navbar');
    const hasHero = document.getElementById('hero-section');

    function updateNavbar() {
        const scrolled = window.scrollY > 60;
        if (hasHero) {
            navbar.classList.toggle('is-scrolled', scrolled);
        } else {
            navbar.classList.add('is-scrolled');
        }
    }
    updateNavbar();
    window.addEventListener('scroll', updateNavbar, { passive: true });

    // Mobile menu
    const toggle     = document.getElementById('mobile-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const icon       = document.getElementById('mobile-icon');
    if (toggle && mobileMenu) {
        toggle.addEventListener('click', () => {
            const open = mobileMenu.classList.toggle('hidden');
            icon.className = open ? 'bi bi-list text-2xl' : 'bi bi-x text-2xl';
        });
    }

    // Back to top
    const topBtn = document.getElementById('back-to-top');
    window.addEventListener('scroll', () => {
        topBtn.classList.toggle('hidden', window.scrollY < 300);
        topBtn.classList.toggle('flex',   window.scrollY >= 300);
    }, { passive: true });
    topBtn?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
})();
</script>

@stack('scripts')
</body>
</html>
