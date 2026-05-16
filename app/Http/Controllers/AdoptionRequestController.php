<?php

namespace App\Http\Controllers;

use App\Models\AdoptionModel;
use App\Models\ShelterTransferModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AdoptionApplicationRequest;
use App\Enums\AdoptionStatus;
use App\Enums\AdoptionRequestStatus;
use App\Enums\CollectionStatus;
use App\Enums\PointType;
use App\Models\AdoptionRequestModel;
use App\Models\CollectionModel;
use App\Models\SugargliderModel;
use App\Services\PointService;
use Carbon\Carbon;

class AdoptionRequestController extends Controller
{
    // Pemohon ajukan permohonan adopsi
    function store(AdoptionApplicationRequest $request)
    {
        $adoption = AdoptionModel::where('id', $request->adoption_id)
            ->where('user_id', '!=', Auth::id())
            ->where('status', AdoptionStatus::AKTIF->value)
            ->firstOrFail();

        // Cegah double permohonan
        $existing = AdoptionRequestModel::where('adoption_id', $adoption->id)
            ->where('user_id', Auth::id())
            ->whereNotIn('status', [AdoptionRequestStatus::DITOLAK->value])
            ->first();

        if ($existing) {
            return redirect()->route('adoption.list')->with('error', 'Anda sudah mengajukan permohonan untuk adopsi ini.');
        }

        AdoptionRequestModel::create([
            'adoption_id' => $adoption->id,
            'harga'       => $request->harga,
            'status'      => AdoptionRequestStatus::MENUNGGU->value,
            'keterangan'  => $request->keterangan,
            'user_id'     => Auth::id(),
            'shelter_id'  => $request->shelter_id,
        ]);

        return redirect()->route('adoption.list')->with('pesan', 'Permohonan berhasil dikirimkan.');
    }

    // Pemilik memilih pemohon terpilih
    function backend_adoption_select(Request $request)
    {
        $adoption = AdoptionModel::where('id', $request->adoption_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $adoptionrequest = AdoptionRequestModel::where('id', $request->adoption_request_id)
            ->where('adoption_id', $adoption->id)
            ->firstOrFail();

        $adoptionrequest->status = AdoptionRequestStatus::DIPILIH->value;
        $adoptionrequest->save();

        // Tolak semua permohonan lain yang masih menunggu
        AdoptionRequestModel::where('adoption_id', $adoption->id)
            ->where('status', AdoptionRequestStatus::MENUNGGU->value)
            ->where('id', '!=', $adoptionrequest->id)
            ->update(['status' => AdoptionRequestStatus::DITOLAK->value]);

        return redirect()->route('adoption.request', $adoption->id)->with('pesan', 'Pemohon berhasil dipilih.');
    }

    // Pemohon upload bukti transfer (khusus adopsi berbayar)
    function upload_payment(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|max:2048',
        ]);

        $adoptionrequest = AdoptionRequestModel::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', AdoptionRequestStatus::DIPILIH->value)
            ->firstOrFail();

        $adoption = AdoptionModel::findOrFail($adoptionrequest->adoption_id);

        if ($adoption->harga == 0) {
            abort(422, 'Adopsi ini gratis, tidak perlu upload bukti transfer.');
        }

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        $adoptionrequest->bukti_transfer = $path;
        $adoptionrequest->paid_at        = Carbon::now();
        $adoptionrequest->status         = AdoptionRequestStatus::DIBAYAR->value;
        $adoptionrequest->save();

        return redirect()->route('adoption.list')->with('pesan', 'Bukti transfer berhasil diunggah. Menunggu konfirmasi pemilik.');
    }

    // Pemohon konfirmasi terima (adopsi gratis — skip pembayaran)
    function confirm_free(Request $request, $id)
    {
        $adoptionrequest = AdoptionRequestModel::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', AdoptionRequestStatus::DIPILIH->value)
            ->firstOrFail();

        $adoption = AdoptionModel::findOrFail($adoptionrequest->adoption_id);

        if ($adoption->harga > 0) {
            abort(422, 'Adopsi ini berbayar, harap upload bukti transfer.');
        }

        $adoptionrequest->status       = AdoptionRequestStatus::DIBAYAR->value;
        $adoptionrequest->confirmed_at = Carbon::now();
        $adoptionrequest->save();

        return redirect()->route('adoption.list')->with('pesan', 'Permohonan dikonfirmasi. Menunggu pemilik mengirimkan sugar glider.');
    }

    // Pemilik konfirmasi pembayaran diterima → izinkan pengiriman
    function confirm_payment(Request $request, $id)
    {
        $adoptionrequest = AdoptionRequestModel::where('id', $id)
            ->where('status', AdoptionRequestStatus::DIBAYAR->value)
            ->firstOrFail();

        $adoption = AdoptionModel::where('id', $adoptionrequest->adoption_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $adoptionrequest->confirmed_at = Carbon::now();
        $adoptionrequest->save();

        return redirect()->route('adoption.request', $adoption->id)->with('pesan', 'Pembayaran dikonfirmasi. Silakan kirimkan sugar glider.');
    }

    // Pemilik tandai sudah mengirimkan sugar glider secara fisik
    function backend_adoption_shipping(Request $request)
    {
        $adoptionrequest = AdoptionRequestModel::findOrFail($request->id);
        $adoption        = AdoptionModel::where('id', $request->adoption_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Pastikan sudah melewati tahap pembayaran (gratis: confirmed_at terisi, berbayar: confirmed_at terisi)
        if ($adoptionrequest->status !== AdoptionRequestStatus::DIBAYAR->value || is_null($adoptionrequest->confirmed_at)) {
            return redirect()->route('adoption.request', $adoption->id)->with('error', 'Belum dapat mengirim: konfirmasi pembayaran diperlukan terlebih dahulu.');
        }

        $adoptionrequest->status = AdoptionRequestStatus::DIKIRIM->value;
        $adoptionrequest->save();

        return redirect()->route('adoption.request', $adoption->id)->with('pesan', 'Sugar glider ditandai sudah dikirim.');
    }

    // Pemohon konfirmasi sudah terima fisik → kepemilikan berpindah
    function backend_adoption_finalize(Request $request)
    {
        $adoptionrequest = AdoptionRequestModel::where('id', $request->adoptionrequest_id)
            ->where('user_id', Auth::id())
            ->where('status', AdoptionRequestStatus::DIKIRIM->value)
            ->firstOrFail();

        $shelter = \App\Models\ShelterModel::where('id', $adoptionrequest->shelter_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $adoption    = AdoptionModel::findOrFail($adoptionrequest->adoption_id);
        $collection  = CollectionModel::findOrFail($adoption->collection_id);
        $sugarglider = SugargliderModel::findOrFail($collection->sugarglider_id);

        $oldOwner = \App\Models\User::find($collection->user_id);

        // Catat riwayat perpindahan kandang
        ShelterTransferModel::create([
            'sugarglider_id'      => $sugarglider->id,
            'collection_id'       => $collection->id,
            'from_shelter_id'     => $collection->shelter_id,
            'from_user_id'        => $collection->user_id,
            'to_shelter_id'       => $shelter->id,
            'to_user_id'          => Auth::id(),
            'adoption_request_id' => $adoptionrequest->id,
        ]);

        // Pindahkan kepemilikan SG ke adopter
        $sugarglider->user_id = Auth::id();
        $sugarglider->save();

        // Tandai penempatan lama sebagai selesai (jadi riwayat), adopter tempatkan sendiri
        $collection->status = CollectionStatus::SELESAI->value;
        $collection->save();

        $adoption->status = AdoptionStatus::SELESAI->value;
        $adoption->save();

        $adoptionrequest->status = AdoptionRequestStatus::SELESAI->value;
        $adoptionrequest->save();

        $svc = app(PointService::class);
        if ($oldOwner) {
            $svc->earn($oldOwner, PointType::ADOPTION_SOLD, $adoptionrequest);
        }
        $svc->earn(Auth::user(), PointType::ADOPTION_RECEIVED, $adoptionrequest);

        return redirect()->route('sugarglider.index')->with('pesan', 'Selamat! Sugar glider berhasil diadopsi. Silakan tambahkan penempatan di kandang Anda.');
    }
}
