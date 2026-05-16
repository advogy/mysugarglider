<?php

namespace App\Observers;

use App\Enums\PointType;
use App\Models\ProfileModel;
use App\Services\PointService;

class ProfileObserver
{
    public function saved(ProfileModel $profile): void
    {
        if (!$profile->telepon || !$profile->alamat) return;

        $user = $profile->user;
        if (!$user) return;

        app(PointService::class)->earn($user, PointType::PROFILE_COMPLETE, $profile);
    }
}
