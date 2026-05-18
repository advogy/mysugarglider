<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdoptionRequest;
use App\Enums\CollectionStatus;
use App\Enums\AdoptionStatus;
use App\Enums\AdoptionRequestStatus;
use App\Enums\PointType;
use App\Models\ProfileModel;
use App\Models\ShelterModel;
use App\Models\CollectionModel;
use App\Models\SugargliderModel;
use App\Models\AdoptionModel;
use App\Models\AdoptionRequestModel;
use App\Services\PointService;
use Illuminate\Support\Facades\DB;

class AdoptionController extends Controller
{
    function backend_adoption_index()
    {
        $profile = ProfileModel::where('user_id', Auth::id())->first();

        if (is_null($profile)) {
            return view('profiles.v_profile_no');
        }

        $collection = CollectionModel::whereIn('status', [
            CollectionStatus::PRIVAT->value,
            CollectionStatus::PUBLIK->value,
            CollectionStatus::ADOPSI->value,
        ])->where('user_id', Auth::id())->first();

        if (is_null($collection)) {
            return view('collections.v_backend_collection_no_adoption');
        }

        $lockedAdoptionIds = AdoptionRequestModel::whereIn('status', [
                AdoptionRequestStatus::DIBAYAR->value,
                AdoptionRequestStatus::DIKIRIM->value,
            ])->pluck('adoption_id')->all();

        $lockedEditIds = AdoptionRequestModel::whereIn('status', [
                AdoptionRequestStatus::DIPILIH->value,
                AdoptionRequestStatus::DIBAYAR->value,
                AdoptionRequestStatus::DIKIRIM->value,
            ])->pluck('adoption_id')->all();

        $data = [
            'lockedAdoptionIds' => $lockedAdoptionIds,
            'lockedEditIds'     => $lockedEditIds,
            'adoptions' => AdoptionModel::select(
                    'adoptions.id as id',
                    'adoptions.harga as harga',
                    'sugargliders.nama as nama',
                    'sugargliders.jenis as jenis',
                    'collections.id as collection_id',
                    DB::raw('COUNT(adoption_requests.adoption_id) as total_permohonan')
                )
                ->leftJoin('collections', 'collections.id', '=', 'adoptions.collection_id')
                ->leftJoin('sugargliders', 'sugargliders.id', '=', 'collections.sugarglider_id')
                ->leftJoin('adoption_requests', 'adoption_requests.adoption_id', '=', 'adoptions.id')
                ->where('collections.status', CollectionStatus::ADOPSI->value)
                ->where('adoptions.status', AdoptionStatus::AKTIF->value)
                ->where('adoptions.user_id', Auth::id())
                ->groupBy(
                    'adoption_requests.adoption_id', 'adoptions.id', 'adoptions.harga',
                    'sugargliders.nama', 'sugargliders.jenis', 'collections.id'
                )
                ->paginate(10),
        ];

        return view('adoption.v_backend_adoption_index', $data);
    }

    function create()
    {
        $adoption = AdoptionModel::where('status', AdoptionStatus::AKTIF->value)->pluck('collection_id')->all();

        $data = [
            'collections' => CollectionModel::select('collections.id', 'sugargliders.nama as nama')
                ->leftJoin('sugargliders', 'collections.sugarglider_id', '=', 'sugargliders.id')
                ->whereIn('collections.status', [CollectionStatus::PRIVAT->value, CollectionStatus::PUBLIK->value])
                ->where('collections.user_id', Auth::id())
                ->whereNotIn('collections.id', $adoption)
                ->orderBy('nama', 'asc')
                ->get(),
        ];

        return view('adoption.v_backend_adoption_create', $data);
    }

    function store(AdoptionRequest $request)
    {
        $collection = CollectionModel::where('id', $request->collection_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $adoption = AdoptionModel::create([
            'collection_id' => $collection->id,
            'harga'         => $request->harga,
            'keterangan'    => $request->keterangan,
            'status'        => AdoptionStatus::AKTIF->value,
            'user_id'       => Auth::id(),
        ]);

        $collection->status = CollectionStatus::ADOPSI->value;
        $collection->save();

        app(PointService::class)->earn(Auth::user(), PointType::ADOPTION_OPEN, $adoption);

        return redirect()->route('adoption.index')->with('pesan', 'Adopsi berhasil dibuka.');
    }

    function edit($id)
    {
        $adoption = AdoptionModel::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $hasActive = AdoptionRequestModel::where('adoption_id', $id)
            ->whereIn('status', [
                AdoptionRequestStatus::DIPILIH->value,
                AdoptionRequestStatus::DIBAYAR->value,
                AdoptionRequestStatus::DIKIRIM->value,
            ])->exists();

        if ($hasActive) {
            return redirect()->route('adoption.index')
                ->with('error', 'Tidak dapat mengedit adopsi yang sedang dalam proses pemilihan atau transfer.');
        }

        $activeAdoptions = AdoptionModel::where('status', AdoptionStatus::AKTIF->value)
            ->where('id', '!=', $id)
            ->pluck('collection_id')->all();

        $data = [
            'adoption'    => AdoptionModel::select(
                    'adoptions.id as id',
                    'adoptions.collection_id as collection_id',
                    'adoptions.harga as harga',
                    'adoptions.keterangan as keterangan',
                    'sugargliders.nama as nama'
                )
                ->leftJoin('collections', 'collections.id', '=', 'adoptions.collection_id')
                ->leftJoin('sugargliders', 'sugargliders.id', '=', 'collections.sugarglider_id')
                ->findOrFail($id),

            'collections' => CollectionModel::select('collections.id', 'sugargliders.nama as nama')
                ->leftJoin('sugargliders', 'collections.sugarglider_id', '=', 'sugargliders.id')
                ->whereIn('collections.status', [CollectionStatus::PRIVAT->value, CollectionStatus::PUBLIK->value, CollectionStatus::ADOPSI->value])
                ->where('collections.user_id', Auth::id())
                ->whereNotIn('collections.id', $activeAdoptions)
                ->orderBy('nama', 'asc')
                ->get(),
        ];

        return view('adoption.v_backend_adoption_edit', $data);
    }

    function update(AdoptionRequest $request)
    {
        $adoption = AdoptionModel::where('id', $request->id)->where('user_id', Auth::id())->firstOrFail();
        $adoption->collection_id = $request->collection_id;
        $adoption->harga         = $request->harga;
        $adoption->keterangan    = $request->keterangan;
        $adoption->save();

        return redirect()->route('adoption.index')->with('pesan', 'Data berhasil diperbaharui.');
    }

    function destroy($id)
    {
        $adoption = AdoptionModel::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $hasActive = AdoptionRequestModel::where('adoption_id', $id)
            ->whereIn('status', [
                AdoptionRequestStatus::DIPILIH->value,
                AdoptionRequestStatus::DIBAYAR->value,
                AdoptionRequestStatus::DIKIRIM->value,
            ])->exists();

        if ($hasActive) {
            return redirect()->route('adoption.index')
                ->with('error', 'Tidak dapat menutup adopsi yang sedang dalam proses.');
        }

        $collection = CollectionModel::find($adoption->collection_id);
        if ($collection) {
            $collection->status = CollectionStatus::PRIVAT->value;
            $collection->save();
        }

        $adoption->status = AdoptionStatus::NONAKTIF->value;
        $adoption->save();

        return redirect()->route('adoption.index')->with('pesan', 'Listing adopsi berhasil ditutup.');
    }

    function backend_adoption_list()
    {
        $profile = ProfileModel::where('user_id', Auth::id())->first();
        $shelter = ShelterModel::where('user_id', Auth::id())->first();

        if (is_null($profile)) {
            return view('profiles.v_profile_no');
        }

        if (is_null($shelter)) {
            return view('shelters.v_backend_shelter_no');
        }

        $hiddenByOthers = AdoptionRequestModel::whereIn('status', [
                AdoptionRequestStatus::DIPILIH->value,
                AdoptionRequestStatus::DIBAYAR->value,
                AdoptionRequestStatus::DIKIRIM->value,
            ])->where('user_id', '!=', Auth::id())->pluck('adoption_id');

        $data = [
            'adoptions' => AdoptionModel::select(
                    'adoptions.id as id',
                    'adoptions.user_id as ownerUserId',
                    'adoptions.harga as harga',
                    'adoptions.keterangan as keterangan',
                    'sugargliders.nama as sgNama',
                    'sugargliders.id as sgId',
                    'sugargliders.jenis as sgJenis',
                    'shelters.id as sId',
                    'shelters.nama as sNama',
                    'adoption_requests.id as arId',
                    'adoption_requests.status as arStatus',
                    'adoption_requests.harga as arHarga',
                    'adoption_requests.bukti_transfer as arBukti',
                    'collections.id as cId',
                    'adoption_requests.shelter_id as arShelterId',
                    'owner_profiles.telepon as ownerTelp',
                )
                ->leftJoin('collections', 'collections.id', '=', 'adoptions.collection_id')
                ->leftJoin('sugargliders', 'sugargliders.id', '=', 'collections.sugarglider_id')
                ->leftJoin('shelters', 'shelters.id', '=', 'collections.shelter_id')
                ->leftJoin('adoption_requests', function ($join) {
                    $join->on('adoption_requests.adoption_id', '=', 'adoptions.id')
                         ->where('adoption_requests.user_id', '=', Auth::id());
                })
                ->leftJoin('profiles as owner_profiles', 'owner_profiles.user_id', '=', 'adoptions.user_id')
                ->where('adoptions.user_id', '!=', Auth::id())
                ->where(function ($q) use ($hiddenByOthers) {
                    // Adopsi aktif yang belum diambil orang lain
                    $q->where(function ($q2) use ($hiddenByOthers) {
                        $q2->where('adoptions.status', AdoptionStatus::AKTIF->value)
                           ->whereNotIn('adoptions.id', $hiddenByOthers);
                    })
                    // Atau: permohonan user ditolak (tampilkan sebagai riwayat)
                    ->orWhere('adoption_requests.status', AdoptionRequestStatus::DITOLAK->value);
                })
                ->orderBy('adoptions.updated_at', 'desc')
                ->paginate(10),

            'shelters' => ShelterModel::where('user_id', Auth::id())->get(),
        ];

        return view('adoption.v_backend_adoption_list', $data);
    }

    function backend_adoption_request(Request $request)
    {
        AdoptionModel::where('id', $request->id)->where('user_id', Auth::id())->firstOrFail();

        $data = [
            'sugarglider' => SugargliderModel::select(
                    'adoptions.id as id',
                    'adoptions.harga as harga',
                    'sugargliders.nama as nama',
                    'sugargliders.jenis as jenis'
                )
                ->leftJoin('collections', 'collections.sugarglider_id', '=', 'sugargliders.id')
                ->leftJoin('adoptions', 'adoptions.collection_id', '=', 'collections.id')
                ->where('adoptions.id', $request->id)
                ->first(),

            'adoptionrequests' => AdoptionRequestModel::select(
                    'users.name as nama',
                    'shelters.id as kandang_id',
                    'shelters.nama as kandang',
                    'adoption_requests.id as id',
                    'adoption_requests.user_id as userId',
                    'adoption_requests.harga as harga',
                    'adoption_requests.status as status',
                    'adoption_requests.keterangan as keterangan',
                    'adoption_requests.bukti_transfer as bukti_transfer',
                    'adoption_requests.nama_ekspedisi as nama_ekspedisi',
                    'adoption_requests.resi_pengiriman as resi_pengiriman',
                    'adoption_requests.bukti_pengiriman as bukti_pengiriman',
                    'adoption_requests.paid_at as paid_at',
                    'adoption_requests.confirmed_at as confirmed_at',
                    'adoption_requests.created_at as created_at',
                    'applicant_profiles.telepon as applicantTelp',
                )
                ->where('adoption_id', $request->id)
                ->leftJoin('users', 'users.id', '=', 'adoption_requests.user_id')
                ->leftJoin('shelters', 'shelters.id', '=', 'adoption_requests.shelter_id')
                ->leftJoin('profiles as applicant_profiles', 'applicant_profiles.user_id', '=', 'adoption_requests.user_id')
                ->paginate(10),
        ];

        return view('adoption.v_backend_adoption_requests', $data);
    }
}
