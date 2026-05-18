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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Nunito:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-50 font-body text-bark antialiased">

@include('partials._sidebar')

<div class="lg:pl-64 min-h-screen flex flex-col">

    @include('partials._topbar')

    {{-- Page content --}}
    <main class="be-content flex-1 p-4 sm:p-6">
        <x-alert :message="session('pesan') ?? session('success')" />
        <x-alert type="danger" :message="session('error')" />

        @yield('content')
    </main>

    @include('partials._footer')

</div>

{{-- WhatsApp Chat (user only) --}}
@php $waNumber = \App\Models\AppConfig::get('contact_whatsapp'); @endphp
@if ($waNumber && !Auth::user()->isAdmin())
<div class="fixed bottom-6 right-6 z-40">
    <span class="absolute inset-0 rounded-full bg-sage/50 cs-ping"></span>
    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo admin MySugarGlider.id, saya ingin bertanya...') }}"
       target="_blank" rel="noopener"
       class="relative w-11 h-11 bg-sage text-white rounded-full shadow-hover
              flex items-center justify-center hover:bg-sage-dark transition-all duration-300"
       title="Chat dengan Admin">
        <i class="bi bi-headset text-lg"></i>
    </a>
</div>
@endif

{{-- Scroll to top --}}
<button id="back-to-top"
        class="fixed {{ ($waNumber && !Auth::user()->isAdmin()) ? 'bottom-20' : 'bottom-6' }} right-6 w-11 h-11 bg-sage text-white rounded-full shadow-hover
               flex items-center justify-center z-40 opacity-0 pointer-events-none
               hover:bg-sage-dark transition-all duration-300"
        onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="bi bi-chevron-up"></i>
</button>

<script>
function openSidebar() {
    document.getElementById('sidebar').classList.add('is-open');
    document.getElementById('sidebar-overlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('is-open');
    document.getElementById('sidebar-overlay').classList.add('hidden');
    document.body.style.overflow = '';
}
document.getElementById('sidebar-close')?.addEventListener('click', closeSidebar);
window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
        document.getElementById('sidebar-overlay').classList.add('hidden');
        document.body.style.overflow = '';
    }
});
const btt = document.getElementById('back-to-top');
window.addEventListener('scroll', () => {
    if (window.scrollY > 200) {
        btt.classList.remove('opacity-0', 'pointer-events-none');
        btt.classList.add('opacity-100');
    } else {
        btt.classList.add('opacity-0', 'pointer-events-none');
        btt.classList.remove('opacity-100');
    }
});
</script>

@stack('scripts')

<script>
(function () {
    const bell        = document.getElementById('notif-bell');
    const badge       = document.getElementById('notif-badge');
    const dropdown    = document.getElementById('notif-dropdown');
    const list        = document.getElementById('notif-list');
    const readAllBtn  = document.getElementById('notif-read-all');
    const csrfToken   = document.querySelector('meta[name=csrf-token]')?.content ?? '';
    const favicon     = '{{ asset("assets/images/favicon.png") }}';

    let dropdownOpen     = false;
    let lastUnreadCount  = -1;

    // ── Toggle dropdown ──────────────────────────────────────
    bell?.addEventListener('click', e => {
        e.stopPropagation();
        dropdownOpen = !dropdownOpen;
        dropdown.classList.toggle('hidden', !dropdownOpen);
        if (dropdownOpen) fetchNotifications();
    });

    document.addEventListener('click', () => {
        if (dropdownOpen) { dropdownOpen = false; dropdown.classList.add('hidden'); }
    });
    dropdown?.addEventListener('click', e => e.stopPropagation());

    // ── Fetch & render ───────────────────────────────────────
    async function fetchNotifications(checkNew = false) {
        try {
            const res  = await fetch('{{ route("notifications.index") }}');
            const data = await res.json();

            // Browser popup saat ada notif baru
            if (checkNew && lastUnreadCount >= 0 && data.unread_count > lastUnreadCount) {
                const newest = data.notifications.find(n => !n.read);
                if (newest) showBrowserNotif(newest.title, newest.body, newest.url);
            }
            lastUnreadCount = data.unread_count;

            // Badge
            if (data.unread_count > 0) {
                badge.classList.remove('hidden');
                badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
            } else {
                badge.classList.add('hidden');
            }

            // List
            if (!data.notifications.length) {
                list.innerHTML = '<div class="p-6 text-center text-bark-muted text-sm"><i class="bi bi-bell-slash block text-2xl mb-2 opacity-40"></i>Belum ada notifikasi</div>';
                return;
            }

            list.innerHTML = data.notifications.map(n => `
                <a href="${escHtml(n.url)}" data-id="${escHtml(n.id)}"
                   class="notif-item flex items-start gap-3 px-4 py-3 hover:bg-cream transition-colors ${n.read ? '' : 'bg-sage/5'}">
                    <span class="w-8 h-8 rounded-xl flex-shrink-0 mt-0.5 flex items-center justify-center ${n.read ? 'bg-gray-100' : 'bg-sage/10'}">
                        <i class="bi ${escHtml(n.icon)} text-sm ${n.read ? 'text-gray-400' : 'text-sage'}"></i>
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-bark truncate">${escHtml(n.title)}</p>
                        <p class="text-xs text-bark-muted mt-0.5 line-clamp-2">${escHtml(n.body)}</p>
                        <p class="text-[10px] text-bark-muted/60 mt-0.5">${escHtml(n.time)}</p>
                    </div>
                    ${!n.read ? '<span class="w-2 h-2 rounded-full bg-sage flex-shrink-0 mt-2"></span>' : ''}
                </a>
            `).join('');

            list.querySelectorAll('.notif-item').forEach(el => {
                el.addEventListener('click', async function () {
                    if (!this.dataset.id) return;
                    await fetch(`/my/notifications/${this.dataset.id}/read`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                    });
                });
            });
        } catch (_) {}
    }

    // ── Mark all read ────────────────────────────────────────
    readAllBtn?.addEventListener('click', async () => {
        await fetch('{{ route("notifications.read-all") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
        });
        fetchNotifications();
    });

    // ── Browser Notification API ──────────────────────────────
    async function requestPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            await Notification.requestPermission();
        }
    }

    function showBrowserNotif(title, body, url) {
        if (!('Notification' in window) || Notification.permission !== 'granted') return;
        const n = new Notification(title, { body, icon: favicon });
        n.onclick = () => { window.focus(); window.location.href = url; n.close(); };
        setTimeout(() => n.close(), 6000);
    }

    function escHtml(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ── Init ─────────────────────────────────────────────────
    requestPermission();
    fetchNotifications();
    setInterval(() => fetchNotifications(true), 30000);
})();
</script>
</body>
</html>
