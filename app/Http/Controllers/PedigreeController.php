<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                    ->where('sg.user_id', Auth::id())
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
        return SugargliderModel::silsilah($id);
    }
}
