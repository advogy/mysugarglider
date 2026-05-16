<?php

namespace App\Enums;

enum CollectionStatus: int
{
    case PRIVAT  = 1;
    case PUBLIK  = 2;
    case ADOPSI  = 3;
    case MATI    = 4;
    case SELESAI = 5;

    public function label(): string
    {
        return match($this) {
            self::PRIVAT  => 'Privat',
            self::PUBLIK  => 'Publik',
            self::ADOPSI  => 'Adopsi',
            self::MATI    => 'Mati',
            self::SELESAI => 'Selesai',
        };
    }
}
