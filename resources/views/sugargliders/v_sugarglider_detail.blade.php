@extends('layouts.v_main')

@section('title', 'Detail — ' . ($collection ? $collection->sgNama : 'Sugar Glider'))

@section('content')
<div class="page-sg-detail">

<header class="premium-page-header">
    <div class="header-blob-1"></div>
    <h1 class="page-title">{{ $collection ? $collection->sgNama : 'Data Tidak Ditemukan' }}</h1>
    <p class="page-subtitle">Detail Profil Sugar Glider</p>
</header>

@if (!$collection)
<div class="text-center py-24 bg-white">
    <h3 class="text-2xl font-bold text-[#1A1A1A] mb-2">Sugar Glider Tidak Ditemukan</h3>
    <a href="{{ route('collections') }}" class="text-[#118AB2] font-bold hover:underline">Kembali ke Koleksi</a>
</div>
@else

<div class="bg-white pb-20">
    <div class="detail-container">
        
        {{-- Profile Panel --}}
        <div>
            <div class="profile-card">
                <div class="profile-img-wrapper">
                    @if ($collection->sgGambar)
                        <img src="{{ asset('/upload/sugargliders/' . $collection->sgGambar) }}" alt="{{ $collection->sgNama }}" class="profile-img">
                    @else
                        <img src="{{ asset('assets/images/pets/sg_card1_1778842695259.png') }}" class="profile-img img-dim">
                    @endif
                </div>
                <div class="profile-info">
                    <h2 class="profile-name">{{ $collection->sgNama }}</h2>
                    <p class="profile-code">{{ $collection->sgKode }}</p>
                    
                    @if ($collection->clUser != '0' && $collection->stNama)
                        <div class="mb-4">
                            <a href="{{ route('shelter.show', $collection->stId) }}" class="tag-shelter">
                                <i class="bi bi-shop"></i> {{ $collection->stNama }}
                            </a>
                        </div>
                    @endif

                    @if ($collection->sgKeterangan)
                        <p class="text-[#666] text-[0.9rem] italic mb-4">"{{ $collection->sgKeterangan }}"</p>
                    @endif

                    @if ($collection->clUser != '0' && $collection->clStatus == '3')
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="tag-adopsi mb-4">Tersedia Adopsi</div>
                            @guest
                                <a href="{{ route('login') }}" class="btn-blue">Masuk untuk Mengajukan</a>
                            @else
                                <a href="{{ route('adoption.list') }}" class="btn-blue"><i class="bi bi-heart-fill"></i> Ajukan Adopsi</a>
                            @endguest
                        </div>
                    @endif
                </div>
            </div>
            
            @if ($keturunans->isNotEmpty())
            <div class="info-card mt-8">
                <h3 class="info-title"><i class="bi bi-diagram-3-fill text-green-sg"></i> Keturunan</h3>
                <div class="space-y-3">
                    @foreach ($keturunans as $kt)
                        <a href="{{ route('sugarglider.show', $kt->id) }}" class="block p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                            <div class="font-bold text-[#1A1A1A] text-[1.1rem]">{{ $kt->nama }}</div>
                            <div class="text-[#999] text-sm">{{ $kt->jenis }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Details Panel --}}
        <div>
            <div class="info-card">
                <h3 class="info-title"><i class="bi bi-info-circle-fill text-blue-sg"></i> Informasi Profil</h3>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Jenis Kelamin</div>
                        <div class="info-value">
                            @if ($collection->sgKelamin == '0')
                                <span class="text-female">♀ Betina</span>
                            @else
                                <span class="text-male">♂ Jantan</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Usia</div>
                        <div class="info-value">
                            @if ($collection->sgTglLahir)
                                {{ \Carbon\Carbon::parse($collection->sgTglLahir)->diff(\Carbon\Carbon::now())->format('%y thn %m bln') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Morph / Jenis</div>
                        <div class="info-value font-bold text-green-sg">{{ $collection->sgJenis ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Warna</div>
                        <div class="info-value">{{ $collection->sgWarna ?? '—' }}</div>
                    </div>
                    @if ($collection->sgGenetika)
                    <div class="info-item col-full">
                        <div class="info-label">Genetika</div>
                        <div class="info-value">{{ $collection->sgGenetika }}</div>
                    </div>
                    @endif
                    @if ($collection->sgFenotype)
                    <div class="info-item col-full no-border">
                        <div class="info-label">Fenotype</div>
                        <div class="info-value leading-comfortable">{{ $collection->sgFenotype }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="info-card">
                <h3 class="info-title"><i class="bi bi-bezier2 text-orange-sg"></i> Silsilah Indukan</h3>
                
                <div class="grid sm:grid-cols-2 gap-6 mb-8">
                    <div class="pedigree-box male-box">
                        <div class="pedigree-label label-male">♂ Indukan Jantan</div>
                        @if ($collection->sgIndukanJantan && $indukan->mId)
                            <a href="{{ route('sugarglider.show', $indukan->mId) }}" class="font-extrabold text-xl text-[#1A1A1A] hover:underline">{{ $indukan->jantan }}</a>
                            <p class="text-[#666] text-sm mt-1">{{ $indukan->mJenis }}</p>
                        @else
                            <p class="text-[#999] italic">Tidak diketahui</p>
                        @endif
                    </div>
                    <div class="pedigree-box female-box">
                        <div class="pedigree-label label-female">♀ Indukan Betina</div>
                        @if ($collection->sgIndukanBetina && $indukan->fId)
                            <a href="{{ route('sugarglider.show', $indukan->fId) }}" class="font-extrabold text-xl text-[#1A1A1A] hover:underline">{{ $indukan->betina }}</a>
                            <p class="text-[#666] text-sm mt-1">{{ $indukan->fJenis }}</p>
                        @else
                            <p class="text-[#999] italic">Tidak diketahui</p>
                        @endif
                    </div>
                </div>

                <a href="{{ route('pedigree.show', $collection->sgId) }}" class="btn-blue"><i class="bi bi-diagram-3"></i> Lihat Bagan Silsilah Lengkap</a>
            </div>

        </div>
    </div>
</div>
@endif
</div>
@endsection
