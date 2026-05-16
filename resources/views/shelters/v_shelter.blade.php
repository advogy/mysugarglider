@extends('layouts.v_main')

@section('title', 'Kandang Peternak')

@section('content')

<header class="premium-page-header">
    <div class="header-blob-1"></div>
    <h1 class="page-title">Kandang Peternak</h1>
    <p class="page-subtitle">
        Kenali peternak-peternak sugar glider terbaik di seluruh Indonesia yang telah terverifikasi dalam komunitas kami.
    </p>
</header>

{{-- Search bar --}}
<div class="search-section">
    <form action="{{ route('shelters') }}" method="GET" class="search-bar-wrap theme-green">
        <i class="bi bi-search search-bar-icon"></i>
        <input type="text" name="q" value="{{ $search }}"
               placeholder="Cari nama kandang atau lokasi..."
               class="search-bar-input" autocomplete="off">
        <button type="submit" class="search-bar-btn">Cari</button>
    </form>
    @if ($search !== '')
        <p class="search-result-info">
            Menampilkan <strong>{{ $shelters->total() }}</strong> hasil untuk "<em>{{ $search }}</em>"
            &nbsp;·&nbsp;
            <a href="{{ route('shelters') }}" class="link-green">Hapus pencarian</a>
        </p>
    @endif
</div>

@if ($shelters->isEmpty())
<div class="text-center py-24 bg-white">
    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-5 text-gray-400 text-4xl">
        <i class="bi bi-house-x"></i>
    </div>
    <h3 class="text-2xl font-bold text-[#1A1A1A] mb-2 font-outfit">Belum Ada Kandang</h3>
    <p class="text-[#666] text-sm">Belum ada kandang yang terdaftar di dalam platform saat ini.</p>
</div>
@else
<div class="bg-white">
    <div class="shelter-grid">
        @php
            $colorClasses = ['sc-sage', 'sc-blue', 'sc-honey', 'sc-green'];
            $icons        = ['bi-house-heart', 'bi-shop', 'bi-building', 'bi-house-door'];
        @endphp

        @foreach ($shelters as $index => $shelter)
        <div class="shelter-card {{ $colorClasses[$index % 4] }}">
            <div class="shelter-blob"></div>

            <div class="sg-count-badge">
                <span class="count-num">{{ $shelter->sg_count }}</span>
                <span class="count-label">ekor</span>
            </div>

            <div class="shelter-icon">
                <i class="bi {{ $icons[$index % 4] }}"></i>
            </div>

            <h3 class="shelter-name">{{ $shelter->nama }}</h3>
            <p class="shelter-address">
                <i class="bi bi-geo-alt sc-icon mt-1"></i>
                <span>{{ Str::limit($shelter->alamat, 60) }}</span>
            </p>
            <p class="text-sm text-bark-muted mb-6 line-clamp-2">
                {{ $shelter->keterangan ?? 'Peternak sugar glider terverifikasi.' }}
            </p>

            <a href="{{ route('shelter.show', $shelter->id) }}" class="btn-outline-shelter">Kunjungi Kandang</a>
        </div>
        @endforeach
    </div>

    <div class="flex justify-center pb-20">
        {{ $shelters->links('pagination::v_pagination_public') }}
    </div>
</div>
@endif

@endsection
