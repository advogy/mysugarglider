<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MySugarGlider') — Platform Sugar Glider #1 Indonesia</title>
    <meta name="description" content="Platform komunitas Sugar Glider terpercaya di Indonesia. Catat silsilah, kelola kandang, dan adopsi Sugar Glider impian Anda.">

    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@400;500;600;700&family=Nunito:wght@400;500;600;700;800;900&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-body antialiased text-bark bg-white overflow-x-hidden">

{{-- ═══════════════════════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════════════════════ --}}
<header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 @yield('navbar-class', '')">
    <nav class="max-w-7xl mx-auto px-5 sm:px-8">
        <div class="flex items-center justify-between h-[70px]">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex-shrink-0 hover:opacity-80 transition-opacity" style="text-decoration:none;">
                <span class="nav-logo-my" style="font-family:'Outfit',sans-serif; font-weight:800; font-size:1.5rem; color:#1A1A1A; letter-spacing:-0.5px; line-height:1;">
                    My<span style="color:#06D6A0;">SugarGlider</span><span style="color:#FFD166; font-size:0.9rem;">.id</span>
                </span>
            </a>

            {{-- Desktop nav links --}}
            <div id="nav-desktop-links" class="hidden lg:flex items-center gap-8">
                <a href="{{ route('home') }}"
                   class="relative font-bold text-[0.95rem] transition-colors {{ request()->routeIs('home') || request()->routeIs('index') ? 'text-bark' : 'text-bark-muted hover:text-bark' }}" style="font-family: 'Inter', sans-serif;">
                    Beranda
                    @if(request()->routeIs('home') || request()->routeIs('index'))
                    <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 w-4 h-1.5 rounded-full" style="background-color: #FFD166;"></span>
                    @endif
                </a>

                <a href="{{ route('collections') }}"
                   class="relative font-bold text-[0.95rem] transition-colors {{ request()->routeIs('collections') && request('status') !== 'adopsi' ? 'text-bark' : 'text-bark-muted hover:text-bark' }}" style="font-family: 'Inter', sans-serif;">
                    Koleksi
                    @if(request()->routeIs('collections') && request('status') !== 'adopsi')
                    <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 w-4 h-1.5 rounded-full" style="background-color: #FFD166;"></span>
                    @endif
                </a>

                <a href="{{ route('collections', ['status' => 'adopsi']) }}"
                   class="relative font-bold text-[0.95rem] transition-colors {{ request()->routeIs('collections') && request('status') === 'adopsi' ? 'text-bark' : 'text-bark-muted hover:text-bark' }}" style="font-family: 'Inter', sans-serif;">
                    Adopsi
                    @if(request()->routeIs('collections') && request('status') === 'adopsi')
                    <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 w-4 h-1.5 rounded-full" style="background-color: #FFD166;"></span>
                    @endif
                </a>

                <a href="{{ route('shelters') }}"
                   class="relative font-bold text-[0.95rem] transition-colors {{ request()->routeIs('shelters') || request()->routeIs('shelter.show') ? 'text-bark' : 'text-bark-muted hover:text-bark' }}" style="font-family: 'Inter', sans-serif;">
                    Kandang
                    @if(request()->routeIs('shelters') || request()->routeIs('shelter.show'))
                    <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 w-4 h-1.5 rounded-full" style="background-color: #FFD166;"></span>
                    @endif
                </a>
                
                <a href="{{ route('about') }}"
                   class="relative font-bold text-[0.95rem] transition-colors {{ request()->routeIs('about') ? 'text-bark' : 'text-bark-muted hover:text-bark' }}" style="font-family: 'Inter', sans-serif;">
                    Tentang
                    @if(request()->routeIs('about'))
                    <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 w-4 h-1.5 rounded-full" style="background-color: #FFD166;"></span>
                    @endif
                </a>
            </div>

            {{-- Right: Auth / User --}}
            <div class="hidden lg:flex items-center gap-5">
                @auth
                    <a href="{{ route('dashboard.index') }}"
                       class="nav-user-link flex items-center gap-2 font-bold text-[0.95rem] text-bark hover:opacity-70 transition-opacity" style="font-family: 'Inter', sans-serif;">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset('/upload/avatars/' . Auth::user()->avatar) }}"
                                 class="w-8 h-8 rounded-full object-cover border-2" style="border-color: #FFD166;" alt="">
                        @else
                            <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: #FFD166;">
                                <i class="bi bi-person-fill text-bark text-sm"></i>
                            </div>
                        @endif
                        <span>{{ Str::limit(Auth::user()->name, 12) }}</span>
                    </a>
                    <a href="{{ route('dashboard.index') }}" class="font-bold text-[0.95rem] px-6 py-2.5 rounded-full transition-all hover:opacity-90" style="font-family: 'Inter', sans-serif; background-color: #118AB2; color: #FFF; box-shadow: 0 4px 15px rgba(17,138,178,0.2);">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-user-link font-bold text-[0.95rem] text-bark hover:opacity-70 transition-opacity" style="font-family: 'Inter', sans-serif;">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="font-bold text-[0.95rem] px-6 py-2.5 rounded-full transition-all hover:opacity-90" style="font-family: 'Inter', sans-serif; background-color: #118AB2; color: #FFF; box-shadow: 0 4px 15px rgba(17,138,178,0.2);">
                        Daftar
                    </a>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <button id="mobile-toggle" class="lg:hidden p-2 rounded-xl text-bark-light hover:bg-sage-50 transition-colors" aria-label="Menu">
                <i class="bi bi-list text-2xl" id="mobile-icon"></i>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="lg:hidden hidden bg-white rounded-[24px] mb-4 overflow-hidden p-3" style="box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
            <div class="flex flex-col gap-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-5 py-3.5 text-[0.95rem] rounded-2xl text-bark hover:bg-[#F8F9FA] transition-colors" style="font-family: 'Inter', sans-serif; font-weight: 600;">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(255, 209, 102, 0.2); color: #E5A910;"><i class="bi bi-house"></i></div>
                    Beranda
                </a>
                <a href="{{ route('collections') }}" class="flex items-center gap-3 px-5 py-3.5 text-[0.95rem] rounded-2xl text-bark hover:bg-[#F8F9FA] transition-colors" style="font-family: 'Inter', sans-serif; font-weight: 600;">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(17, 138, 178, 0.1); color: #118AB2;"><i class="bi bi-grid-3x3-gap"></i></div>
                    Semua Koleksi
                </a>
                <a href="{{ route('collections') }}?status=adopsi" class="flex items-center gap-3 px-5 py-3.5 text-[0.95rem] rounded-2xl text-bark hover:bg-[#F8F9FA] transition-colors" style="font-family: 'Inter', sans-serif; font-weight: 600;">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(255, 209, 102, 0.2); color: #E5A910;"><i class="bi bi-house-heart"></i></div>
                    Adopsi
                </a>
                <a href="{{ route('shelters') }}" class="flex items-center gap-3 px-5 py-3.5 text-[0.95rem] rounded-2xl text-bark hover:bg-[#F8F9FA] transition-colors" style="font-family: 'Inter', sans-serif; font-weight: 600;">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(17, 138, 178, 0.1); color: #118AB2;"><i class="bi bi-house-check"></i></div>
                    Kandang
                </a>
                <a href="{{ route('about') }}" class="flex items-center gap-3 px-5 py-3.5 text-[0.95rem] rounded-2xl text-bark hover:bg-[#F8F9FA] transition-colors" style="font-family: 'Inter', sans-serif; font-weight: 600;">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(153, 153, 153, 0.1); color: #666;"><i class="bi bi-info-circle"></i></div>
                    Tentang
                </a>
                <div class="px-2 py-4 flex gap-3 mt-2 border-t border-gray-100">
                    @auth
                        <a href="{{ route('dashboard.index') }}" class="flex-1 text-center font-bold text-[0.95rem] px-6 py-3 rounded-full transition-all hover:opacity-90" style="font-family: 'Inter', sans-serif; background-color: #118AB2; color: #FFF;">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="flex-1 text-center font-bold text-[0.95rem] px-6 py-3 rounded-full transition-all hover:bg-gray-100" style="font-family: 'Inter', sans-serif; border: 2px solid #EAEAEA; color: #1A1A1A;">Masuk</a>
                        <a href="{{ route('register') }}" class="flex-1 text-center font-bold text-[0.95rem] px-6 py-3 rounded-full transition-all hover:opacity-90" style="font-family: 'Inter', sans-serif; background-color: #118AB2; color: #FFF;">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>

{{-- Main Content --}}
<main>
    @yield('content')
</main>

{{-- ═══════════════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════════════ --}}
<footer style="background:#F8FAFB; border-top: 1.5px solid #EFEFEF; margin-top: 0;">
    <div style="max-width:1200px; margin:0 auto; padding: 40px 5% 32px;">

        {{-- Top row: logo + tagline --}}
        <div style="display:flex; flex-direction:column; align-items:center; text-align:center; margin-bottom:32px;">
            <a href="{{ route('home') }}" style="text-decoration:none; display:inline-flex; align-items:center; gap:6px; margin-bottom:10px;">
                <span style="font-family:'Outfit',sans-serif; font-weight:800; font-size:1.5rem; color:#1A1A1A; letter-spacing:-0.5px;">
                    My<span style="color:#06D6A0;">SugarGlider</span><span style="color:#FFD166; font-size:0.9rem;">.id</span>
                </span>
            </a>
            <p style="font-size:0.875rem; color:#999; max-width:380px; line-height:1.6; margin:0; font-family:'Inter',sans-serif;">
                Platform komunitas Sugar Glider terpercaya di Indonesia — catat silsilah, kelola kandang, dan adopsi sahabat berbulu Anda.
            </p>
        </div>

        {{-- Middle: nav links --}}
        <div style="display:flex; justify-content:center; flex-wrap:wrap; gap:6px 28px; margin-bottom:36px;">
            <a href="{{ route('home') }}"        style="font-size:0.9rem; font-weight:600; color:#555; text-decoration:none; font-family:'Inter',sans-serif; transition:color .2s;" onmouseover="this.style.color='#1A1A1A'" onmouseout="this.style.color='#555'">Beranda</a>
            <a href="{{ route('collections') }}" style="font-size:0.9rem; font-weight:600; color:#555; text-decoration:none; font-family:'Inter',sans-serif;" onmouseover="this.style.color='#1A1A1A'" onmouseout="this.style.color='#555'">Koleksi</a>
            <a href="{{ route('collections') }}?status=adopsi" style="font-size:0.9rem; font-weight:600; color:#555; text-decoration:none; font-family:'Inter',sans-serif;" onmouseover="this.style.color='#1A1A1A'" onmouseout="this.style.color='#555'">Adopsi</a>
            <a href="{{ route('shelters') }}"    style="font-size:0.9rem; font-weight:600; color:#555; text-decoration:none; font-family:'Inter',sans-serif;" onmouseover="this.style.color='#1A1A1A'" onmouseout="this.style.color='#555'">Kandang</a>
            <a href="{{ route('about') }}"       style="font-size:0.9rem; font-weight:600; color:#555; text-decoration:none; font-family:'Inter',sans-serif;" onmouseover="this.style.color='#1A1A1A'" onmouseout="this.style.color='#555'">Tentang</a>
        </div>

        {{-- Bottom row: social icons + copyright --}}
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; padding-top:24px; border-top:1.5px solid #EFEFEF;">

            {{-- Social icons --}}
            <div style="display:flex; gap:10px;">
                <a href="#" style="width:36px;height:36px;border-radius:50%;background:#ADE8F4;display:flex;align-items:center;justify-content:center;color:#118AB2;font-size:0.95rem;text-decoration:none;transition:opacity .2s;" onmouseover="this.style.opacity='.75'" onmouseout="this.style.opacity='1'">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="#" style="width:36px;height:36px;border-radius:50%;background:#ADE8F4;display:flex;align-items:center;justify-content:center;color:#118AB2;font-size:0.95rem;text-decoration:none;transition:opacity .2s;" onmouseover="this.style.opacity='.75'" onmouseout="this.style.opacity='1'">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="#" style="width:36px;height:36px;border-radius:50%;background:#B7E4C7;display:flex;align-items:center;justify-content:center;color:#1A6B3C;font-size:0.95rem;text-decoration:none;transition:opacity .2s;" onmouseover="this.style.opacity='.75'" onmouseout="this.style.opacity='1'">
                    <i class="bi bi-tiktok"></i>
                </a>
                <a href="#" style="width:36px;height:36px;border-radius:50%;background:#FFE9A0;display:flex;align-items:center;justify-content:center;color:#B37D00;font-size:0.95rem;text-decoration:none;transition:opacity .2s;" onmouseover="this.style.opacity='.75'" onmouseout="this.style.opacity='1'">
                    <i class="bi bi-youtube"></i>
                </a>
            </div>

            {{-- Copyright --}}
            <p style="font-size:0.8rem; color:#bbb; margin:0; font-family:'Inter',sans-serif;">
                &copy; {{ date('Y') }} MySugarGlider.id &nbsp;·&nbsp; All rights reserved.
            </p>
        </div>

    </div>
</footer>

{{-- Back to top --}}
<button id="back-to-top"
        class="fixed bottom-6 right-6 w-11 h-11 bg-sage text-white rounded-full shadow-hover
               flex items-center justify-center z-40 opacity-0 pointer-events-none
               hover:bg-sage-dark transition-all duration-300"
        onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="bi bi-chevron-up font-bold"></i>
</button>

@stack('scripts')

<script>
// Navbar scroll effect
const navbar = document.getElementById('navbar');
const btt    = document.getElementById('back-to-top');
window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
        navbar.classList.add('bg-white/95', 'shadow-soft', 'backdrop-blur-md', 'is-scrolled');
        btt.classList.remove('opacity-0', 'pointer-events-none');
        btt.classList.add('opacity-100');
    } else {
        navbar.classList.remove('bg-white/95', 'shadow-soft', 'backdrop-blur-md', 'is-scrolled');
        btt.classList.add('opacity-0', 'pointer-events-none');
        btt.classList.remove('opacity-100');
    }
});

// Mobile menu toggle
const mobileToggle = document.getElementById('mobile-toggle');
const mobileMenu   = document.getElementById('mobile-menu');
const mobileIcon   = document.getElementById('mobile-icon');
mobileToggle.addEventListener('click', () => {
    const isOpen = !mobileMenu.classList.contains('hidden');
    mobileMenu.classList.toggle('hidden', isOpen);
    mobileIcon.className = isOpen ? 'bi bi-list text-2xl' : 'bi bi-x-lg text-xl';
});
</script>
</body>
</html>
