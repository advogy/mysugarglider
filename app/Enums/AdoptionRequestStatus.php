<?php

namespace App\Enums;

enum AdoptionRequestStatus: int
{
    case MENUNGGU    = 1;
    case DIBATALKAN  = 2;
    case DITOLAK     = 4;
    case DIPILIH  = 5;
    case DIBAYAR  = 6; // payment step — referenced in view, controller belum diimplementasi
    case DIKIRIM  = 7;
    case SELESAI  = 8;

    public function label(): string
    {
        return match($this) {
            self::MENUNGGU   => 'Menunggu',
            self::DIBATALKAN => 'Dibatalkan',
            self::DITOLAK    => 'Tidak Terpilih',
            self::DIPILIH  => 'Terpilih - Pembayaran',
            self::DIBAYAR  => 'Terpilih - Pengiriman',
            self::DIKIRIM  => 'Terpilih - Terima',
            self::SELESAI  => 'Terpilih - Selesai',
        };
    }
}
