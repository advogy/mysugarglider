@extends('layouts.v_main')

@push('meta')
    <meta name="description" content="Lihat koleksi Sugar Glider terlengkap di Indonesia — MySugarGlider.id">
@endpush

@section('title', 'Koleksi Sugar Glider')

@section('content')

<x-page-hero
    title="Koleksi Sugar Glider"
    :breadcrumbs="[['label'=>'Beranda','url'=>route('home')],['label'=>'Koleksi']]"
/>

<section class="py-16 bg-cream">
    <div class="max-w-7xl mx-auto px-6">

        @if ($collections->isEmpty())
            <div class="text-center py-24">
                <img src="{{ asset('assets/images/mascot/glider-glide.svg') }}"
                     class="w-20 mx-auto mb-5 opacity-30" alt="">
                <h3 class="text-xl font-bold text-bark mb-2">Belum Ada Koleksi</h3>
                <p class="text-bark-muted text-sm">Belum ada sugar glider yang terdaftar secara publik.</p>
            </div>
        @else
            {{-- Table card --}}
            <div class="card overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-cream-dark flex items-center justify-between">
                    <h2 class="font-bold text-bark text-base flex items-center gap-2">
                        <i class="bi bi-collection text-sage"></i>
                        Daftar Koleksi
                    </h2>
                    <span class="badge-sage">
                        {{ $collections->total() }} Sugar Glider
                    </span>
                </div>

                <div class="overflow-x-auto scrollbar-thin">
                    <table class="be-table">
                        <thead>
                            <tr>
                                <th class="hidden md:table-cell">No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th class="hidden sm:table-cell">Kelamin</th>
                                <th class="hidden lg:table-cell">Morph / Jenis</th>
                                <th class="hidden md:table-cell">Kandang</th>
                                <th>Status</th>
                                <th>Silsilah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($collections as $c)
                                <tr>
                                    <td class="hidden md:table-cell text-bark-muted text-xs">
                                        {{ ($collections->currentPage() - 1) * $collections->perPage() + $loop->iteration }}
                                    </td>

                                    <td>
                                        <a href="{{ route('sugarglider.show', $c->sgId) }}"
                                           class="font-mono text-sm font-bold text-sage hover:underline">
                                            {{ $c->sgKode }}
                                        </a>
                                    </td>

                                    <td>
                                        <a href="{{ route('sugarglider.show', $c->sgId) }}"
                                           class="font-bold text-bark hover:text-sage transition-colors">
                                            {{ $c->sgNama }}
                                        </a>
                                    </td>

                                    <td class="hidden sm:table-cell">
                                        @if ($c->sgKelamin == '0')
                                            <span class="text-pink-500 font-bold text-sm">♀ Betina</span>
                                        @else
                                            <span class="text-blue-500 font-bold text-sm">♂ Jantan</span>
                                        @endif
                                    </td>

                                    <td class="hidden lg:table-cell text-bark-light text-sm">
                                        {{ $c->sgJenis ?? '—' }}
                                    </td>

                                    <td class="hidden md:table-cell">
                                        <a href="{{ route('shelter.show', $c->stId) }}"
                                           class="text-sm font-semibold text-bark-light hover:text-sage transition-colors">
                                            {{ $c->stNama }}
                                        </a>
                                    </td>

                                    <td>
                                        @if ($c->sgStatus == '3')
                                            <span class="badge-honey">Adopsi</span>
                                        @else
                                            <span class="badge-sage">Koleksi</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('pedigree.show', $c->sgId) }}"
                                           class="w-8 h-8 bg-sage-100 rounded-xl flex items-center justify-center
                                                  hover:bg-sage hover:text-white text-sage
                                                  transition-all duration-200">
                                            <i class="bi bi-diagram-3 text-sm"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="flex justify-center">
                {{ $collections->links('pagination::v_pagination_public') }}
            </div>
        @endif
    </div>
</section>

@endsection
