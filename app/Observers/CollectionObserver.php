<?php

namespace App\Observers;

use App\Enums\PointType;
use App\Models\CollectionModel;
use App\Services\PointService;

class CollectionObserver
{
    public function created(CollectionModel $collection): void
    {
        $user = \App\Models\User::find($collection->user_id);
        if (!$user) return;

        app(PointService::class)->earn($user, PointType::COLLECTION_CREATE, $collection);
    }
}
