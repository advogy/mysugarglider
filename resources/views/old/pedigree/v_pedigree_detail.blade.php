@extends('layouts.v_main')

@section('title', 'Silsilah — ' . $collection->sgNama . ' ' . $collection->sgKode)

@push('styles')
<style>
    .pedigree-table { border-collapse: collapse; }
    .pedigree-table td, .pedigree-table th {
        padding: 0.5rem 0.85rem; font-size: 0.78rem;
        vertical-align: middle; border: 1px solid #F0EBE1;
    }
    .pedigree-table th {
        background: #F0F7F3; font-weight: 700; text-align: center;
        font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.08em;
        color: #3D6B52;
    }
    .cell-male   { background: #EFF6FF; }
    .cell-female { background: #FDF2F8; }
    .cell-self   { background: #F0F7F3; font-weight: 700; }
    .pedigree-table a { color: #5C8A6E; font-weight: 700; text-decoration: none; }
    .pedigree-table a:hover { text-decoration: underline; }
    .pedigree-table .species { display: block; color: #888; font-weight: 400; font-size: 0.68rem; margin-top: 2px; }
</style>
@endpush

@section('content')

<x-page-hero
    title="Bagan Silsilah"
    :breadcrumbs="[
        ['label'=>'Beranda','url'=>route('home')],
        ['label'=>'Koleksi','url'=>route('collections')],
        ['label'=>$collection->sgKode,'url'=>route('sugarglider.show',$collection->sgId)],
        ['label'=>'Silsilah'],
    ]"
/>

<section class="py-16 bg-cream">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Info card --}}
        <div class="card p-5 mb-8 flex flex-col sm:flex-row items-start sm:items-center gap-5">
            @if ($collection->sgGambar)
                <img src="{{ asset('/upload/sugargliders/' . $collection->sgGambar) }}"
                     alt="{{ $collection->sgNama }}"
                     class="w-20 h-20 rounded-2xl object-cover flex-shrink-0">
            @else
                <div class="w-20 h-20 rounded-2xl bg-sage-100 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-image text-3xl text-sage/30"></i>
                </div>
            @endif

            <div class="flex-1">
                <h2 class="text-2xl font-display font-bold text-bark">{{ $collection->sgNama }}</h2>
                <p class="text-bark-muted text-sm mt-1 flex items-center gap-2 flex-wrap">
                    <span class="font-mono">{{ $collection->sgKode }}</span>
                    @if ($collection->stNama)
                        <span class="text-bark-muted/50">·</span>
                        <a href="{{ route('shelter.show', $collection->stId) }}"
                           class="text-sage font-bold hover:underline">
                            {{ $collection->stNama }}
                        </a>
                    @endif
                </p>
            </div>

            <a href="{{ route('sugarglider.show', $collection->sgId) }}" class="btn-secondary flex-shrink-0">
                <i class="bi bi-person-lines-fill"></i> Profil Lengkap
            </a>
        </div>

        {{-- Legend --}}
        <div class="flex items-center gap-5 mb-4">
            <div class="flex items-center gap-2 text-xs font-semibold text-bark-muted">
                <span class="w-4 h-4 rounded bg-blue-50 border border-blue-200 inline-block"></span>
                Jantan (♂)
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-bark-muted">
                <span class="w-4 h-4 rounded bg-pink-50 border border-pink-200 inline-block"></span>
                Betina (♀)
            </div>
        </div>

        {{-- Pedigree table --}}
        <div class="card overflow-hidden">
            <div class="overflow-x-auto scrollbar-thin">
                <table class="pedigree-table w-full">
                    <thead>
                        <tr>
                            <th>Sugar Glider</th>
                            <th>Indukan</th>
                            <th>Kakek–Nenek</th>
                            <th>Moyang</th>
                            <th>Buyut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="16" class="cell-self text-center">
                                @if ($silsilah->id != 0)
                                    <a href="{{ route('sugarglider.show', $silsilah->id) }}">
                                        @if ($collection->sgKelamin === 1) &#9794; @else &#9792; @endif
                                        {{ $silsilah->nama }}
                                    </a>
                                    <span class="species">{{ $silsilah->jenis ?? __('text.unknown') }}</span>
                                @else {{ __('text.unknown') }} @endif
                            </td>
                            <td rowspan="8" class="cell-male">
                                @if ($silsilah->mId != 0) <a href="{{ route('sugarglider.show', $silsilah->mId) }}">&#9794; {{ $silsilah->mNama }}</a><span class="species">{{ $silsilah->mJenis ?? __('text.unknown') }}</span>
                                @else &#9794; {{ __('text.unknown') }} @endif
                            </td>
                            <td rowspan="4" class="cell-male">
                                @if ($silsilah->mmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmId) }}">&#9794; {{ $silsilah->mmNama }}</a><span class="species">{{ $silsilah->mmJenis ?? __('text.unknown') }}</span>
                                @else &#9794; {{ __('text.unknown') }} @endif
                            </td>
                            <td rowspan="2" class="cell-male">
                                @if ($silsilah->mmmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmmId) }}">&#9794; {{ $silsilah->mmmNama }}</a><span class="species">{{ $silsilah->mmmJenis ?? __('text.unknown') }}</span>
                                @else &#9794; {{ __('text.unknown') }} @endif
                            </td>
                            <td class="cell-male">
                                @if ($silsilah->mmmmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmmmId) }}">&#9794; {{ $silsilah->mmmmNama }}</a><span class="species">{{ $silsilah->mmmmJenis ?? __('text.unknown') }}</span>
                                @else &#9794; {{ __('text.unknown') }} @endif
                            </td>
                        </tr>
                        <tr><td class="cell-female">@if ($silsilah->mmmfId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmmfId) }}">&#9792; {{ $silsilah->mmmfNama }}</a><span class="species">{{ $silsilah->mmmfJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td></tr>
                        <tr>
                            <td rowspan="2" class="cell-female">@if ($silsilah->mmfId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmfId) }}">&#9792; {{ $silsilah->mmfNama }}</a><span class="species">{{ $silsilah->mmfJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td>
                            <td class="cell-male">@if ($silsilah->mmfmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmfmId) }}">&#9794; {{ $silsilah->mmfmNama }}</a><span class="species">{{ $silsilah->mmfmJenis ?? __('text.unknown') }}</span>@else &#9794; {{ __('text.unknown') }} @endif</td>
                        </tr>
                        <tr><td class="cell-female">@if ($silsilah->mmffId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmffId) }}">&#9792; {{ $silsilah->mmffNama }}</a><span class="species">{{ $silsilah->mmffJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td></tr>
                        <tr>
                            <td rowspan="4" class="cell-female">@if ($silsilah->mfId != 0) <a href="{{ route('sugarglider.show', $silsilah->mfId) }}">&#9792; {{ $silsilah->mfNama }}</a><span class="species">{{ $silsilah->mfJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td>
                            <td rowspan="2" class="cell-male">@if ($silsilah->mfmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mfmId) }}">&#9794; {{ $silsilah->mfmNama }}</a><span class="species">{{ $silsilah->mfmJenis ?? __('text.unknown') }}</span>@else &#9794; {{ __('text.unknown') }} @endif</td>
                            <td class="cell-male">@if ($silsilah->mfmmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mfmmId) }}">&#9794; {{ $silsilah->mfmmNama }}</a><span class="species">{{ $silsilah->mfmmJenis ?? __('text.unknown') }}</span>@else &#9794; {{ __('text.unknown') }} @endif</td>
                        </tr>
                        <tr><td class="cell-female">@if ($silsilah->mfmfId != 0) <a href="{{ route('sugarglider.show', $silsilah->mfmfId) }}">&#9792; {{ $silsilah->mfmfNama }}</a><span class="species">{{ $silsilah->mfmfJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td></tr>
                        <tr>
                            <td rowspan="2" class="cell-female">@if ($silsilah->mffId != 0) <a href="{{ route('sugarglider.show', $silsilah->mffId) }}">&#9792; {{ $silsilah->mffNama }}</a><span class="species">{{ $silsilah->mffJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td>
                            <td class="cell-male">@if ($silsilah->mffmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mffmId) }}">&#9794; {{ $silsilah->mffmNama }}</a><span class="species">{{ $silsilah->mffmJenis ?? __('text.unknown') }}</span>@else &#9794; {{ __('text.unknown') }} @endif</td>
                        </tr>
                        <tr><td class="cell-female">@if ($silsilah->mfffId != 0) <a href="{{ route('sugarglider.show', $silsilah->mfffId) }}">&#9792; {{ $silsilah->mfffNama }}</a><span class="species">{{ $silsilah->mfffJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td></tr>
                        {{-- Female side --}}
                        <tr>
                            <td rowspan="8" class="cell-female">@if ($silsilah->fId != 0) <a href="{{ route('sugarglider.show', $silsilah->fId) }}">&#9792; {{ $silsilah->fNama }}</a><span class="species">{{ $silsilah->fJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td>
                            <td rowspan="4" class="cell-male">@if ($silsilah->fmId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmId) }}">&#9794; {{ $silsilah->fmNama }}</a><span class="species">{{ $silsilah->fmJenis ?? __('text.unknown') }}</span>@else &#9794; {{ __('text.unknown') }} @endif</td>
                            <td rowspan="2" class="cell-male">@if ($silsilah->fmmId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmmId) }}">&#9794; {{ $silsilah->fmmNama }}</a><span class="species">{{ $silsilah->fmmJenis ?? __('text.unknown') }}</span>@else &#9794; {{ __('text.unknown') }} @endif</td>
                            <td class="cell-male">@if ($silsilah->fmmmId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmmmId) }}">&#9794; {{ $silsilah->fmmmNama }}</a><span class="species">{{ $silsilah->fmmmJenis ?? __('text.unknown') }}</span>@else &#9794; {{ __('text.unknown') }} @endif</td>
                        </tr>
                        <tr><td class="cell-female">@if ($silsilah->fmmfId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmmfId) }}">&#9792; {{ $silsilah->fmmfNama }}</a><span class="species">{{ $silsilah->fmmfJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td></tr>
                        <tr>
                            <td rowspan="2" class="cell-female">@if ($silsilah->fmfId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmfId) }}">&#9792; {{ $silsilah->fmfNama }}</a><span class="species">{{ $silsilah->fmfJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td>
                            <td class="cell-male">@if ($silsilah->fmfmId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmfmId) }}">&#9794; {{ $silsilah->fmfmNama }}</a><span class="species">{{ $silsilah->fmfmJenis ?? __('text.unknown') }}</span>@else &#9794; {{ __('text.unknown') }} @endif</td>
                        </tr>
                        <tr><td class="cell-female">@if ($silsilah->fmffId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmffId) }}">&#9792; {{ $silsilah->fmffNama }}</a><span class="species">{{ $silsilah->fmffJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td></tr>
                        <tr>
                            <td rowspan="4" class="cell-female">@if ($silsilah->ffId != 0) <a href="{{ route('sugarglider.show', $silsilah->ffId) }}">&#9792; {{ $silsilah->ffNama }}</a><span class="species">{{ $silsilah->ffJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td>
                            <td rowspan="2" class="cell-male">@if ($silsilah->ffmId != 0) <a href="{{ route('sugarglider.show', $silsilah->ffmId) }}">&#9794; {{ $silsilah->ffmNama }}</a><span class="species">{{ $silsilah->ffmJenis ?? __('text.unknown') }}</span>@else &#9794; {{ __('text.unknown') }} @endif</td>
                            <td class="cell-male">@if ($silsilah->ffmmId != 0) <a href="{{ route('sugarglider.show', $silsilah->ffmmId) }}">&#9794; {{ $silsilah->ffmmNama }}</a><span class="species">{{ $silsilah->ffmmJenis ?? __('text.unknown') }}</span>@else &#9794; {{ __('text.unknown') }} @endif</td>
                        </tr>
                        <tr><td class="cell-female">@if ($silsilah->ffmfId != 0) <a href="{{ route('sugarglider.show', $silsilah->ffmfId) }}">&#9792; {{ $silsilah->ffmfNama }}</a><span class="species">{{ $silsilah->ffmfJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td></tr>
                        <tr>
                            <td rowspan="2" class="cell-female">@if ($silsilah->fffId != 0) <a href="{{ route('sugarglider.show', $silsilah->fffId) }}">&#9792; {{ $silsilah->fffNama }}</a><span class="species">{{ $silsilah->fffJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td>
                            <td class="cell-male">@if ($silsilah->fffmId != 0) <a href="{{ route('sugarglider.show', $silsilah->fffmId) }}">&#9794; {{ $silsilah->fffmNama }}</a><span class="species">{{ $silsilah->fffmJenis ?? __('text.unknown') }}</span>@else &#9794; {{ __('text.unknown') }} @endif</td>
                        </tr>
                        <tr><td class="cell-female">@if ($silsilah->ffffId != 0) <a href="{{ route('sugarglider.show', $silsilah->ffffId) }}">&#9792; {{ $silsilah->ffffNama }}</a><span class="species">{{ $silsilah->ffffJenis ?? __('text.unknown') }}</span>@else &#9792; {{ __('text.unknown') }} @endif</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

@endsection
