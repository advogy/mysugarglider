@extends('layouts.v_backend')

@section('title', $sugarglider->nama)

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sugarglider.index') }}" class="text-bark-muted hover:text-bark transition-colors flex-shrink-0">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h2 class="text-xl font-bold text-bark">{{ $sugarglider->nama }}</h2>
                @if ($sugarglider->kelamin == '0')
                    <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full bg-pink-50 text-pink-500 border border-pink-200">
                        ♀ Betina
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-500 border border-blue-200">
                        ♂ Jantan
                    </span>
                @endif
                @if ($sugarglider->jenis)
                    <span class="badge-sage">{{ $sugarglider->jenis }}</span>
                @endif
            </div>
            <p class="text-bark-muted text-sm font-mono mt-0.5">{{ $sugarglider->kode }}</p>
        </div>
    </div>
    <div class="flex items-center gap-2 flex-shrink-0">
        <a href="{{ route('sugarglider.edit', $sugarglider->id) }}" class="btn-edit">
            <i class="bi bi-pencil"></i>
            <span>Edit</span>
        </a>
        <button type="button"
            onclick="confirmDelete('{{ route('sugarglider.destroy', $sugarglider->id) }}', '{{ $sugarglider->nama }}')"
            class="btn-delete">
            <i class="bi bi-trash"></i>
        </button>
    </div>
</div>

@if (session('pesan'))
    <div class="alert-success mb-5">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <p class="font-semibold">{{ session('pesan') }}</p>
    </div>
@endif

{{-- Main content grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

    {{-- Biodata card --}}
    <div class="lg:col-span-2 be-card p-6 space-y-4">
        <div class="flex items-center gap-3 mb-2">
            @if ($sugarglider->gambar)
                <img src="{{ asset('/upload/sugargliders/' . $sugarglider->gambar) }}"
                     class="w-20 h-20 rounded-2xl object-cover flex-shrink-0" alt="{{ $sugarglider->nama }}">
            @else
                <div class="w-20 h-20 rounded-2xl bg-sage-100 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-heart text-sage text-2xl"></i>
                </div>
            @endif
            <div>
                <p class="text-xs font-bold text-bark-muted uppercase tracking-wide mb-1">Biodata</p>
                <p class="text-bark text-sm">
                    @if ($sugarglider->tgl_lahir)
                        @php
                            $isMati   = $collection && (int)$collection->status === \App\Enums\CollectionStatus::MATI->value;
                            $endDate  = $isMati ? \Carbon\Carbon::parse($collection->updated_at) : \Carbon\Carbon::now();
                            $diff     = \Carbon\Carbon::parse($sugarglider->tgl_lahir)->diff($endDate);
                            $parts    = [];
                            if ($diff->y > 0) $parts[] = $diff->y . ' thn';
                            if ($diff->m > 0) $parts[] = $diff->m . ' bln';
                            $usia     = $parts ? implode(' ', $parts) : '< 1 bln';
                        @endphp
                        Lahir {{ \Carbon\Carbon::parse($sugarglider->tgl_lahir)->translatedFormat('d F Y') }}
                        <span class="text-bark-muted">&middot;
                            @if ($isMati)
                                Usia {{ $usia }} <span class="text-xs">(saat mati)</span>
                            @else
                                {{ $usia }}
                            @endif
                        </span>
                    @else
                        Tanggal lahir tidak diketahui
                    @endif
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div>
                <p class="text-bark-muted text-xs font-semibold uppercase tracking-wide mb-0.5">Warna</p>
                <p class="text-bark font-medium">{{ $sugarglider->warna ?: '—' }}</p>
            </div>
            <div>
                <p class="text-bark-muted text-xs font-semibold uppercase tracking-wide mb-0.5">Genetika</p>
                <p class="text-bark font-medium">{{ $sugarglider->genetika ?: '—' }}</p>
            </div>
            <div>
                <p class="text-bark-muted text-xs font-semibold uppercase tracking-wide mb-0.5">Fenotype</p>
                <p class="text-bark font-medium">{{ $sugarglider->fenotype ?: '—' }}</p>
            </div>
            <div>
                <p class="text-bark-muted text-xs font-semibold uppercase tracking-wide mb-0.5">Morph / Jenis</p>
                <p class="text-bark font-medium">{{ $sugarglider->jenis ?: '—' }}</p>
            </div>
            <div>
                <p class="text-bark-muted text-xs font-semibold uppercase tracking-wide mb-0.5">Indukan ♂ Jantan</p>
                @if ($silsilah && $silsilah->mId)
                    @php
                        $anc = $ancestorMap[$silsilah->mId] ?? null;
                        $ownM = $anc && $anc->user_id === Auth::id();
                        $visM = $anc && in_array((int)$anc->cl_status, [\App\Enums\CollectionStatus::PUBLIK->value, \App\Enums\CollectionStatus::ADOPSI->value]);
                    @endphp
                    @if ($ownM)
                        <a href="{{ route('sugarglider.backend.show', $silsilah->mId) }}" class="text-sage-dark font-medium hover:underline">{{ $silsilah->mNama }}</a>
                    @elseif ($visM)
                        <a href="{{ route('sugarglider.show', $silsilah->mId) }}" class="text-sage-dark font-medium hover:underline" target="_blank" rel="noopener">{{ $silsilah->mNama }}</a>
                    @else
                        <span class="font-medium text-bark">{{ $silsilah->mNama }}</span>
                        <span class="text-xs text-gray-400 ml-1">(privat)</span>
                    @endif
                @else
                    <p class="text-bark-muted">—</p>
                @endif
            </div>
            <div>
                <p class="text-bark-muted text-xs font-semibold uppercase tracking-wide mb-0.5">Indukan ♀ Betina</p>
                @if ($silsilah && $silsilah->fId)
                    @php
                        $anc = $ancestorMap[$silsilah->fId] ?? null;
                        $ownF = $anc && $anc->user_id === Auth::id();
                        $visF = $anc && in_array((int)$anc->cl_status, [\App\Enums\CollectionStatus::PUBLIK->value, \App\Enums\CollectionStatus::ADOPSI->value]);
                    @endphp
                    @if ($ownF)
                        <a href="{{ route('sugarglider.backend.show', $silsilah->fId) }}" class="text-sage-dark font-medium hover:underline">{{ $silsilah->fNama }}</a>
                    @elseif ($visF)
                        <a href="{{ route('sugarglider.show', $silsilah->fId) }}" class="text-sage-dark font-medium hover:underline" target="_blank" rel="noopener">{{ $silsilah->fNama }}</a>
                    @else
                        <span class="font-medium text-bark">{{ $silsilah->fNama }}</span>
                        <span class="text-xs text-gray-400 ml-1">(privat)</span>
                    @endif
                @else
                    <p class="text-bark-muted">—</p>
                @endif
            </div>
        </div>

        @if ($sugarglider->keterangan)
            <div class="pt-2 border-t border-cream-dark">
                <p class="text-bark-muted text-xs font-semibold uppercase tracking-wide mb-1">Keterangan</p>
                <p class="text-bark text-sm leading-relaxed">{{ $sugarglider->keterangan }}</p>
            </div>
        @endif
    </div>

    {{-- Sidebar cards --}}
    <div class="space-y-4">

        {{-- Penempatan --}}
        <div class="be-card p-5">
            <p class="text-xs font-bold text-bark-muted uppercase tracking-wide mb-3">Penempatan Saat Ini</p>
            @if ($collection && $collection->shelter)
                @php
                    $clStatus = (int) $collection->status;
                    $statusLabel = match($clStatus) {
                        1 => ['Privat', 'inline-flex items-center bg-gray-100 text-gray-500 text-xs font-bold px-2.5 py-1 rounded-full'],
                        2 => ['Publik', 'badge-sage'],
                        3 => ['Adopsi', 'bg-honey-50 text-honey-dark border border-honey/30 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold'],
                        4 => ['Mati', 'inline-flex items-center bg-gray-800 text-gray-100 text-xs font-bold px-2.5 py-1 rounded-full'],
                        default => ['—', 'text-bark-muted text-xs'],
                    };
                @endphp
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="font-bold text-bark text-sm">{{ $collection->shelter->nama }}</p>
                        <span class="{{ $statusLabel[1] }} mt-1.5">{{ $statusLabel[0] }}</span>
                    </div>
                    <a href="{{ route('collection.edit', $collection->id) }}"
                       class="text-bark-muted hover:text-bark transition-colors flex-shrink-0 mt-0.5">
                        <i class="bi bi-pencil text-sm"></i>
                    </a>
                </div>
            @else
                <p class="text-bark-muted text-sm mb-3">Belum ada penempatan aktif.</p>
                <a href="{{ route('collection.create') }}" class="btn-create text-xs py-2 px-4 inline-flex">
                    <i class="bi bi-plus-lg"></i> Tambah Penempatan
                </a>
            @endif
        </div>

        {{-- Riwayat Pindah --}}
        @if ($transfers->isNotEmpty())
            <div class="be-card p-5">
                <p class="text-xs font-bold text-bark-muted uppercase tracking-wide mb-3">Riwayat Adopsi</p>
                <div class="space-y-3">
                    @foreach ($transfers as $t)
                        <div class="text-xs border-l-2 border-sage pl-3">
                            <p class="text-bark-muted mb-0.5">{{ \Carbon\Carbon::parse($t->created_at)->translatedFormat('d M Y') }}</p>
                            <p class="text-bark font-semibold">{{ $t->from_shelter_nama ?? '—' }}</p>
                            <p class="text-bark-muted flex items-center gap-1">
                                <i class="bi bi-arrow-down text-sage"></i>
                                {{ $t->to_shelter_nama }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Keturunan --}}
        <div class="be-card p-5">
            <p class="text-xs font-bold text-bark-muted uppercase tracking-wide mb-3">
                Keturunan
                @if ($keturunan->isNotEmpty())
                    <span class="ml-1 font-normal normal-case text-bark-muted">({{ $keturunan->count() }})</span>
                @endif
            </p>
            @if ($keturunan->isEmpty())
                <p class="text-bark-muted text-xs italic">Belum ada keturunan tercatat.</p>
            @else
                <div class="space-y-2">
                    @foreach ($keturunan as $kt)
                        @php
                            $ktOwn = $kt->user_id === Auth::id();
                            $ktVis = in_array((int)$kt->cl_status, [
                                \App\Enums\CollectionStatus::PUBLIK->value,
                                \App\Enums\CollectionStatus::ADOPSI->value,
                            ]);
                        @endphp
                        <div class="flex items-center justify-between gap-2 text-sm">
                            <div class="min-w-0">
                                @if ($ktOwn)
                                    <a href="{{ route('sugarglider.backend.show', $kt->id) }}" class="font-medium text-bark hover:text-sage-dark hover:underline truncate block">
                                        <span class="{{ $kt->kelamin == 1 ? 'text-blue-400' : 'text-rose-400' }}">
                                            {{ $kt->kelamin == 1 ? '♂' : '♀' }}
                                        </span>
                                        {{ $kt->nama }}
                                    </a>
                                @elseif ($ktVis)
                                    <a href="{{ route('sugarglider.show', $kt->id) }}" target="_blank" rel="noopener" class="font-medium text-bark hover:text-sage-dark hover:underline truncate block">
                                        <span class="{{ $kt->kelamin == 1 ? 'text-blue-400' : 'text-rose-400' }}">
                                            {{ $kt->kelamin == 1 ? '♂' : '♀' }}
                                        </span>
                                        {{ $kt->nama }}
                                    </a>
                                @endif
                                <p class="text-xs text-bark-muted truncate">{{ $kt->jenis ?? '—' }}
                                    @if (!$ktOwn)
                                        &middot; {{ $kt->user_name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

{{-- Silsilah --}}
<div class="be-card overflow-hidden">
    <div class="px-6 py-4 border-b border-cream-dark">
        <p class="font-bold text-bark">Silsilah</p>
        <p class="text-bark-muted text-xs mt-0.5">Pohon keturunan hingga 4 generasi</p>
    </div>
    <div class="overflow-x-auto scrollbar-thin">
        @if ($silsilah)
        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="bg-cream text-bark text-center text-xs font-bold uppercase tracking-wide">
                    <th class="border border-cream-dark px-3 py-2.5">Sugar Glider</th>
                    <th class="border border-cream-dark px-3 py-2.5">Indukan</th>
                    <th class="border border-cream-dark px-3 py-2.5">Kakek-Nenek</th>
                    <th class="border border-cream-dark px-3 py-2.5">Moyang</th>
                    <th class="border border-cream-dark px-3 py-2.5">Buyut</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $nodes = [
                        ['key' => 'm',    'gender' => 'm', 'rows' => 8],
                        ['key' => 'f',    'gender' => 'f', 'rows' => 8],
                        ['key' => 'mm',   'gender' => 'm', 'rows' => 4],
                        ['key' => 'mf',   'gender' => 'f', 'rows' => 4],
                        ['key' => 'fm',   'gender' => 'm', 'rows' => 4],
                        ['key' => 'ff',   'gender' => 'f', 'rows' => 4],
                        ['key' => 'mmm',  'gender' => 'm', 'rows' => 2],
                        ['key' => 'mmf',  'gender' => 'f', 'rows' => 2],
                        ['key' => 'mfm',  'gender' => 'm', 'rows' => 2],
                        ['key' => 'mff',  'gender' => 'f', 'rows' => 2],
                        ['key' => 'fmm',  'gender' => 'm', 'rows' => 2],
                        ['key' => 'fmf',  'gender' => 'f', 'rows' => 2],
                        ['key' => 'ffm',  'gender' => 'm', 'rows' => 2],
                        ['key' => 'fff',  'gender' => 'f', 'rows' => 2],
                        ['key' => 'mmmm', 'gender' => 'm', 'rows' => 1],
                        ['key' => 'mmmf', 'gender' => 'f', 'rows' => 1],
                        ['key' => 'mmfm', 'gender' => 'm', 'rows' => 1],
                        ['key' => 'mmff', 'gender' => 'f', 'rows' => 1],
                        ['key' => 'mfmm', 'gender' => 'm', 'rows' => 1],
                        ['key' => 'mfmf', 'gender' => 'f', 'rows' => 1],
                        ['key' => 'mffm', 'gender' => 'm', 'rows' => 1],
                        ['key' => 'mfff', 'gender' => 'f', 'rows' => 1],
                        ['key' => 'fmmm', 'gender' => 'm', 'rows' => 1],
                        ['key' => 'fmmf', 'gender' => 'f', 'rows' => 1],
                        ['key' => 'fmfm', 'gender' => 'm', 'rows' => 1],
                        ['key' => 'fmff', 'gender' => 'f', 'rows' => 1],
                        ['key' => 'ffmm', 'gender' => 'm', 'rows' => 1],
                        ['key' => 'ffmf', 'gender' => 'f', 'rows' => 1],
                        ['key' => 'fffm', 'gender' => 'm', 'rows' => 1],
                        ['key' => 'ffff', 'gender' => 'f', 'rows' => 1],
                    ];
                    $rowIndex = 0;
                    // track which parent cells are already rendered
                    $rendered = [];
                    // For each of the 16 leaf rows, determine which ancestor cells to render
                    // We'll use the original rowspan approach from the existing view
                @endphp
                {{-- Row 1: SG + m + mm + mmm + mmmm --}}
                <tr>
                    <td rowspan="16" class="bg-sage/5 border border-cream-dark px-3 py-2 text-center align-middle">
                        <span class="{{ $sugarglider->kelamin == '0' ? 'text-rose-400' : 'text-blue-400' }}">
                            {{ $sugarglider->kelamin == '0' ? '♀' : '♂' }}
                        </span><br>
                        {{ $silsilah->nama }}<br>
                        <span class="text-bark-muted text-xs">{{ $sugarglider->jenis ?? '—' }}</span>
                    </td>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mId,   'nama' => $silsilah->mNama,    'jenis' => $silsilah->mJenis,    'gender' => 'm', 'rows' => 8,  'bg' => 'bg-blue-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mmId,  'nama' => $silsilah->mmNama,   'jenis' => $silsilah->mmJenis,   'gender' => 'm', 'rows' => 4,  'bg' => 'bg-blue-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mmmId, 'nama' => $silsilah->mmmNama,  'jenis' => $silsilah->mmmJenis,  'gender' => 'm', 'rows' => 2,  'bg' => 'bg-blue-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mmmmId,'nama' => $silsilah->mmmmNama, 'jenis' => $silsilah->mmmmJenis, 'gender' => 'm', 'rows' => 1,  'bg' => 'bg-blue-50/50'])
                </tr>
                {{-- Row 2: mmmf --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mmmfId,'nama' => $silsilah->mmmfNama, 'jenis' => $silsilah->mmmfJenis, 'gender' => 'f', 'rows' => 1,  'bg' => 'bg-rose-50/50'])
                </tr>
                {{-- Row 3: mmf + mmfm --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mmfId, 'nama' => $silsilah->mmfNama,  'jenis' => $silsilah->mmfJenis,  'gender' => 'f', 'rows' => 2,  'bg' => 'bg-rose-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mmfmId,'nama' => $silsilah->mmfmNama, 'jenis' => $silsilah->mmfmJenis, 'gender' => 'm', 'rows' => 1,  'bg' => 'bg-blue-50/50'])
                </tr>
                {{-- Row 4: mmff --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mmffId,'nama' => $silsilah->mmffNama, 'jenis' => $silsilah->mmffJenis, 'gender' => 'f', 'rows' => 1,  'bg' => 'bg-rose-50/50'])
                </tr>
                {{-- Row 5: mf + mfm + mfmm --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mfId,  'nama' => $silsilah->mfNama,   'jenis' => $silsilah->mfJenis,   'gender' => 'f', 'rows' => 4,  'bg' => 'bg-rose-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mfmId, 'nama' => $silsilah->mfmNama,  'jenis' => $silsilah->mfmJenis,  'gender' => 'm', 'rows' => 2,  'bg' => 'bg-blue-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mfmmId,'nama' => $silsilah->mfmmNama, 'jenis' => $silsilah->mfmmJenis, 'gender' => 'm', 'rows' => 1,  'bg' => 'bg-blue-50/50'])
                </tr>
                {{-- Row 6: mfmf --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mfmfId,'nama' => $silsilah->mfmfNama, 'jenis' => $silsilah->mfmfJenis, 'gender' => 'f', 'rows' => 1,  'bg' => 'bg-rose-50/50'])
                </tr>
                {{-- Row 7: mff + mffm --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mffId, 'nama' => $silsilah->mffNama,  'jenis' => $silsilah->mffJenis,  'gender' => 'f', 'rows' => 2,  'bg' => 'bg-rose-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mffmId,'nama' => $silsilah->mffmNama, 'jenis' => $silsilah->mffmJenis, 'gender' => 'm', 'rows' => 1,  'bg' => 'bg-blue-50/50'])
                </tr>
                {{-- Row 8: mfff --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->mfffId,'nama' => $silsilah->mfffNama, 'jenis' => $silsilah->mfffJenis, 'gender' => 'f', 'rows' => 1,  'bg' => 'bg-rose-50/50'])
                </tr>
                {{-- Row 9: f + fm + fmm + fmmm --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->fId,   'nama' => $silsilah->fNama,    'jenis' => $silsilah->fJenis,    'gender' => 'f', 'rows' => 8,  'bg' => 'bg-rose-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->fmId,  'nama' => $silsilah->fmNama,   'jenis' => $silsilah->fmJenis,   'gender' => 'm', 'rows' => 4,  'bg' => 'bg-blue-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->fmmId, 'nama' => $silsilah->fmmNama,  'jenis' => $silsilah->fmmJenis,  'gender' => 'm', 'rows' => 2,  'bg' => 'bg-blue-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->fmmmId,'nama' => $silsilah->fmmmNama, 'jenis' => $silsilah->fmmmJenis, 'gender' => 'm', 'rows' => 1,  'bg' => 'bg-blue-50/50'])
                </tr>
                {{-- Row 10: fmmf --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->fmmfId,'nama' => $silsilah->fmmfNama, 'jenis' => $silsilah->fmmfJenis, 'gender' => 'f', 'rows' => 1,  'bg' => 'bg-rose-50/50'])
                </tr>
                {{-- Row 11: fmf + fmfm --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->fmfId, 'nama' => $silsilah->fmfNama,  'jenis' => $silsilah->fmfJenis,  'gender' => 'f', 'rows' => 2,  'bg' => 'bg-rose-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->fmfmId,'nama' => $silsilah->fmfmNama, 'jenis' => $silsilah->fmfmJenis, 'gender' => 'm', 'rows' => 1,  'bg' => 'bg-blue-50/50'])
                </tr>
                {{-- Row 12: fmff --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->fmffId,'nama' => $silsilah->fmffNama, 'jenis' => $silsilah->fmffJenis, 'gender' => 'f', 'rows' => 1,  'bg' => 'bg-rose-50/50'])
                </tr>
                {{-- Row 13: ff + ffm + ffmm --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->ffId,  'nama' => $silsilah->ffNama,   'jenis' => $silsilah->ffJenis,   'gender' => 'f', 'rows' => 4,  'bg' => 'bg-rose-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->ffmId, 'nama' => $silsilah->ffmNama,  'jenis' => $silsilah->ffmJenis,  'gender' => 'm', 'rows' => 2,  'bg' => 'bg-blue-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->ffmmId,'nama' => $silsilah->ffmmNama, 'jenis' => $silsilah->ffmmJenis, 'gender' => 'm', 'rows' => 1,  'bg' => 'bg-blue-50/50'])
                </tr>
                {{-- Row 14: ffmf --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->ffmfId,'nama' => $silsilah->ffmfNama, 'jenis' => $silsilah->ffmfJenis, 'gender' => 'f', 'rows' => 1,  'bg' => 'bg-rose-50/50'])
                </tr>
                {{-- Row 15: fff + fffm --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->fffId, 'nama' => $silsilah->fffNama,  'jenis' => $silsilah->fffJenis,  'gender' => 'f', 'rows' => 2,  'bg' => 'bg-rose-50/50'])
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->fffmId,'nama' => $silsilah->fffmNama, 'jenis' => $silsilah->fffmJenis, 'gender' => 'm', 'rows' => 1,  'bg' => 'bg-blue-50/50'])
                </tr>
                {{-- Row 16: ffff --}}
                <tr>
                    @include('sugargliders._pedigree_cell', ['id' => $silsilah->ffffId,'nama' => $silsilah->ffffNama, 'jenis' => $silsilah->ffffJenis, 'gender' => 'f', 'rows' => 1,  'bg' => 'bg-rose-50/50'])
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
        @else
            <div class="py-12 text-center text-bark-muted">
                <i class="bi bi-diagram-3 text-3xl opacity-30 mb-2 block"></i>
                <p class="text-sm">Data silsilah tidak tersedia.</p>
            </div>
        @endif
    </div>
</div>

<x-delete-modal />

@endsection
