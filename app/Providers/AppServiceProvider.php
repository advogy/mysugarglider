<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\SugargliderModel;
use App\Models\ShelterModel;
use App\Models\CollectionModel;
use App\Models\ProfileModel;
use App\Models\User;
use App\Observers\ProfileObserver;
use App\Observers\ShelterObserver;
use App\Observers\SugargliderObserver;
use App\Observers\CollectionObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::defaultView('default');

        ProfileModel::observe(ProfileObserver::class);
        ShelterModel::observe(ShelterObserver::class);
        SugargliderModel::observe(SugargliderObserver::class);
        CollectionModel::observe(CollectionObserver::class);

        View::composer(['layouts.v_auth', 'pages.v_about'], function ($view) {
            $view->with([
                'stat_sg'      => SugargliderModel::count(),
                'stat_shelter' => ShelterModel::count(),
                'stat_user'    => User::count(),
            ]);
        });
    }
}
