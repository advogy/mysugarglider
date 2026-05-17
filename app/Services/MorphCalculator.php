<?php

namespace App\Services;

/**
 * Morph genetics calculator for Sugar Gliders using Mendelian inheritance.
 *
 * Input per-morph values (sire_gene / dam_gene arrays):
 *   'none' = tidak membawa gen ini
 *   'het'  = pembawa (Pp) — hanya berlaku untuk morph resesif
 *   'full' = mengekspresikan morph ini (pp untuk resesif; Mm untuk dominan)
 *
 * Loci diasumsikan independen (tidak terpaut kromosom),
 * sehingga probabilitas dikombinasikan via cartesian product Punnett.
 */
class MorphCalculator
{
    public static function morphList(): array
    {
        return [
            // Resesif
            'albino'    => ['label' => 'Albino',             'type' => 'recessive', 'gene' => 'alb', 'color' => 'bg-yellow-50 text-yellow-700'],
            'leucistic' => ['label' => 'Leucistic',          'type' => 'recessive', 'gene' => 'leu', 'color' => 'bg-slate-100 text-slate-600'],
            'platinum'  => ['label' => 'Platinum',           'type' => 'recessive', 'gene' => 'plt', 'color' => 'bg-blue-50 text-blue-700'],
            'cinnamon'  => ['label' => 'Cinnamon/Creamino',  'type' => 'recessive', 'gene' => 'cin', 'color' => 'bg-orange-50 text-orange-700'],
            'caramel'   => ['label' => 'Caramel',            'type' => 'recessive', 'gene' => 'car', 'color' => 'bg-amber-50 text-amber-800'],
            'ringtail'  => ['label' => 'Ringtail',           'type' => 'recessive', 'gene' => 'rt',  'color' => 'bg-lime-50 text-lime-700'],
            // Dominan
            'mosaic'    => ['label' => 'Mosaic',             'type' => 'dominant',  'gene' => 'mos', 'color' => 'bg-purple-50 text-purple-700'],
            'whiteface' => ['label' => 'White Face',         'type' => 'dominant',  'gene' => 'wf',  'color' => 'bg-indigo-50 text-indigo-700'],
            'whitetip'  => ['label' => 'White Tip',          'type' => 'dominant',  'gene' => 'wt',  'color' => 'bg-teal-50 text-teal-700'],
        ];
    }

    /**
     * Combo morphs — named combinations that result from multiple genes expressed simultaneously.
     * Sorted most-specific first so the best match is returned when multiple combos qualify.
     */
    public static function comboMorphs(): array
    {
        // Urutan: paling spesifik (banyak gene) di atas agar dideteksi duluan
        return [
            // ── Quadruple combos ─────────────────────────────────────────
            ['label' => 'Rubi TPM',                'desc' => 'Albino + Cinnamon + Platinum + Mosaic (sangat langka)', 'requires' => ['albino', 'cinnamon', 'platinum', 'mosaic'], 'color' => 'bg-rose-200 text-rose-900 border-rose-400'],

            // ── Triple combos ────────────────────────────────────────────
            ['label' => 'PlatMos Albino',          'desc' => 'Platinum + Mosaic + Albino',          'requires' => ['platinum', 'mosaic', 'albino'],        'color' => 'bg-fuchsia-100 text-fuchsia-900 border-fuchsia-300'],
            ['label' => 'Creamos Albino / Rubi Mosaic', 'desc' => 'Cinnamon + Mosaic + Albino',     'requires' => ['cinnamon', 'mosaic', 'albino'],        'color' => 'bg-rose-100 text-rose-900 border-rose-300'],
            ['label' => 'PlatMos Cinnamon',        'desc' => 'Platinum + Mosaic + Cinnamon',        'requires' => ['platinum', 'mosaic', 'cinnamon'],      'color' => 'bg-orange-200 text-orange-900 border-orange-400'],
            ['label' => 'Mocisstic Albino',        'desc' => 'Mosaic + Leucistic + Albino',         'requires' => ['mosaic', 'leucistic', 'albino'],       'color' => 'bg-gray-200 text-gray-900 border-gray-400'],
            ['label' => 'Rubi Platinum',           'desc' => 'Albino + Cinnamon + Platinum',        'requires' => ['albino', 'cinnamon', 'platinum'],      'color' => 'bg-pink-100 text-pink-900 border-pink-300'],
            ['label' => 'Rubi Leucistic',          'desc' => 'Albino + Cinnamon + Leucistic',       'requires' => ['albino', 'cinnamon', 'leucistic'],     'color' => 'bg-red-50 text-red-900 border-red-200'],

            // ── Double combos ─────────────────────────────────────────────
            ['label' => 'PlatMos / TPM',           'desc' => 'Platinum + Mosaic. PlatMos dan TPM adalah kombinasi gen yang sama — perbedaan keduanya hanya pada standar penampilan, bukan genetika.', 'requires' => ['platinum', 'mosaic'], 'color' => 'bg-violet-100 text-violet-900 border-violet-300'],
            ['label' => 'Mocisstic',               'desc' => 'Mosaic + Leucistic',                  'requires' => ['mosaic', 'leucistic'],             'color' => 'bg-slate-200 text-slate-900 border-slate-400'],
            ['label' => 'Creamos / Creamino Mosaic','desc' => 'Cinnamon/Creamino + Mosaic',          'requires' => ['cinnamon', 'mosaic'],              'color' => 'bg-orange-100 text-orange-900 border-orange-300'],
            ['label' => 'Rubi',                    'desc' => 'Albino + Cinnamon — mata ruby/merah',  'requires' => ['albino', 'cinnamon'],              'color' => 'bg-red-100 text-red-900 border-red-300'],
            ['label' => 'Mosaic Albino',           'desc' => 'Mosaic + Albino',                      'requires' => ['mosaic', 'albino'],                'color' => 'bg-yellow-100 text-yellow-900 border-yellow-300'],
            ['label' => 'Platinum Albino',         'desc' => 'Platinum + Albino',                    'requires' => ['platinum', 'albino'],              'color' => 'bg-sky-100 text-sky-900 border-sky-300'],
            ['label' => 'Silber / Plat Cinnamon',  'desc' => 'Platinum + Cinnamon',                  'requires' => ['platinum', 'cinnamon'],            'color' => 'bg-cyan-100 text-cyan-900 border-cyan-300'],
            ['label' => 'White Face Mosaic',       'desc' => 'White Face + Mosaic',                  'requires' => ['whiteface', 'mosaic'],             'color' => 'bg-indigo-100 text-indigo-900 border-indigo-300'],
            ['label' => 'White Face Creamino',     'desc' => 'White Face + Cinnamon/Creamino',       'requires' => ['whiteface', 'cinnamon'],            'color' => 'bg-indigo-50 text-indigo-800 border-indigo-200'],
            ['label' => 'White Face Platinum',     'desc' => 'White Face + Platinum',                'requires' => ['whiteface', 'platinum'],            'color' => 'bg-blue-100 text-blue-900 border-blue-300'],
            ['label' => 'White Face Albino',       'desc' => 'White Face + Albino',                  'requires' => ['whiteface', 'albino'],              'color' => 'bg-yellow-50 text-yellow-900 border-yellow-300'],
            ['label' => 'White Tip Mosaic',        'desc' => 'White Tip + Mosaic',                   'requires' => ['whitetip', 'mosaic'],              'color' => 'bg-teal-100 text-teal-900 border-teal-300'],
            ['label' => 'Caramel Mosaic',          'desc' => 'Caramel + Mosaic',                     'requires' => ['caramel', 'mosaic'],               'color' => 'bg-amber-100 text-amber-900 border-amber-300'],
            ['label' => 'Caramel Albino',          'desc' => 'Caramel + Albino',                     'requires' => ['caramel', 'albino'],               'color' => 'bg-amber-200 text-amber-900 border-amber-400'],
            ['label' => 'Caramel Platinum',        'desc' => 'Caramel + Platinum',                   'requires' => ['caramel', 'platinum'],             'color' => 'bg-yellow-100 text-yellow-800 border-yellow-300'],
            ['label' => 'Leucistic Albino',        'desc' => 'Leucistic + Albino (sangat langka)',    'requires' => ['leucistic', 'albino'],             'color' => 'bg-gray-100 text-gray-800 border-gray-300'],
            ['label' => 'Cinnamon Leucistic',      'desc' => 'Cinnamon/Creamino + Leucistic',        'requires' => ['cinnamon', 'leucistic'],           'color' => 'bg-orange-50 text-orange-800 border-orange-200'],
            ['label' => 'Platinum Leucistic',      'desc' => 'Platinum + Leucistic (sangat langka)',  'requires' => ['platinum', 'leucistic'],           'color' => 'bg-blue-50 text-blue-800 border-blue-200'],
            ['label' => 'Ringtail Mosaic',         'desc' => 'Ringtail + Mosaic',                    'requires' => ['ringtail', 'mosaic'],              'color' => 'bg-lime-100 text-lime-900 border-lime-300'],
            ['label' => 'Ringtail Albino',         'desc' => 'Ringtail + Albino',                    'requires' => ['ringtail', 'albino'],              'color' => 'bg-lime-50 text-lime-800 border-lime-200'],
            ['label' => 'Ringtail Platinum',       'desc' => 'Ringtail + Platinum',                  'requires' => ['ringtail', 'platinum'],            'color' => 'bg-lime-50 text-lime-800 border-lime-200'],
        ];
    }

    /**
     * Hitung probabilitas morph keturunan.
     *
     * @param  array $sireGenes  ['albino' => 'none'|'het'|'full', 'platinum' => 'het', ...]
     * @param  array $damGenes   sama seperti sireGenes
     */
    public function calculate(array $sireGenes, array $damGenes): array
    {
        $allMorphs   = static::morphList();
        $activeGenes = $this->collectGenes($sireGenes, $damGenes, $allMorphs);

        if (empty($activeGenes)) {
            return [
                'outcomes'     => [['morphs' => ['Classic Grey'], 'percent' => 100.0, 'het' => []]],
                'sire_display' => $this->buildDisplay($sireGenes, $allMorphs),
                'dam_display'  => $this->buildDisplay($damGenes, $allMorphs),
            ];
        }

        $combined = [['genes' => [], 'prob' => 1.0]];
        foreach ($activeGenes as $geneKey => $locus) {
            $locusOutcomes = $this->locusOutcomes($locus['sire'], $locus['dam'], $locus['type'], $geneKey, $allMorphs);
            $combined      = $this->cartesianCombine($combined, $locusOutcomes);
        }

        $outcomes = $this->resolveOutcomes($combined, $allMorphs);
        $outcomes = $this->mergeOutcomes($outcomes);
        $outcomes = array_map(fn($o) => array_merge($o, [
            'combo' => $this->detectCombo($o['morph_keys']),
        ]), $outcomes);
        usort($outcomes, fn($a, $b) => $b['percent'] <=> $a['percent']);

        return [
            'outcomes'     => $outcomes,
            'sire_display' => $this->buildDisplay($sireGenes, $allMorphs),
            'dam_display'  => $this->buildDisplay($damGenes, $allMorphs),
        ];
    }

    /**
     * Tentukan loci aktif dan genotipe masing-masing indukan.
     * Nilai 'none' pada kedua indukan = locus diabaikan.
     */
    private function collectGenes(array $sireGenes, array $damGenes, array $allMorphs): array
    {
        $genes = [];

        foreach ($allMorphs as $key => $morph) {
            $geneKey  = $morph['gene'];
            $sireVal  = $sireGenes[$key] ?? 'none';
            $damVal   = $damGenes[$key]  ?? 'none';

            if ($sireVal === 'none' && $damVal === 'none') continue;

            if ($morph['type'] === 'recessive') {
                // none='PP', het='Pp', full='pp'
                $sireGeno = match($sireVal) { 'full' => 'pp', 'het' => 'Pp', default => 'PP' };
                $damGeno  = match($damVal)  { 'full' => 'pp', 'het' => 'Pp', default => 'PP' };

                // Kedua PP berarti tidak ada gen yang diturunkan — skip
                if ($sireGeno === 'PP' && $damGeno === 'PP') continue;

                $genes[$geneKey] = ['sire' => $sireGeno, 'dam' => $damGeno, 'type' => 'recessive', 'morph' => $key];

            } elseif ($morph['type'] === 'dominant') {
                // none='mm', full='Mm' (het tidak berlaku untuk dominan)
                $sireGeno = ($sireVal === 'full') ? 'Mm' : 'mm';
                $damGeno  = ($damVal  === 'full') ? 'Mm' : 'mm';

                $genes[$geneKey] = ['sire' => $sireGeno, 'dam' => $damGeno, 'type' => 'dominant', 'morph' => $key];
            }
        }

        return $genes;
    }

    /**
     * Hitung semua kemungkinan genotipe dari satu locus (Punnett 2×2).
     */
    private function locusOutcomes(string $sireGeno, string $damGeno, string $type, string $geneKey, array $allMorphs): array
    {
        $sireGametes = [substr($sireGeno, 0, 1), substr($sireGeno, 1, 1)];
        $damGametes  = [substr($damGeno,  0, 1), substr($damGeno,  1, 1)];

        $counts = [];
        foreach ($sireGametes as $sg) {
            foreach ($damGametes as $dg) {
                $geno = $this->sortGeno($sg . $dg);
                $counts[$geno] = ($counts[$geno] ?? 0) + 1;
            }
        }

        $total    = array_sum($counts);
        $outcomes = [];
        $morphKey = $this->morphKeyFromGene($geneKey, $allMorphs);

        foreach ($counts as $geno => $count) {
            [$expresses, $isHet] = $this->interpGeno($geno, $type);
            $outcomes[] = [
                'geno'      => $geno,
                'prob'      => $count / $total,
                'expresses' => $expresses,
                'het'       => $isHet,
                'gene'      => $geneKey,
                'morph'     => $morphKey,
            ];
        }

        return $outcomes;
    }

    private function sortGeno(string $geno): string
    {
        $alleles = [substr($geno, 0, 1), substr($geno, 1, 1)];
        usort($alleles, fn($a, $b) => ctype_upper($a) ? -1 : 1);
        return implode('', $alleles);
    }

    /** Returns [bool $expresses, bool $isHet] */
    private function interpGeno(string $geno, string $type): array
    {
        if ($type === 'recessive') {
            $expresses = ($geno === 'pp');
            $isHet     = ($geno === 'Pp');
            return [$expresses, $isHet];
        }
        // dominant: mm=tidak, Mm=ekspres
        return [$geno !== 'mm', false];
    }

    private function morphKeyFromGene(string $gene, array $allMorphs): ?string
    {
        foreach ($allMorphs as $key => $morph) {
            if (($morph['gene'] ?? null) === $gene) return $key;
        }
        return null;
    }

    private function cartesianCombine(array $combined, array $locusOutcomes): array
    {
        $result = [];
        foreach ($combined as $existing) {
            foreach ($locusOutcomes as $locus) {
                $newGenes = $existing['genes'];
                $newGenes[$locus['gene']] = $locus;
                $result[] = ['genes' => $newGenes, 'prob' => $existing['prob'] * $locus['prob']];
            }
        }
        return $result;
    }

    private function resolveOutcomes(array $combined, array $allMorphs): array
    {
        $outcomes = [];
        foreach ($combined as $item) {
            $expressed   = [];
            $expressedKeys = [];
            $het         = [];

            foreach ($item['genes'] as $geneKey => $locus) {
                $morphKey = $locus['morph'];
                if (!$morphKey) continue;
                if ($locus['expresses']) {
                    $expressed[]     = $allMorphs[$morphKey]['label'] ?? $morphKey;
                    $expressedKeys[] = $morphKey;
                } elseif ($locus['het']) {
                    $het[] = $allMorphs[$morphKey]['label'] ?? $morphKey;
                }
            }

            if (empty($expressed)) {
                $expressed[]     = 'Classic Grey';
                $expressedKeys[] = 'classic';
            }

            $outcomes[] = [
                'morphs'     => $expressed,
                'morph_keys' => $expressedKeys,
                'het'        => $het,
                'percent'    => round($item['prob'] * 100, 4),
            ];
        }
        return $outcomes;
    }

    private function mergeOutcomes(array $outcomes): array
    {
        $merged = [];
        foreach ($outcomes as $o) {
            sort($o['morphs']);
            sort($o['morph_keys']);
            sort($o['het']);
            $key = implode('|', $o['morphs']) . '::' . implode('|', $o['het']);
            if (isset($merged[$key])) {
                $merged[$key]['percent'] = round($merged[$key]['percent'] + $o['percent'], 4);
            } else {
                $merged[$key] = $o;
            }
        }
        return array_values($merged);
    }

    /**
     * Return the most specific named combo morph that matches the expressed keys,
     * or null if no combo applies.
     */
    private function detectCombo(array $expressedKeys): ?array
    {
        foreach (static::comboMorphs() as $combo) {
            if (empty(array_diff($combo['requires'], $expressedKeys))) {
                return $combo;
            }
        }
        return null;
    }

    public function buildDisplay(array $genes, array $allMorphs): string
    {
        $expressed = [];
        $hets      = [];
        foreach ($genes as $key => $val) {
            if ($val === 'none' || !isset($allMorphs[$key])) continue;
            $label = $allMorphs[$key]['label'];
            if ($val === 'full') $expressed[] = $label;
            if ($val === 'het')  $hets[]      = 'het ' . $label;
        }
        $parts = array_merge($expressed, $hets);
        return empty($parts) ? 'Classic Grey' : implode(' + ', $parts);
    }
}
