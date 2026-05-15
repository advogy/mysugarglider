@extends('layouts.v_main')

@section('title', 'Beranda — MySugarGlider')

@section('content')
<div class="page-home">

{{-- ════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════ --}}
<section class="hero">
    <div class="hero-inner">

        {{-- Text --}}
        <div class="hero-text">
            <div class="hero-tag">
                <span><i class="bi bi-stars"></i></span>
                Platform #1 Sugar Glider Indonesia
            </div>
            <h1 class="hero-h1">
                Sahabat berbulu<br>
                mencari <em>rumah</em><br>
                yang tepat
            </h1>
            <p class="hero-p">
                Catat silsilah, kelola kandang, dan temukan sugar glider adopsi terbaik dari peternak terpercaya seluruh Indonesia.
            </p>
            <div class="hero-cta">
                <a href="{{ route('collections') }}" class="btn-yellow">
                    <i class="bi bi-search"></i> Temukan Sahabat
                </a>
                <div class="hero-checklist">
                    <span><i class="bi bi-check-circle-fill"></i> Gratis selamanya</span>
                    <span><i class="bi bi-check-circle-fill"></i> Tanpa iklan</span>
                    <span><i class="bi bi-check-circle-fill"></i> Komunitas terverifikasi</span>
                </div>
            </div>
        </div>

        {{-- Visual --}}
        <div class="hero-visual">
            <div class="hv-blob hv-blob-yellow"></div>
            <div class="hv-blob hv-blob-blue"></div>
            <div class="hv-blob hv-blob-green"></div>

            @php
                $fallbacks = [
                    ['name'=>'Mochi', 'sub'=>'Classic Grey · 8 bln', 'img'=>asset('assets/images/pets/sg_hero_1778842679372.png')],
                    ['name'=>'Nala',  'sub'=>'Mosaic · 1 thn',       'img'=>asset('assets/images/pets/sg_card1_1778842695259.png')],
                    ['name'=>'Kiki',  'sub'=>'Platinum · 2 thn',     'img'=>asset('assets/images/pets/sg_card2_1778842710532.png')],
                ];
                $heroes = [];
                foreach ([0,1,2] as $i) {
                    $sg = $hero_items->get($i);
                    if ($sg) {
                        $sub = ($sg->jenis ?? 'Sugar Glider') . ($sg->usia_str ? ' · ' . $sg->usia_str : '');
                        $heroes[] = ['name' => $sg->nama, 'sub' => $sub, 'img' => asset('/upload/sugargliders/' . $sg->gambar)];
                    } else {
                        $heroes[] = $fallbacks[$i];
                    }
                }
            @endphp

            {{-- Floating badge: top --}}
            <div class="hv-badge hv-badge-1">
                <div class="hv-badge-dot"></div>
                <div>
                    <div class="b-name">{{ $heroes[0]['name'] }}</div>
                    <div class="b-sub">{{ $heroes[0]['sub'] }}</div>
                </div>
            </div>

            {{-- Main photo card --}}
            <div class="hv-card hv-card-1">
                <img src="{{ $heroes[0]['img'] }}" alt="{{ $heroes[0]['name'] }}">
            </div>

            {{-- Badge right --}}
            <div class="hv-badge hv-badge-2">
                <div class="hv-badge-dot"></div>
                <div>
                    <div class="b-name">{{ $heroes[1]['name'] }}</div>
                    <div class="b-sub">{{ $heroes[1]['sub'] }}</div>
                </div>
            </div>

            {{-- Second photo --}}
            <div class="hv-card hv-card-2">
                <img src="{{ $heroes[1]['img'] }}" alt="{{ $heroes[1]['name'] }}">
            </div>

            {{-- Badge left small --}}
            <div class="hv-badge hv-badge-3">
                <div class="hv-badge-dot"></div>
                <div>
                    <div class="b-name">{{ $heroes[2]['name'] }}</div>
                    <div class="b-sub">{{ $heroes[2]['sub'] }}</div>
                </div>
            </div>

            {{-- Third photo --}}
            <div class="hv-card hv-card-3">
                <img src="{{ $heroes[2]['img'] }}" alt="{{ $heroes[2]['name'] }}">
            </div>
        </div>
    </div>

    {{-- Wave bottom --}}
    <div class="wave-bottom">
        <svg viewBox="0 0 1440 90" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path class="fill-white" fill-opacity="1"
                d="M0,56L80,48C160,40,320,24,480,28C640,32,800,56,960,60C1120,64,1280,48,1360,40L1440,32L1440,90L0,90Z"/>
        </svg>
    </div>
</section>

{{-- ════════════════════════════════════════════════
     KENALAN DULU
════════════════════════════════════════════════ --}}
<section class="pets-section">
    <h2 class="sec-title">Kenalan Dulu</h2>
    <p class="sec-sub">Sugar glider imut dari berbagai morph siap menemukan rumah baru mereka</p>

    {{-- Filter bar --}}
    @php $uniqueJenis = $gallery_items->pluck('jenis')->filter()->unique()->sort()->values(); @endphp
    <div class="filter-bar">
        <button class="filter-pill-ref active" data-filter="semua">
            <i class="bi bi-grid-3x3-gap"></i> Semua
        </button>
        @foreach ($uniqueJenis as $jenis)
        <button class="filter-pill-ref" data-filter="{{ strtolower($jenis) }}">{{ $jenis }}</button>
        @endforeach
        <a href="{{ route('collections', ['status' => 'adopsi']) }}" class="filter-pill-adopsi">
            <i class="bi bi-heart-fill"></i> Cari Adopsi
        </a>
    </div>

    {{-- Pet cards --}}
    <div class="pet-grid">
        @php
            $circles = ['yellow','blue','green','mint','yellow','blue','green','mint'];
            $items = $gallery_items->isNotEmpty()
                ? $gallery_items
                : collect(array_fill(0, 8, (object)[
                    'id'    => 1,
                    'nama'  => 'Momo',
                    'jenis' => 'Classic Grey',
                    'gambar'=> null,
                ]));
        @endphp

        @foreach ($items->take(8) as $i => $item)
        <div class="pet-card" data-jenis="{{ strtolower($item->jenis ?? 'classic grey') }}">
            <div class="pet-img-wrap">
                <div class="pet-circle {{ $circles[$i % count($circles)] }}"></div>
                @php $src = (!empty($item->gambar) && !str_starts_with($item->gambar,'assets/'))
                    ? asset('/upload/sugargliders/'.$item->gambar)
                    : asset('assets/images/pets/sg_card1_1778842695259.png'); @endphp
                <img src="{{ $src }}" alt="{{ $item->nama }}" class="pet-photo">
            </div>
            <div class="pet-card-name">{{ $item->nama ?? 'Momo' }}</div>
            <div class="pet-card-type">{{ $item->jenis ?? 'Classic Grey' }}</div>
            <a href="{{ route('sugarglider.show', $item->id ?? 1) }}" class="btn-card-outline">Pelajari Lebih Lanjut</a>
        </div>
        @endforeach
    </div>

    <div class="see-more-wrap">
        <a href="{{ route('collections') }}" class="btn-teal">Lihat Semua Koleksi</a>
    </div>
</section>

{{-- ════════════════════════════════════════════════
     WE HAVE A PET
════════════════════════════════════════════════ --}}
<section class="we-have">
    <div class="wave-top">
        <svg viewBox="0 0 1440 90" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path class="fill-white" fill-opacity="1"
                d="M0,56L80,48C160,40,320,24,480,28C640,32,800,56,960,60C1120,64,1280,48,1360,40L1440,32L1440,90L0,90Z"/>
        </svg>
    </div>

    <div class="we-have-inner">
        <div class="we-have-text">
            <h2 class="we-have-h2">Kami memiliki koleksi<br>Sugar Glider. Anda?</h2>
            <p class="we-have-p">
                Bergabunglah dengan ratusan peternak sugar glider dari seluruh Indonesia. Catat silsilah, kelola kandang, dan temukan sugar glider adopsi terbaik. Ekosistem terlengkap untuk sahabat berbulu Anda.
            </p>
            <a href="{{ route('register') }}" class="btn-green">Mulai Bergabung</a>
        </div>

        <div class="we-have-visual">
            <div class="we-blob"></div>
            <img src="{{ asset('assets/images/pets/sg_hero_1778842679372.png') }}" alt="Sugar Glider" class="we-img">

            {{-- Floating badge --}}
            <div class="we-badge">
                <div class="we-badge-avatar">
                    <img src="{{ asset('assets/images/pets/sg_card1_1778842695259.png') }}" alt="">
                </div>
                <div>
                    <div class="we-badge-name">Miffy</div>
                    <div class="we-badge-sub">Mosaic · Tersedia untuk adopsi</div>
                </div>
            </div>
        </div>
    </div>

    <div class="wave-bottom">
        <svg viewBox="0 0 1440 90" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path class="fill-gray" fill-opacity="1"
                d="M0,56L80,48C160,40,320,24,480,28C640,32,800,56,960,60C1120,64,1280,48,1360,40L1440,32L1440,90L0,90Z"/>
        </svg>
    </div>
</section>

{{-- ════════════════════════════════════════════════
     HAPPY HISTORY / KISAH BAHAGIA
════════════════════════════════════════════════ --}}
<section class="history">
    <h2 class="sec-title">Kisah Bahagia</h2>
    <p class="sec-sub">Ribuan sugar glider telah menemukan rumah baru mereka melalui platform ini.</p>

    <div class="history-carousel">
        <button class="history-nav" id="hist-prev"><i class="bi bi-chevron-left"></i></button>

        <div class="history-center">
            <div class="history-blob">
                <p class="history-quote" id="hist-quote">
                    "Berkat MySugarGlider, saya bisa menemukan silsilah lengkap dari peliharaan saya dan memastikan genetikanya sehat. Sangat merekomendasikan!"
                </p>
                <div class="history-author" id="hist-author">Arjuna &nbsp;·&nbsp; 2 Tahun bersama</div>
            </div>

            <img src="{{ asset('assets/images/pets/sg_card1_1778842695259.png') }}"  class="history-avatar ha-1" alt="">
            <img src="{{ asset('assets/images/pets/sg_card2_1778842710532.png') }}"  class="history-avatar ha-2" alt="">
            <img src="{{ asset('assets/images/pets/sg_hero_1778842679372.png') }}"   class="history-avatar ha-3" alt="">
            <img src="{{ asset('assets/images/pets/sg_card1_1778842695259.png') }}"  class="history-avatar ha-4" alt="">
        </div>

        <button class="history-nav" id="hist-next"><i class="bi bi-chevron-right"></i></button>
    </div>

    <div class="history-dots">
        <div class="history-dot active"></div>
        <div class="history-dot"></div>
        <div class="history-dot"></div>
    </div>
</section>

{{-- ════════════════════════════════════════════════
     THEY HAVE A HOME / MEREKA PUNYA RUMAH
════════════════════════════════════════════════ --}}
<section class="home-section">
    <div class="wave-top">
        <svg viewBox="0 0 1440 90" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path class="fill-gray" fill-opacity="1"
                d="M0,56L80,48C160,40,320,24,480,28C640,32,800,56,960,60C1120,64,1280,48,1360,40L1440,32L1440,90L0,90Z"/>
        </svg>
    </div>

    <div class="home-inner">
        <div class="home-visual">
            <div class="home-blob"></div>
            <img src="{{ asset('assets/images/pets/sg_card2_1778842710532.png') }}" alt="Sugar Glider Home" class="home-img">

            {{-- Floating location badge --}}
            <div class="home-location-badge">
                <i class="bi bi-house-heart-fill"></i>
                <div>
                    <div class="loc-name">Kandang Bahagia</div>
                    <div class="loc-sub">Jakarta Selatan</div>
                </div>
            </div>
        </div>

        <div class="home-content">
            <h2 class="home-h2">Mereka Memiliki Rumah</h2>
            <p class="home-p">
                Telusuri kandang peternak sugar glider dari seluruh Indonesia. Ribuan kandang aktif setiap harinya mencatat silsilah dan kesehatan sahabat berbulu mereka.
            </p>

            {{-- 6-photo gallery (2 rows × 3 cols) --}}
            <div class="home-gallery">
                <div class="home-gallery-item">
                    <img src="{{ asset('assets/images/pets/sg_card1_1778842695259.png') }}" alt="">
                </div>
                <div class="home-gallery-item">
                    <img src="{{ asset('assets/images/pets/sg_hero_1778842679372.png') }}" alt="">
                </div>
                <div class="home-gallery-item">
                    <img src="{{ asset('assets/images/pets/sg_card2_1778842710532.png') }}" alt="">
                </div>
                <div class="home-gallery-item">
                    <img src="{{ asset('assets/images/pets/sg_hero_1778842679372.png') }}" alt="">
                </div>
                <div class="home-gallery-item">
                    <img src="{{ asset('assets/images/pets/sg_card2_1778842710532.png') }}" alt="">
                </div>
                <div class="home-gallery-item">
                    <img src="{{ asset('assets/images/pets/sg_card1_1778842695259.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>

</div>
@endsection

@push('scripts')
<script>
// ── Filter pills
const filterBtns = document.querySelectorAll('.filter-pill-ref[data-filter]');
const petCards   = document.querySelectorAll('.pets-section .pet-card');

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filter = btn.dataset.filter;
        petCards.forEach(card => {
            const match = filter === 'semua' || card.dataset.jenis.includes(filter);
            card.style.display = match ? '' : 'none';
        });
    });
});

// ── History carousel
const slides = [
    { quote: '"Berkat MySugarGlider, saya bisa menemukan silsilah lengkap dari peliharaan saya dan memastikan genetikanya sehat. Sangat merekomendasikan!"', author: 'Arjuna · 2 Tahun bersama' },
    { quote: '"Platform ini luar biasa! Proses adopsi sangat mudah dan transparan. Sugar glider saya sekarang hidup bahagia di kandang baru."', author: 'Sinta · 1 Tahun bersama' },
    { quote: '"Saya awalnya ragu, tapi setelah mencoba MySugarGlider saya langsung jatuh cinta. Data pedigree-nya lengkap dan akurat."', author: 'Budi · 8 Bulan bersama' },
];
let current = 0;
const quoteEl = document.getElementById('hist-quote');
const authorEl = document.getElementById('hist-author');
const dots = document.querySelectorAll('.history-dot');

function showSlide(n) {
    current = (n + slides.length) % slides.length;
    quoteEl.textContent = slides[current].quote;
    authorEl.textContent = slides[current].author;
    dots.forEach((d, i) => d.classList.toggle('active', i === current));
}
document.getElementById('hist-prev').addEventListener('click', () => showSlide(current - 1));
document.getElementById('hist-next').addEventListener('click', () => showSlide(current + 1));
dots.forEach((d, i) => d.addEventListener('click', () => showSlide(i)));
</script>
@endpush
