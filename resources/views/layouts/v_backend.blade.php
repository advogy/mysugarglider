<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') – {{ config('app.name') }}</title>

    <link href="{{ asset('assets/images/favicon.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-cream font-body text-bark antialiased">

{{-- Sidebar --}}
<aside id="sidebar" class="sidebar translate-x-0 xl:translate-x-0 scrollbar-thin overflow-y-auto">

    <div class="flex items-center justify-between px-5 py-5 border-b border-white/10 flex-shrink-0">
        <a href="{{ route('index') }}" class="flex items-center gap-2">
            <img src="{{ asset('assets/images/logo/logo.svg') }}" alt="{{ config('app.name') }}" class="h-7 brightness-0 invert">
        </a>
        <button id="sidebar-close" class="xl:hidden text-white/50 hover:text-white transition-colors">
            <i class="bi bi-x text-2xl"></i>
        </button>
    </div>

    <div class="px-5 py-4 border-b border-white/10 flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl overflow-hidden bg-sage flex-shrink-0">
                @if (Auth::user()->avatar)
                    <img src="{{ asset('/upload/avatars/' . Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="bi bi-person-fill text-white text-sm"></i>
                    </div>
                @endif
            </div>
            <div class="min-w-0">
                <p class="text-white text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                <p class="text-white/40 text-xs truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-0.5">

        <p class="sidebar-title">Menu Utama</p>
        <a href="{{ route('dashboard.index') }}" class="sidebar-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <i class="bi bi-grid-fill text-base"></i><span>Dashboard</span>
        </a>

        <p class="sidebar-title">Data Saya</p>
        <a href="{{ route('shelter.index') }}" class="sidebar-link {{ request()->is('*shelters*') ? 'active' : '' }}">
            <i class="bi bi-house-heart-fill text-base"></i><span>{{ __('text.shelter_data') }}</span>
        </a>
        <a href="{{ route('sugarglider.index') }}" class="sidebar-link {{ request()->is('*sugargliders*') ? 'active' : '' }}">
            <i class="bi bi-heart-fill text-base"></i><span>{{ __('text.sugarglider_data') }}</span>
        </a>
        <a href="{{ route('collection.index') }}" class="sidebar-link {{ request()->is('*collections*') ? 'active' : '' }}">
            <i class="bi bi-collection-fill text-base"></i><span>{{ __('text.collection_data') }}</span>
        </a>
        <a href="{{ route('adoption.index') }}" class="sidebar-link {{ request()->is('*adoptions*') ? 'active' : '' }}">
            <i class="bi bi-journal-check text-base"></i><span>{{ __('text.adoption_data') }}</span>
        </a>

        <p class="sidebar-title">Jelajahi</p>
        <a href="{{ route('pedigree.index') }}" class="sidebar-link {{ request()->is('*pedigree*') ? 'active' : '' }}">
            <i class="bi bi-diagram-3-fill text-base"></i><span>{{ __('text.pedigree') }}</span>
        </a>
        <a href="{{ route('adoption.list') }}" class="sidebar-link {{ request()->routeIs('adoption.list') ? 'active' : '' }}">
            <i class="bi bi-heart-arrow text-base"></i><span>Adopsi Baru</span>
        </a>

        <p class="sidebar-title">Akun</p>
        <a href="{{ route('profile') }}" class="sidebar-link {{ request()->routeIs('profile') ? 'active' : '' }}">
            <i class="bi bi-person-badge-fill text-base"></i><span>{{ __('text.profile') }}</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
        <button type="button" onclick="document.getElementById('logout-form').submit()" class="sidebar-link w-full text-left">
            <i class="bi bi-box-arrow-left text-base"></i><span>{{ __('text.logout') }}</span>
        </button>
    </nav>

    <div class="px-5 py-4 border-t border-white/10 flex-shrink-0">
        <p class="text-white/25 text-xs font-semibold">v{{ config('app.version', '1.0') }}</p>
    </div>
</aside>

<div id="sidebar-overlay" class="fixed inset-0 bg-bark/50 z-30 xl:hidden hidden backdrop-blur-sm" onclick="closeSidebar()"></div>

<div class="xl:pl-64 min-h-screen flex flex-col transition-all duration-300">

    {{-- Topbar --}}
    <header class="topbar">
        <button id="sidebar-open" class="xl:hidden p-2 rounded-xl hover:bg-cream transition-colors" onclick="openSidebar()">
            <i class="bi bi-list text-xl text-bark"></i>
        </button>

        <div class="hidden xl:block">
            <h1 class="text-base font-bold text-bark">@yield('title')</h1>
        </div>

        <a href="{{ route('dashboard.index') }}" class="xl:hidden">
            <img src="{{ asset('assets/images/logo/logo.svg') }}" alt="{{ config('app.name') }}" class="h-7">
        </a>

        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" target="_blank" class="btn-ghost text-xs">
                <i class="bi bi-globe text-sm"></i>
                <span class="hidden sm:inline">Lihat Situs</span>
            </a>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl overflow-hidden bg-sage flex-shrink-0">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('/upload/avatars/' . Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="bi bi-person-fill text-white text-xs"></i>
                        </div>
                    @endif
                </div>
                <span class="hidden sm:block text-sm font-bold text-bark">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </header>

    {{-- Page content --}}
    <main class="flex-1 p-6">
        @if (session('success'))
            <div class="alert-success mb-6">
                <i class="bi bi-check-circle-fill text-lg"></i>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="alert-danger mb-6">
                <i class="bi bi-exclamation-circle-fill text-lg"></i>
                <p class="font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="px-6 py-4 border-t border-cream-dark flex flex-col sm:flex-row items-center justify-between gap-2">
        <p class="text-bark-muted text-xs">
            &copy; 2022–{{ date('Y') }} <span class="font-semibold text-bark">{{ config('app.name') }}</span>
        </p>
        <p class="text-bark-muted text-xs">
            Developed by <a href="https://athoria.me" target="_blank" rel="noopener" class="text-sage font-semibold hover:underline">AthoRia.me</a>
        </p>
    </footer>
</div>

<script>
function openSidebar() {
    document.getElementById('sidebar').classList.remove('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.add('hidden');
    document.body.style.overflow = '';
}
document.getElementById('sidebar-close')?.addEventListener('click', closeSidebar);
window.addEventListener('resize', () => {
    if (window.innerWidth >= 1280) {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.add('hidden');
        document.body.style.overflow = '';
    }
});
if (window.innerWidth < 1280) {
    document.getElementById('sidebar').classList.add('-translate-x-full');
}
</script>

@stack('scripts')
</body>
</html>
