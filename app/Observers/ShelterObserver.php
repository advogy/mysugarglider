<?php

namespace App\Observers;

use App\Enums\PointType;
use App\Models\ShelterModel;
use App\Models\PointLog;
use App\Services\PointService;

class ShelterObserver
{
    public function created(ShelterModel $shelter): void
    {
        $user = $shelter->user ?? \App\Models\User::find($shelter->user_id);
        if (!$user) return;

        $maxBonus = (int) (\App\Models\PointConfig::where('key', 'shelter_max_bonus')->value('value') ?? 5);
        $existing = PointLog::where('user_id', $user->id)
            ->where('type', PointType::SHELTER_CREATE->value)
            ->count();

        if ($existing >= $maxBonus) return;

        app(PointService::class)->earn($user, PointType::SHELTER_CREATE, $shelter);
    }
}
