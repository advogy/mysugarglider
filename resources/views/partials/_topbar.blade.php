<header class="topbar">
    <button id="sidebar-open" class="lg:hidden p-2 rounded-xl hover:bg-cream transition-colors" onclick="openSidebar()">
        <i class="bi bi-list text-xl text-bark"></i>
    </button>

    <div class="hidden lg:block">
        <h1 class="font-ui text-base font-bold text-bark">@yield('title')</h1>
    </div>

    <a href="{{ route('dashboard.index') }}" class="lg:hidden flex-shrink-0 no-underline hover:opacity-80 transition-opacity">
        <span class="site-logo font-number font-extrabold text-bark text-xl">
            My<span class="logo-sg">SugarGlider</span><span class="logo-id">.id</span>
        </span>
    </a>

    <div class="flex items-center gap-2 sm:gap-3">
        <a href="{{ route('home') }}" target="_blank" class="btn-ghost text-xs">
            <i class="bi bi-globe text-sm"></i>
            <span class="hidden sm:inline">Lihat Situs</span>
        </a>

        {{-- User dropdown --}}
        <div class="relative group">
            {{-- Trigger --}}
            <div class="flex items-center gap-2 cursor-pointer rounded-xl px-2 py-1 hover:bg-cream transition-colors select-none">
                <div class="w-8 h-8 rounded-xl overflow-hidden bg-sage-100 flex-shrink-0">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('/upload/avatars/' . Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="bi bi-person-fill text-sage text-xs"></i>
                        </div>
                    @endif
                </div>
                <span class="font-ui hidden sm:block text-sm font-bold text-bark">{{ Auth::user()->name }}</span>
                <i class="bi bi-chevron-down hidden sm:block text-xs text-bark-muted duration-200 group-hover:rotate-180 transition-transform"></i>
            </div>

            {{-- Dropdown panel --}}
            <div class="absolute right-0 top-full pt-1 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-150 z-50 w-64">
                <div class="bg-white rounded-2xl shadow-xl border border-cream-dark overflow-hidden">

                    {{-- Header: avatar + name + email --}}
                    <div class="px-4 py-3 border-b border-cream-dark flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl overflow-hidden bg-sage-100 flex-shrink-0">
                            @if (Auth::user()->avatar)
                                <img src="{{ asset('/upload/avatars/' . Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="bi bi-person-fill text-sage text-sm"></i>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="font-ui text-sm font-bold text-bark truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-bark-muted truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    @if (!Auth::user()->isAdmin())
                    {{-- Poin + Level --}}
                    @php $lvl = Auth::user()->level(); @endphp
                    <div class="px-4 py-3 border-b border-cream-dark flex items-center gap-3">
                        <div class="flex-1 text-center">
                            <p class="text-xs text-bark-muted mb-0.5">Poin</p>
                            <p class="font-ui font-bold text-bark text-sm">{{ number_format(Auth::user()->total_points ?? 0) }}</p>
                        </div>
                        <div class="w-px h-8 bg-cream-dark"></div>
                        <div class="flex-1 text-center">
                            <p class="text-xs text-bark-muted mb-0.5">Level</p>
                            <p class="font-ui font-bold text-sm {{ $lvl['color'] }}">{{ $lvl['label'] }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Menu items --}}
                    <div class="py-1.5">
                        <a href="{{ route('profile') }}"
                           class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-bark hover:bg-cream transition-colors {{ request()->routeIs('profile') ? 'bg-cream font-bold' : '' }}">
                            <i class="bi bi-person-badge-fill text-bark-muted w-4 text-sm"></i>
                            <span class="font-ui font-semibold">Profil</span>
                        </a>
                        @if (!Auth::user()->isAdmin())
                        <a href="{{ route('points.index') }}"
                           class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-bark hover:bg-cream transition-colors {{ request()->is('*points*') && !request()->is('*admin*') ? 'bg-cream font-bold' : '' }}">
                            <i class="bi bi-star-fill text-bark-muted w-4 text-sm"></i>
                            <span class="font-ui font-semibold">Poin Saya</span>
                        </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <i class="bi bi-box-arrow-left w-4 text-sm"></i>
                                <span class="font-ui font-semibold">Keluar</span>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>
