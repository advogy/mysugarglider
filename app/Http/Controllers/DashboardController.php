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

class DashboardController extends Controller
{
    public function index()
    {
        $profile = ProfileModel::where('user_id', Auth::id())->first();

        $user = User::find(Auth::id());

        $data = [
            'user'    => $user,
            'profile' => $profile,
            'profile_done' => $profile && $profile->telepon && $profile->alamat,
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
