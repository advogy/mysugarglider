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
                        <img src="{{ asset('/upload/shelters/' . $shelter->gambar) }}" alt="{{ $shelter->nama }}" class="profile-img">
                    @else
                        <i class="bi bi-house-heart placeholder-icon"></i>
                        <span class="placeholder-label">Belum ada foto kandang</span>
                    @endif
                </div>
                <div class="profile-info">
                    <h2 class="profile-name">{{ $shelter->nama }}</h2>

                    @if ($shelter->keterangan)
                        <p class="text-[#666] text-[0.95rem] italic mb-6 border-l-4 pl-4 border-[#06D6A0]">"{{ $shelter->keterangan }}"</p>
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
                <div class="text-center py-16 bg-[#F8F9FA] rounded-[30px] border border-[#F0F0F0]">
                    <i class="bi bi-box-seam text-5xl text-[#EAEAEA] mb-4 block"></i>
                    <p class="text-[#999] font-semibold">Belum ada koleksi di kandang ini.</p>
                </div>
            @else
                <div class="pet-list">
                    @foreach ($sugargliders as $sg)
                        <a href="{{ route('sugarglider.show', $sg->sgId) }}" class="pet-row">
                            @if ($sg->sgGambar)
                                <img src="{{ asset('/upload/sugargliders/' . $sg->sgGambar) }}" alt="{{ $sg->sgNama }}" class="pet-row-img">
                            @else
                                <div class="pet-row-img flex items-center justify-center text-2xl text-[#06D6A0] bg-[#EAFBF6]"><i class="bi bi-heart-fill"></i></div>
                            @endif

                            <div class="pet-row-info">
                                <div class="pet-row-name">{{ $sg->sgNama }}</div>
                                <div class="pet-row-meta">
                                    @if ($sg->sgKelamin == '0')
                                        <span class="text-female">♀ Betina</span>
                                    @else
                                        <span class="text-male">♂ Jantan</span>
                                    @endif
                                    @if ($sg->sgJenis)
                                        <span>•</span>
                                        <span>{{ $sg->sgJenis }}</span>
                                    @endif
                                </div>
                            </div>

                            @if ($sg->sgStatus == '3')
                                <span class="badge-adopsi">Adopsi</span>
                            @endif

                            <i class="bi bi-arrow-right-short text-3xl text-[#EAEAEA]"></i>
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
@endsection
