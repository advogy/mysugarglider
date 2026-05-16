<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use App\Http\Requests\SugargliderRequest;
use App\Models\SugargliderModel;
use App\Models\ProfileModel;
use App\Models\ShelterModel;
use App\Models\CollectionModel;
use App\Models\AdoptionModel;
use App\Models\AdoptionRequestModel;
use App\Enums\AdoptionStatus;
use App\Enums\AdoptionRequestStatus;
use Carbon\Carbon;

class SugargliderController extends Controller
{
    function index()
    {
        $data = [
            'sugargliders' => SugargliderModel::paginate(20),
        ];

        return view('sugargliders.v_sugarglider', $data);
    }

    function backend_sugarglider_index()
    {
        $profile = ProfileModel::where('user_id', Auth::id())->first();

        if (is_null($profile)) {
            return view('profiles.v_profile_no');
        }

        $data = [
            'sugargliders' => SugargliderModel::leftJoin('collections', function ($join) {
                $join->on('collections.sugarglider_id', '=', 'sugargliders.id')
                     ->whereNull('collections.deleted_at')
                     ->where('collections.status', '!=', \App\Enums\CollectionStatus::SELESAI->value);
            })
            ->leftJoin('shelters', function ($join) {
                $join->on('shelters.id', '=', 'collections.shelter_id')
                     ->whereNull('shelters.deleted_at');
            })
            ->select('sugargliders.*', 'shelters.nama as kandang_nama', 'collections.status as cl_status')
            ->where('sugargliders.user_id', Auth::id())
            ->whereNull('sugargliders.deleted_at')
            ->paginate(20),
        ];

        return view('sugargliders.v_backend_sugarglider_index', $data);
    }

    function backend_show($id)
    {
        $sugarglider = SugargliderModel::findOrFail($id);

        if ($sugarglider->user_id !== Auth::id()) {
            abort(403);
        }

        $collection = CollectionModel::with('shelter')
            ->where('sugarglider_id', $sugarglider->id)
            ->where('status', '!=', \App\Enums\CollectionStatus::SELESAI->value)
            ->first();

        $silsilah = SugargliderModel::silsilah($sugarglider->id);

        $ancestorMap = collect();
        if ($silsilah) {
            $ids = collect([
                $silsilah->mId,    $silsilah->fId,
                $silsilah->mmId,   $silsilah->mfId,   $silsilah->fmId,   $silsilah->ffId,
                $silsilah->mmmId,  $silsilah->mmfId,  $silsilah->mfmId,  $silsilah->mffId,
                $silsilah->fmmId,  $silsilah->fmfId,  $silsilah->ffmId,  $silsilah->fffId,
                $silsilah->mmmmId, $silsilah->mmmfId, $silsilah->mmfmId, $silsilah->mmffId,
                $silsilah->mfmmId, $silsilah->mfmfId, $silsilah->mffmId, $silsilah->mfffId,
                $silsilah->fmmmId, $silsilah->fmmfId, $silsilah->fmfmId, $silsilah->fmffId,
                $silsilah->ffmmId, $silsilah->ffmfId, $silsilah->fffmId, $silsilah->ffffId,
            ])->filter()->unique()->values();

            if ($ids->isNotEmpty()) {
                $ancestorMap = SugargliderModel::select('sugargliders.id', 'sugargliders.user_id', 'collections.status as cl_status')
                    ->leftJoin('collections', function ($join) {
                        $join->on('collections.sugarglider_id', '=', 'sugargliders.id')
                             ->whereNull('collections.deleted_at')
                             ->where('collections.status', '!=', \App\Enums\CollectionStatus::SELESAI->value);
                    })
                    ->whereIn('sugargliders.id', $ids->all())
                    ->get()
                    ->keyBy('id');
            }
        }

        $transfers = \App\Models\ShelterTransferModel::leftJoin('shelters as from_st', 'shelter_transfers.from_shelter_id', '=', 'from_st.id')
            ->join('shelters as to_st', 'shelter_transfers.to_shelter_id', '=', 'to_st.id')
            ->leftJoin('users as from_u', 'shelter_transfers.from_user_id', '=', 'from_u.id')
            ->join('users as to_u', 'shelter_transfers.to_user_id', '=', 'to_u.id')
            ->select(
                'shelter_transfers.created_at',
                'from_st.nama as from_shelter_nama',
                'to_st.nama as to_shelter_nama',
                'from_u.name as from_user_name',
                'to_u.name as to_user_name',
            )
            ->where('shelter_transfers.sugarglider_id', $sugarglider->id)
            ->orderBy('shelter_transfers.created_at', 'asc')
            ->get();

        $keturunan = SugargliderModel::select(
                'sugargliders.id',
                'sugargliders.nama',
                'sugargliders.jenis',
                'sugargliders.kelamin',
                'sugargliders.user_id',
                'collections.status as cl_status',
                'users.name as user_name',
            )
            ->join('users', 'users.id', '=', 'sugargliders.user_id')
            ->leftJoin('collections', function ($join) {
                $join->on('collections.sugarglider_id', '=', 'sugargliders.id')
                     ->whereNull('collections.deleted_at')
                     ->where('collections.status', '!=', \App\Enums\CollectionStatus::SELESAI->value);
            })
            ->where(function ($q) use ($sugarglider) {
                $q->where('sugargliders.indukan_jantan', $sugarglider->id)
                  ->orWhere('sugargliders.indukan_betina', $sugarglider->id);
            })
            ->whereNull('sugargliders.deleted_at')
            ->where(function ($q) {
                $q->where('sugargliders.user_id', Auth::id())
                  ->orWhereIn('collections.status', [
                      \App\Enums\CollectionStatus::PUBLIK->value,
                      \App\Enums\CollectionStatus::ADOPSI->value,
                  ]);
            })
            ->orderBy('sugargliders.user_id')
            ->orderBy('sugargliders.nama')
            ->get();

        return view('sugargliders.v_backend_sugarglider_detail', compact('sugarglider', 'collection', 'silsilah', 'transfers', 'ancestorMap', 'keturunan'));
    }

    function create()
    {
        return view('sugargliders.v_backend_sugarglider_create');
    }

    function parents(\Illuminate\Http\Request $request)
    {
        $q       = trim($request->input('q', ''));
        $kelamin = (int) $request->input('kelamin', 1);
        $exclude = (int) $request->input('exclude', 0);
        $userId  = Auth::id();

        $results = SugargliderModel::select(
                'sugargliders.id',
                'sugargliders.nama',
                'sugargliders.jenis',
                'sugargliders.user_id',
                'users.name as user_name'
            )
            ->join('users', 'users.id', '=', 'sugargliders.user_id')
            ->where('sugargliders.kelamin', $kelamin)
            ->whereNull('sugargliders.deleted_at')
            ->whereNotIn('sugargliders.id', function ($sub) {
                $sub->select('sugarglider_id')
                    ->from('collections')
                    ->where('status', \App\Enums\CollectionStatus::MATI->value)
                    ->whereNull('deleted_at');
            })
            ->where(function ($q2) use ($userId) {
                // SG sendiri: semua status boleh
                // SG orang lain: hanya yang PUBLIK atau ADOPSI
                $q2->where('sugargliders.user_id', $userId)
                   ->orWhereIn('sugargliders.id', function ($sub) {
                       $sub->select('sugarglider_id')
                           ->from('collections')
                           ->whereIn('status', [
                               \App\Enums\CollectionStatus::PUBLIK->value,
                               \App\Enums\CollectionStatus::ADOPSI->value,
                           ])
                           ->whereNull('deleted_at');
                   });
            })
            ->when($q !== '', fn($query) => $query->where('sugargliders.nama', 'like', "%{$q}%"))
            ->when($exclude > 0, fn($query) => $query->where('sugargliders.id', '!=', $exclude))
            ->orderByRaw('sugargliders.user_id = ? DESC', [$userId])
            ->orderBy('sugargliders.nama')
            ->limit(30)
            ->get()
            ->map(fn($sg) => [
                'value' => $sg->id,
                'text'  => $sg->user_id === $userId
                    ? "{$sg->nama} – {$sg->jenis}"
                    : "{$sg->nama} – {$sg->jenis} ({$sg->user_name})",
                'group' => $sg->user_id === $userId ? 'mine' : 'other',
            ]);

        return response()->json($results);
    }

    function store(SugargliderRequest $request)
    {
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imagename = 'sg-' . $request->kode . '.' . $image->extension();

            ImageManager::gd()->read($image)->coverDown(500, 500)->save(public_path('upload/sugargliders/' . $imagename));
        } else {
            $imagename = null;
        }

        SugargliderModel::create([
            'kode'              => $request->kode,
            'nama'              => $request->nama,
            'kelamin'           => $request->kelamin,
            'tgl_lahir'         => $request->tgl_lahir,
            'warna'             => $request->warna,
            'jenis'             => $request->jenis,
            'genetika'          => $request->genetika,
            'fenotype'          => $request->fenotype,
            'indukan_betina'    => $request->indukan_betina,
            'indukan_jantan'    => $request->indukan_jantan,
            'gambar'            => $imagename,
            'keterangan'        => $request->keterangan,
            'user_id'           => Auth::id(),
        ]);

        return redirect()->route('sugarglider.index')->with('pesan', 'Data berhasil ditambahkan.');
    }

    function show($id)
    {
        $data = [
            'indukan' =>
            SugargliderModel::leftjoin('sugargliders as m', 'sugargliders.indukan_jantan', '=', 'm.id')
                ->leftjoin('sugargliders as f', 'sugargliders.indukan_betina', '=', 'f.id')
                ->select(
                    'sugargliders.nama as nama',
                    'sugargliders.id as id',
                    'sugargliders.jenis as jenis',
                    'm.nama as jantan',
                    'm.id as mId',
                    'm.jenis as mJenis',
                    'f.nama as betina',
                    'f.id as fId',
                    'f.jenis as fJenis',
                )
                ->where('sugargliders.id', $id)
                ->first(),

            'collection' =>
            CollectionModel::leftjoin('shelters', 'collections.shelter_id', '=', 'shelters.id')
                ->leftjoin('sugargliders', 'collections.sugarglider_id', '=', 'sugargliders.id')
                ->select(
                    'collections.id as cId',
                    'collections.status as clStatus',
                    'collections.user_id as clUser',
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
                )
                ->where('sugargliders.id', '=', $id)
                ->first(),

            'keturunans' =>
            CollectionModel::join('sugargliders', 'sugargliders.id', '=', 'collections.sugarglider_id')
                ->select(
                    'sugargliders.id',
                    'sugargliders.nama',
                    'sugargliders.jenis'
                )
                ->where('sugargliders.indukan_betina', '=', $id)
                ->orWhere('sugargliders.indukan_jantan', '=', $id)
                ->get(),
        ];

        $collection = $data['collection'];

        // Hitung usia di controller agar tidak ada logika di view
        $usia = null;
        if ($collection && $collection->sgTglLahir) {
            $diff = Carbon::parse($collection->sgTglLahir)->diff(Carbon::now());
            $parts = [];
            if ($diff->y > 0) $parts[] = $diff->y . ' thn';
            if ($diff->m > 0) $parts[] = $diff->m . ' bln';
            $usia = $parts ? implode(' ', $parts) : '< 1 bln';
        }

        // Cari adoption yang masih terbuka (belum ada pemohon terpilih)
        $adoptionId = null;
        if ($collection && $collection->clStatus == 3) {
            $adoptionId = AdoptionModel::where('collection_id', $collection->cId)
                ->where('status', AdoptionStatus::AKTIF->value)
                ->whereNotIn('id', AdoptionRequestModel::whereIn('status', [
                    AdoptionRequestStatus::DIPILIH->value,
                    AdoptionRequestStatus::DIBAYAR->value,
                    AdoptionRequestStatus::DIKIRIM->value,
                ])->pluck('adoption_id'))
                ->value('id');
        }

        $data['usia']       = $usia;
        $data['adoptionId'] = $adoptionId;

        return view('sugargliders.v_sugarglider_detail', $data);
    }

    function edit($id)
    {
        $sugarglider   = SugargliderModel::findOrFail($id);
        $this->authorize('update', $sugarglider);

        $indukanJantan = $sugarglider->indukan_jantan
            ? SugargliderModel::select('id', 'nama', 'jenis')->find($sugarglider->indukan_jantan)
            : null;
        $indukanBetina = $sugarglider->indukan_betina
            ? SugargliderModel::select('id', 'nama', 'jenis')->find($sugarglider->indukan_betina)
            : null;

        return view('sugargliders.v_backend_sugarglider_edit', compact('sugarglider', 'indukanJantan', 'indukanBetina'));
    }

    function update(SugargliderRequest $request)
    {
        $sugarglider = SugargliderModel::find($request->id);

        $sugarglider->kode              = $request->kode;
        $sugarglider->nama              = $request->nama;
        $sugarglider->kelamin           = $request->kelamin;
        $sugarglider->tgl_lahir         = $request->tgl_lahir;
        $sugarglider->warna             = $request->warna;
        $sugarglider->jenis             = $request->jenis;
        $sugarglider->genetika          = $request->genetika;
        $sugarglider->fenotype          = $request->fenotype;
        $sugarglider->indukan_betina    = $request->indukan_betina;
        $sugarglider->indukan_jantan    = $request->indukan_jantan;
        $sugarglider->keterangan        = $request->keterangan;

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imagename = 'sg-' . $request->kode . '.' . $image->extension();

            ImageManager::gd()->read($image)->coverDown(500, 500)->save(public_path('upload/sugargliders/' . $imagename));

            $sugarglider->gambar = $imagename;
        }

        $sugarglider->save();

        return redirect()->route('sugarglider.index')->with('pesan', 'Data berhasil diperbaharui.');
    }

    function destroy(Request $request)
    {
        $sugarglider = SugargliderModel::findOrFail($request->id);
        $this->authorize('delete', $sugarglider);
        $sugarglider->delete();

        return redirect()->route('sugarglider.index')->with('pesan', 'Data berhasil dihapus.');
    }
}
