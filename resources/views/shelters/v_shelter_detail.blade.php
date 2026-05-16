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
                <div class="map-card">
                    <iframe class="w-full h-full border-0" src="https://www.google.com/maps/embed?pb={{ $shelter->gmaps }}" allowfullscreen loading="lazy"></iframe>
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
@endsection
