<?php

namespace App\Enums;

enum CollectionStatus: int
{
    case NONAKTIF = 0;
    case AKTIF    = 1;
    case PUBLIK   = 2;
    case ADOPSI   = 3;
    case SELESAI  = 9;

    public function label(): string
    {
        return match($this) {
            self::NONAKTIF => 'Nonaktif',
            self::AKTIF    => 'Aktif',
            self::PUBLIK   => 'Publik',
            self::ADOPSI   => 'Adopsi',
            self::SELESAI  => 'Selesai',
        };
    }
}
