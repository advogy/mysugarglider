<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CollectionRequest;
use App\Enums\CollectionStatus;
use App\Enums\AdoptionStatus;
use App\Enums\AdoptionRequestStatus;
use App\Models\ProfileModel;
use App\Models\CollectionModel;
use App\Models\ShelterModel;
use App\Models\SugargliderModel;
use App\Models\AdoptionModel;
use App\Models\AdoptionRequestModel;
use App\Enums\PointType;
use App\Services\PointService;

class CollectionController extends Controller
{
    function index(Request $request)
    {
        $search       = trim($request->get('q', ''));
        $statusFilter = $request->get('status', '');

        // Collection IDs where the owner has already selected an applicant (adoption in progress)
        $inProgressCollections = AdoptionModel::whereIn('id',
            AdoptionRequestModel::whereIn('status', [
                AdoptionRequestStatus::DIPILIH->value,
                AdoptionRequestStatus::DIBAYAR->value,
                AdoptionRequestStatus::DIKIRIM->value,
            ])->pluck('adoption_id')
        )->pluck('collection_id');

        // Collection IDs that have an active adoption listing
        $activeAdoptionCollections = AdoptionModel::where('status', AdoptionStatus::AKTIF->value)
            ->pluck('collection_id');

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
            ->where(function ($q) use ($statusFilter, $inProgressCollections, $activeAdoptionCollections) {
                if ($statusFilter === 'adopsi') {
                    // Hanya tampilkan adopsi yg pemilik sudah buka listing & belum ada pemohon terpilih
                    $q->where('collections.status', CollectionStatus::ADOPSI->value)
                      ->whereIn('collections.id', $activeAdoptionCollections)
                      ->whereNotIn('collections.id', $inProgressCollections);
                } else {
                    // Tampilkan semua publik + adopsi yg masih terbuka
                    $q->where('collections.status', CollectionStatus::PUBLIK->value)
                      ->orWhere(function ($q2) use ($inProgressCollections) {
                          $q2->where('collections.status', CollectionStatus::ADOPSI->value)
                             ->whereNotIn('collections.id', $inProgressCollections);
                      });
                }
            });

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

    function backend_collection_index(Request $request)
    {
        $profile = ProfileModel::where('user_id', Auth::id())->first();

        if (is_null($profile)) {
            return view('profiles.v_profile_no');
        }

        $q = trim($request->get('q', ''));

        $collections = CollectionModel::join('sugargliders as sg', 'collections.sugarglider_id', '=', 'sg.id')
            ->join('shelters as st', 'collections.shelter_id', '=', 'st.id')
            ->select(
                'collections.id as id',
                'collections.status as status',
                'sg.nama as sgNama',
                'sg.gambar as sgGambar',
                'st.nama as stNama',
                'st.gambar as stGambar',
            )
            ->where('st.user_id', Auth::id())
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('sg.nama', 'like', "%$q%")
                    ->orWhere('st.nama', 'like', "%$q%");
            }))
            ->orderBy('collections.updated_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('collections.v_backend_collection_index', compact('collections', 'q'));
    }

    function create()
    {
        $sugarglidercollections = CollectionModel::where('status', '!=', CollectionStatus::SELESAI->value)
            ->pluck('sugarglider_id')->all();

        $data = [
            'shelters'     => ShelterModel::where('status', 1)->where('user_id', Auth::id())->orderBy('nama', 'asc')->get(),
            'sugargliders' => SugargliderModel::whereNotIn('id', $sugarglidercollections)->where('user_id', Auth::id())->orderBy('nama', 'asc')->get(),
        ];
        return view('collections.v_backend_collection_create', $data);
    }

    function store(CollectionRequest $request)
    {
        $collection = CollectionModel::create([
            'shelter_id'        => $request->shelter_id,
            'sugarglider_id'    => $request->sugarglider_id,
            'status'            => $request->status,
            'user_id'           => Auth::id(),
        ]);

        app(PointService::class)->earn(Auth::user(), PointType::COLLECTION_CREATE, $collection);

        return redirect()->route('collection.index')->with('pesan', 'Data berhasil ditambahkan.');
    }

    function edit($id)
    {

        $collection = CollectionModel::findOrFail($id);
        $this->authorize('update', $collection);

        $sugarglidercollections = CollectionModel::where('status', '!=', CollectionStatus::SELESAI->value)
            ->where('id', '!=', $id)
            ->pluck('sugarglider_id')->all();

        $data = [
            'collection'   => $collection,
            'shelters'     => ShelterModel::where('status', 1)->where('user_id', Auth::id())->orderBy('nama', 'asc')->get(),
            'sugargliders' => SugargliderModel::whereNotIn('id', $sugarglidercollections)->where('user_id', Auth::id())->orderBy('nama', 'asc')->get(),
        ];

        return view('collections.v_backend_collection_edit', $data);
    }

    function update(CollectionRequest $request)
    {
        $collection = CollectionModel::findOrFail($request->id);
        $this->authorize('update', $collection);
        $collection->shelter_id     = $request->shelter_id;
        $collection->sugarglider_id = $request->sugarglider_id;

        // Status ADOPSI hanya boleh diset oleh sistem adopsi, bukan form ini
        if ($request->status != CollectionStatus::ADOPSI->value) {
            $collection->status = $request->status;

            // Jika status diubah dari ADOPSI ke lain, nonaktifkan adoption aktif
            if ($collection->getOriginal('status') == CollectionStatus::ADOPSI->value) {
                $adoption = AdoptionModel::where('collection_id', $request->id)
                    ->where('status', AdoptionStatus::AKTIF->value)
                    ->first();
                if ($adoption) {
                    $adoption->status = AdoptionStatus::NONAKTIF->value;
                    $adoption->save();
                }
            }
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
