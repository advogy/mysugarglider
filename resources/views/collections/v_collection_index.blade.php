@extends('layouts.v_main')

@section('title', request('status') == 'adopsi' ? 'Buka Adopsi' : 'Semua Koleksi')

@section('content')
<div class="page-collections">

<header class="premium-page-header">
    <div class="header-blob-1"></div>
    <h1 class="page-title">{{ request('status') == 'adopsi' ? 'Koleksi Adopsi' : 'Semua Koleksi' }}</h1>
    <p class="page-subtitle">
        {{ request('status') == 'adopsi' ? 'Temukan teman baru untuk melengkapi kebahagiaan Anda dari peternak terpercaya kami.' : 'Jelajahi seluruh database sugar glider yang terdaftar di komunitas MySugarGlider.' }}
    </p>
</header>

{{-- Search bar --}}
<div class="search-section">
    <form action="{{ route('collections') }}" method="GET" class="search-bar-wrap theme-blue">
        @if (request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <i class="bi bi-search search-bar-icon"></i>
        <input type="text" name="q" value="{{ $search }}"
               placeholder="Cari nama, jenis, atau kandang..."
               class="search-bar-input" autocomplete="off">
        <button type="submit" class="search-bar-btn">Cari</button>
    </form>
    @if ($search !== '')
        <p class="search-result-info">
            Menampilkan <strong>{{ $collections->total() }}</strong> hasil untuk "<em>{{ $search }}</em>"
            &nbsp;·&nbsp;
            <a href="{{ route('collections', request()->except('q', 'page')) }}" class="link-blue">Hapus pencarian</a>
        </p>
    @endif
</div>

@if ($collections->isEmpty())
<div class="text-center py-24 bg-white">
    <img src="{{ asset('assets/images/pets/sg_card1_1778842695259.png') }}" class="w-40 mx-auto mb-5 opacity-50 grayscale" alt="">
    <h3 class="text-2xl font-bold text-[#1A1A1A] mb-2 font-outfit">Belum Ada Koleksi</h3>
    <p class="text-[#666] text-sm">Belum ada sugar glider yang terdaftar secara publik pada kategori ini.</p>
</div>
@else
<div class="bg-white">
    <div class="pet-grid">
        @php $blobs = ['yellow', 'blue', 'green', 'sage']; @endphp

        @foreach ($collections as $index => $c)
        <div class="pet-card">
            @if ($c->sgStatus == '3')
                <span class="pet-badge badge-adopsi">Adopsi</span>
            @else
                <span class="pet-badge badge-koleksi">Koleksi</span>
            @endif

            <div class="pet-image-wrapper">
                <div class="pet-circle-bg {{ $blobs[$index % count($blobs)] }}"></div>
                @if ($c->sgGambar)
                    <img src="{{ asset('upload/sugargliders/' . $c->sgGambar) }}" alt="{{ $c->sgNama }}" class="pet-img">
                @else
                    <i class="bi bi-heart-fill icon-placeholder-card"></i>
                @endif
            </div>
            <h3 class="pet-name">{{ $c->sgNama }}</h3>
            <p class="pet-desc">
                @if ($c->sgKelamin == '0')
                    <i class="bi bi-gender-female icon-female"></i> Betina
                @else
                    <i class="bi bi-gender-male icon-male"></i> Jantan
                @endif
                <span class="divider-light">|</span>
                {{ $c->sgJenis ?? 'Classic Grey' }}
            </p>
            <div class="text-[0.7rem] text-[#999] mb-5 font-bold uppercase tracking-widest"><i class="bi bi-shop"></i> {{ $c->stNama }}</div>
            <a href="{{ route('sugarglider.show', $c->sgId) }}" class="btn-outline">Lihat Detail</a>
        </div>
        @endforeach
    </div>

    <div class="flex justify-center pb-20">
        {{ $collections->links('pagination::v_pagination_public') }}
    </div>
</div>
@endif

</div>
@endsection
