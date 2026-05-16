<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardItem extends Model
{
    protected $table = 'reward_items';

    protected $fillable = [
        'kode', 'nama', 'deskripsi', 'kategori',
        'poin_required', 'diskon_persen',
        'stok', 'aktif', 'gambar',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function redemptions()
    {
        return $this->hasMany(Redemption::class);
    }

    public function isAvailable(): bool
    {
        return $this->aktif && ($this->stok === null || $this->stok > 0);
    }

    public function isDigital(): bool
    {
        return $this->kategori === 'diskon_adopsi';
    }
}
