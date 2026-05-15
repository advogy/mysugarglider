@extends('layouts.v_main')

@section('title', 'Kandang — ' . $shelter->nama)

@section('content')

<x-page-hero
    :title="$shelter->nama"
    :breadcrumbs="[['label'=>'Beranda','url'=>route('home')],['label'=>'Kandang','url'=>route('shelters')],['label'=>$shelter->nama]]"
/>

{{-- Google Maps --}}
@if ($shelter->gmaps)
    <div class="w-full">
        <iframe class="w-full h-56 md:h-72 border-0"
                src="https://www.google.com/maps/embed?pb={{ $shelter->gmaps }}"
                allowfullscreen loading="lazy"></iframe>
    </div>
@endif

<section class="py-16 bg-cream">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-5 gap-10">

            {{-- ─── Shelter Info Sidebar ─── --}}
            <div class="lg:col-span-2">
                <div class="card overflow-hidden sticky top-24">
                    {{-- Cover --}}
                    @if ($shelter->gambar)
                        <img src="{{ asset('/upload/shelters/' . $shelter->gambar) }}"
                             alt="{{ $shelter->nama }}"
                             class="w-full aspect-video object-cover">
                    @else
                        <div class="w-full aspect-video bg-sage-100 flex items-center justify-center">
                            <i class="bi bi-house-heart text-6xl text-sage/30"></i>
                        </div>
                    @endif

                    <div class="p-6">
                        <h2 class="text-2xl font-display font-bold text-bark mb-4">{{ $shelter->nama }}</h2>

                        <div class="space-y-3">
                            @if ($shelter->alamat)
                                <div class="flex items-start gap-3 text-sm">
                                    <i class="bi bi-geo-alt text-sage mt-0.5 flex-shrink-0 text-base"></i>
                                    <span class="text-bark-light">{{ $shelter->alamat }}</span>
                                </div>
                            @endif

                            @if ($shelter->user && $shelter->user->email)
                                <div class="flex items-center gap-3 text-sm">
                                    <i class="bi bi-envelope text-sage flex-shrink-0 text-base"></i>
                                    <a href="mailto:{{ $shelter->user->email }}"
                                       class="text-bark-light hover:text-sage font-semibold transition-colors">
                                        {{ $shelter->user->email }}
                                    </a>
                                </div>
                            @endif

                            @if ($shelter->user && $shelter->user->profile && $shelter->user->profile->telepon)
                                <div class="flex items-center gap-3 text-sm">
                                    <i class="bi bi-telephone text-sage flex-shrink-0 text-base"></i>
                                    <a href="tel:{{ $shelter->user->profile->telepon }}"
                                       class="text-bark-light hover:text-sage font-semibold transition-colors">
                                        {{ $shelter->user->profile->telepon }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        @if ($shelter->keterangan)
                            <div class="mt-4 pt-4 border-t border-cream-dark">
                                <p class="text-bark-muted text-sm leading-relaxed italic">
                                    "{{ $shelter->keterangan }}"
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ─── Sugar Glider Collection ─── --}}
            <div class="lg:col-span-3">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-display font-bold text-bark flex items-center gap-2">
                        <i class="bi bi-collection text-sage"></i>
                        Koleksi Sugar Glider
                    </h3>
                    @if (!$sugargliders->isEmpty())
                        <span class="badge-sage">{{ $sugargliders->total() }} ekor</span>
                    @endif
                </div>

                @if ($sugargliders->isEmpty())
                    <div class="card text-center py-16">
                        <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}"
                             class="w-16 mx-auto mb-3 opacity-30" alt="">
                        <p class="text-bark-muted font-semibold">Belum ada koleksi di kandang ini.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($sugargliders as $sg)
                            <a href="{{ route('sugarglider.show', $sg->sgId) }}"
                               class="card flex items-center gap-4 p-4 hover:shadow-hover transition-all duration-200 group">

                                {{-- Photo --}}
                                <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 bg-sage-100">
                                    @if ($sg->sgGambar)
                                        <img src="{{ asset('/upload/sugargliders/' . $sg->sgGambar) }}"
                                             alt="{{ $sg->sgNama }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="bi bi-heart text-sage"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-bark group-hover:text-sage transition-colors truncate">
                                        {{ $sg->sgNama }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if ($sg->sgJenis)
                                            <span class="badge-sage text-[10px]">{{ $sg->sgJenis }}</span>
                                        @endif
                                        @if ($sg->sgKelamin == '0')
                                            <span class="text-pink-500 text-xs font-bold">♀</span>
                                        @else
                                            <span class="text-blue-500 text-xs font-bold">♂</span>
                                        @endif
                                    </div>
                                </div>

                                @if ($sg->sgStatus == '3')
                                    <span class="badge-honey flex-shrink-0">Adopsi</span>
                                @endif

                                <i class="bi bi-chevron-right text-bark-muted group-hover:text-sage transition-colors flex-shrink-0"></i>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $sugargliders->links('pagination::v_pagination_public') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
