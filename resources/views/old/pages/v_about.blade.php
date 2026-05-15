@extends('layouts.v_main')

@push('meta')
    <meta name="description" content="Tentang MySugarGlider.id — platform data & komunitas peternak sugar glider Indonesia.">
@endpush

@section('title', 'Tentang Kami')

@section('content')

<x-page-hero
    title="Tentang MySugarGlider"
    :breadcrumbs="[['label'=>'Beranda','url'=>route('home')],['label'=>'Tentang']]"
/>

{{-- Story --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            {{-- Visual --}}
            <div class="relative flex justify-center order-2 lg:order-1">
                <div class="w-72 h-72 bg-sage-100"
                     style="border-radius:60% 40% 30% 70% / 60% 30% 70% 40%;"></div>
                <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}"
                     alt="Sugar Glider"
                     class="absolute inset-0 w-full h-full object-contain p-10 animate-float">

                <div class="absolute top-4 right-4 bg-white rounded-2xl shadow-card px-4 py-3 text-center animate-float-2">
                    <div class="text-2xl font-display font-bold text-sage">2022</div>
                    <div class="text-xs text-bark-muted font-semibold">Tahun Berdiri</div>
                </div>
                <div class="absolute bottom-4 left-4 bg-honey rounded-2xl shadow-card px-4 py-3 text-center animate-float-3">
                    <div class="text-2xl font-display font-bold text-bark">🇮🇩</div>
                    <div class="text-xs text-bark font-bold">Made in Indonesia</div>
                </div>
            </div>

            {{-- Text --}}
            <div class="order-1 lg:order-2">
                <x-section-header
                    label="Kisah Kami"
                    title="Karena Sugar Glider Anda begitu penting..."
                    :center="false"
                />
                <div class="space-y-4 text-bark-light text-base leading-relaxed mt-5">
                    <p>
                        <a href="{{ route('home') }}" class="text-sage font-bold hover:underline">MySugarGlider.id</a>
                        didirikan pada Desember 2022 — bermula dari beberapa koleksi sugar glider sebagai hobi, lalu berkembang menjadi kecintaan mendalam agar setiap hewan mendapat perawatan terbaik dan menghasilkan keturunan berkualitas.
                    </p>
                    <p>
                        Platform ini hadir sebagai wadah bagi para pencinta, pemilik, dan peternak sugar glider dalam menyimpan data silsilah. Dengan silsilah yang akurat, Anda bisa mendapatkan keturunan sugar glider yang berkualitas tinggi.
                    </p>
                    <p class="font-bold text-sage text-lg">Salam lestari Sugar Glider!</p>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    @auth
                        <a href="{{ route('dashboard.index') }}" class="btn-primary">
                            <i class="bi bi-grid-fill"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary">
                            Mulai Bergabung <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('login') }}" class="btn-secondary">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Features --}}
<section class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-6">
        <x-section-header
            label="Fitur Platform"
            title="Semua yang Anda butuhkan"
            subtitle="Dirancang khusus untuk komunitas peternak sugar glider Indonesia."
            class="mb-12"
        />

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $features = [
                    ['icon'=>'bi-diagram-3',   'color'=>'sage',  'title'=>'Silsilah Lengkap',     'desc'=>'Rekam indukan jantan & betina untuk menelusuri pohon keturunan hingga beberapa generasi.'],
                    ['icon'=>'bi-gem',          'color'=>'honey', 'title'=>'Kualitas Terjamin',    'desc'=>'Catat genetika, fenotype, dan morph untuk breeding berkualitas terbaik.'],
                    ['icon'=>'bi-heart-arrow',  'color'=>'sage',  'title'=>'Adopsi Terpercaya',   'desc'=>'Sistem adopsi terverifikasi dengan alur yang jelas dari penawaran hingga selesai.'],
                    ['icon'=>'bi-people',       'color'=>'sky',   'title'=>'Komunitas Aktif',     'desc'=>'Bergabung dengan ratusan peternak dari seluruh Indonesia dan saling berbagi.'],
                ];
                $fBg = ['sage'=>'bg-sage-100 text-sage','honey'=>'bg-honey-50 text-honey-dark','sky'=>'bg-sky text-blue-600'];
            @endphp
            @foreach ($features as $f)
                <div class="card p-6 hover:shadow-hover transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 {{ $fBg[$f['color']] }} rounded-2xl flex items-center justify-center mb-4">
                        <i class="bi {{ $f['icon'] }} text-xl"></i>
                    </div>
                    <h4 class="font-bold text-bark mb-2">{{ $f['title'] }}</h4>
                    <p class="text-bark-muted text-sm leading-relaxed">{{ $f['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Stats banner --}}
<section class="py-16 bg-sage">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-3 gap-6 text-center">
            <div>
                <div class="text-4xl md:text-5xl font-display font-bold text-white mb-1">2022</div>
                <p class="text-white/60 text-sm font-semibold">Tahun Berdiri</p>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-display font-bold text-honey mb-1">100%</div>
                <p class="text-white/60 text-sm font-semibold">Komunitas Peternak</p>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-display font-bold text-white mb-1">🇮🇩</div>
                <p class="text-white/60 text-sm font-semibold">Made in Indonesia</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-20 bg-white">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <x-section-header
            label="Bergabung Sekarang"
            title="Jadilah bagian dari komunitas"
            subtitle="Daftar gratis dan mulai catat data sugar glider Anda hari ini. Tidak ada biaya tersembunyi."
        />
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="btn-primary px-8 py-4">
                Daftar Gratis <i class="bi bi-arrow-right"></i>
            </a>
            <a href="{{ route('collections') }}" class="btn-secondary px-8 py-4">
                <i class="bi bi-search"></i> Lihat Koleksi
            </a>
        </div>
    </div>
</section>

@endsection
