@include('layouts.v_header')
<body class="bg-cream min-h-screen flex items-center justify-center p-6">

<div class="text-center max-w-md mx-auto animate-fade-in">
    <img src="{{ asset('assets/images/mascot/glider-sad.svg') }}"
         alt="Halaman tidak ditemukan"
         class="w-48 mx-auto mb-6">

    <div class="inline-flex items-center gap-2 bg-honey/20 text-honey-dark text-xs font-semibold px-4 py-1.5 rounded-full mb-4">
        <i class="bi bi-exclamation-triangle"></i> Error 404
    </div>

    <h1 class="text-3xl font-display text-bark mb-3">Halaman Tidak Ditemukan</h1>
    <p class="text-bark-muted leading-relaxed mb-8">
        Halaman yang kamu cari tidak ada atau sudah dihapus. Sugar glider pun bingung mencarinya!
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('home') }}" class="btn-primary">
            <i class="bi bi-house"></i> Kembali ke Beranda
        </a>
        <a href="{{ route('collections') }}" class="btn-secondary">
            <i class="bi bi-collection"></i> Lihat Koleksi
        </a>
    </div>
</div>

@include('layouts.v_footer')
