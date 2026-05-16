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
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@400;500;600;700&family=Nunito:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-cream font-body text-bark antialiased">

@include('partials._sidebar')

<div class="lg:pl-64 min-h-screen flex flex-col">

    @include('partials._topbar')

    {{-- Page content --}}
    <main class="be-content flex-1 p-4 sm:p-6">
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

    @include('partials._footer')

</div>

{{-- Scroll to top --}}
<button id="back-to-top"
        class="fixed bottom-6 right-6 w-11 h-11 bg-sage text-white rounded-full shadow-hover
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
</body>
</html>
