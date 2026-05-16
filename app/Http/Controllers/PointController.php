<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PointLog;
use App\Models\RewardItem;
use App\Models\Redemption;
use App\Services\PointService;
use App\Enums\RedemptionStatus;

class PointController extends Controller
{
    function index()
    {
        $user = Auth::user();

        $data = [
            'user'          => $user,
            'available'     => app(PointService::class)->available($user),
            'level'         => $user->level(),
            'recent_logs'   => PointLog::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get(),
            'rewards'       => RewardItem::where('aktif', true)->orderBy('poin_required', 'asc')->get(),
            'redemptions'   => Redemption::where('user_id', $user->id)
                                ->with('rewardItem')
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get(),
        ];

        return view('points.v_backend_points_index', $data);
    }

    function redeem(Request $request)
    {
        $request->validate([
            'reward_item_id' => 'required|exists:reward_items,id',
            'alamat'         => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $item = RewardItem::findOrFail($request->reward_item_id);

        try {
            $redemption = app(PointService::class)->redeem($user, $item, $request->alamat);
        } catch (\Exception $e) {
            return redirect()->route('points.index')->with('error', $e->getMessage());
        }

        $msg = $item->kategori === 'diskon_adopsi'
            ? "Penukaran berhasil! Kode diskon Anda: {$redemption->kode}"
            : 'Penukaran berhasil! Admin akan memproses permintaan Anda.';

        return redirect()->route('points.index')->with('pesan', $msg);
    }

    function history()
    {
        $user = Auth::user();

        $data = [
            'logs'        => PointLog::where('user_id', $user->id)
                              ->orderBy('created_at', 'desc')
                              ->paginate(20),
            'redemptions' => Redemption::where('user_id', $user->id)
                              ->with('rewardItem')
                              ->orderBy('created_at', 'desc')
                              ->paginate(20),
        ];

        return view('points.v_backend_points_history', $data);
    }

    function apply_discount(Request $request, $adoption_request_id)
    {
        $request->validate(['kode' => 'required|string']);

        $user = Auth::user();

        try {
            $result = app(PointService::class)->applyDiscountCode($request->kode, 0, $user);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('discount', $result);
    }
}
