@extends('layouts.v_main')

@push('meta')
    <meta name="description" content="MySugarGlider.id — Platform komunitas peternak sugar glider Indonesia. Catat silsilah, kelola kandang, dan temukan adopsi terbaik.">
@endpush

@section('title', 'Database Sugar Glider Indonesia')

@section('content')

{{-- ═══════════ HERO ═══════════ --}}
<section id="hero-section"
         class="relative min-h-screen flex items-center overflow-hidden
                bg-gradient-to-br from-sage-dark via-sage to-sage-light">

    {{-- Animated blobs --}}
    <div class="absolute top-20 right-16 w-80 h-80 bg-white/8 animate-blob pointer-events-none"
         style="border-radius:60% 40% 30% 70% / 60% 30% 70% 40%;"></div>
    <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-honey/15 pointer-events-none"
         style="border-radius:40% 60% 70% 30% / 60% 40% 60% 30%;animation:blob 10s ease-in-out infinite 1s;"></div>
    <div class="absolute top-1/3 right-1/3 w-32 h-32 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 pt-28 pb-20 w-full">
        <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16">

            {{-- ─── Text ─── --}}
            <div class="flex-1 text-center lg:text-left animate-fade-in">
                <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm
                            text-white text-xs font-bold px-4 py-2 rounded-full mb-6 border border-white/20">
                    <span class="w-2 h-2 bg-honey rounded-full animate-pulse"></span>
                    Platform #1 Sugar Glider Indonesia
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-display text-white leading-[1.15] mb-6">
                    Sahabat berbulu<br>
                    mencari <span class="text-honey">rumah</span><br>
                    yang tepat
                </h1>

                <p class="text-white/75 text-lg leading-relaxed mb-8 max-w-md mx-auto lg:mx-0">
                    Catat silsilah, kelola kandang, dan temukan sugar glider adopsi terbaik dari peternak terpercaya seluruh Indonesia.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                    <a href="{{ route('collections') }}" class="btn-honey text-base px-8 py-4">
                        <i class="bi bi-search"></i> Cari Sugar Glider
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn-outline-white text-base px-8 py-4">
                            Daftar Gratis <i class="bi bi-arrow-right"></i>
                        </a>
                    @else
                        <a href="{{ route('dashboard.index') }}" class="btn-outline-white text-base px-8 py-4">
                            <i class="bi bi-grid-fill"></i> Dashboard
                        </a>
                    @endguest
                </div>

                {{-- Trust badges --}}
                <div class="mt-10 flex items-center gap-5 justify-center lg:justify-start flex-wrap">
                    <div class="flex items-center gap-2 text-white/70 text-sm">
                        <i class="bi bi-check-circle-fill text-honey text-base"></i>
                        Gratis selamanya
                    </div>
                    <div class="flex items-center gap-2 text-white/70 text-sm">
                        <i class="bi bi-check-circle-fill text-honey text-base"></i>
                        Tanpa iklan
                    </div>
                    <div class="flex items-center gap-2 text-white/70 text-sm">
                        <i class="bi bi-check-circle-fill text-honey text-base"></i>
                        Komunitas terverifikasi
                    </div>
                </div>
            </div>

            {{-- ─── Floating SG photos (PetsYou style) ─── --}}
            <div class="flex-1 flex justify-center lg:justify-end">
                <div class="relative w-80 h-80 sm:w-96 sm:h-96">

                    {{-- Large center blob --}}
                    <div class="absolute inset-0 bg-white/12 backdrop-blur-sm"
                         style="border-radius:60% 40% 30% 70% / 60% 30% 70% 40%;"></div>

                    {{-- Mascot in center --}}
                    <div class="absolute inset-0 flex items-center justify-center">
                        <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}"
                             alt="Sugar Glider"
                             class="w-52 sm:w-64 drop-shadow-2xl animate-float">
                    </div>

                    {{-- Floating badges --}}
                    <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-hover px-4 py-3
                                flex items-center gap-2 animate-float-2">
                        <div class="w-9 h-9 bg-sage-100 rounded-xl flex items-center justify-center">
                            <i class="bi bi-collection text-sage text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xl font-display font-bold text-bark leading-none">
                                {{ $count_collections }}
                            </p>
                            <p class="text-xs text-bark-muted leading-tight">Koleksi SG</p>
                        </div>
                    </div>

                    <div class="absolute -bottom-4 -left-4 bg-honey rounded-2xl shadow-hover px-4 py-3
                                flex items-center gap-2 animate-float-3">
                        <div class="w-9 h-9 bg-white/50 rounded-xl flex items-center justify-center">
                            <i class="bi bi-heart-fill text-bark text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xl font-display font-bold text-bark leading-none">
                                {{ $count_adoptions }}
                            </p>
                            <p class="text-xs text-bark font-bold leading-tight">Siap Adopsi</p>
                        </div>
                    </div>

                    <div class="absolute top-1/2 -left-10 -translate-y-1/2 bg-white rounded-2xl shadow-card
                                px-3 py-2 flex items-center gap-2 animate-float">
                        <i class="bi bi-house-heart text-sage text-base"></i>
                        <div>
                            <p class="text-sm font-bold text-bark leading-none">{{ $count_shelters }}</p>
                            <p class="text-[11px] text-bark-muted">Kandang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Wave transition --}}
    <div class="absolute bottom-0 inset-x-0">
        <svg viewBox="0 0 1440 64" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0 64L1440 64L1440 24C1200 64 960 4 720 24C480 44 240 4 0 24Z" fill="#ffffff"/>
        </svg>
    </div>
</section>

{{-- ═══════════ STATS ═══════════ --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $stats = [
                    ['icon'=>'bi-collection',   'val'=>$count_collections, 'label'=>'Koleksi Sugar Glider', 'color'=>'sage'],
                    ['icon'=>'bi-house-heart',   'val'=>$count_shelters,   'label'=>'Kandang Aktif',        'color'=>'sage'],
                    ['icon'=>'bi-people',         'val'=>$count_users,      'label'=>'Peternak Terdaftar',   'color'=>'sky'],
                    ['icon'=>'bi-heart',          'val'=>$count_adoptions,  'label'=>'Siap Diadopsi',        'color'=>'honey'],
                ];
                $iconBg = ['sage'=>'bg-sage-100 text-sage','sky'=>'bg-sky text-blue-600','honey'=>'bg-honey-50 text-honey-dark'];
            @endphp
            @foreach ($stats as $s)
            <div class="stat-card group">
                <div class="w-12 h-12 {{ $iconBg[$s['color']] }} rounded-2xl flex items-center justify-center mx-auto mb-3
                            group-hover:scale-110 transition-transform duration-300">
                    <i class="bi {{ $s['icon'] }} text-xl"></i>
                </div>
                <div class="text-3xl font-display font-bold text-bark mb-1">
                    <span class="purecounter"
                          data-purecounter-start="0"
                          data-purecounter-end="{{ $s['val'] }}"
                          data-purecounter-duration="1">0</span>
                </div>
                <p class="text-bark-muted text-sm font-semibold">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════ NICE TO MEET YOU (PetsYou "Nice to meet you") ═══════════ --}}
<section class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-6">

        <x-section-header
            label="Kenalan Dulu"
            title="Sugar Glider kami"
            subtitle="Temukan sugar glider impianmu dari koleksi peternak terpercaya seluruh Indonesia."
            class="mb-10"
        />

        {{-- Collections grid --}}
        @if ($gallery_items->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5 mb-10">
                @php
                    $blobs = ['sage','honey','sky','sage','honey','sky','sage','honey'];
                    $i = 0;
                @endphp
                @foreach ($gallery_items as $item)
                    <x-sg-card
                        :id="$item->id ?? 0"
                        :nama="$item->nama"
                        :kode="$item->kode ?? ''"
                        :jenis="$item->jenis ?? ''"
                        :kelamin="$item->kelamin ?? '1'"
                        :gambar="$item->gambar ?? null"
                        :status="$item->status ?? '2'"
                        :shelter="$item->shelter_nama ?? null"
                        :blob="$blobs[$i % count($blobs)]"
                    />
                    @php $i++; @endphp
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('collections') }}" class="btn-primary px-8 py-3.5">
                    Lihat Semua Koleksi <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        @else
            <div class="text-center py-20">
                <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}"
                     class="w-20 mx-auto mb-4 opacity-30" alt="">
                <p class="text-bark-muted font-semibold">Belum ada koleksi tersedia.</p>
            </div>
        @endif
    </div>
</section>

{{-- ═══════════ WHY MSG (PetsYou "We have a cat. Are you?") ═══════════ --}}
<section class="py-20 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            {{-- Visual --}}
            <div class="relative order-2 lg:order-1 flex justify-center">
                <div class="relative w-80 h-80">
                    {{-- Large background blob --}}
                    <div class="absolute inset-0 bg-sage-100"
                         style="border-radius:60% 40% 30% 70% / 60% 30% 70% 40%;"></div>
                    <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}"
                         alt="Sugar Glider"
                         class="absolute inset-0 w-full h-full object-contain p-8 animate-float">
                </div>

                {{-- Feature badges --}}
                <div class="absolute top-5 -right-5 bg-white rounded-2xl shadow-card p-4 max-w-[160px] animate-float-2">
                    <div class="w-9 h-9 bg-sage-100 rounded-xl flex items-center justify-center mb-2">
                        <i class="bi bi-diagram-3 text-sage"></i>
                    </div>
                    <p class="text-xs font-bold text-bark leading-tight">Lacak silsilah hingga 3 generasi</p>
                </div>

                <div class="absolute bottom-5 -left-5 bg-honey rounded-2xl shadow-card p-4 max-w-[140px] animate-float-3">
                    <div class="w-9 h-9 bg-white/60 rounded-xl flex items-center justify-center mb-2">
                        <i class="bi bi-heart-arrow text-bark"></i>
                    </div>
                    <p class="text-xs font-bold text-bark leading-tight">Adopsi terverifikasi</p>
                </div>
            </div>

            {{-- Text --}}
            <div class="order-1 lg:order-2">
                <x-section-header
                    label="Kenapa MySugarGlider?"
                    title="Platform terlengkap untuk peternak SG"
                    :center="false"
                />
                <p class="section-subtitle mt-3 mb-8">
                    MySugarGlider.id hadir sebagai platform pertama yang menggabungkan pencatatan data, silsilah keturunan, dan ekosistem adopsi untuk komunitas sugar glider Indonesia.
                </p>

                <div class="space-y-4">
                    @php
                        $features = [
                            ['icon'=>'bi-diagram-3',   'color'=>'sage',  'title'=>'Lacak Silsilah',     'desc'=>'Rekam indukan jantan & betina untuk menelusuri pohon keturunan hingga beberapa generasi.'],
                            ['icon'=>'bi-gem',          'color'=>'honey', 'title'=>'Data Genetika',      'desc'=>'Catat genetika, fenotype, dan morph setiap individu untuk breeding berkualitas.'],
                            ['icon'=>'bi-heart-arrow',  'color'=>'sage',  'title'=>'Adopsi Terpercaya',  'desc'=>'Sistem adopsi terverifikasi antar peternak terdaftar dengan alur yang jelas.'],
                            ['icon'=>'bi-people',       'color'=>'sky',   'title'=>'Komunitas Aktif',    'desc'=>'Bergabung dengan ratusan peternak sugar glider dari seluruh Indonesia.'],
                        ];
                        $featureBg = ['sage'=>'bg-sage-100 text-sage','honey'=>'bg-honey-50 text-honey-dark','sky'=>'bg-sky text-blue-600'];
                    @endphp
                    @foreach ($features as $f)
                        <div class="flex items-start gap-4 p-4 rounded-2xl hover:bg-cream transition-colors duration-200 group">
                            <div class="w-11 h-11 {{ $featureBg[$f['color']] }} rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                <i class="bi {{ $f['icon'] }} text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-bark mb-0.5">{{ $f['title'] }}</h4>
                                <p class="text-bark-muted text-sm leading-relaxed">{{ $f['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    <a href="{{ route('about') }}" class="btn-secondary">
                        Pelajari Lebih Lanjut <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ SHELTERS (PetsYou "They have a home") ═══════════ --}}
<section class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-6">
        <x-section-header
            label="Komunitas"
            title="Kandang Peternak"
            subtitle="Telusuri kandang peternak sugar glider dari seluruh Indonesia."
            class="mb-12"
        />

        @if ($shelters->isNotEmpty())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach ($shelters as $shelter)
                    <x-shelter-card
                        :id="$shelter->id"
                        :nama="$shelter->nama"
                        :alamat="$shelter->alamat"
                        :keterangan="$shelter->keterangan"
                        :gambar="$shelter->gambar"
                    />
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('shelters') }}" class="btn-secondary px-8 py-3.5">
                    Lihat Semua Kandang <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        @endif
    </div>
</section>

{{-- ═══════════ CONTACT ═══════════ --}}
<section class="py-20 bg-white" id="contact">
    <div class="max-w-7xl mx-auto px-6">
        <x-section-header
            label="Hubungi Kami"
            title="Ada Pertanyaan?"
            subtitle="Jangan ragu menghubungi kami seputar sugar glider atau kunjungi kandang kami langsung."
            class="mb-12"
        />

        <div class="grid lg:grid-cols-5 gap-10">

            {{-- Info cards --}}
            <div class="lg:col-span-2 space-y-4">
                @php
                    $contacts = [
                        ['icon'=>'bi-geo-alt',  'color'=>'sage',  'label'=>'Lokasi',    'value'=>'Kota Surabaya, Jawa Timur',   'href'=>null],
                        ['icon'=>'bi-envelope', 'color'=>'sage',  'label'=>'Email',     'value'=>'info@mysugarglider.id',       'href'=>'mailto:info@mysugarglider.id'],
                        ['icon'=>'bi-whatsapp', 'color'=>'honey', 'label'=>'WhatsApp',  'value'=>'+62 857 5533 3232',           'href'=>'https://wa.me/6285755333232'],
                    ];
                    $cBg = ['sage'=>'bg-sage-100 text-sage','honey'=>'bg-honey-50 text-honey-dark'];
                @endphp
                @foreach ($contacts as $c)
                    <div class="card p-5 flex items-center gap-4">
                        <div class="w-11 h-11 {{ $cBg[$c['color']] }} rounded-2xl flex items-center justify-center flex-shrink-0">
                            <i class="bi {{ $c['icon'] }} text-lg"></i>
                        </div>
                        <div>
                            <p class="font-bold text-bark text-sm">{{ $c['label'] }}</p>
                            @if ($c['href'])
                                <a href="{{ $c['href'] }}"
                                   class="text-sage text-sm font-semibold hover:underline">
                                    {{ $c['value'] }}
                                </a>
                            @else
                                <p class="text-bark-muted text-sm">{{ $c['value'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Form --}}
            <div class="lg:col-span-3 card p-8">
                @if (session('pesan'))
                    <div class="alert-success mb-6">
                        <i class="bi bi-check-circle-fill text-sage text-lg"></i>
                        <p class="font-semibold">{{ session('pesan') }}</p>
                    </div>
                @endif

                <form action="{{ route('contact.post') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="input-field" placeholder="John Doe" required>
                            @error('name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email" class="input-field" placeholder="john@email.com" required>
                            @error('email')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Subjek</label>
                        <input type="text" name="subject" class="input-field" placeholder="Pertanyaan seputar sugar glider..." required>
                        @error('subject')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Pesan</label>
                        <textarea name="messages" rows="4" class="textarea-field" placeholder="Tulis pesan Anda..." required></textarea>
                        @error('messages')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn-primary w-full justify-center py-3.5">
                        <i class="bi bi-send"></i> Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
