<?php

namespace App\Enums;

enum PointType: string
{
    case PROFILE_COMPLETE   = 'profile_complete';
    case SHELTER_CREATE     = 'shelter_create';
    case SG_CREATE          = 'sg_create';
    case SG_PHOTO           = 'sg_photo';
    case SG_PEDIGREE        = 'sg_pedigree';
    case COLLECTION_CREATE  = 'collection_create';
    case ADOPTION_OPEN      = 'adoption_open';
    case ADOPTION_SOLD      = 'adoption_sold';
    case ADOPTION_RECEIVED  = 'adoption_received';
    case TESTIMONIAL        = 'testimonial';

    public function points(): int
    {
        return match($this) {
            self::PROFILE_COMPLETE  => 50,
            self::SHELTER_CREATE    => 25,
            self::SG_CREATE         => 30,
            self::SG_PHOTO          => 10,
            self::SG_PEDIGREE       => 15,
            self::COLLECTION_CREATE => 10,
            self::ADOPTION_OPEN     => 20,
            self::ADOPTION_SOLD     => 100,
            self::ADOPTION_RECEIVED => 75,
            self::TESTIMONIAL       => 50,
        };
    }

    public function label(): string
    {
        return match($this) {
            self::PROFILE_COMPLETE  => 'Profil dilengkapi',
            self::SHELTER_CREATE    => 'Tambah kandang',
            self::SG_CREATE         => 'Input Sugar Glider',
            self::SG_PHOTO          => 'SG dilengkapi foto',
            self::SG_PEDIGREE       => 'Indukan SG diisi',
            self::COLLECTION_CREATE => 'Tambah penempatan',
            self::ADOPTION_OPEN     => 'Buka adopsi',
            self::ADOPTION_SOLD     => 'SG berhasil diadopsi',
            self::ADOPTION_RECEIVED => 'Berhasil mengadopsi',
            self::TESTIMONIAL       => 'Menulis testimoni',
        };
    }
}
