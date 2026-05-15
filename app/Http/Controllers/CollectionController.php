<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CollectionRequest;
use App\Enums\CollectionStatus;
use App\Enums\AdoptionStatus;
use App\Models\ProfileModel;
use App\Models\CollectionModel;
use App\Models\ShelterModel;
use App\Models\SugargliderModel;
use App\Models\AdoptionModel;

class CollectionController extends Controller
{
    function index(Request $request)
    {
        $search = trim($request->get('q', ''));

        $query = CollectionModel::join('sugargliders as sg', 'collections.sugarglider_id', '=', 'sg.id')
            ->join('shelters as st', 'collections.shelter_id', '=', 'st.id')
            ->select(
                'collections.id as id',
                'collections.sugarglider_id as sgId',
                'collections.shelter_id as stId',
                'collections.status as sgStatus',
                'sg.nama as sgNama',
                'sg.kode as sgKode',
                'sg.kelamin as sgKelamin',
                'sg.jenis as sgJenis',
                'sg.gambar as sgGambar',
                'st.nama as stNama',
            )
            ->whereIn('collections.status', [CollectionStatus::PUBLIK->value, CollectionStatus::ADOPSI->value]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('sg.nama', 'like', "%{$search}%")
                  ->orWhere('sg.jenis', 'like', "%{$search}%")
                  ->orWhere('st.nama', 'like', "%{$search}%");
            });
        }

        $data = [
            'collections' => $query->paginate(20)->appends($request->query()),
            'search'      => $search,
        ];

        return view('collections.v_collection_index', $data);
    }

    function backend_collection_index()
    {
        $profile = ProfileModel::where('user_id', Auth::id())->first();
        $sugarglider = SugargliderModel::where('user_id', Auth::id())->first();

        if (is_null($profile)) {
            return view('profiles.v_profile_no');
        } elseif (is_null($sugarglider)) {
            return view('sugargliders.v_backend_sugarglider_no');
        } else {
            $data = [
                'collections' =>
                CollectionModel::join('sugargliders as sg', 'collections.sugarglider_id', '=', 'sg.id')
                    ->join('shelters as st', 'collections.shelter_id', '=', 'st.id')
                    ->select(
                        'collections.id as id',
                        'collections.status as status',
                        'sg.nama as sgNama',
                        'st.nama as stNama',
                    )
                    ->whereIn('collections.status', [CollectionStatus::PUBLIK->value, CollectionStatus::ADOPSI->value])
                    ->where('collections.user_id', Auth::id())
                    ->paginate(10)
            ];

            return view('collections.v_backend_collection_index', $data);
        }
    }

    function create()
    {
        $sugarglidercollections = CollectionModel::pluck('sugarglider_id')->all();

        $data = [
            'shelters' => ShelterModel::where('status', 1)->where('user_id', Auth::id())->orderBy('nama', 'asc')->get(),
            'sugargliders' => SugargliderModel::whereNotIn('id', $sugarglidercollections)->where('user_id', Auth::id())->orderBy('nama', 'asc')->get(),
        ];
        return view('collections.v_backend_collection_create', $data);
    }

    function store(CollectionRequest $request)
    {
        CollectionModel::create([
            'shelter_id'        => $request->shelter_id,
            'sugarglider_id'    => $request->sugarglider_id,
            'status'            => $request->status,
            'user_id'           => Auth::id(),
        ]);

        return redirect()->route('collection.index')->with('pesan', 'Data berhasil ditambahkan.');
    }

    function edit($id)
    {

        $this->authorize('update', CollectionModel::find($id));

        $sugarglidercollections = CollectionModel::pluck('sugarglider_id')->all();

        $data = [
            'collection' => CollectionModel::findOrFail($id),
            'shelters' => ShelterModel::where('status', 1)->where('user_id', Auth::id())->orderBy('nama', 'asc')->get(),
            'sugargliders' => SugargliderModel::whereNotIn('id', $sugarglidercollections)->where('user_id', Auth::id())->orderBy('nama', 'asc')->get(),
        ];

        return view('collections.v_backend_collection_edit', $data);
    }

    function update(CollectionRequest $request)
    {
        $collection = CollectionModel::find($request->id);
        $collection->shelter_id     = $request->shelter_id;
        $collection->sugarglider_id = $request->sugarglider_id;
        $collection->status         = $request->status;

        if ($collection->status == CollectionStatus::NONAKTIF->value) {
            $adoption           = AdoptionModel::where('collection_id', $request->id)->first();
            $adoption->status   = AdoptionStatus::NONAKTIF->value;
            $adoption->save();
        }

        $collection->save();

        return redirect()->route('collection.index')->with('pesan', 'Data berhasil diperbaharui.');
    }

    function destroy(Request $request)
    {
        $collection = CollectionModel::findOrFail($request->id);
        $this->authorize('delete', $collection);
        $collection->delete();

        return redirect()->route('collection.index')->with('pesan', 'Data berhasil dihapus.');
    }
}
