@extends('layouts.v_main')
@section('title', 'Silsilah — ' . $collection->sgNama . ' ' . $collection->sgKode)

@section('content')

<header class="premium-page-header">
    <div class="header-blob-1 blob-right blob-blue-light"></div>
    <h1 class="page-title size-md">Bagan Silsilah</h1>
</header>

<div class="bg-white pb-20 pt-10">
    <div class="pedigree-container">

        <div class="profile-bar">
            <div class="pb-info">
                @if ($collection->sgGambar)
                    <img src="{{ asset('/upload/sugargliders/' . $collection->sgGambar) }}" class="pb-img" alt="{{ $collection->sgNama }}">
                @else
                    <div class="pb-img flex items-center justify-center text-3xl pedigree-ph"><i class="bi bi-bezier2"></i></div>
                @endif
                <div>
                    <div class="pb-name">{{ $collection->sgNama }}</div>
                    <div class="pb-meta">
                        {{ $collection->sgKode }}
                        @if ($collection->stNama)
                            <span class="divider-light">|</span>
                            <a href="{{ route('shelter.show', $collection->stId) }}" class="link-green">{{ $collection->stNama }}</a>
                        @endif
                    </div>
                </div>
            </div>
            <a href="{{ route('sugarglider.show', $collection->sgId) }}" class="pb-btn">Profil Lengkap</a>
        </div>

        <div class="legend">
            <div class="legend-item"><div class="legend-color legend-color-male"></div> Jantan (♂)</div>
            <div class="legend-item"><div class="legend-color legend-color-female"></div> Betina (♀)</div>
        </div>

        <div class="tree-card overflow-x-auto scrollbar-thin">
            <table class="pedigree-table">
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
                        <td rowspan="16" class="cell-self">
                            @if ($silsilah->id != 0)
                                <a href="{{ route('sugarglider.show', $silsilah->id) }}">
                                    @if ($collection->sgKelamin === 1) <span class="icon-male">&#9794;</span> @else <span class="icon-female">&#9792;</span> @endif
                                    {{ $silsilah->nama }}
                                </a>
                                <span class="species">{{ $silsilah->jenis ?? __('text.unknown') }}</span>
                            @else {{ __('text.unknown') }} @endif
                        </td>
                        <td rowspan="8" class="cell-male">@if ($silsilah->mId != 0) <a href="{{ route('sugarglider.show', $silsilah->mId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->mNama }}</a><span class="species">{{ $silsilah->mJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td rowspan="4" class="cell-male">@if ($silsilah->mmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->mmNama }}</a><span class="species">{{ $silsilah->mmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td rowspan="2" class="cell-male">@if ($silsilah->mmmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->mmmNama }}</a><span class="species">{{ $silsilah->mmmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td class="cell-male">@if ($silsilah->mmmmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmmmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->mmmmNama }}</a><span class="species">{{ $silsilah->mmmmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                    </tr>
                    <tr><td class="cell-female">@if ($silsilah->mmmfId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmmfId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->mmmfNama }}</a><span class="species">{{ $silsilah->mmmfJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td></tr>
                    <tr>
                        <td rowspan="2" class="cell-female">@if ($silsilah->mmfId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmfId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->mmfNama }}</a><span class="species">{{ $silsilah->mmfJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td class="cell-male">@if ($silsilah->mmfmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmfmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->mmfmNama }}</a><span class="species">{{ $silsilah->mmfmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                    </tr>
                    <tr><td class="cell-female">@if ($silsilah->mmffId != 0) <a href="{{ route('sugarglider.show', $silsilah->mmffId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->mmffNama }}</a><span class="species">{{ $silsilah->mmffJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td></tr>
                    <tr>
                        <td rowspan="4" class="cell-female">@if ($silsilah->mfId != 0) <a href="{{ route('sugarglider.show', $silsilah->mfId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->mfNama }}</a><span class="species">{{ $silsilah->mfJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td rowspan="2" class="cell-male">@if ($silsilah->mfmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mfmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->mfmNama }}</a><span class="species">{{ $silsilah->mfmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td class="cell-male">@if ($silsilah->mfmmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mfmmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->mfmmNama }}</a><span class="species">{{ $silsilah->mfmmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                    </tr>
                    <tr><td class="cell-female">@if ($silsilah->mfmfId != 0) <a href="{{ route('sugarglider.show', $silsilah->mfmfId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->mfmfNama }}</a><span class="species">{{ $silsilah->mfmfJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td></tr>
                    <tr>
                        <td rowspan="2" class="cell-female">@if ($silsilah->mffId != 0) <a href="{{ route('sugarglider.show', $silsilah->mffId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->mffNama }}</a><span class="species">{{ $silsilah->mffJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td class="cell-male">@if ($silsilah->mffmId != 0) <a href="{{ route('sugarglider.show', $silsilah->mffmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->mffmNama }}</a><span class="species">{{ $silsilah->mffmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                    </tr>
                    <tr><td class="cell-female">@if ($silsilah->mfffId != 0) <a href="{{ route('sugarglider.show', $silsilah->mfffId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->mfffNama }}</a><span class="species">{{ $silsilah->mfffJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td></tr>
                    <tr>
                        <td rowspan="8" class="cell-female">@if ($silsilah->fId != 0) <a href="{{ route('sugarglider.show', $silsilah->fId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->fNama }}</a><span class="species">{{ $silsilah->fJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td rowspan="4" class="cell-male">@if ($silsilah->fmId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->fmNama }}</a><span class="species">{{ $silsilah->fmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td rowspan="2" class="cell-male">@if ($silsilah->fmmId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->fmmNama }}</a><span class="species">{{ $silsilah->fmmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td class="cell-male">@if ($silsilah->fmmmId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmmmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->fmmmNama }}</a><span class="species">{{ $silsilah->fmmmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                    </tr>
                    <tr><td class="cell-female">@if ($silsilah->fmmfId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmmfId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->fmmfNama }}</a><span class="species">{{ $silsilah->fmmfJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td></tr>
                    <tr>
                        <td rowspan="2" class="cell-female">@if ($silsilah->fmfId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmfId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->fmfNama }}</a><span class="species">{{ $silsilah->fmfJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td class="cell-male">@if ($silsilah->fmfmId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmfmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->fmfmNama }}</a><span class="species">{{ $silsilah->fmfmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                    </tr>
                    <tr><td class="cell-female">@if ($silsilah->fmffId != 0) <a href="{{ route('sugarglider.show', $silsilah->fmffId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->fmffNama }}</a><span class="species">{{ $silsilah->fmffJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td></tr>
                    <tr>
                        <td rowspan="4" class="cell-female">@if ($silsilah->ffId != 0) <a href="{{ route('sugarglider.show', $silsilah->ffId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->ffNama }}</a><span class="species">{{ $silsilah->ffJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td rowspan="2" class="cell-male">@if ($silsilah->ffmId != 0) <a href="{{ route('sugarglider.show', $silsilah->ffmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->ffmNama }}</a><span class="species">{{ $silsilah->ffmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td class="cell-male">@if ($silsilah->ffmmId != 0) <a href="{{ route('sugarglider.show', $silsilah->ffmmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->ffmmNama }}</a><span class="species">{{ $silsilah->ffmmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                    </tr>
                    <tr><td class="cell-female">@if ($silsilah->ffmfId != 0) <a href="{{ route('sugarglider.show', $silsilah->ffmfId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->ffmfNama }}</a><span class="species">{{ $silsilah->ffmfJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td></tr>
                    <tr>
                        <td rowspan="2" class="cell-female">@if ($silsilah->fffId != 0) <a href="{{ route('sugarglider.show', $silsilah->fffId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->fffNama }}</a><span class="species">{{ $silsilah->fffJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                        <td class="cell-male">@if ($silsilah->fffmId != 0) <a href="{{ route('sugarglider.show', $silsilah->fffmId) }}"><span class="icon-male">&#9794;</span> {{ $silsilah->fffmNama }}</a><span class="species">{{ $silsilah->fffmJenis ?? __('text.unknown') }}</span>@else <span class="icon-male">&#9794;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td>
                    </tr>
                    <tr><td class="cell-female">@if ($silsilah->ffffId != 0) <a href="{{ route('sugarglider.show', $silsilah->ffffId) }}"><span class="icon-female">&#9792;</span> {{ $silsilah->ffffNama }}</a><span class="species">{{ $silsilah->ffffJenis ?? __('text.unknown') }}</span>@else <span class="icon-female">&#9792;</span> <span class="text-unknown">{{ __('text.unknown') }}</span> @endif</td></tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
