<?php

namespace App\Enums;

enum AdoptionStatus: int
{
    case NONAKTIF = 0;
    case AKTIF    = 1;
    case SELESAI  = 9;

    public function label(): string
    {
        return match($this) {
            self::NONAKTIF => 'Nonaktif',
            self::AKTIF    => 'Aktif',
            self::SELESAI  => 'Selesai',
        };
    }
}
