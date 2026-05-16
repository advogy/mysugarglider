<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    protected $table = 'redemptions';

    protected $fillable = [
        'user_id', 'reward_item_id', 'poin_used', 'kategori',
        'status', 'kode_klaim', 'diskon_persen',
        'alamat_pengiriman', 'catatan',
        'approved_at', 'claimed_at', 'expired_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'claimed_at'  => 'datetime',
        'expired_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rewardItem()
    {
        return $this->belongsTo(RewardItem::class);
    }

    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }

    public function isUsable(): bool
    {
        return $this->status === 'approved' && !$this->isExpired();
    }
}
