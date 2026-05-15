<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\CollectionStatus;
use App\Models\SugargliderModel;
use App\Models\ProfileModel;
use App\Models\CollectionModel;

class PedigreeController extends Controller
{
    function index()
    {
        return redirect()->route('collections');
    }

    function show($id)
    {
        $data = [
            'collection' =>
            CollectionModel::leftjoin('shelters', 'collections.shelter_id', '=', 'shelters.id')
                ->leftjoin('sugargliders', 'collections.sugarglider_id', '=', 'sugargliders.id')
                ->select(
                    'sugargliders.id as sgId',
                    'sugargliders.kode as sgKode',
                    'sugargliders.nama as sgNama',
                    'sugargliders.kelamin as sgKelamin',
                    'sugargliders.tgl_lahir as sgTglLahir',
                    'sugargliders.warna as sgWarna',
                    'sugargliders.jenis as sgJenis',
                    'sugargliders.genetika as sgGenetika',
                    'sugargliders.fenotype as sgFenotype',
                    'sugargliders.indukan_jantan as sgIndukanJantan',
                    'sugargliders.indukan_betina as sgIndukanBetina',
                    'sugargliders.gambar as sgGambar',
                    'sugargliders.keterangan as sgKeterangan',
                    'shelters.id as stId',
                    'shelters.nama as stNama',
                    'collections.status as clStatus'
                )
                ->where('sugargliders.id', '=', $id)
                ->first(),

            'silsilah' => $this->buildSilsilahQuery($id),
        ];

        return view('pedigree.v_pedigree_detail', $data);
    }

    function backend_pedigree_index()
    {
        $profile = ProfileModel::where('user_id', Auth::id())->first();

        if (is_null($profile)) {
            return view('profiles.v_profile_no');
        } else {

            $data = [
                'collections' =>
                CollectionModel::join('sugargliders as sg', 'collections.sugarglider_id', '=', 'sg.id')
                    ->join('shelters as st', 'collections.shelter_id', '=', 'st.id')
                    ->select(
                        'collections.id as id',
                        'collections.sugarglider_id as sgId',
                        'collections.shelter_id as stId',
                        'collections.status as status',
                        'sg.nama as sgNama',
                        'sg.kode as sgKode',
                        'sg.jenis as sgJenis',
                        'sg.gambar as sgGambar',
                        'st.nama as stNama',
                    )
                    ->whereIn('collections.status', [CollectionStatus::PUBLIK->value, CollectionStatus::ADOPSI->value])
                    ->paginate(10)
            ];

            return view('pedigree.v_backend_pedigree_index', $data);
        }
    }

    function backend_show($id)
    {
        $data = [
            'sugarglider' => SugargliderModel::find($id),
            'collection' => CollectionModel::with('shelter')->where('sugarglider_id', $id)->first(),
            'silsilah' => $this->buildSilsilahQuery($id),
        ];

        return view('pedigree.v_backend_pedigree_detail', $data);
    }

    private function buildSilsilahQuery(int $id): ?object
    {
        return SugargliderModel::leftjoin('sugargliders as m', 'sugargliders.indukan_jantan', '=', 'm.id')
            ->leftjoin('sugargliders as f', 'sugargliders.indukan_betina', '=', 'f.id')
            ->leftjoin('sugargliders as mm', 'm.indukan_jantan', '=', 'mm.id')
            ->leftjoin('sugargliders as mf', 'm.indukan_betina', '=', 'mf.id')
            ->leftjoin('sugargliders as fm', 'f.indukan_jantan', '=', 'fm.id')
            ->leftjoin('sugargliders as ff', 'f.indukan_betina', '=', 'ff.id')
            ->leftjoin('sugargliders as mmm', 'mm.indukan_jantan', '=', 'mmm.id')
            ->leftjoin('sugargliders as mmf', 'mm.indukan_betina', '=', 'mmf.id')
            ->leftjoin('sugargliders as mfm', 'mf.indukan_jantan', '=', 'mfm.id')
            ->leftjoin('sugargliders as mff', 'mf.indukan_betina', '=', 'mff.id')
            ->leftjoin('sugargliders as fmm', 'fm.indukan_jantan', '=', 'fmm.id')
            ->leftjoin('sugargliders as fmf', 'fm.indukan_betina', '=', 'fmf.id')
            ->leftjoin('sugargliders as ffm', 'ff.indukan_jantan', '=', 'ffm.id')
            ->leftjoin('sugargliders as fff', 'ff.indukan_betina', '=', 'fff.id')
            ->leftjoin('sugargliders as mmmm', 'mmm.indukan_jantan', '=', 'mmmm.id')
            ->leftjoin('sugargliders as mmmf', 'mmm.indukan_betina', '=', 'mmmf.id')
            ->leftjoin('sugargliders as mmfm', 'mmf.indukan_jantan', '=', 'mmfm.id')
            ->leftjoin('sugargliders as mmff', 'mmf.indukan_betina', '=', 'mmff.id')
            ->leftjoin('sugargliders as mfmm', 'mfm.indukan_jantan', '=', 'mfmm.id')
            ->leftjoin('sugargliders as mfmf', 'mfm.indukan_betina', '=', 'mfmf.id')
            ->leftjoin('sugargliders as mffm', 'mff.indukan_jantan', '=', 'mffm.id')
            ->leftjoin('sugargliders as mfff', 'mff.indukan_betina', '=', 'mfff.id')
            ->leftjoin('sugargliders as fmmm', 'fmm.indukan_jantan', '=', 'fmmm.id')
            ->leftjoin('sugargliders as fmmf', 'fmm.indukan_betina', '=', 'fmmf.id')
            ->leftjoin('sugargliders as fmfm', 'fmf.indukan_jantan', '=', 'fmfm.id')
            ->leftjoin('sugargliders as fmff', 'fmf.indukan_betina', '=', 'fmff.id')
            ->leftjoin('sugargliders as ffmm', 'ffm.indukan_jantan', '=', 'ffmm.id')
            ->leftjoin('sugargliders as ffmf', 'ffm.indukan_betina', '=', 'ffmf.id')
            ->leftjoin('sugargliders as fffm', 'fff.indukan_jantan', '=', 'fffm.id')
            ->leftjoin('sugargliders as ffff', 'fff.indukan_betina', '=', 'ffff.id')
            ->select(
                'sugargliders.nama as nama',       'sugargliders.id as id',         'sugargliders.jenis as jenis',
                'm.nama as mNama',                 'm.id as mId',                   'm.jenis as mJenis',
                'f.nama as fNama',                 'f.id as fId',                   'f.jenis as fJenis',
                'mm.nama as mmNama',               'mm.id as mmId',                 'mm.jenis as mmJenis',
                'mf.nama as mfNama',               'mf.id as mfId',                 'mf.jenis as mfJenis',
                'fm.nama as fmNama',               'fm.id as fmId',                 'fm.jenis as fmJenis',
                'ff.nama as ffNama',               'ff.id as ffId',                 'ff.jenis as ffJenis',
                'mmm.nama as mmmNama',             'mmm.id as mmmId',               'mmm.jenis as mmmJenis',
                'mmf.nama as mmfNama',             'mmf.id as mmfId',               'mmf.jenis as mmfJenis',
                'mfm.nama as mfmNama',             'mfm.id as mfmId',               'mfm.jenis as mfmJenis',
                'mff.nama as mffNama',             'mff.id as mffId',               'mff.jenis as mffJenis',
                'fmm.nama as fmmNama',             'fmm.id as fmmId',               'fmm.jenis as fmmJenis',
                'fmf.nama as fmfNama',             'fmf.id as fmfId',               'fmf.jenis as fmfJenis',
                'ffm.nama as ffmNama',             'ffm.id as ffmId',               'ffm.jenis as ffmJenis',
                'fff.nama as fffNama',             'fff.id as fffId',               'fff.jenis as fffJenis',
                'mmmm.nama as mmmmNama',           'mmmm.id as mmmmId',             'mmmm.jenis as mmmmJenis',
                'mmmf.nama as mmmfNama',           'mmmf.id as mmmfId',             'mmmf.jenis as mmmfJenis',
                'mmfm.nama as mmfmNama',           'mmfm.id as mmfmId',             'mmfm.jenis as mmfmJenis',
                'mmff.nama as mmffNama',           'mmff.id as mmffId',             'mmff.jenis as mmffJenis',
                'mfmm.nama as mfmmNama',           'mfmm.id as mfmmId',             'mfmm.jenis as mfmmJenis',
                'mfmf.nama as mfmfNama',           'mfmf.id as mfmfId',             'mfmf.jenis as mfmfJenis',
                'mffm.nama as mffmNama',           'mffm.id as mffmId',             'mffm.jenis as mffmJenis',
                'mfff.nama as mfffNama',           'mfff.id as mfffId',             'mfff.jenis as mfffJenis',
                'fmmm.nama as fmmmNama',           'fmmm.id as fmmmId',             'fmmm.jenis as fmmmJenis',
                'fmmf.nama as fmmfNama',           'fmmf.id as fmmfId',             'fmmf.jenis as fmmfJenis',
                'fmfm.nama as fmfmNama',           'fmfm.id as fmfmId',             'fmfm.jenis as fmfmJenis',
                'fmff.nama as fmffNama',           'fmff.id as fmffId',             'fmff.jenis as fmffJenis',
                'ffmm.nama as ffmmNama',           'ffmm.id as ffmmId',             'ffmm.jenis as ffmmJenis',
                'ffmf.nama as ffmfNama',           'ffmf.id as ffmfId',             'ffmf.jenis as ffmfJenis',
                'fffm.nama as fffmNama',           'fffm.id as fffmId',             'fffm.jenis as fffmJenis',
                'ffff.nama as ffffNama',           'ffff.id as ffffId',             'ffff.jenis as ffffJenis',
            )
            ->where('sugargliders.id', $id)
            ->first();
    }
}
