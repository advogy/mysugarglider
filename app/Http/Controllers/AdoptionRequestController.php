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
use App\Models\AppConfig;
use App\Models\CollectionModel;
use App\Models\SugargliderModel;
use App\Services\PointService;
use App\Notifications\AdoptionNotification;
use App\Models\User;
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

        // Notifikasi ke pemilik adopsi
        $adoption->load('collection.sugarglider');
        $sgNama = $adoption->collection?->sugarglider?->nama ?? 'Sugar Glider';
        $owner  = User::find($adoption->user_id);
        $owner?->notify(new AdoptionNotification(
            title: 'Permohonan Adopsi Baru',
            body:  Auth::user()->name . " mengajukan permohonan untuk {$sgNama}.",
            url:   route('adoption.request', $adoption->id),
            icon:  'bi-inbox',
        ));

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

        // Ambil daftar pemohon yang akan ditolak sebelum bulk update
        $rejectedUserIds = AdoptionRequestModel::where('adoption_id', $adoption->id)
            ->where('status', AdoptionRequestStatus::MENUNGGU->value)
            ->where('id', '!=', $adoptionrequest->id)
            ->pluck('user_id');

        // Tolak semua permohonan lain yang masih menunggu
        AdoptionRequestModel::where('adoption_id', $adoption->id)
            ->where('status', AdoptionRequestStatus::MENUNGGU->value)
            ->where('id', '!=', $adoptionrequest->id)
            ->update(['status' => AdoptionRequestStatus::DITOLAK->value]);

        // Notifikasi ke semua pemohon yang ditolak
        $adoption->load('collection.sugarglider');
        $sgNama = $adoption->collection?->sugarglider?->nama ?? 'Sugar Glider';
        User::whereIn('id', $rejectedUserIds)->each(function ($user) use ($sgNama) {
            $user->notify(new AdoptionNotification(
                title: 'Permohonan Tidak Terpilih',
                body:  "Permohonan Anda untuk {$sgNama} tidak terpilih karena pemohon lain sudah dipilih pemilik.",
                url:   route('adoption.list'),
                icon:  'bi-x-circle',
            ));
        });

        // Notifikasi ke pemohon terpilih
        $adoption->load('collection.sugarglider');
        $sgNama   = $adoption->collection?->sugarglider?->nama ?? 'Sugar Glider';
        $applicant = User::find($adoptionrequest->user_id);
        $applicant?->notify(new AdoptionNotification(
            title: 'Permohonan Anda Dipilih!',
            body:  "Selamat! Permohonan adopsi {$sgNama} Anda dipilih. Silakan lanjutkan proses pembayaran.",
            url:   route('adoption.list'),
            icon:  'bi-stars',
        ));

        return redirect()->route('adoption.request', $adoption->id)->with('pesan', 'Pemohon berhasil dipilih.');
    }

    // Batalkan pilihan — bisa dilakukan pemohon ATAU pemilik, hanya saat status DIPILIH
    function cancel_selection(Request $request, $id)
    {
        $adoptionrequest = AdoptionRequestModel::where('id', $id)
            ->where('status', AdoptionRequestStatus::DIPILIH->value)
            ->firstOrFail();

        $adoption = AdoptionModel::findOrFail($adoptionrequest->adoption_id);

        // Hanya pemohon atau pemilik yang boleh membatalkan
        if (Auth::id() !== $adoptionrequest->user_id && Auth::id() !== $adoption->user_id) {
            abort(403);
        }

        $adoptionrequest->status = AdoptionRequestStatus::DIBATALKAN->value;
        $adoptionrequest->save();

        // Kembalikan semua permohonan yang ditolak ke status menunggu
        AdoptionRequestModel::where('adoption_id', $adoption->id)
            ->where('status', AdoptionRequestStatus::DITOLAK->value)
            ->update(['status' => AdoptionRequestStatus::MENUNGGU->value]);

        // Notifikasi ke pihak yang tidak membatalkan
        $adoption->load('collection.sugarglider');
        $sgNama = $adoption->collection?->sugarglider?->nama ?? 'Sugar Glider';

        if (Auth::id() === $adoptionrequest->user_id) {
            // Pemohon yang batalkan → notif ke pemilik
            $owner = User::find($adoption->user_id);
            $owner?->notify(new AdoptionNotification(
                title: 'Pilihan Adopsi Dibatalkan',
                body:  "Pemohon membatalkan pilihan adopsi {$sgNama}. Anda dapat memilih pemohon lain.",
                url:   route('adoption.request', $adoption->id),
                icon:  'bi-x-circle',
            ));
            return redirect()->route('adoption.list')
                ->with('pesan', 'Permohonan adopsi berhasil dibatalkan.');
        }

        // Pemilik yang batalkan → notif ke pemohon
        $applicant = User::find($adoptionrequest->user_id);
        $applicant?->notify(new AdoptionNotification(
            title: 'Pilihan Adopsi Dibatalkan',
            body:  "Pemilik membatalkan pilihan Anda untuk adopsi {$sgNama}.",
            url:   route('adoption.list'),
            icon:  'bi-x-circle',
        ));

        return redirect()->route('adoption.request', $adoption->id)
            ->with('pesan', 'Pilihan dibatalkan. Pemohon lain kini dapat dipilih kembali.');
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
        $adoptionrequest->platform_fee   = (int) AppConfig::get('admin_platform_fee', 0);
        $adoptionrequest->status         = AdoptionRequestStatus::DIBAYAR->value;
        $adoptionrequest->save();

        // Notifikasi ke pemilik & admin: bukti sudah diupload
        $adoption->load('collection.sugarglider');
        $sgNama = $adoption->collection?->sugarglider?->nama ?? 'Sugar Glider';

        $owner = User::find($adoption->user_id);
        $owner?->notify(new AdoptionNotification(
            title: 'Bukti Transfer Diunggah',
            body:  "Pemohon telah mengunggah bukti transfer untuk adopsi {$sgNama}. Menunggu konfirmasi admin.",
            url:   route('adoption.request', $adoption->id),
            icon:  'bi-shield-check',
        ));

        User::where('role', 'admin')->each(function ($admin) use ($sgNama, $adoption) {
            $admin->notify(new AdoptionNotification(
                title: 'Konfirmasi Pembayaran Diperlukan',
                body:  "Ada bukti transfer baru untuk adopsi {$sgNama} yang perlu dikonfirmasi.",
                url:   route('admin.adoptions.index'),
                icon:  'bi-shield-check',
            ));
        });

        return redirect()->route('adoption.list')->with('pesan', 'Bukti transfer berhasil diunggah. Menunggu konfirmasi admin.');
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

        // Notifikasi ke pemilik: adopsi gratis dikonfirmasi, silakan kirim
        $adoption->load('collection.sugarglider');
        $sgNama = $adoption->collection?->sugarglider?->nama ?? 'Sugar Glider';
        $owner  = User::find($adoption->user_id);
        $owner?->notify(new AdoptionNotification(
            title: 'Adopsi Gratis Dikonfirmasi',
            body:  "Pemohon mengkonfirmasi adopsi gratis {$sgNama}. Silakan kirimkan sugar glider.",
            url:   route('adoption.request', $adoption->id),
            icon:  'bi-truck',
        ));

        return redirect()->route('adoption.list')->with('pesan', 'Permohonan dikonfirmasi. Menunggu pemilik mengirimkan sugar glider.');
    }

    // Pemilik tandai sudah mengirimkan sugar glider secara fisik
    function backend_adoption_shipping(Request $request)
    {
        $request->validate([
            'nama_ekspedisi'   => 'required|string|max:100',
            'resi_pengiriman'  => 'required|string|max:100',
            'bukti_pengiriman' => 'nullable|image|max:2048',
        ]);

        $adoptionrequest = AdoptionRequestModel::findOrFail($request->id);
        $adoption        = AdoptionModel::where('id', $request->adoption_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($adoptionrequest->status !== AdoptionRequestStatus::DIBAYAR->value || is_null($adoptionrequest->confirmed_at)) {
            return redirect()->route('adoption.request', $adoption->id)->with('error', 'Belum dapat mengirim: konfirmasi pembayaran diperlukan terlebih dahulu.');
        }

        $adoptionrequest->nama_ekspedisi  = $request->nama_ekspedisi;
        $adoptionrequest->resi_pengiriman = $request->resi_pengiriman;

        if ($request->hasFile('bukti_pengiriman')) {
            $adoptionrequest->bukti_pengiriman = $request->file('bukti_pengiriman')->store('bukti_pengiriman', 'public');
        }

        $adoptionrequest->status = AdoptionRequestStatus::DIKIRIM->value;
        $adoptionrequest->save();

        // Notifikasi ke pemohon: SG sudah dikirim, konfirmasi penerimaan
        $adoption->load('collection.sugarglider');
        $sgNama    = $adoption->collection?->sugarglider?->nama ?? 'Sugar Glider';
        $applicant = User::find($adoptionrequest->user_id);
        $applicant?->notify(new AdoptionNotification(
            title: 'Sugar Glider Dalam Pengiriman',
            body:  "{$sgNama} sudah dikirim via {$adoptionrequest->nama_ekspedisi} (No. Resi: {$adoptionrequest->resi_pengiriman}). Konfirmasi saat sudah diterima.",
            url:   route('adoption.list'),
            icon:  'bi-house-heart',
        ));

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

        // Tandai penempatan lama sebagai selesai (jadi riwayat)
        $collection->status = CollectionStatus::SELESAI->value;
        $collection->save();

        // Buat penempatan baru untuk adopter di kandang yang dipilih
        CollectionModel::create([
            'sugarglider_id' => $sugarglider->id,
            'shelter_id'     => $shelter->id,
            'user_id'        => Auth::id(),
            'status'         => CollectionStatus::PUBLIK->value,
        ]);

        $adoption->status = AdoptionStatus::SELESAI->value;
        $adoption->save();

        $adoptionrequest->status = AdoptionRequestStatus::SELESAI->value;
        $adoptionrequest->save();

        $svc = app(PointService::class);
        if ($oldOwner) {
            $svc->earn($oldOwner, PointType::ADOPTION_SOLD, $adoptionrequest);
        }
        $svc->earn(Auth::user(), PointType::ADOPTION_RECEIVED, $adoptionrequest);

        // Notifikasi ke pemilik lama: adopsi selesai, menunggu pencairan dana
        $oldOwner?->notify(new AdoptionNotification(
            title: 'Adopsi Selesai',
            body:  "{$sugarglider->nama} sudah diterima pemohon. Dana akan segera dicairkan ke rekening Anda.",
            url:   route('adoption.index'),
            icon:  'bi-send-check',
        ));

        return redirect()->route('sugarglider.index')->with('pesan', 'Selamat! Sugar glider berhasil diadopsi dan sudah masuk ke koleksi kandang Anda.');
    }
}
