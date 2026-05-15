@include('layouts.v_header')
<body class="bg-cream min-h-screen flex items-center justify-center p-6">

<div class="text-center max-w-md mx-auto animate-fade-in">
    <img src="{{ asset('assets/images/mascot/glider-sad.svg') }}"
         alt="Tidak diizinkan"
         class="w-48 mx-auto mb-6">

    <div class="inline-flex items-center gap-2 bg-red-100 text-red-600 text-xs font-semibold px-4 py-1.5 rounded-full mb-4">
        <i class="bi bi-shield-x"></i> Error 403
    </div>

    <h1 class="text-3xl font-display text-bark mb-3">Akses Ditolak</h1>
    <p class="text-bark-muted leading-relaxed mb-8">
        Maaf, kamu tidak memiliki izin untuk mengakses halaman ini. Pastikan kamu sudah login dengan akun yang benar.
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('home') }}" class="btn-primary">
            <i class="bi bi-house"></i> Kembali ke Beranda
        </a>
        @guest
        <a href="{{ route('login') }}" class="btn-secondary">
            <i class="bi bi-box-arrow-in-right"></i> Masuk
        </a>
        @endguest
    </div>
</div>

@include('layouts.v_footer')
