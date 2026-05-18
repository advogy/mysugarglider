<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdoptionModel;
use App\Models\AdoptionRequestModel;
use App\Models\ProfileModel;
use App\Models\SugargliderModel;
use App\Enums\AdoptionStatus;
use App\Enums\AdoptionRequestStatus;
use App\Notifications\AdoptionNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAdoptionController extends Controller
{
    public function index()
    {
        // Pembayaran menunggu konfirmasi admin (bukti sudah diupload, belum dikonfirmasi)
        $pendingPayment = AdoptionRequestModel::with(['adoption.owner', 'adoption.collection.sugarglider', 'applicant'])
            ->where('status', AdoptionRequestStatus::DIBAYAR->value)
            ->whereNull('confirmed_at')
            ->latest('paid_at')
            ->get();

        // Adopsi selesai berbayar, dana belum dicairkan ke pemilik
        $pendingDisbursement = AdoptionRequestModel::with(['adoption.owner', 'adoption.collection.sugarglider', 'applicant'])
            ->where('status', AdoptionRequestStatus::SELESAI->value)
            ->whereNull('disbursed_at')
            ->whereHas('adoption', fn ($q) => $q->where('harga', '>', 0))
            ->latest('updated_at')
            ->get();

        // Ambil profil bank pemilik sekaligus
        $ownerIds = $pendingDisbursement->pluck('adoption.owner.id')->filter()->unique();
        $profiles = ProfileModel::whereIn('user_id', $ownerIds)->get()->keyBy('user_id');

        // Semua listing adopsi (dikelompokkan per adopsi) untuk kontrol
        $allAdoptions = AdoptionModel::with(['collection.sugarglider', 'owner', 'requests'])
            ->withCount('requests as total_permohonan')
            ->latest('updated_at')
            ->paginate(20, ['*'], 'page_all');

        return view('admin.adoptions.v_adoptions', compact('pendingPayment', 'pendingDisbursement', 'profiles', 'allAdoptions'));
    }

    // Admin lihat semua permohonan untuk satu listing adopsi
    public function showRequests($id)
    {
        $sugarglider = SugargliderModel::select(
                'adoptions.id as id',
                'adoptions.harga as harga',
                'sugargliders.nama as nama',
                'sugargliders.jenis as jenis'
            )
            ->leftJoin('collections', 'collections.sugarglider_id', '=', 'sugargliders.id')
            ->leftJoin('adoptions', 'adoptions.collection_id', '=', 'collections.id')
            ->where('adoptions.id', $id)
            ->firstOrFail();

        $adoptionrequests = AdoptionRequestModel::select(
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
            ->where('adoption_id', $id)
            ->leftJoin('users', 'users.id', '=', 'adoption_requests.user_id')
            ->leftJoin('shelters', 'shelters.id', '=', 'adoption_requests.shelter_id')
            ->leftJoin('profiles as applicant_profiles', 'applicant_profiles.user_id', '=', 'adoption_requests.user_id')
            ->paginate(10);

        return view('admin.adoptions.v_adoption_requests', compact('sugarglider', 'adoptionrequests'));
    }

    // Admin konfirmasi pembayaran diterima → pemilik boleh kirim
    public function confirmPayment($id)
    {
        $adoptionrequest = AdoptionRequestModel::where('id', $id)
            ->where('status', AdoptionRequestStatus::DIBAYAR->value)
            ->whereNull('confirmed_at')
            ->firstOrFail();

        $adoptionrequest->confirmed_at = Carbon::now();
        $adoptionrequest->save();

        $adoptionrequest->load('adoption.collection.sugarglider', 'adoption.owner', 'applicant');
        $sgNama = $adoptionrequest->adoption?->collection?->sugarglider?->nama ?? 'Sugar Glider';

        // Notif ke pemilik: pembayaran dikonfirmasi, silakan kirim SG
        $adoptionrequest->adoption?->owner?->notify(new AdoptionNotification(
            title: 'Pembayaran Dikonfirmasi',
            body:  "Admin mengkonfirmasi pembayaran untuk adopsi {$sgNama}. Silakan kirimkan sugar glider ke pemohon.",
            url:   route('adoption.request', $adoptionrequest->adoption_id),
            icon:  'bi-truck',
        ));

        // Notif ke pemohon: pembayaran diterima, tunggu pengiriman
        $adoptionrequest->applicant?->notify(new AdoptionNotification(
            title: 'Pembayaran Diterima',
            body:  "Pembayaran adopsi {$sgNama} telah dikonfirmasi admin. Pemilik akan segera mengirimkan sugar glider.",
            url:   route('adoption.list'),
            icon:  'bi-shield-check',
        ));

        return redirect()->route('admin.adoptions.index')
            ->with('pesan', 'Pembayaran dikonfirmasi. Pemilik kini dapat melakukan pengiriman.');
    }

    // Admin tandai dana sudah dicairkan ke pemilik
    public function disburse($id)
    {
        $adoptionrequest = AdoptionRequestModel::where('id', $id)
            ->where('status', AdoptionRequestStatus::SELESAI->value)
            ->whereNull('disbursed_at')
            ->firstOrFail();

        $adoptionrequest->disbursed_at = Carbon::now();
        $adoptionrequest->save();

        $adoptionrequest->load('adoption.collection.sugarglider', 'adoption.owner');
        $sgNama = $adoptionrequest->adoption?->collection?->sugarglider?->nama ?? 'Sugar Glider';

        // Notif ke pemilik: dana sudah dicairkan
        $adoptionrequest->adoption?->owner?->notify(new AdoptionNotification(
            title: 'Dana Adopsi Dicairkan',
            body:  "Dana hasil adopsi {$sgNama} sudah dicairkan ke rekening Anda. Terima kasih!",
            url:   route('adoption.index'),
            icon:  'bi-check-circle',
        ));

        return redirect()->route('admin.adoptions.index')
            ->with('pesan', 'Dana berhasil ditandai sudah dicairkan ke pemilik.');
    }
}
