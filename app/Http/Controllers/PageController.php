<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SugargliderModel;
use App\Models\ShelterModel;
use App\Models\AdoptionModel;
use App\Models\CollectionModel;
use App\Models\Testimonial;
use App\Models\AppConfig;
use App\Enums\CollectionStatus;
use App\Enums\AdoptionStatus;
use Carbon\Carbon;

class PageController extends Controller
{
    function index()
    {
        $heroItems = SugargliderModel::whereNotNull('gambar')
            ->where('gambar', '!=', '')
            ->inRandomOrder()
            ->limit(3)
            ->get()
            ->map(function ($sg) {
                $months = $sg->tgl_lahir
                    ? Carbon::parse($sg->tgl_lahir)->diffInMonths(now())
                    : null;
                $sg->usia_str = $months !== null
                    ? ($months >= 12 ? floor($months / 12) . ' thn' : $months . ' bln')
                    : null;
                return $sg;
            });

        $galleryItems = SugargliderModel::whereNotNull('gambar')
            ->where('gambar', '!=', '')
            ->inRandomOrder()
            ->limit(8)
            ->get();

        $testimonials = Testimonial::aktif()->get();

        $sgAvatars = SugargliderModel::whereNotNull('gambar')
            ->where('gambar', '!=', '')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $featuredShelter = ShelterModel::where('status', '1')
            ->whereHas('sugargliders', fn ($q) => $q->whereNotNull('gambar')->where('gambar', '!=', ''))
            ->with(['sugargliders' => fn ($q) => $q->whereNotNull('gambar')->where('gambar', '!=', '')->inRandomOrder()->limit(6)])
            ->inRandomOrder()
            ->first();

        $data = [
            'count_sugargliders'    => SugargliderModel::count(),
            'count_shelters'        => ShelterModel::count(),
            'count_users'           => User::count(),
            'count_collections'     => CollectionModel::whereIn('status', [CollectionStatus::PUBLIK->value, CollectionStatus::ADOPSI->value])->count(),
            'count_adoptions'       => AdoptionModel::where('status', AdoptionStatus::AKTIF->value)->count(),
            'shelters'              => ShelterModel::where('status', '1')->inRandomOrder()->limit(6)->get(),
            'gallery_items'         => $galleryItems,
            'we_have_item'          => $galleryItems->isNotEmpty() ? $galleryItems->last() : null,
            'hero_items'            => $heroItems,
            'testimonials'          => $testimonials,
            'sg_avatars'            => $sgAvatars,
            'featured_shelter'      => $featuredShelter,
        ];

        return view('pages/v_home', $data);
    }
    function create()
    {
    }
    function store()
    {
    }
    function show()
    {
    }
    function edit()
    {
    }
    function update()
    {
    }
    function destroy()
    {
    }

    function adoptionGuide()
    {
        return view('pages.v_adoption_guide');
    }

    function about()
    {
        $data = [
            'shelters'      => ShelterModel::where('status', '1')->get(),
            'about_heading' => AppConfig::get('about_heading'),
            'about_intro'   => AppConfig::get('about_intro'),
            'about_content' => AppConfig::get('about_content'),
        ];
        return view('pages/v_about', $data);
    }
}
