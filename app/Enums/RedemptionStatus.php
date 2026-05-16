<?php

namespace App\Enums;

enum RedemptionStatus: string
{
    case PENDING   = 'pending';
    case APPROVED  = 'approved';
    case USED      = 'used';
    case EXPIRED   = 'expired';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING   => 'Menunggu',
            self::APPROVED  => 'Disetujui',
            self::USED      => 'Digunakan',
            self::EXPIRED   => 'Kadaluarsa',
            self::CANCELLED => 'Dibatalkan',
        };
    }
}
