@extends('layouts.v_main')
@section('title', 'Kandang — ' . $shelter->nama)
@section('navbar-class', 'nav-transparent-light')

@section('content')
<div class="page-shelter-detail">

<header class="shelter-page-header">
    <p class="page-eyebrow">Profil Kandang</p>
    <h1 class="page-title">{{ $shelter->nama }}</h1>
</header>

<div class="bg-white pb-20">
    <div class="detail-container">
        
        {{-- Shelter Profile --}}
        <div>
            <div class="profile-card">
                <div class="profile-img-wrapper">
                    @if ($shelter->gambar)
                        <button type="button" onclick="previewPhoto('{{ asset('/upload/shelters/' . $shelter->gambar) }}', '{{ addslashes($shelter->nama) }}')" class="w-full h-full block focus:outline-none cursor-zoom-in">
                            <img src="{{ asset('/upload/shelters/' . $shelter->gambar) }}" alt="{{ $shelter->nama }}" class="profile-img hover:opacity-90 transition-opacity">
                        </button>
                    @else
                        <i class="bi bi-house-heart placeholder-icon"></i>
                        <span class="placeholder-label">Belum ada foto kandang</span>
                    @endif
                </div>
                <div class="profile-info">
                    <h2 class="profile-name">{{ $shelter->nama }}</h2>

                    @if ($shelter->keterangan)
                        <p class="profile-quote">"{{ $shelter->keterangan }}"</p>
                    @endif

                    @if ($shelter->alamat)
                        <div class="contact-item">
                            <div class="contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>{{ $shelter->alamat }}</div>
                        </div>
                    @endif

                    @if ($shelter->user && $shelter->user->email)
                        <div class="contact-item">
                            <div class="contact-icon icon-blue"><i class="bi bi-envelope-fill"></i></div>
                            <a href="mailto:{{ $shelter->user->email }}" class="hover:underline font-semibold text-blue-sg">{{ $shelter->user->email }}</a>
                        </div>
                    @endif

                    @if ($shelter->user && $shelter->user->profile && $shelter->user->profile->telepon)
                        <div class="contact-item mb-0">
                            <div class="contact-icon icon-yellow"><i class="bi bi-telephone-fill"></i></div>
                            <a href="tel:{{ $shelter->user->profile->telepon }}" class="hover:underline font-semibold text-orange-sg">{{ $shelter->user->profile->telepon }}</a>
                        </div>
                    @endif
                </div>
            </div>

            @if ($shelter->gmaps)
                @php
                    $mapsUrl = str_starts_with($shelter->gmaps, 'http')
                        ? $shelter->gmaps
                        : 'https://www.google.com/maps/embed?pb=' . $shelter->gmaps;
                @endphp
                <div class="map-card" onclick="openMapsModal()">
                    <iframe class="w-full h-full border-0" src="{{ $mapsUrl }}" allowfullscreen loading="lazy"></iframe>
                    <div class="map-card-overlay">
                        <span><i class="bi bi-arrows-fullscreen"></i> Perbesar Peta</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Collection List --}}
        <div class="collection-panel">
            <div class="collection-header">
                <h3 class="collection-title">Koleksi Kami</h3>
                @if (!$sugargliders->isEmpty())
                    <span class="badge-count">{{ $sugargliders->total() }} ekor</span>
                @endif
            </div>

            @if ($sugargliders->isEmpty())
                <div class="text-center py-16 bg-gray-50 rounded-3xl border border-gray-100">
                    <i class="bi bi-box-seam text-5xl text-gray-200 mb-4 block"></i>
                    <p class="text-bark-muted font-semibold">Belum ada koleksi di kandang ini.</p>
                </div>
            @else
                <div class="pet-list">
                    @foreach ($sugargliders as $sg)
                        <a href="{{ route('sugarglider.show', $sg->sgId) }}" class="pet-row">
                            @if ($sg->sgGambar)
                                <button type="button" onclick="event.preventDefault(); previewPhoto('{{ asset('/upload/sugargliders/' . $sg->sgGambar) }}', '{{ addslashes($sg->sgNama) }}')" class="flex-shrink-0 focus:outline-none cursor-zoom-in">
                                    <img src="{{ asset('/upload/sugargliders/' . $sg->sgGambar) }}" alt="{{ $sg->sgNama }}" class="pet-row-img hover:opacity-80 transition-opacity">
                                </button>
                            @else
                                <div class="pet-row-img flex items-center justify-center text-2xl text-green-sg bg-sage-pale"><i class="bi bi-heart-fill"></i></div>
                            @endif

                            <div class="pet-row-info">
                                <div class="pet-row-name">{{ $sg->sgNama }}</div>
                                <div class="pet-row-meta">
                                    @include('partials.gender', ['kelamin' => $sg->sgKelamin])
                                    @if ($sg->sgJenis)
                                        <span>•</span>
                                        <span>{{ $sg->sgJenis }}</span>
                                    @endif
                                </div>
                            </div>

                            @if ($sg->sgStatus == \App\Enums\CollectionStatus::ADOPSI->value)
                                <span class="badge-adopsi">Adopsi</span>
                            @endif

                            <i class="bi bi-arrow-right-short text-3xl text-gray-200"></i>
                        </a>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $sugargliders->links('pagination::v_pagination_public') }}
                </div>
            @endif
        </div>

    </div>
</div>
</div>

<x-photo-preview-modal />

@if ($shelter->gmaps)
<div id="map-modal" class="be-modal hidden" onclick="closeMapsModal(event)">
    <div onclick="event.stopPropagation()"
         style="width:90%;max-width:860px;height:78vh;background:#fff;border-radius:24px;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 30px 80px rgba(0,0,0,0.25);">
        <div style="padding:16px 20px;border-bottom:1px solid #F0F0F0;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
            <span style="font-family:'Inter',sans-serif;font-weight:700;font-size:0.875rem;color:#1A1A1A;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-geo-alt-fill" style="color:#06D6A0;"></i>
                {{ $shelter->nama }}
            </span>
            <button onclick="closeMapsModal()"
                    style="background:none;border:none;cursor:pointer;color:#999;font-size:1.1rem;line-height:1;padding:4px;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <iframe src="{{ $mapsUrl }}" style="flex:1;width:100%;border:none;" allowfullscreen loading="lazy"></iframe>
    </div>
</div>

@push('scripts')
<script>
function openMapsModal() {
    const m = document.getElementById('map-modal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closeMapsModal(e) {
    if (!e || e.target === document.getElementById('map-modal')) {
        const m = document.getElementById('map-modal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
}
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeMapsModal(); });
</script>
@endpush
@endif

@endsection
