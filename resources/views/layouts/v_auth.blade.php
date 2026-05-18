<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') — MySugarGlider</title>

    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-body antialiased text-bark bg-white auth-page">

<div class="min-h-screen flex">

    {{-- ── Left: Form panel ─────────────────────────────── --}}
    <div class="w-full lg:w-1/2 flex flex-col bg-white">

        {{-- Logo — sticky at top, left-aligned --}}
        <div class="auth-logo-bar sticky top-0 bg-white z-10 flex items-center h-[70px] flex-shrink-0">
            <a href="{{ route('home') }}" class="auth-logo-link">
                <span class="auth-logo-text">
                    My<span class="auth-logo-green">SugarGlider</span><span class="auth-logo-gold">.id</span>
                </span>
            </a>
        </div>

        {{-- Form area — vertically centered --}}
        <div class="flex-1 flex flex-col justify-center px-5 sm:px-8 py-10">
            <div class="max-w-[400px] w-full mx-auto auth-form">
                @yield('form')
            </div>
        </div>

    </div>

    {{-- ── Right: Decorative panel ─────────────────────── --}}
    <div class="hidden lg:flex lg:w-1/2 relative decorative-panel items-center justify-center">

        <div class="auth-blob-1"></div>
        <div class="auth-blob-2"></div>

        <div class="relative z-10 text-center px-12 w-full">

            <img src="{{ asset('assets/images/pets/sg_hero_1778842679372.png') }}"
                 alt="Sugar Glider" class="auth-sg-img">

            <h2 class="auth-panel-title">
                Komunitas Sugar Glider<br>Terpercaya #1 Indonesia
            </h2>
            <p class="auth-panel-desc">
                Catat silsilah, kelola kandang, dan temukan sahabat berbulu impian Anda bersama ribuan pecinta lainnya.
            </p>

            {{-- Stats — supplied by AppServiceProvider View Composer --}}
            <div class="auth-stats">
                <div class="auth-stat-box stat-divider">
                    <div class="auth-stat-num color-yellow">{{ $stat_sg }}+</div>
                    <div class="auth-stat-label">Sugar Glider</div>
                </div>
                <div class="auth-stat-box stat-divider">
                    <div class="auth-stat-num color-blue">{{ $stat_shelter }}+</div>
                    <div class="auth-stat-label">Kandang</div>
                </div>
                <div class="auth-stat-box">
                    <div class="auth-stat-num color-green">{{ $stat_user }}+</div>
                    <div class="auth-stat-label">Member</div>
                </div>
            </div>
        </div>

    </div>
</div>

@stack('scripts')
<script>
function togglePassword(btnEl) {
    const input = btnEl.closest('.relative').querySelector('input');
    const icon  = btnEl.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
</body>
</html>
