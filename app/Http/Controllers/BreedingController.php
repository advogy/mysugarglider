<?php

namespace App\Http\Controllers;

use App\Models\SugargliderModel;
use App\Services\InbreedingCalculator;
use App\Services\MorphCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BreedingController extends Controller
{
    public function inbreeding()
    {
        [$males, $females, $malesOthers, $femalesOthers] = $this->loadSgLists();

        return view('breeding.v_inbreeding', compact('males', 'females', 'malesOthers', 'femalesOthers'));
    }

    public function calculateInbreeding(Request $request)
    {
        $calc = new InbreedingCalculator();

        if ($request->mode === 'db') {
            $request->validate([
                'sire_id' => 'required|exists:sugargliders,id',
                'dam_id'  => 'required|exists:sugargliders,id',
            ], [
                'sire_id.required' => 'Pilih calon indukan jantan.',
                'dam_id.required'  => 'Pilih calon indukan betina.',
                'sire_id.exists'   => 'Sugar Glider jantan tidak ditemukan.',
                'dam_id.exists'    => 'Sugar Glider betina tidak ditemukan.',
            ]);

            if ($request->sire_id === $request->dam_id) {
                return back()->withInput()->with('error', 'Indukan jantan dan betina tidak boleh sama.');
            }

            $sireNode = $calc->buildFromDb((int) $request->sire_id);
            $damNode  = $calc->buildFromDb((int) $request->dam_id);
        } else {
            $request->validate([
                'sire_name' => 'required|string|max:100',
                'dam_name'  => 'required|string|max:100',
            ], [
                'sire_name.required' => 'Masukkan nama calon indukan jantan.',
                'dam_name.required'  => 'Masukkan nama calon indukan betina.',
            ]);

            if (strtolower(trim($request->sire_name)) === strtolower(trim($request->dam_name))) {
                return back()->withInput()->with('error', 'Nama indukan jantan dan betina tidak boleh sama.');
            }

            $sireNode = $calc->buildFromManual($request->all(), 'sire');
            $damNode  = $calc->buildFromManual($request->all(), 'dam');
        }

        $result = $calc->calculate($sireNode, $damNode);
        $result['sire_name'] = $sireNode['name'];
        $result['dam_name']  = $damNode['name'];

        [$males, $females, $malesOthers, $femalesOthers] = $this->loadSgLists();

        return view('breeding.v_inbreeding', compact('males', 'females', 'malesOthers', 'femalesOthers', 'result', 'request'));
    }

    private function loadSgLists(): array
    {
        $userId = Auth::id();
        $query  = fn(string $kelamin, bool $own) => SugargliderModel::with('collections.shelter')
            ->where('kelamin', $kelamin)
            ->where('user_id', $own ? '=' : '!=', $userId)
            ->orderBy('nama')
            ->get();

        return [
            $query('1', true),
            $query('0', true),
            $query('1', false),
            $query('0', false),
        ];
    }

    public function morph()
    {
        $morphs = MorphCalculator::morphList();
        return view('breeding.v_morph', compact('morphs'));
    }

    public function calculateMorph(Request $request)
    {
        $allMorphs     = MorphCalculator::morphList();
        $sireExpressed = $request->input('sire_expressed', '');
        $sireHet       = $request->input('sire_het', []);
        $damExpressed  = $request->input('dam_expressed', '');
        $damHet        = $request->input('dam_het', []);

        $sireGenes = [];
        $damGenes  = [];
        foreach ($allMorphs as $key => $morph) {
            $sireGenes[$key] = ($sireExpressed === $key) ? 'full'
                : (in_array($key, $sireHet) && $morph['type'] === 'recessive' ? 'het' : 'none');
            $damGenes[$key]  = ($damExpressed === $key) ? 'full'
                : (in_array($key, $damHet)  && $morph['type'] === 'recessive' ? 'het' : 'none');
        }

        $calc   = new MorphCalculator();
        $result = $calc->calculate($sireGenes, $damGenes);

        $morphs = MorphCalculator::morphList();
        return view('breeding.v_morph', compact(
            'morphs', 'result', 'sireGenes', 'damGenes',
            'sireExpressed', 'sireHet', 'damExpressed', 'damHet'
        ));
    }
}
