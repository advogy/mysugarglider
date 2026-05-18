@extends('layouts.v_backend')

@section('title', 'Inbreeding Calculator')

@section('content')

<x-page-header
    title="Inbreeding Calculator"
    subtitle="Hitung koefisien persilangan sedarah (F) berdasarkan silsilah"
/>

<x-alert type="danger" :errors="$errors" />

@php $activeMode = old('mode', isset($request) ? $request->mode : 'db'); @endphp

{{-- Form --}}
<form method="POST" action="{{ route('breeding.inbreeding.calculate') }}" id="breeding-form">
    @csrf
    <input type="hidden" name="mode" id="mode-input" value="{{ $activeMode }}">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-5">
    <div class="lg:col-span-8">

    {{-- ── Unified form card ── --}}
    <div class="be-card">

        {{-- Tab header --}}
        <div class="px-4 py-3 border-b border-cream-dark flex items-center gap-1">
            <button type="button" id="tab-db" onclick="switchBreedingMode('db')"
                    class="px-3.5 py-1.5 rounded-lg text-sm font-ui font-bold transition-all {{ $activeMode === 'db' ? 'bg-sage text-white shadow-sm' : 'text-bark-muted hover:text-bark hover:bg-cream' }}">
                <i class="bi bi-database-fill mr-1.5"></i>Dari Data SG
            </button>
            <button type="button" id="tab-manual" onclick="switchBreedingMode('manual')"
                    class="px-3.5 py-1.5 rounded-lg text-sm font-ui font-bold transition-all {{ $activeMode === 'manual' ? 'bg-sage text-white shadow-sm' : 'text-bark-muted hover:text-bark hover:bg-cream' }}">
                <i class="bi bi-pencil-fill mr-1.5"></i>Input Manual
            </button>
        </div>

        {{-- ── MODE: DARI DATA ── --}}
        <div id="section-db" class="{{ $activeMode === 'db' ? '' : 'hidden' }}">
            <div class="p-5 space-y-4">
                <p class="text-sm text-bark-muted">Pilih dua Sugar Glider dari data yang tersedia. Sistem membaca silsilah otomatis dari database.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="flex items-center gap-1.5 text-sm font-semibold text-bark mb-1.5">
                            <i class="bi bi-gender-male text-blue-500"></i>Calon Indukan Jantan
                        </label>
                        @if($males->isEmpty() && $malesOthers->isEmpty())
                            <p class="text-sm text-bark-muted italic">Belum ada data Sugar Glider jantan.</p>
                        @else
                            <select name="sire_id" id="select-sire" class="input-field">
                                <option value="">-- Pilih Jantan --</option>
                                @if($males->isNotEmpty())
                                <optgroup label="SG Saya">
                                    @foreach($males as $sg)
                                        @php
                                            $shelter  = $sg->collections->sortByDesc('id')->first()?->shelter?->nama ?? null;
                                            $parts    = array_filter([$sg->jenis ?: null, $sg->kode ?: null, $shelter]);
                                            $optLabel = $sg->nama . ($parts ? ' (' . implode(') (', $parts) . ')' : '');
                                        @endphp
                                        <option value="{{ $sg->id }}"
                                                data-nama="{{ $sg->nama }}"
                                                data-jenis="{{ $sg->jenis ?? '' }}"
                                                data-kode="{{ $sg->kode ?? '' }}"
                                                data-kandang="{{ $shelter ?? '' }}"
                                                @selected((old('sire_id') ?? (isset($request) ? $request->sire_id : '')) == $sg->id)>
                                            {{ $optLabel }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                @endif
                                @if($malesOthers->isNotEmpty())
                                <optgroup label="SG Lainnya">
                                    @foreach($malesOthers as $sg)
                                        @php
                                            $shelter  = $sg->collections->sortByDesc('id')->first()?->shelter?->nama ?? null;
                                            $parts    = array_filter([$sg->jenis ?: null, $sg->kode ?: null, $shelter]);
                                            $optLabel = $sg->nama . ($parts ? ' (' . implode(') (', $parts) . ')' : '');
                                        @endphp
                                        <option value="{{ $sg->id }}"
                                                data-nama="{{ $sg->nama }}"
                                                data-jenis="{{ $sg->jenis ?? '' }}"
                                                data-kode="{{ $sg->kode ?? '' }}"
                                                data-kandang="{{ $shelter ?? '' }}"
                                                @selected((old('sire_id') ?? (isset($request) ? $request->sire_id : '')) == $sg->id)>
                                            {{ $optLabel }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                @endif
                            </select>
                        @endif
                    </div>
                    <div>
                        <label class="flex items-center gap-1.5 text-sm font-semibold text-bark mb-1.5">
                            <i class="bi bi-gender-female text-pink-500"></i>Calon Indukan Betina
                        </label>
                        @if($females->isEmpty() && $femalesOthers->isEmpty())
                            <p class="text-sm text-bark-muted italic">Belum ada data Sugar Glider betina.</p>
                        @else
                            <select name="dam_id" id="select-dam" class="input-field">
                                <option value="">-- Pilih Betina --</option>
                                @if($females->isNotEmpty())
                                <optgroup label="SG Saya">
                                    @foreach($females as $sg)
                                        @php
                                            $shelter  = $sg->collections->sortByDesc('id')->first()?->shelter?->nama ?? null;
                                            $parts    = array_filter([$sg->jenis ?: null, $sg->kode ?: null, $shelter]);
                                            $optLabel = $sg->nama . ($parts ? ' (' . implode(') (', $parts) . ')' : '');
                                        @endphp
                                        <option value="{{ $sg->id }}"
                                                data-nama="{{ $sg->nama }}"
                                                data-jenis="{{ $sg->jenis ?? '' }}"
                                                data-kode="{{ $sg->kode ?? '' }}"
                                                data-kandang="{{ $shelter ?? '' }}"
                                                @selected((old('dam_id') ?? (isset($request) ? $request->dam_id : '')) == $sg->id)>
                                            {{ $optLabel }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                @endif
                                @if($femalesOthers->isNotEmpty())
                                <optgroup label="SG Lainnya">
                                    @foreach($femalesOthers as $sg)
                                        @php
                                            $shelter  = $sg->collections->sortByDesc('id')->first()?->shelter?->nama ?? null;
                                            $parts    = array_filter([$sg->jenis ?: null, $sg->kode ?: null, $shelter]);
                                            $optLabel = $sg->nama . ($parts ? ' (' . implode(') (', $parts) . ')' : '');
                                        @endphp
                                        <option value="{{ $sg->id }}"
                                                data-nama="{{ $sg->nama }}"
                                                data-jenis="{{ $sg->jenis ?? '' }}"
                                                data-kode="{{ $sg->kode ?? '' }}"
                                                data-kandang="{{ $shelter ?? '' }}"
                                                @selected((old('dam_id') ?? (isset($request) ? $request->dam_id : '')) == $sg->id)>
                                            {{ $optLabel }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                @endif
                            </select>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ── MODE: MANUAL ── --}}
        <div id="section-manual" class="{{ $activeMode === 'manual' ? '' : 'hidden' }}">
            <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-cream-dark">

                {{-- SIRE (Jantan) --}}
                <div class="p-5 space-y-4">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-gender-male text-blue-500 text-base"></i>
                        <h3 class="font-ui font-bold text-bark text-sm">Indukan Jantan</h3>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-bark-light block mb-1.5">Nama Jantan <span class="text-red-500">*</span></label>
                        <input type="text" name="sire_name" value="{{ old('sire_name') }}"
                               placeholder="cth: Buddy" class="input-field" autocomplete="off">
                    </div>

                    <div class="border-t border-cream-dark pt-4">
                        <p class="text-sm font-semibold text-bark-muted mb-3">Generasi 1 — Orang Tua</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-male text-blue-400 text-xs"></i> Ayah</label>
                                <input type="text" name="sire_sire_name" value="{{ old('sire_sire_name') }}"
                                       placeholder="Nama ayah" class="input-field" autocomplete="off">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-female text-pink-400 text-xs"></i> Ibu</label>
                                <input type="text" name="sire_dam_name" value="{{ old('sire_dam_name') }}"
                                       placeholder="Nama ibu" class="input-field" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-cream-dark pt-4">
                        <p class="text-sm font-semibold text-bark-muted mb-3">Generasi 2 — Kakek & Nenek</p>
                        <div class="grid grid-cols-2 gap-x-3 gap-y-3">
                            <div class="col-span-2">
                                <p class="text-xs text-bark-muted/70 mb-1.5">via Ayah</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-male text-blue-400 text-xs"></i> Kakek</label>
                                <input type="text" name="sire_sire_sire_name" value="{{ old('sire_sire_sire_name') }}"
                                       placeholder="Nama" class="input-field" autocomplete="off">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-female text-pink-400 text-xs"></i> Nenek</label>
                                <input type="text" name="sire_sire_dam_name" value="{{ old('sire_sire_dam_name') }}"
                                       placeholder="Nama" class="input-field" autocomplete="off">
                            </div>
                            <div class="col-span-2 mt-1">
                                <p class="text-xs text-bark-muted/70 mb-1.5">via Ibu</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-male text-blue-400 text-xs"></i> Kakek</label>
                                <input type="text" name="sire_dam_sire_name" value="{{ old('sire_dam_sire_name') }}"
                                       placeholder="Nama" class="input-field" autocomplete="off">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-female text-pink-400 text-xs"></i> Nenek</label>
                                <input type="text" name="sire_dam_dam_name" value="{{ old('sire_dam_dam_name') }}"
                                       placeholder="Nama" class="input-field" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    {{-- Gen 3 collapsible --}}
                    <div class="border-t border-cream-dark pt-3">
                        <button type="button" onclick="toggleGen3('sire')"
                                class="flex items-center gap-1.5 text-sm text-bark-muted hover:text-bark transition-colors">
                            <i id="sire-gen3-icon" class="bi bi-chevron-right text-xs transition-transform"></i>
                            <span class="font-semibold">Generasi 3 — Buyut (Opsional)</span>
                        </button>
                        <div id="sire-gen3" class="hidden mt-3 space-y-3">
                            @php
                                $sireGen3 = [
                                    ['label_via' => 'Ayah → Kakek', 'prefix' => 'sire_sire_sire'],
                                    ['label_via' => 'Ayah → Nenek', 'prefix' => 'sire_sire_dam'],
                                    ['label_via' => 'Ibu → Kakek',  'prefix' => 'sire_dam_sire'],
                                    ['label_via' => 'Ibu → Nenek',  'prefix' => 'sire_dam_dam'],
                                ];
                            @endphp
                            @foreach($sireGen3 as $g)
                                <div>
                                    <p class="text-xs text-bark-muted/70 mb-1.5">via {{ $g['label_via'] }}</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-male text-blue-400 text-xs"></i> Buyut ♂</label>
                                            <input type="text" name="{{ $g['prefix'] }}_sire_name" value="{{ old($g['prefix'] . '_sire_name') }}"
                                                   placeholder="Nama" class="input-field" autocomplete="off">
                                        </div>
                                        <div>
                                            <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-female text-pink-400 text-xs"></i> Buyut ♀</label>
                                            <input type="text" name="{{ $g['prefix'] }}_dam_name" value="{{ old($g['prefix'] . '_dam_name') }}"
                                                   placeholder="Nama" class="input-field" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- DAM (Betina) --}}
                <div class="p-5 space-y-4">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-gender-female text-pink-500 text-base"></i>
                        <h3 class="font-ui font-bold text-bark text-sm">Indukan Betina</h3>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-bark-light block mb-1.5">Nama Betina <span class="text-red-500">*</span></label>
                        <input type="text" name="dam_name" value="{{ old('dam_name') }}"
                               placeholder="cth: Luna" class="input-field" autocomplete="off">
                    </div>

                    <div class="border-t border-cream-dark pt-4">
                        <p class="text-sm font-semibold text-bark-muted mb-3">Generasi 1 — Orang Tua</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-male text-blue-400 text-xs"></i> Ayah</label>
                                <input type="text" name="dam_sire_name" value="{{ old('dam_sire_name') }}"
                                       placeholder="Nama ayah" class="input-field" autocomplete="off">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-female text-pink-400 text-xs"></i> Ibu</label>
                                <input type="text" name="dam_dam_name" value="{{ old('dam_dam_name') }}"
                                       placeholder="Nama ibu" class="input-field" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-cream-dark pt-4">
                        <p class="text-sm font-semibold text-bark-muted mb-3">Generasi 2 — Kakek & Nenek</p>
                        <div class="grid grid-cols-2 gap-x-3 gap-y-3">
                            <div class="col-span-2">
                                <p class="text-xs text-bark-muted/70 mb-1.5">via Ayah</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-male text-blue-400 text-xs"></i> Kakek</label>
                                <input type="text" name="dam_sire_sire_name" value="{{ old('dam_sire_sire_name') }}"
                                       placeholder="Nama" class="input-field" autocomplete="off">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-female text-pink-400 text-xs"></i> Nenek</label>
                                <input type="text" name="dam_sire_dam_name" value="{{ old('dam_sire_dam_name') }}"
                                       placeholder="Nama" class="input-field" autocomplete="off">
                            </div>
                            <div class="col-span-2 mt-1">
                                <p class="text-xs text-bark-muted/70 mb-1.5">via Ibu</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-male text-blue-400 text-xs"></i> Kakek</label>
                                <input type="text" name="dam_dam_sire_name" value="{{ old('dam_dam_sire_name') }}"
                                       placeholder="Nama" class="input-field" autocomplete="off">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-female text-pink-400 text-xs"></i> Nenek</label>
                                <input type="text" name="dam_dam_dam_name" value="{{ old('dam_dam_dam_name') }}"
                                       placeholder="Nama" class="input-field" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-cream-dark pt-3">
                        <button type="button" onclick="toggleGen3('dam')"
                                class="flex items-center gap-1.5 text-sm text-bark-muted hover:text-bark transition-colors">
                            <i id="dam-gen3-icon" class="bi bi-chevron-right text-xs transition-transform"></i>
                            <span class="font-semibold">Generasi 3 — Buyut (Opsional)</span>
                        </button>
                        <div id="dam-gen3" class="hidden mt-3 space-y-3">
                            @php
                                $damGen3 = [
                                    ['label_via' => 'Ayah → Kakek', 'prefix' => 'dam_sire_sire'],
                                    ['label_via' => 'Ayah → Nenek', 'prefix' => 'dam_sire_dam'],
                                    ['label_via' => 'Ibu → Kakek',  'prefix' => 'dam_dam_sire'],
                                    ['label_via' => 'Ibu → Nenek',  'prefix' => 'dam_dam_dam'],
                                ];
                            @endphp
                            @foreach($damGen3 as $g)
                                <div>
                                    <p class="text-xs text-bark-muted/70 mb-1.5">via {{ $g['label_via'] }}</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-male text-blue-400 text-xs"></i> Buyut ♂</label>
                                            <input type="text" name="{{ $g['prefix'] }}_sire_name" value="{{ old($g['prefix'] . '_sire_name') }}"
                                                   placeholder="Nama" class="input-field" autocomplete="off">
                                        </div>
                                        <div>
                                            <label class="text-sm font-semibold text-bark-light block mb-1.5"><i class="bi bi-gender-female text-pink-400 text-xs"></i> Buyut ♀</label>
                                            <input type="text" name="{{ $g['prefix'] }}_dam_name" value="{{ old($g['prefix'] . '_dam_name') }}"
                                                   placeholder="Nama" class="input-field" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>{{-- /grid cols manual --}}
        </div>{{-- /section-manual --}}

        {{-- Card footer: submit --}}
        <div class="px-5 py-4 border-t border-cream-dark flex justify-end bg-cream/40 rounded-b-2xl">
            <button type="submit" class="btn-create px-6">
                <i class="bi bi-calculator"></i> Hitung Koefisien F
            </button>
        </div>

    </div>{{-- /be-card --}}

    </div>{{-- /col-span-8 --}}
    <div class="lg:col-span-4">

    {{-- Disclaimer --}}
    <div class="be-card overflow-hidden">
        <div class="px-4 py-3 border-b border-cream-dark flex items-center gap-2 bg-blue-50/60">
            <i class="bi bi-info-circle-fill text-blue-500 text-sm flex-shrink-0"></i>
            <p class="font-ui font-bold text-bark text-sm">Metode & Dasar Perhitungan</p>
        </div>
        <div class="p-4 text-sm space-y-3">
                <p class="text-bark-muted">
                    Kalkulator ini menggunakan <strong class="text-bark">Wright's Path Coefficient</strong>
                    (Wright, 1922) — metode standar untuk menghitung koefisien inbreeding (F) berdasarkan silsilah.
                </p>
                <div class="bg-cream rounded-xl px-4 py-3 font-mono text-xs text-bark text-center tracking-wide">
                    F = Σ [ (½)^(L<sub>s</sub> + L<sub>d</sub> + 1) ]
                </div>
                <ul class="text-bark-muted space-y-1.5 list-disc list-inside text-xs">
                    <li><strong class="text-bark">F</strong> = koefisien inbreeding (0 = tidak berkerabat, 0.25 = full sibling)</li>
                    <li><strong class="text-bark">L<sub>s</sub></strong> = jarak generasi sire → leluhur bersama</li>
                    <li><strong class="text-bark">L<sub>d</sub></strong> = jarak generasi dam → leluhur bersama</li>
                    <li>Dijumlahkan untuk setiap jalur melalui setiap leluhur bersama</li>
                </ul>
                <div class="border-t border-cream-dark pt-3 text-xs text-bark-muted">
                    <strong class="text-bark">Keterbatasan:</strong>
                    Akurasi bergantung pada kelengkapan silsilah. Sistem membaca maksimal
                    <strong class="text-bark">4 generasi</strong> ke belakang; leluhur yang tidak diinput tidak diperhitungkan — nilai F aktual bisa lebih tinggi.
                </div>
        </div>
    </div>

    </div>{{-- /col-span-4 --}}
    </div>{{-- /grid --}}
</form>

{{-- ── RESULTS ── --}}
@if(isset($result))
<div id="results" class="space-y-5">

    {{-- Main result card --}}
    <div class="be-card overflow-hidden">
        <div class="px-6 py-4 border-b border-cream-dark">
            <p class="text-sm text-bark-muted">
                Hasil persilangan: <span class="font-bold text-bark">{{ $result['sire_name'] }}</span>
                <i class="bi bi-x text-bark-muted mx-1"></i>
                <span class="font-bold text-bark">{{ $result['dam_name'] }}</span>
            </p>
        </div>
        <div class="p-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                {{-- F value --}}
                <div class="text-center sm:text-left">
                    <p class="text-xs text-bark-muted uppercase tracking-wide mb-1">Koefisien Inbreeding (F)</p>
                    <p class="text-5xl font-number font-extrabold {{ $result['risk']['color'] }}">
                        {{ $result['percent'] }}<span class="text-2xl">%</span>
                    </p>
                    <div class="mt-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-sm font-bold {{ $result['risk']['bg'] }}">
                            @if($result['F'] == 0)
                                <i class="bi bi-check-circle-fill"></i>
                            @elseif($result['F'] < 0.125)
                                <i class="bi bi-info-circle-fill"></i>
                            @elseif($result['F'] < 0.25)
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            @else
                                <i class="bi bi-x-circle-fill"></i>
                            @endif
                            {{ $result['risk']['label'] }}
                        </span>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="hidden sm:block w-px bg-cream-dark self-stretch"></div>

                {{-- Interpretation --}}
                <div class="flex-1 text-sm space-y-2">
                    <p class="font-bold text-bark">Interpretasi</p>
                    @if($result['F'] == 0)
                        <p class="text-bark-muted">Tidak ditemukan leluhur bersama dari data yang tersedia. Persilangan ini aman dari risiko inbreeding.</p>
                    @elseif($result['F'] < 0.0625)
                        <p class="text-bark-muted">Tingkat kekerabatan sangat rendah. Lebih jauh dari sepupu pertama. Umumnya masih dapat diterima.</p>
                    @elseif($result['F'] < 0.125)
                        <p class="text-bark-muted">Setara tingkat kekerabatan sepupu pertama (6.25%) atau lebih dekat. Perlu dipertimbangkan lebih lanjut.</p>
                    @elseif($result['F'] < 0.25)
                        <p class="text-bark-muted">Setara atau lebih dekat dari half-sibling (12.5%). Berisiko menimbulkan kelainan genetik pada keturunan. Tidak disarankan.</p>
                    @else
                        <p class="text-bark-muted">Setara atau lebih dekat dari full-sibling / parent-offspring (25%). Sangat berisiko. Hindari persilangan ini.</p>
                    @endif

                    <div class="pt-1 text-xs text-bark-muted">
                        Data leluhur tersedia: <span class="font-semibold">{{ $result['sire_slots'] }}</span> dari jantan,
                        <span class="font-semibold">{{ $result['dam_slots'] }}</span> dari betina.
                        @if($result['sire_slots'] <= 1 || $result['dam_slots'] <= 1)
                            <span class="text-amber-600 font-semibold">Silsilah tidak lengkap — nilai F mungkin lebih rendah dari kenyataan.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reference table --}}
    <div class="be-card p-5">
        <p class="text-sm font-semibold text-bark-muted mb-3">Referensi Nilai F</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-sm">
            @foreach([
                ['label' => 'Full Sibling / Parent×Anak', 'f' => '25%', 'risk' => 'Sangat Berisiko', 'color' => 'text-red-600'],
                ['label' => 'Half-Sibling',                'f' => '12.5%','risk' => 'Berisiko',       'color' => 'text-orange-600'],
                ['label' => 'Sepupu Pertama',              'f' => '6.25%','risk' => 'Sedang',         'color' => 'text-amber-600'],
                ['label' => 'Tidak Berkerabat',            'f' => '0%',   'risk' => 'Aman',           'color' => 'text-sage'],
            ] as $ref)
                <div class="bg-cream rounded-xl p-3">
                    <p class="font-bold {{ $ref['color'] }}">{{ $ref['f'] }}</p>
                    <p class="text-bark font-semibold mt-0.5">{{ $ref['label'] }}</p>
                    <p class="text-bark-muted mt-0.5">{{ $ref['risk'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Common ancestors --}}
    @if(count($result['common_ancestors']) > 0)
        <div class="be-card">
            <div class="px-5 py-4 border-b border-cream-dark">
                <p class="font-ui font-bold text-bark text-sm">Leluhur Bersama</p>
                <p class="text-xs text-bark-muted mt-0.5">Individu yang muncul di silsilah kedua indukan</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-cream-dark text-xs text-bark-muted uppercase tracking-wide">
                            <th class="px-5 py-3 text-left font-semibold">Nama Leluhur</th>
                            <th class="px-5 py-3 text-right font-semibold">Kontribusi F</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-dark">
                        @foreach($result['common_ancestors'] as $anc)
                            <tr>
                                <td class="px-5 py-3 font-medium text-bark">{{ $anc['name'] }}</td>
                                <td class="px-5 py-3 text-right font-mono font-bold {{ $result['risk']['color'] }}">
                                    {{ $anc['percent'] }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="be-card p-5">
            <div class="flex items-center gap-2 text-sage">
                <i class="bi bi-check-circle-fill"></i>
                <p class="text-sm font-semibold">Tidak ditemukan leluhur bersama dari data yang tersedia.</p>
            </div>
        </div>
    @endif

</div>
@endif

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css" rel="stylesheet">
<style>
/* Control (input box) */
.ts-wrapper .ts-control {
    border: 1px solid #e2d9c8 !important;
    border-radius: 0.75rem !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.875rem !important;
    color: #3d2e1e !important;
    background: #fff !important;
    box-shadow: none !important;
    min-height: 2.5rem !important;
    cursor: pointer;
}
.ts-wrapper.focus .ts-control {
    border-color: #7a9e7e !important;
    box-shadow: 0 0 0 3px rgba(122,158,126,0.15) !important;
}
/* Dropdown — uses plain .ts-dropdown because dropdownParent:'body' detaches it from .ts-wrapper */
.ts-dropdown {
    background: #ffffff !important;
    border: 1px solid #e2d9c8 !important;
    border-radius: 0.875rem !important;
    box-shadow: 0 8px 32px rgba(61,46,30,0.12) !important;
    font-size: 0.875rem !important;
    margin-top: 6px !important;
    color: #3d2e1e !important;
    z-index: 9999 !important;
    overflow: hidden !important;
    padding: 0.25rem !important;
}
.ts-dropdown .option {
    padding: 0.5rem 0.625rem !important;
    color: #3d2e1e !important;
    border-radius: 0.5rem !important;
    margin-bottom: 1px !important;
    border-left: 2px solid transparent !important;
    transition: background 0.1s, border-color 0.1s !important;
    cursor: pointer;
}
.ts-dropdown .option:hover,
.ts-dropdown .option.active {
    background: #f3ede4 !important;
    border-left-color: #7a9e7e !important;
    color: #2d1f10 !important;
}
.ts-dropdown .option.selected {
    background: #e8f0e9 !important;
    border-left-color: #7a9e7e !important;
    color: #2d5a32 !important;
}
.ts-dropdown .optgroup {
    padding-top: 0.25rem !important;
}
.ts-dropdown .optgroup + .optgroup {
    border-top: 1px solid #f0ebe2 !important;
    margin-top: 0.25rem !important;
    padding-top: 0.25rem !important;
}
.ts-dropdown .optgroup-header {
    font-size: 0.65rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.08em !important;
    color: #b5a090 !important;
    padding: 0.375rem 0.625rem 0.25rem !important;
    background: transparent !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
function switchBreedingMode(mode) {
    document.getElementById('mode-input').value = mode;
    document.getElementById('section-db').classList.toggle('hidden', mode !== 'db');
    document.getElementById('section-manual').classList.toggle('hidden', mode !== 'manual');

    const tabDb     = document.getElementById('tab-db');
    const tabManual = document.getElementById('tab-manual');
    const activeClass   = ['bg-sage', 'text-white'];
    const inactiveClass = ['bg-white', 'border', 'border-cream-dark', 'text-bark', 'hover:bg-cream'];

    if (mode === 'db') {
        tabDb.classList.add(...activeClass);
        tabDb.classList.remove(...inactiveClass);
        tabManual.classList.remove(...activeClass);
        tabManual.classList.add(...inactiveClass);
    } else {
        tabManual.classList.add(...activeClass);
        tabManual.classList.remove(...inactiveClass);
        tabDb.classList.remove(...activeClass);
        tabDb.classList.add(...inactiveClass);
    }
}

function toggleGen3(side) {
    const el   = document.getElementById(side + '-gen3');
    const icon = document.getElementById(side + '-gen3-icon');
    const hidden = el.classList.toggle('hidden');
    icon.style.transform = hidden ? 'rotate(0deg)' : 'rotate(90deg)';
}

window.addEventListener('DOMContentLoaded', () => {
    const tsOptions = {
        placeholder: '— ketik untuk mencari —',
        allowEmptyOption: false,
        maxOptions: null,
        dropdownParent: 'body',
        render: {
            option: function(data, escape) {
                let top = '<span style="font-weight:700;color:#2d1f10">' + escape(data.nama || '') + '</span>';
                if (data.jenis) top += ' <span style="font-weight:600;color:#5a3e2b;opacity:.8">(' + escape(data.jenis) + ')</span>';
                if (data.kode)  top += ' <span style="color:#9c8a78;font-size:.8em;font-weight:500">' + escape(data.kode) + '</span>';
                let bottom = data.kandang
                    ? '<div style="font-style:italic;color:#b5a090;font-size:.78em;margin-top:1px">' + escape(data.kandang) + '</div>'
                    : '';
                return '<div style="line-height:1.35">' + top + bottom + '</div>';
            },
            item: function(data, escape) {
                let html = '<span style="font-weight:700">' + escape(data.nama || '') + '</span>';
                if (data.jenis) html += ' <span style="font-weight:600;opacity:.75">(' + escape(data.jenis) + ')</span>';
                if (data.kode)  html += ' <span style="color:#9c8a78;font-size:.82em"> ' + escape(data.kode) + '</span>';
                if (data.kandang) html += ' <em style="color:#b5a090;font-size:.82em"> · ' + escape(data.kandang) + '</em>';
                return '<span>' + html + '</span>';
            },
        },
    };

    if (document.getElementById('select-sire')) {
        new TomSelect('#select-sire', tsOptions);
    }
    if (document.getElementById('select-dam')) {
        new TomSelect('#select-dam', tsOptions);
    }

    @if(isset($result))
    document.getElementById('results')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    @endif
});
</script>
@endpush

@endsection
