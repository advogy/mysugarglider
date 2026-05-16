<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use App\Http\Requests\ShelterRequest;
use App\Enums\CollectionStatus;
use App\Models\ShelterModel;
use App\Models\ProfileModel;
use App\Models\SugargliderModel;

class ShelterController extends Controller
{
    function index(Request $request)
    {
        $search = trim($request->get('q', ''));

        $query = ShelterModel::withCount(['collections as sg_count' => function ($q) {
            $q->whereIn('status', [CollectionStatus::PUBLIK->value, CollectionStatus::ADOPSI->value])
              ->whereNull('deleted_at');
        }]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $data = [
            'shelters' => $query->paginate(10)->appends($request->query()),
            'search'   => $search,
        ];

        return view('shelters.v_shelter', $data);
    }

    function backend_shelters_index()
    {
        $profile = ProfileModel::where('user_id', Auth::id())->first();

        if (is_null($profile)) {
            return view('profiles.v_profile_no');
        } else {
            $data = [
                'shelters' => ShelterModel::withCount(['collections as sg_count' => function ($q) {
                    $q->whereNull('collections.deleted_at');
                }])
                ->where('user_id', Auth::id())
                ->paginate(20)
            ];

            return view('shelters.v_backend_shelter_index', $data);
        }
    }

    function create()
    {
        return view('shelters.v_backend_shelter_create');
    }

    function store(ShelterRequest $request)
    {
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imagename = 'shelter-' . $request->kode . '.' . $image->extension();

            ImageManager::gd()->read($image)->coverDown(500, 500)->save(public_path('upload/shelters/' . $imagename));
        } else {
            $imagename = null;
        }

        ShelterModel::create([
            'nama'              => $request->nama,
            'kode'              => $request->kode,
            'alamat'            => $request->alamat,
            'status'            => $request->status,
            'user_id'           => Auth::id(),
            'gambar'            => $imagename,
            'keterangan'        => $request->keterangan,
            'gmaps'             => $request->gmaps,
        ]);

        return redirect()->route('shelter.index')->with('pesan', 'Data berhasil ditambahkan.');
    }

    function show($id)
    {
        $data = [
            'shelter' => ShelterModel::find($id),
            'sugargliders' => SugargliderModel::leftjoin('collections', 'collections.sugarglider_id', '=', 'sugargliders.id')
                ->leftjoin('shelters', 'collections.shelter_id', '=', 'shelters.id')
                ->select(
                    'sugargliders.id as sgId',
                    'sugargliders.kode as sgKode',
                    'sugargliders.nama as sgNama',
                    'sugargliders.jenis as sgJenis',
                    'sugargliders.gambar as sgGambar',
                    'sugargliders.kelamin as sgKelamin',
                    'collections.status as sgStatus',
                )
                ->whereIn('collections.status', [CollectionStatus::PUBLIK->value, CollectionStatus::ADOPSI->value])
                ->where('shelters.id', $id)
                ->whereNull('collections.deleted_at')
                ->paginate(10)
        ];
        return view('shelters.v_shelter_detail', $data);
    }

    function edit($id)
    {

        $this->authorize('update', ShelterModel::find($id));

        $data = [
            'shelter' => ShelterModel::findOrFail($id)
        ];

        return view('shelters.v_backend_shelter_edit', $data);
    }

    function update(ShelterRequest $request)
    {
        $shelter = ShelterModel::find($request->id);
        $shelter->nama        = $request->nama;
        $shelter->kode        = $request->kode;
        $shelter->alamat      = $request->alamat;
        $shelter->status      = $request->status;
        $shelter->user_id     = Auth::id();
        $shelter->keterangan  = $request->keterangan;
        $shelter->gmaps       = $request->gmaps;

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imagename = 'shelter-' . $shelter->kode . '.' . $image->extension();

            ImageManager::gd()->read($image)->coverDown(500, 500)->save(public_path('upload/shelters/' . $imagename));

            $shelter->gambar = $imagename;
        }

        $shelter->save();

        return redirect()->route('shelter.index')->with('pesan', 'Data berhasil diperbaharui.');
    }

    function destroy(Request $request)
    {
        $shelter = ShelterModel::findOrFail($request->id);
        $this->authorize('delete', $shelter);

        $shelter->delete();

        return redirect()->route('shelter.index')->with('pesan', 'Data berhasil dihapus.');
    }
}
