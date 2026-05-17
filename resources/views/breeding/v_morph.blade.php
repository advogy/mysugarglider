@extends('layouts.v_backend')

@section('title', 'Morph Predictor')

@section('content')

<x-page-header
    title="Morph Predictor"
    subtitle="Prediksi morph keturunan berdasarkan genetika Mendel"
/>

<x-alert type="danger" :errors="$errors" />

@php
    $sireExpressed = $sireExpressed ?? [];
    $sireHet       = $sireHet       ?? [];
    $damExpressed  = $damExpressed  ?? [];
    $damHet        = $damHet        ?? [];
    $recessiveMorphs = collect($morphs)->filter(fn($m) => $m['type'] === 'recessive');
@endphp

<form method="POST" action="{{ route('breeding.morph.calculate') }}">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-5">

        {{-- ── Kiri: form kedua indukan ── --}}
        <div class="lg:col-span-8">
        <div class="be-card">

            {{-- Sire + Dam dalam satu card, divide-x --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-cream-dark">

                @include('breeding._morph_form_side', [
                    'side'          => 'sire',
                    'label'         => 'Indukan Jantan',
                    'icon'          => 'bi-gender-male text-blue-500',
                    'morphs'        => $morphs,
                    'recessives'    => $recessiveMorphs,
                    'expressed'     => $sireExpressed,
                    'het'           => $sireHet,
                ])

                @include('breeding._morph_form_side', [
                    'side'          => 'dam',
                    'label'         => 'Indukan Betina',
                    'icon'          => 'bi-gender-female text-pink-500',
                    'morphs'        => $morphs,
                    'recessives'    => $recessiveMorphs,
                    'expressed'     => $damExpressed,
                    'het'           => $damHet,
                ])

            </div>

            {{-- Card footer: legend + submit --}}
            <div class="px-5 py-4 border-t border-cream-dark bg-cream/40 rounded-b-2xl space-y-3">
                <p class="text-xs text-bark-muted">
                    <strong class="text-bark">Morph Utama</strong> = morph yang diekspresikan indukan (pilih satu).
                    <strong class="text-bark">Het</strong> = gen resesif yang dibawa tapi tidak terlihat, bisa pilih banyak.
                    Contoh: <em>Mosaic het Platinum het Albino</em>.
                </p>
                <div class="flex justify-end">
                    <button type="submit" class="btn-create px-6">
                        <i class="bi bi-stars"></i> Prediksi Keturunan
                    </button>
                </div>
            </div>

        </div>
        </div>

        {{-- ── Kanan: Legend + Disclaimer ── --}}
        <div class="lg:col-span-4">
        <div class="be-card overflow-hidden">

            {{-- Disclaimer --}}
            <div class="px-4 py-3 border-b border-cream-dark flex items-center gap-2 bg-amber-50/60">
                <i class="bi bi-exclamation-triangle-fill text-amber-500 text-sm flex-shrink-0"></i>
                <p class="font-ui font-bold text-bark text-sm">Disclaimer & Dasar Perhitungan</p>
            </div>
            <div class="p-4 text-sm space-y-3">
                <p class="text-bark-muted">
                    Menggunakan <strong class="text-bark">Hukum Mendel</strong> — probabilitas pewarisan gen berdasarkan genotipe kedua indukan, dengan asumsi setiap lokus gen bersifat <em>independen</em>.
                </p>
                <p class="text-bark-muted">
                    Data pola pewarisan bersumber dari <strong class="text-bark">konsensus komunitas breeder Sugar Glider</strong>, bukan penelitian genetik formal.
                </p>
                <ul class="text-bark-muted space-y-1.5 list-disc list-inside text-xs">
                    <li><strong class="text-bark">Morph Mosaic</strong> — mekanisme pewarisannya masih diperdebatkan; diasumsikan dominan.</li>
                    <li><strong class="text-bark">PlatMos vs TPM</strong> — secara genetika identik. Perbedaan hanya pada standar penampilan.</li>
                    <li><strong class="text-bark">Red, Black Beauty, Melanistic, White Mosaic, Marble Mosaic, Piebald Mosaic</strong> — tidak dimasukkan karena kombinasi gen belum diketahui.</li>
                    <li>Hasil aktual dapat bervariasi. Gunakan sebagai <em>estimasi probabilitas</em>.</li>
                </ul>
            </div>

        </div>
        </div>

    </div>
</form>

{{-- ── RESULTS ── --}}
@if(isset($result))
<div id="results" class="space-y-5">

    <div class="be-card px-6 py-4">
        <p class="text-sm text-bark-muted">
            Persilangan:
            <span class="font-bold text-bark">{{ $result['sire_display'] }}</span>
            <i class="bi bi-x text-bark-muted mx-1.5"></i>
            <span class="font-bold text-bark">{{ $result['dam_display'] }}</span>
        </p>
    </div>

    <div class="be-card">
        <div class="px-5 py-4 border-b border-cream-dark">
            <p class="font-ui font-bold text-bark text-sm">Prediksi Morph Keturunan</p>
            <p class="text-xs text-bark-muted mt-0.5">Probabilitas berdasarkan hukum Mendel — loci diasumsikan independen</p>
        </div>

        <div class="divide-y divide-cream-dark">
            @foreach($result['outcomes'] as $outcome)
                <div class="px-5 py-4 flex items-center gap-4">
                    {{-- Percentage --}}
                    <div class="w-14 flex-shrink-0 text-right">
                        <span class="font-number font-bold text-bark">{{ $outcome['percent'] }}%</span>
                    </div>

                    {{-- Bar --}}
                    <div class="w-24 flex-shrink-0">
                        <div class="h-2 bg-cream rounded-full overflow-hidden">
                            <div class="h-full bg-sage rounded-full" style="width: {{ min($outcome['percent'], 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Morph labels --}}
                    <div class="flex flex-wrap items-center gap-1.5 flex-1">

                        {{-- Combo badge (jika ada) --}}
                        @if(!empty($outcome['combo']))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border {{ $outcome['combo']['color'] }}"
                                  title="{{ $outcome['combo']['desc'] }}">
                                <i class="bi bi-stars text-xs"></i>
                                {{ $outcome['combo']['label'] }}
                            </span>
                            <span class="text-bark-muted text-xs">·</span>
                        @endif

                        {{-- Individual morph badges --}}
                        @foreach($outcome['morphs'] as $morphLabel)
                            @php $entry = collect($morphs)->first(fn($m) => $m['label'] === $morphLabel); @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $entry['color'] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $morphLabel }}
                            </span>
                        @endforeach

                        {{-- Het badges --}}
                        @foreach($outcome['het'] as $hetLabel)
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold border border-amber-300 text-amber-700 bg-white">
                                het {{ $hetLabel }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        @php $total = collect($result['outcomes'])->sum('percent'); @endphp
        <div class="px-5 py-3 border-t border-cream-dark bg-cream/50 flex justify-between text-xs text-bark-muted">
            <span>Total</span>
            <span class="font-mono font-bold text-bark">{{ round($total, 2) }}%</span>
        </div>
    </div>

</div>
@endif

@push('scripts')
<script>
@if(isset($result))
window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('results')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
});
@endif
</script>
@endpush

@endsection
