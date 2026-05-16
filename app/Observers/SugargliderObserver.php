<?php

namespace App\Observers;

use App\Enums\PointType;
use App\Models\SugargliderModel;
use App\Services\PointService;

class SugargliderObserver
{
    public function created(SugargliderModel $sg): void
    {
        $user = \App\Models\User::find($sg->user_id);
        if (!$user) return;

        $svc = app(PointService::class);

        $svc->earn($user, PointType::SG_CREATE, $sg);

        if ($sg->gambar) {
            $svc->earn($user, PointType::SG_PHOTO, $sg);
        }

        if ($sg->indukan_jantan || $sg->indukan_betina) {
            $svc->earn($user, PointType::SG_PEDIGREE, $sg);
        }
    }

    public function updated(SugargliderModel $sg): void
    {
        $user = \App\Models\User::find($sg->user_id);
        if (!$user) return;

        $svc = app(PointService::class);

        if ($sg->wasChanged('gambar') && $sg->gambar) {
            $svc->earn($user, PointType::SG_PHOTO, $sg);
        }

        if ($sg->wasChanged(['indukan_jantan', 'indukan_betina'])
            && ($sg->indukan_jantan || $sg->indukan_betina)) {
            $svc->earn($user, PointType::SG_PEDIGREE, $sg);
        }
    }
}
