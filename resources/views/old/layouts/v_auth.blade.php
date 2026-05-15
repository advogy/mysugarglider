@include('layouts.v_header')

<body class="bg-cream font-body min-h-screen">

<div class="min-h-screen grid lg:grid-cols-2">

    {{-- ─── Left: Form Panel ─── --}}
    <div class="flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-16 xl:px-24">

        {{-- Logo --}}
        <a href="{{ route('index') }}" class="inline-block mb-10">
            <img src="{{ asset('assets/images/logo/logo.svg') }}"
                 alt="{{ config('app.name') }}"
                 class="h-9">
        </a>

        {{-- Form slot --}}
        <div class="max-w-md w-full">
            @yield('content')
        </div>

        {{-- Footer --}}
        <p class="mt-10 text-xs text-bark-muted">
            &copy; {{ date('Y') }} {{ config('app.name') }}
            &mdash; Dibuat dengan <span class="text-red-400">♥</span> oleh
            <a href="https://athoria.me" class="text-sage font-semibold hover:underline" target="_blank" rel="noopener">AthoRia.me</a>
        </p>
    </div>

    {{-- ─── Right: Visual Panel ─── --}}
    <div class="hidden lg:flex flex-col items-center justify-center relative overflow-hidden
                bg-gradient-to-br from-sage-dark via-sage to-sage-light">

        {{-- Decorative blobs --}}
        <div class="absolute top-10 right-10 w-64 h-64 bg-white/8 animate-blob"
             style="border-radius:60% 40% 30% 70% / 60% 30% 70% 40%;"></div>
        <div class="absolute bottom-10 left-10 w-48 h-48 bg-honey/15 animate-blob"
             style="border-radius:40% 60% 70% 30% / 60% 40% 60% 30%; animation-delay:2s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>

        {{-- Content --}}
        <div class="relative z-10 text-center px-10">
            <div class="w-36 h-36 bg-white/15 rounded-full flex items-center justify-center mx-auto mb-8 animate-float">
                <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}"
                     alt="Maskot"
                     class="w-24 drop-shadow-2xl">
            </div>
            <h2 class="text-3xl font-display text-white mb-3 leading-tight">
                Selamat datang di<br>
                <span class="text-honey">MySugarGlider</span>
            </h2>
            <p class="text-white/70 text-sm leading-relaxed max-w-xs mx-auto">
                Platform komunitas peternak sugar glider Indonesia. Catat silsilah, kelola kandang, dan temukan adopsi terbaik.
            </p>

            <div class="mt-8 flex items-center justify-center gap-6">
                <div class="text-center">
                    <div class="text-2xl font-display font-bold text-white">500+</div>
                    <div class="text-white/50 text-xs mt-0.5">Sugar Glider</div>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div class="text-center">
                    <div class="text-2xl font-display font-bold text-white">50+</div>
                    <div class="text-white/50 text-xs mt-0.5">Kandang Aktif</div>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div class="text-center">
                    <div class="text-2xl font-display font-bold text-white">200+</div>
                    <div class="text-white/50 text-xs mt-0.5">Peternak</div>
                </div>
            </div>
        </div>
    </div>
</div>

@stack('modals')
@stack('scripts')
</body>
</html>
