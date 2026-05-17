<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ProfileModel;
use App\Models\SugargliderModel;
use App\Models\ShelterModel;
use App\Models\CollectionModel;
use App\Models\AdoptionModel;
use App\Models\Testimonial;
use App\Enums\AdoptionStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());

        if ($user->isAdmin()) {
            $data = [
                'user'                       => $user,
                'count_users'                => User::where('id', '!=', Auth::id())->count(),
                'count_shelters'             => ShelterModel::count(),
                'count_sugargliders'         => SugargliderModel::count(),
                'count_collections'          => CollectionModel::where('status', '!=', \App\Enums\CollectionStatus::SELESAI->value)->count(),
                'count_adoptions'            => AdoptionModel::where('status', AdoptionStatus::AKTIF->value)->count(),
                'count_testimonials_pending' => Testimonial::where('status', 'pending')->count(),
            ];
            return view('dashboard.v_admin', $data);
        }

        $profile = ProfileModel::where('user_id', Auth::id())->first();

        $data = [
            'user'    => $user,
            'profile' => $profile,
            'profile_done' => $profile && $profile->telepon && $profile->alamat && $profile->kode_profil,
            'count_sugargliders'    => SugargliderModel::where('user_id', Auth::id())->count(),
            'count_shelters'        => ShelterModel::where('user_id', Auth::id())->count(),
            'count_collections'     => CollectionModel::where('user_id', Auth::id())->where('status', '!=', \App\Enums\CollectionStatus::SELESAI->value)->count(),
            'count_adoptions'       => AdoptionModel::where('user_id', Auth::id())->count(),
            'count_adoptable'       => AdoptionModel::where('user_id', '!=', Auth::id())->where('status', 1)->count(),
            'total_points'          => $user->total_points ?? 0,
            'level'                 => $user->level(),
            'my_testimonial'        => Testimonial::where('user_id', Auth::id())->first(),
        ];
        return view('dashboard.v_index', $data);
    }
}
