<?php

namespace App\Services;

use App\Models\SugargliderModel;

class InbreedingCalculator
{
    private const MAX_DEPTH = 4;

    /**
     * Build a pedigree node tree from DB records (recursive, up to MAX_DEPTH).
     */
    public function buildFromDb(int $sgId, int $depth = 0): ?array
    {
        if ($depth > self::MAX_DEPTH) return null;

        $sg = SugargliderModel::find($sgId);
        if (!$sg) return null;

        return [
            'id'   => 'db_' . $sg->id,
            'name' => $sg->nama . ($sg->kode ? ' (' . $sg->kode . ')' : ''),
            'sire' => $sg->indukan_jantan ? $this->buildFromDb($sg->indukan_jantan, $depth + 1) : null,
            'dam'  => $sg->indukan_betina ? $this->buildFromDb($sg->indukan_betina, $depth + 1) : null,
        ];
    }

    /**
     * Build a pedigree node tree from manual form data.
     * Keys: {prefix}_name, {prefix}_sire_name, {prefix}_dam_name, etc.
     */
    public function buildFromManual(array $data, string $prefix): array
    {
        $name = trim($data[$prefix . '_name'] ?? '');
        return [
            'id'   => null,
            'name' => $name ?: null,
            'sire' => trim($data[$prefix . '_sire_name'] ?? '') !== ''
                        ? $this->buildFromManual($data, $prefix . '_sire')
                        : null,
            'dam'  => trim($data[$prefix . '_dam_name'] ?? '') !== ''
                        ? $this->buildFromManual($data, $prefix . '_dam')
                        : null,
        ];
    }

    /**
     * Calculate inbreeding coefficient F using Wright's Path Coefficient method.
     * Returns F, percent, risk level, and common ancestor breakdown.
     */
    public function calculate(array $sire, array $dam): array
    {
        $sireAncestors = $this->getAncestors($sire, 0);
        $damAncestors  = $this->getAncestors($dam, 0);

        $commonKeys = array_intersect_key($sireAncestors, $damAncestors);

        $F = 0.0;
        $commonAncestors = [];

        foreach ($commonKeys as $key => $_) {
            $sireDepths = $sireAncestors[$key]['depths'];
            $damDepths  = $damAncestors[$key]['depths'];
            $contribution = 0.0;

            // Wright's formula: Σ (1/2)^(Ls + Ld + 1) for each path combination
            foreach ($sireDepths as $ls) {
                foreach ($damDepths as $ld) {
                    $contribution += pow(0.5, $ls + $ld + 1);
                }
            }

            $F += $contribution;
            $commonAncestors[] = [
                'name'         => $sireAncestors[$key]['name'],
                'contribution' => $contribution,
                'percent'      => round($contribution * 100, 4),
            ];
        }

        usort($commonAncestors, fn($a, $b) => $b['contribution'] <=> $a['contribution']);

        return [
            'F'                => $F,
            'percent'          => round($F * 100, 4),
            'risk'             => $this->riskLevel($F),
            'common_ancestors' => $commonAncestors,
            'sire_slots'       => $this->countFilledSlots($sire),
            'dam_slots'        => $this->countFilledSlots($dam),
        ];
    }

    /**
     * Collect all ancestors of a node with their distances (depths).
     * Same individual can appear via multiple paths — all depths are collected.
     * Depth 0 = the individual itself; depth 1 = parents; etc.
     */
    private function getAncestors(array $node, int $depth): array
    {
        if ($depth > self::MAX_DEPTH || empty($node['name'])) return [];

        // DB nodes use db_{id} as key; manual nodes use name hash for identity matching
        $key = $node['id'] ?? ('m_' . md5(strtolower(trim($node['name']))));

        $result = [
            $key => ['name' => $node['name'], 'depths' => [$depth]],
        ];

        foreach (['sire', 'dam'] as $side) {
            if (!empty($node[$side])) {
                $sub = $this->getAncestors($node[$side], $depth + 1);
                foreach ($sub as $k => $data) {
                    if (isset($result[$k])) {
                        $result[$k]['depths'] = array_merge($result[$k]['depths'], $data['depths']);
                    } else {
                        $result[$k] = $data;
                    }
                }
            }
        }

        return $result;
    }

    private function countFilledSlots(array $node): int
    {
        if (empty($node['name'])) return 0;
        return 1
            + (!empty($node['sire']) ? $this->countFilledSlots($node['sire']) : 0)
            + (!empty($node['dam'])  ? $this->countFilledSlots($node['dam'])  : 0);
    }

    private function riskLevel(float $F): array
    {
        if ($F == 0)     return ['label' => 'Aman',           'color' => 'text-sage',      'bg' => 'bg-sage/10 border-sage/30 text-sage-dark'];
        if ($F < 0.0625) return ['label' => 'Sangat Rendah',  'color' => 'text-blue-600',  'bg' => 'bg-blue-50 border-blue-200 text-blue-700'];
        if ($F < 0.125)  return ['label' => 'Sedang',         'color' => 'text-amber-600', 'bg' => 'bg-amber-50 border-amber-200 text-amber-700'];
        if ($F < 0.25)   return ['label' => 'Berisiko',       'color' => 'text-orange-600','bg' => 'bg-orange-50 border-orange-200 text-orange-700'];
        return            ['label' => 'Sangat Berisiko', 'color' => 'text-red-600',   'bg' => 'bg-red-50 border-red-200 text-red-700'];
    }
}
