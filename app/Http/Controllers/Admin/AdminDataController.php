<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShelterRequest;
use App\Http\Requests\SugargliderRequest;
use App\Models\CollectionModel;
use App\Models\ShelterModel;
use App\Models\SugargliderModel;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class AdminDataController extends Controller
{
    // ── Shelters ──────────────────────────────────────────────────────────────

    public function shelters(Request $request)
    {
        $q = $request->input('q');

        $shelters = ShelterModel::with('user')
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%$q%")
                    ->orWhere('kode', 'like', "%$q%");
            }))
            ->withCount('sugargliders')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.data.v_shelters', compact('shelters', 'q'));
    }

    public function editShelter(ShelterModel $shelter)
    {
        return view('admin.data.v_shelter_edit', compact('shelter'));
    }

    public function updateShelter(ShelterRequest $request, ShelterModel $shelter)
    {
        $shelter->nama       = $request->nama;
        $shelter->kode       = $request->kode;
        $shelter->alamat     = $request->alamat;
        $shelter->status     = $request->status;
        $shelter->keterangan = $request->keterangan;
        $shelter->gmaps      = $request->gmaps;

        if ($request->hasFile('gambar')) {
            $image     = $request->file('gambar');
            $imagename = 'shelter-' . $shelter->kode . '.' . $image->extension();
            ImageManager::gd()->read($image)->coverDown(500, 500)->save(public_path('upload/shelters/' . $imagename));
            $shelter->gambar = $imagename;
        }

        $shelter->save();

        return redirect()->route('admin.data.shelters')->with('pesan', 'Data kandang berhasil diperbarui.');
    }

    public function destroyShelter(ShelterModel $shelter)
    {
        $shelter->delete();
        return back()->with('pesan', "Kandang {$shelter->nama} berhasil dihapus.");
    }

    // ── Sugargliders ──────────────────────────────────────────────────────────

    public function sugargliders(Request $request)
    {
        $q = $request->input('q');

        $sugargliders = SugargliderModel::with('user')
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%$q%")
                    ->orWhere('kode', 'like', "%$q%")
                    ->orWhere('jenis', 'like', "%$q%");
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.data.v_sugargliders', compact('sugargliders', 'q'));
    }

    public function editSugarglider(SugargliderModel $sugarglider)
    {
        $indukanJantan = $sugarglider->indukan_jantan
            ? SugargliderModel::select('id', 'nama', 'jenis')->find($sugarglider->indukan_jantan)
            : null;
        $indukanBetina = $sugarglider->indukan_betina
            ? SugargliderModel::select('id', 'nama', 'jenis')->find($sugarglider->indukan_betina)
            : null;

        return view('admin.data.v_sugarglider_edit', compact('sugarglider', 'indukanJantan', 'indukanBetina'));
    }

    public function updateSugarglider(SugargliderRequest $request, SugargliderModel $sugarglider)
    {
        $sugarglider->kode           = $request->kode;
        $sugarglider->nama           = $request->nama;
        $sugarglider->kelamin        = $request->kelamin;
        $sugarglider->tgl_lahir      = $request->tgl_lahir;
        $sugarglider->warna          = $request->warna;
        $sugarglider->jenis          = $request->jenis;
        $sugarglider->genetika       = $request->genetika;
        $sugarglider->fenotype       = $request->fenotype;
        $sugarglider->indukan_betina = $request->indukan_betina;
        $sugarglider->indukan_jantan = $request->indukan_jantan;
        $sugarglider->keterangan     = $request->keterangan;

        if ($request->hasFile('gambar')) {
            $image     = $request->file('gambar');
            $imagename = 'sg-' . $request->kode . '.' . $image->extension();
            ImageManager::gd()->read($image)->coverDown(500, 500)->save(public_path('upload/sugargliders/' . $imagename));
            $sugarglider->gambar = $imagename;
        }

        $sugarglider->save();

        return redirect()->route('admin.data.sugargliders')->with('pesan', 'Data sugar glider berhasil diperbarui.');
    }

    public function destroySugarglider(SugargliderModel $sugarglider)
    {
        $sugarglider->delete();
        return back()->with('pesan', "Sugar glider {$sugarglider->nama} berhasil dihapus.");
    }

    // ── Collections ───────────────────────────────────────────────────────────

    public function collections(Request $request)
    {
        $q = $request->input('q');

        $collections = CollectionModel::with(['shelter', 'sugarglider', 'shelter.user'])
            ->when($q, fn($query) => $query->whereHas('sugarglider', fn($sub) => $sub->where('nama', 'like', "%$q%"))
                ->orWhereHas('shelter', fn($sub) => $sub->where('nama', 'like', "%$q%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.data.v_collections', compact('collections', 'q'));
    }

    public function updateCollectionStatus(Request $request, CollectionModel $collection)
    {
        $request->validate(['status' => 'required|integer|between:1,5']);
        $collection->update(['status' => $request->status]);
        return back()->with('pesan', 'Status penempatan berhasil diperbarui.');
    }

    public function destroyCollection(CollectionModel $collection)
    {
        $collection->delete();
        return back()->with('pesan', 'Data penempatan berhasil dihapus.');
    }
}
