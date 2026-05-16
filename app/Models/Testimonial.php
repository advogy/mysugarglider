<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = ['user_id', 'quote', 'author', 'durasi', 'urutan', 'aktif', 'status'];

    protected $casts = ['aktif' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true)
                     ->where('status', self::STATUS_APPROVED)
                     ->orderBy('urutan');
    }

    public function isPending(): bool  { return $this->status === self::STATUS_PENDING; }
    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
    public function isRejected(): bool { return $this->status === self::STATUS_REJECTED; }
}
