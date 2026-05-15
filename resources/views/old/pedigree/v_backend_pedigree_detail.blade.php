@extends('layouts.v_backend')

@section('title', 'Pedigree ' . $sugarglider->kode)

@section('content')

<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('pedigree.index') }}" class="text-bark-muted hover:text-bark transition-colors">
        <i class="bi bi-arrow-left text-xl"></i>
    </a>
    <div>
        <p class="text-bark-muted text-xs font-mono">{{ $sugarglider->kode }}</p>
        <h2 class="text-xl font-bold text-bark">Pedigree — {{ $sugarglider->nama }}</h2>
        <p class="text-bark-muted text-sm mt-0.5">
            {{ $collection->shelter->nama }}
        </p>
    </div>
</div>

<div class="be-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-thin">
        <table class="pedigree-table w-full border-collapse text-sm">
            <thead>
                <tr class="bg-cream text-bark text-center text-xs font-bold uppercase tracking-wide">
                    <th class="border border-cream-dark px-3 py-2.5">Sugar Glider</th>
                    <th class="border border-cream-dark px-3 py-2.5">{{ __('text.parents') }}</th>
                    <th class="border border-cream-dark px-3 py-2.5">{{ __('text.grandparents') }}</th>
                    <th class="border border-cream-dark px-3 py-2.5">{{ __('text.great_grandparents') }}</th>
                    <th class="border border-cream-dark px-3 py-2.5">{{ __('text.great_great_grandparents') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="16" class="cell-self border border-cream-dark px-3 py-2 text-center align-middle">
                        @if ($silsilah->id != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->id) }}" class="hover:underline">
                                @if ($sugarglider->kelamin === 1)
                                    <span class="text-blue-400">&#9794;</span>
                                @else
                                    <span class="text-rose-400">&#9792;</span>
                                @endif
                                <br>{{ $silsilah->nama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->jenis ?? __('text.unknown') }}</span>
                            </a>
                        @else
                            <span class="text-bark-muted">{{ __('text.unknown') }}</span>
                        @endif
                    </td>
                    <td rowspan="8" class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->mNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td rowspan="4" class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->mmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td rowspan="2" class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mmmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mmmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->mmmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mmmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mmmmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mmmmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->mmmmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mmmmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mmmfId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mmmfId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->mmmfNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mmmfJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mmfId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mmfId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->mmfNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mmfJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mmfmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mmfmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->mmfmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mmfmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mmffId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mmffId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->mmffNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mmffJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td rowspan="4" class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mfId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mfId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->mfNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mfJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td rowspan="2" class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mfmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mfmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->mfmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mfmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mfmmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mfmmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->mfmmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mfmmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mfmfId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mfmfId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->mfmfNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mfmfJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mffId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mffId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->mffNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mffJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mffmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mffmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->mffmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mffmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->mfffId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->mfffId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->mfffNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->mfffJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td rowspan="8" class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->fId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->fId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->fNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->fJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td rowspan="4" class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->fmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->fmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->fmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->fmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td rowspan="2" class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->fmmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->fmmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->fmmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->fmmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->fmmmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->fmmmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->fmmmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->fmmmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->fmmfId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->fmmfId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->fmmfNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->fmmfJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->fmfId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->fmfId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->fmfNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->fmfJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->fmfmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->fmfmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->fmfmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->fmfmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->fmffId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->fmffId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->fmffNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->fmffJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td rowspan="4" class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->ffId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->ffId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->ffNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->ffJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td rowspan="2" class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->ffmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->ffmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->ffmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->ffmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->ffmmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->ffmmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->ffmmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->ffmmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->ffmfId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->ffmfId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->ffmfNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->ffmfJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->fffId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->fffId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->fffNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->fffJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                    <td class="cell-male border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->fffmId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->fffmId) }}" class="hover:underline">
                                <span class="text-blue-400">&#9794;</span> {{ $silsilah->fffmNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->fffmJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9794; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
                <tr>
                    <td class="cell-female border border-cream-dark px-3 py-2 align-middle">
                        @if ($silsilah->ffffId != 0)
                            <a href="{{ route('sugarglider.show', $silsilah->ffffId) }}" class="hover:underline">
                                <span class="text-rose-400">&#9792;</span> {{ $silsilah->ffffNama }}<br>
                                <span class="text-bark-muted text-xs">{{ $silsilah->ffffJenis ?? __('text.unknown') }}</span>
                            </a>
                        @else <span class="text-bark-muted text-xs">&#9792; {{ __('text.unknown') }}</span> @endif
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-cream text-bark text-center text-xs font-bold uppercase tracking-wide">
                    <th class="border border-cream-dark px-3 py-2.5">Sugar Glider</th>
                    <th class="border border-cream-dark px-3 py-2.5">Indukan</th>
                    <th class="border border-cream-dark px-3 py-2.5">Kakek-Nenek</th>
                    <th class="border border-cream-dark px-3 py-2.5">Moyang</th>
                    <th class="border border-cream-dark px-3 py-2.5">Buyut</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
