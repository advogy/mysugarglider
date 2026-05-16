<?php

namespace App\Services;

use App\Enums\PointType;
use App\Enums\RedemptionStatus;
use App\Models\PointLog;
use App\Models\Redemption;
use App\Models\RewardItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PointService
{
    /**
     * Berikan poin ke user untuk sebuah aktivitas.
     * Jika $subject diberikan, cek duplikat — satu objek hanya memberi poin sekali.
     */
    public function earn(User $user, PointType $type, Model $subject = null, string $note = null): ?PointLog
    {
        if ($subject) {
            $exists = PointLog::where('user_id', $user->id)
                ->where('type', $type->value)
                ->where('subject_type', get_class($subject))
                ->where('subject_id', $subject->id)
                ->exists();

            if ($exists) return null;
        }

        $log = PointLog::create([
            'user_id'      => $user->id,
            'type'         => $type->value,
            'points'       => $type->points(),
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject ? $subject->id : null,
            'note'         => $note ?? $type->label(),
            'expired_at'   => now()->addYear(),
        ]);

        $user->increment('total_points', $type->points());

        return $log;
    }

    /**
     * Hitung poin tersedia (belum expired, sudah dikurangi redemption).
     */
    public function available(User $user): int
    {
        return (int) PointLog::where('user_id', $user->id)
            ->where(function ($q) {
                $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
            })
            ->sum('points');
    }

    /**
     * Tukar poin dengan reward item.
     */
    public function redeem(User $user, RewardItem $item, string $alamat = null): Redemption
    {
        $available = $this->available($user);

        if ($available < $item->poin_required) {
            throw new \Exception('Poin tidak mencukupi. Poin tersedia: ' . $available);
        }

        if (!$item->isAvailable()) {
            throw new \Exception('Reward ini tidak tersedia atau stok habis.');
        }

        // Kurangi poin
        PointLog::create([
            'user_id'      => $user->id,
            'type'         => 'redeem_' . $item->kategori,
            'points'       => -$item->poin_required,
            'subject_type' => RewardItem::class,
            'subject_id'   => $item->id,
            'note'         => 'Penukaran: ' . $item->nama,
            'expired_at'   => null,
        ]);

        // Kurangi stok jika terbatas
        if ($item->stok !== null) {
            $item->decrement('stok');
        }

        // Auto-approve untuk diskon digital
        $isDigital  = $item->isDigital();
        $kodeKlaim  = $isDigital ? strtoupper(Str::random(8)) : null;
        $approvedAt = $isDigital ? now() : null;
        $expiredAt  = $isDigital ? now()->addDays((int) $this->config('kode_klaim_expired', 30)) : null;

        return Redemption::create([
            'user_id'           => $user->id,
            'reward_item_id'    => $item->id,
            'poin_used'         => $item->poin_required,
            'kategori'          => $item->kategori,
            'status'            => $isDigital ? RedemptionStatus::APPROVED->value : RedemptionStatus::PENDING->value,
            'kode_klaim'        => $kodeKlaim,
            'diskon_persen'     => $item->diskon_persen,
            'alamat_pengiriman' => $alamat,
            'approved_at'       => $approvedAt,
            'expired_at'        => $expiredAt,
        ]);
    }

    /**
     * Validasi dan terapkan kode diskon ke adoption request.
     * Kembalikan array ['diskon_amount' => int, 'harga_final' => int].
     */
    public function applyDiscountCode(string $kode, int $harga, User $user): array
    {
        $redemption = Redemption::where('kode_klaim', strtoupper($kode))
            ->where('user_id', $user->id)
            ->where('status', RedemptionStatus::APPROVED->value)
            ->where('kategori', 'diskon_adopsi')
            ->first();

        if (!$redemption) {
            throw new \Exception('Kode diskon tidak valid atau bukan milik Anda.');
        }

        if ($redemption->isExpired()) {
            throw new \Exception('Kode diskon sudah kadaluarsa.');
        }

        $maxPersen    = (int) $this->config('diskon_max_persen', 30);
        $persen       = min($redemption->diskon_persen, $maxPersen);
        $diskonAmount = (int) round($harga * $persen / 100);
        $hargaFinal   = max(0, $harga - $diskonAmount);

        return [
            'redemption'   => $redemption,
            'diskon_amount' => $diskonAmount,
            'harga_final'  => $hargaFinal,
            'persen'       => $persen,
        ];
    }

    /**
     * Tandai kode diskon sebagai sudah digunakan.
     */
    public function markDiscountUsed(Redemption $redemption): void
    {
        $redemption->update([
            'status'     => RedemptionStatus::USED->value,
            'claimed_at' => now(),
        ]);
    }

    private function config(string $key, mixed $default = null): mixed
    {
        $row = \App\Models\PointConfig::where('key', $key)->first();
        return $row ? $row->value : $default;
    }
}
