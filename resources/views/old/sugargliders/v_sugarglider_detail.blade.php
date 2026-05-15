@extends('layouts.v_main')

@section('title', 'Detail — ' . ($collection ? $collection->sgNama : 'Sugar Glider'))

@section('content')

<x-page-hero
    :title="$collection ? $collection->sgNama : 'Sugar Glider'"
    :breadcrumbs="[
        ['label'=>'Beranda','url'=>route('home')],
        ['label'=>'Koleksi','url'=>route('collections')],
        ['label'=>$collection ? $collection->sgKode : '—'],
    ]"
/>

<section class="py-16 bg-cream">
    <div class="max-w-7xl mx-auto px-6">

        @if (!$collection)
            <div class="text-center py-24">
                <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}"
                     class="w-20 mx-auto mb-4 opacity-30" alt="">
                <p class="text-bark-muted font-semibold">Data sugar glider tidak ditemukan.</p>
                <a href="{{ route('collections') }}" class="btn-secondary mt-6">
                    <i class="bi bi-arrow-left"></i> Kembali ke Koleksi
                </a>
            </div>
        @else

        <div class="grid lg:grid-cols-3 gap-8">

            {{-- ─── Photo & Basic Info ─── --}}
            <div class="lg:col-span-1 space-y-4">

                {{-- Photo card --}}
                <div class="card overflow-hidden">
                    @if ($collection->sgGambar)
                        <a href="{{ asset('/upload/sugargliders/' . $collection->sgGambar) }}" class="glightbox block">
                            <img src="{{ asset('/upload/sugargliders/' . $collection->sgGambar) }}"
                                 alt="{{ $collection->sgNama }}"
                                 class="w-full aspect-square object-cover hover:scale-105 transition-transform duration-500">
                        </a>
                    @else
                        <div class="w-full aspect-square bg-sage-100 flex flex-col items-center justify-center gap-2">
                            <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}"
                                 class="w-20 opacity-30" alt="">
                        </div>
                    @endif

                    <div class="p-5">
                        <h2 class="text-2xl font-display font-bold text-bark mb-1">
                            {{ $collection->sgNama }}
                        </h2>
                        @if ($collection->clUser != '0' && $collection->stNama)
                            <a href="{{ route('shelter.show', $collection->stId) }}"
                               class="text-sage text-sm font-bold hover:underline flex items-center gap-1 mb-1">
                                <i class="bi bi-house-heart"></i> {{ $collection->stNama }}
                            </a>
                        @endif
                        <p class="font-mono text-bark-muted text-xs">{{ $collection->sgKode }}</p>

                        @if ($collection->sgKeterangan)
                            <div class="mt-4 pt-4 border-t border-cream-dark">
                                <p class="text-bark-muted text-sm leading-relaxed italic">
                                    "{{ $collection->sgKeterangan }}"
                                </p>
                            </div>
                        @endif

                        {{-- Adoption CTA --}}
                        @if ($collection->clUser != '0' && $collection->clStatus == '3')
                            <div class="mt-4 pt-4 border-t border-cream-dark">
                                <div class="bg-honey-50 border border-honey/30 rounded-2xl p-4 text-center">
                                    <span class="badge-honey mb-2">Tersedia Adopsi</span>
                                    <p class="text-bark-muted text-xs mt-2 mb-3 leading-relaxed">
                                        Hubungi
                                        <a href="{{ route('shelter.show', $collection->stId) }}"
                                           class="text-sage font-bold hover:underline">
                                            {{ $collection->stNama }}
                                        </a>
                                        untuk info lebih lanjut.
                                    </p>
                                    @guest
                                        <a href="{{ route('login') }}" class="btn-primary w-full justify-center text-xs py-2">
                                            Masuk untuk Mengajukan
                                        </a>
                                    @else
                                        <a href="{{ route('adoption.list') }}" class="btn-primary w-full justify-center text-xs py-2">
                                            <i class="bi bi-heart-arrow"></i> Ajukan Adopsi
                                        </a>
                                    @endguest
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Descendants --}}
                @if ($keturunans->isNotEmpty())
                    <div class="card p-5">
                        <h4 class="font-bold text-bark mb-3 flex items-center gap-2">
                            <i class="bi bi-people text-sage"></i> Keturunan
                        </h4>
                        <ul class="space-y-2">
                            @foreach ($keturunans as $kt)
                                <li>
                                    <a href="{{ route('sugarglider.show', $kt->id) }}"
                                       class="flex items-center gap-2 text-sm text-bark-light hover:text-sage font-semibold transition-colors">
                                        <i class="bi bi-chevron-right text-sage text-xs"></i>
                                        {{ $kt->nama }}
                                        @if ($kt->jenis)
                                            <span class="text-bark-muted font-normal">({{ $kt->jenis }})</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- ─── Detail Info ─── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Profile data --}}
                <div class="card p-6">
                    <h3 class="font-bold text-bark text-base mb-5 flex items-center gap-2">
                        <i class="bi bi-person-lines-fill text-sage"></i> Profil
                    </h3>
                    <dl class="grid sm:grid-cols-2 gap-x-8">
                        @php
                            $rows = [
                                ['label'=>'Jenis Kelamin', 'slot'=>true],
                                ['label'=>'Tanggal Lahir (OOP)', 'value'=>$collection->sgTglLahir],
                                ['label'=>'Usia', 'value'=>\Carbon\Carbon::parse($collection->sgTglLahir)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')],
                                ['label'=>'Warna', 'value'=>$collection->sgWarna],
                                ['label'=>'Morph / Jenis', 'value'=>$collection->sgJenis],
                            ];
                        @endphp

                        <div class="flex justify-between items-center py-3 border-b border-cream-dark">
                            <dt class="text-bark-muted text-sm">Jenis Kelamin</dt>
                            <dd class="font-bold text-sm">
                                @if ($collection->sgKelamin == '0')
                                    <span class="text-pink-500">♀ Betina</span>
                                @else
                                    <span class="text-blue-500">♂ Jantan</span>
                                @endif
                            </dd>
                        </div>

                        <div class="flex justify-between items-center py-3 border-b border-cream-dark">
                            <dt class="text-bark-muted text-sm">Tanggal Lahir (OOP)</dt>
                            <dd class="font-bold text-bark text-sm">{{ $collection->sgTglLahir }}</dd>
                        </div>

                        <div class="flex justify-between items-center py-3 border-b border-cream-dark">
                            <dt class="text-bark-muted text-sm">Usia</dt>
                            <dd class="font-bold text-bark text-sm">
                                {{ \Carbon\Carbon::parse($collection->sgTglLahir)->diff(\Carbon\Carbon::now())->format('%y thn %m bln') }}
                            </dd>
                        </div>

                        <div class="flex justify-between items-center py-3 border-b border-cream-dark">
                            <dt class="text-bark-muted text-sm">Warna</dt>
                            <dd class="font-bold text-bark text-sm">{{ $collection->sgWarna ?? '—' }}</dd>
                        </div>

                        <div class="flex justify-between items-center py-3 border-b border-cream-dark sm:col-span-2">
                            <dt class="text-bark-muted text-sm">Morph / Jenis</dt>
                            <dd>
                                @if ($collection->sgJenis)
                                    <span class="badge-sage">{{ $collection->sgJenis }}</span>
                                @else
                                    <span class="font-bold text-bark text-sm">—</span>
                                @endif
                            </dd>
                        </div>

                        @if ($collection->sgGenetika)
                            <div class="flex justify-between items-center py-3 border-b border-cream-dark sm:col-span-2">
                                <dt class="text-bark-muted text-sm">Genetika</dt>
                                <dd class="font-bold text-bark text-sm">{{ $collection->sgGenetika }}</dd>
                            </div>
                        @endif

                        @if ($collection->sgFenotype)
                            <div class="py-3 border-b border-cream-dark sm:col-span-2">
                                <dt class="text-bark-muted text-sm mb-1">Fenotype</dt>
                                <dd class="font-bold text-bark text-sm leading-relaxed">{{ $collection->sgFenotype }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Pedigree / Silsilah --}}
                <div class="card p-6">
                    <h3 class="font-bold text-bark text-base mb-5 flex items-center gap-2">
                        <i class="bi bi-diagram-3 text-sage"></i> Silsilah Indukan
                    </h3>

                    <div class="grid sm:grid-cols-2 gap-4 mb-5">
                        {{-- Father --}}
                        <div class="bg-blue-50 rounded-2xl p-4">
                            <p class="text-[11px] font-bold text-blue-500 uppercase tracking-wider mb-2">
                                ♂ Indukan Jantan
                            </p>
                            @if ($collection->sgIndukanJantan && $collection->sgIndukanJantan != 0 && $indukan->mId)
                                <a href="{{ route('sugarglider.show', $indukan->mId) }}"
                                   class="font-bold text-bark hover:text-sage transition-colors text-sm">
                                    {{ $indukan->jantan }}
                                </a>
                                <p class="text-bark-muted text-xs mt-0.5">{{ $indukan->mJenis }}</p>
                            @else
                                <p class="text-bark-muted text-sm italic">Tidak diketahui</p>
                            @endif
                        </div>

                        {{-- Mother --}}
                        <div class="bg-pink-50 rounded-2xl p-4">
                            <p class="text-[11px] font-bold text-pink-500 uppercase tracking-wider mb-2">
                                ♀ Indukan Betina
                            </p>
                            @if ($collection->sgIndukanBetina && $collection->sgIndukanBetina != 0 && $indukan->fId)
                                <a href="{{ route('sugarglider.show', $indukan->fId) }}"
                                   class="font-bold text-bark hover:text-sage transition-colors text-sm">
                                    {{ $indukan->betina }}
                                </a>
                                <p class="text-bark-muted text-xs mt-0.5">{{ $indukan->fJenis }}</p>
                            @else
                                <p class="text-bark-muted text-sm italic">Tidak diketahui</p>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('pedigree.show', $collection->sgId) }}"
                       class="btn-secondary w-full justify-center">
                        <i class="bi bi-diagram-3"></i> Lihat Bagan Silsilah Lengkap
                    </a>
                </div>

            </div>
        </div>
        @endif
    </div>
</section>

@endsection
