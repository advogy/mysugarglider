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
        <div class="flex items-center gap-2">
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
        </div>
    </div>
</header>
