<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\RedemptionStatus;
use App\Models\PointConfig;
use App\Models\PointLog;
use App\Models\Redemption;
use App\Models\RewardItem;
use App\Models\User;
use App\Services\PointService;
use Illuminate\Http\Request;

class AdminPointController extends Controller
{
    // ── Daftar User & Poin ────────────────────────────────────────────────

    public function users(Request $request)
    {
        $search = trim($request->get('q', ''));

        $query = User::where('role', 'user')
            ->orderByDesc('total_points');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return view('admin.points.v_users', [
            'users'  => $query->paginate(20)->appends($request->query()),
            'search' => $search,
        ]);
    }

    public function userDetail(User $user)
    {
        $logs = PointLog::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(20, ['*'], 'logs_page');

        $redemptions = Redemption::where('user_id', $user->id)
            ->with('rewardItem')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'red_page');

        return view('admin.points.v_user_detail', [
            'user'        => $user,
            'level'       => $user->level(),
            'available'   => app(PointService::class)->available($user),
            'logs'        => $logs,
            'redemptions' => $redemptions,
        ]);
    }

    // ── Kelola Penukaran ──────────────────────────────────────────────────

    public function redemptions(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = Redemption::with(['user', 'rewardItem'])
            ->orderByDesc('created_at');

        if (in_array($status, ['pending', 'approved', 'cancelled', 'used', 'expired'])) {
            $query->where('status', $status);
        }

        return view('admin.points.v_redemptions', [
            'redemptions' => $query->paginate(20)->appends($request->query()),
            'status'      => $status,
            'counts'      => [
                'pending'   => Redemption::where('status', 'pending')->count(),
                'approved'  => Redemption::where('status', 'approved')->count(),
                'used'      => Redemption::where('status', 'used')->count(),
                'cancelled' => Redemption::where('status', 'cancelled')->count(),
            ],
        ]);
    }

    public function approveRedemption(Request $request, Redemption $redemption)
    {
        if ($redemption->status !== RedemptionStatus::PENDING->value) {
            return back()->with('error', 'Hanya penukaran berstatus pending yang dapat disetujui.');
        }

        $redemption->update([
            'status'      => RedemptionStatus::APPROVED->value,
            'catatan'     => $request->catatan,
            'approved_at' => now(),
        ]);

        return back()->with('pesan', 'Penukaran disetujui.');
    }

    public function cancelRedemption(Request $request, Redemption $redemption)
    {
        if (!in_array($redemption->status, [RedemptionStatus::PENDING->value, RedemptionStatus::APPROVED->value])) {
            return back()->with('error', 'Penukaran ini tidak dapat dibatalkan.');
        }

        $redemption->load('user', 'rewardItem');

        app(PointService::class)->refund($redemption->user, $redemption);

        $redemption->update([
            'status'  => RedemptionStatus::CANCELLED->value,
            'catatan' => $request->catatan ?: $redemption->catatan,
        ]);

        return back()->with('pesan', 'Penukaran dibatalkan dan poin dikembalikan.');
    }

    // ── Reward Items ──────────────────────────────────────────────────────

    public function rewards()
    {
        return view('admin.points.v_rewards', [
            'rewards' => RewardItem::orderBy('kategori')->orderBy('poin_required')->get(),
        ]);
    }

    public function createReward()
    {
        return view('admin.points.v_reward_form', ['reward' => null]);
    }

    public function storeReward(Request $request)
    {
        $data = $request->validate([
            'kode'           => 'required|string|max:20|unique:reward_items,kode',
            'nama'           => 'required|string|max:255',
            'deskripsi'      => 'nullable|string|max:500',
            'kategori'       => 'required|in:diskon_adopsi,souvenir,keperluan_sg',
            'poin_required'  => 'required|integer|min:1',
            'diskon_persen'  => 'nullable|integer|min:1|max:100',
            'stok'           => 'nullable|integer|min:0',
            'aktif'          => 'boolean',
        ]);

        $data['aktif'] = $request->boolean('aktif', true);

        RewardItem::create($data);

        return redirect()->route('admin.points.rewards')->with('pesan', 'Reward berhasil ditambahkan.');
    }

    public function editReward(RewardItem $reward)
    {
        return view('admin.points.v_reward_form', ['reward' => $reward]);
    }

    public function updateReward(Request $request, RewardItem $reward)
    {
        $data = $request->validate([
            'kode'          => 'required|string|max:20|unique:reward_items,kode,' . $reward->id,
            'nama'          => 'required|string|max:255',
            'deskripsi'     => 'nullable|string|max:500',
            'kategori'      => 'required|in:diskon_adopsi,souvenir,keperluan_sg',
            'poin_required' => 'required|integer|min:1',
            'diskon_persen' => 'nullable|integer|min:1|max:100',
            'stok'          => 'nullable|integer|min:0',
            'aktif'         => 'boolean',
        ]);

        $data['aktif'] = $request->boolean('aktif', false);

        $reward->update($data);

        return redirect()->route('admin.points.rewards')->with('pesan', 'Reward berhasil diperbarui.');
    }

    public function destroyReward(RewardItem $reward)
    {
        if ($reward->redemptions()->exists()) {
            return back()->with('error', 'Reward tidak dapat dihapus karena sudah pernah ditukarkan.');
        }

        $reward->delete();

        return back()->with('pesan', 'Reward dihapus.');
    }

    // ── Konfigurasi Poin ──────────────────────────────────────────────────

    public function configs()
    {
        return view('admin.points.v_configs', [
            'configs' => PointConfig::orderBy('key')->get(),
        ]);
    }

    public function updateConfigs(Request $request)
    {
        $request->validate([
            'configs'   => 'required|array',
            'configs.*' => 'required|string|max:100',
        ]);

        foreach ($request->configs as $key => $value) {
            PointConfig::where('key', $key)->update(['value' => $value]);
        }

        return back()->with('pesan', 'Konfigurasi berhasil disimpan.');
    }
}
