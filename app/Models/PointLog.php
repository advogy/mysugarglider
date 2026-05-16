<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointLog extends Model
{
    protected $table = 'point_logs';

    protected $fillable = [
        'user_id', 'type', 'points',
        'subject_type', 'subject_id',
        'note', 'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
        });
    }

    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }
}
