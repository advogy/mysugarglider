<aside id="sidebar" class="sidebar scrollbar-thin overflow-y-auto">

    <div class="flex items-center justify-between px-5 py-[18px] border-b border-cream-dark flex-shrink-0">
        <a href="{{ route('index') }}" class="flex-shrink-0 no-underline hover:opacity-80 transition-opacity">
            <span class="site-logo font-number font-extrabold text-bark text-2xl">
                My<span class="logo-sg">SugarGlider</span><span class="logo-id">.id</span>
            </span>
        </a>
        <button id="sidebar-close" class="lg:hidden text-bark-muted hover:text-bark transition-colors p-1">
            <i class="bi bi-x text-2xl"></i>
        </button>
    </div>

    <div class="px-5 py-4 border-b border-cream-dark flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl overflow-hidden bg-sage-100 flex-shrink-0">
                @if (Auth::user()->avatar)
                    <img src="{{ asset('/upload/avatars/' . Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="bi bi-person-fill text-sage text-sm"></i>
                    </div>
                @endif
            </div>
            <div class="min-w-0">
                <p class="text-bark text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                <p class="text-bark-muted text-xs truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-0.5">

        <p class="sidebar-title">Menu Utama</p>
        <a href="{{ route('dashboard.index') }}" class="sidebar-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <i class="bi bi-grid-fill text-base"></i><span>Dashboard</span>
        </a>

        <p class="sidebar-title">Kelola Data</p>
        <a href="{{ route('shelter.index') }}" class="sidebar-link {{ request()->is('*shelters*') ? 'active' : '' }}">
            <i class="bi bi-house-heart-fill text-base"></i><span>{{ __('text.shelter_data') }}</span>
        </a>
        <a href="{{ route('sugarglider.index') }}" class="sidebar-link {{ request()->is('*sugargliders*') ? 'active' : '' }}">
            <i class="bi bi-heart-fill text-base"></i><span>{{ __('text.sugarglider_data') }}</span>
        </a>
        <a href="{{ route('collection.index') }}" class="sidebar-link {{ request()->is('*collections*') ? 'active' : '' }}">
            <i class="bi bi-collection-fill text-base"></i><span>{{ __('text.collection_data') }}</span>
        </a>

        <p class="sidebar-title">Adopsi</p>
        <a href="{{ route('adoption.index') }}" class="sidebar-link {{ (request()->is('*adoptions*') && !request()->routeIs('adoption.list')) ? 'active' : '' }}">
            <i class="bi bi-journal-check text-base"></i><span>Adopsi Saya</span>
        </a>
        <a href="{{ route('adoption.list') }}" class="sidebar-link {{ request()->routeIs('adoption.list') ? 'active' : '' }}">
            <i class="bi bi-heart-arrow text-base"></i><span>Cari Adopsi</span>
        </a>

        <p class="sidebar-title">Akun</p>
        <a href="{{ route('points.index') }}" class="sidebar-link {{ request()->is('*points*') ? 'active' : '' }}">
            <i class="bi bi-star-fill text-base"></i><span>Poin Saya</span>
        </a>
        @if (Auth::user()->is_admin)
        <p class="sidebar-title">Admin</p>
        <a href="{{ route('testimonial.admin') }}" class="sidebar-link {{ request()->routeIs('testimonial.admin') ? 'active' : '' }}">
            <i class="bi bi-chat-quote-fill text-base"></i><span>Testimoni</span>
        </a>
        @endif
        <a href="{{ route('profile') }}" class="sidebar-link {{ request()->routeIs('profile') ? 'active' : '' }}">
            <i class="bi bi-person-badge-fill text-base"></i><span>{{ __('text.profile') }}</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
        <button type="button" onclick="document.getElementById('logout-form').submit()" class="sidebar-link w-full text-left">
            <i class="bi bi-box-arrow-left text-base"></i><span>{{ __('text.logout') }}</span>
        </button>
    </nav>

</aside>

<div id="sidebar-overlay" class="fixed inset-0 bg-bark/50 z-30 lg:hidden hidden backdrop-blur-sm" onclick="closeSidebar()"></div>
