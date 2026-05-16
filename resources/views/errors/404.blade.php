@extends('layouts.v_main')

@section('title', '404 – Halaman Tidak Ditemukan')

@section('navbar-class', 'bg-white/95 shadow-soft backdrop-blur-md is-scrolled')

@section('content')
<section class="min-h-screen flex items-center justify-center px-5 pt-[70px]">
    <div class="text-center max-w-lg mx-auto py-20">

        {{-- Mascot --}}
        <div class="relative inline-block mb-6">
            <img src="{{ asset('assets/images/mascot/glider-sad.svg') }}"
                 class="w-32 h-32 mx-auto" alt="Sugar Glider sedih">
        </div>

        {{-- Error code --}}
        <p class="font-number font-extrabold text-8xl sm:text-9xl leading-none mb-2"
           style="color: #FFD166; letter-spacing: -4px;">404</p>

        {{-- Title --}}
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-bark mb-3">
            Halaman Tidak Ditemukan
        </h1>

        {{-- Message --}}
        <p class="font-ui text-bark-muted text-base leading-relaxed mb-8">
            Sepertinya halaman yang Anda cari tidak ada,<br class="hidden sm:block">
            sudah dipindahkan, atau alamatnya salah.
        </p>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}"
               class="font-ui w-full sm:w-auto inline-flex items-center justify-center gap-2
                      px-7 py-3 rounded-full font-bold text-white transition-all hover:opacity-90"
               style="background-color: #118AB2; box-shadow: 0 4px 15px rgba(17,138,178,0.25);">
                <i class="bi bi-house"></i>
                Kembali ke Beranda
            </a>
            <button onclick="history.back()"
                    class="font-ui w-full sm:w-auto inline-flex items-center justify-center gap-2
                           px-7 py-3 rounded-full font-bold text-bark border-2 border-cream-dark
                           hover:border-bark-muted transition-all bg-white">
                <i class="bi bi-arrow-left"></i>
                Halaman Sebelumnya
            </button>
        </div>

    </div>
</section>
@endsection
