<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SugargliderController;
use App\Http\Controllers\ShelterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PedigreeController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\AdoptionRequestController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\TestimonialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PageController::class, 'index'])->name('index');
Route::get('/home', [PageController::class, 'index'])->name('home');

// Login & Register (definisi manual — hindari konflik nama route dari Auth::routes())
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware(['DisableBackBtn', 'guest']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate')->middleware('throttle:5,1');
Route::get('/password-forget', [LoginController::class, 'password_forget'])->name('password.forget')->middleware(['DisableBackBtn', 'guest']);
Route::post('/password-link', [LoginController::class, 'password_link'])->name('password.link')->middleware('DisableBackBtn', 'guest');
Route::get('/password-reset/{token}', [LoginController::class, 'password_reset_form'])->name('password.reset')->middleware('guest');
Route::post('/password-reset', [LoginController::class, 'password_reset'])->name('password.reset.action')->middleware('guest');

//only authenticated can access this group
Route::group(['middleware' => ['auth']], function () {

    /**
     * Logout Routes
     */
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    /**
     * Verification Routes
     */
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

    //only verified account can access with this group
    Route::group(['middleware' => ['verified']], function () {
        /**
         * Dashboard Routes
         */
        Route::get('/my', [DashboardController::class, 'index'])->name('dashboard.index');

        /**
         * Profile Routes
         */
        Route::get('/my/profile', [ProfileController::class, 'show'])->name('profile');
        Route::post('/my/profile', [ProfileController::class, 'update_profile'])->name('profile.update');
        Route::post('/my/profile/user', [ProfileController::class, 'update_user'])->name('profile.update.user');
        Route::post('/my/password', [ProfileController::class, 'password_change'])->name('profile.password.change');
        Route::post('/my/profile/avatar', [ProfileController::class, 'update_avatar'])->name('profile.update.avatar');

        /**
         * Shelter Routes
         */
        Route::get('/my/shelters', [ShelterController::class, 'backend_shelters_index'])->name('shelter.index');
        Route::get('/my/shelters/create', [ShelterController::class, 'create'])->name('shelter.create');
        Route::post('/my/shelters', [ShelterController::class, 'store'])->name('shelter.store');
        Route::get('/my/shelters/{id}/edit', [ShelterController::class, 'edit'])->name('shelter.edit');
        Route::put('/my/shelters/{id}', [ShelterController::class, 'update'])->name('shelter.update');
        Route::delete('/my/shelters/{id}', [ShelterController::class, 'destroy'])->name('shelter.destroy');

        /**
         * Sugar Glider Routes
         */
        Route::get('/my/sugargliders', [SugargliderController::class, 'backend_sugarglider_index'])->name('sugarglider.index');
        Route::get('/my/sugargliders/create', [SugargliderController::class, 'create'])->name('sugarglider.create');
        Route::get('/my/sugargliders/parents', [SugargliderController::class, 'parents'])->name('sugarglider.parents');
        Route::post('/my/sugargliders', [SugargliderController::class, 'store'])->name('sugarglider.store');
        Route::get('/my/sugargliders/{id}', [SugargliderController::class, 'backend_show'])->name('sugarglider.backend.show');
        Route::get('/my/sugargliders/{id}/edit', [SugargliderController::class, 'edit'])->name('sugarglider.edit');
        Route::put('/my/sugargliders/{id}', [SugargliderController::class, 'update'])->name('sugarglider.update');
        Route::delete('/my/sugargliders/{id}', [SugargliderController::class, 'destroy'])->name('sugarglider.destroy');

        /**
         * Collections Routes
         */
        Route::get('/my/collections', [CollectionController::class, 'backend_collection_index'])->name('collection.index');
        Route::get('/my/collections/create', [CollectionController::class, 'create'])->name('collection.create');
        Route::post('/my/collections', [CollectionController::class, 'store'])->name('collection.store');
        Route::get('/my/collections/{id}/edit', [CollectionController::class, 'edit'])->name('collection.edit');
        Route::put('/my/collections/{id}', [CollectionController::class, 'update'])->name('collection.update');
        Route::delete('/my/collections/{id}', [CollectionController::class, 'destroy'])->name('collection.destroy');

        /**
         * Pedigree Sugar Glider Routes
         */
        Route::get('/my/pedigree', [PedigreeController::class, 'backend_pedigree_index'])->name('pedigree.index');
        Route::get('/my/pedigree/{id}', [PedigreeController::class, 'backend_show'])->name('pedigree.backend.show');

        /**
         * Adoption Sugar Glider Routes
         */
        Route::get('/my/adoptions', [AdoptionController::class, 'backend_adoption_index'])->name('adoption.index');
        Route::get('/my/adoptions/create', [AdoptionController::class, 'create'])->name('adoption.create');
        Route::post('/my/adoptions', [AdoptionController::class, 'store'])->name('adoption.store');
        Route::get('/my/adoptions/list', [AdoptionController::class, 'backend_adoption_list'])->name('adoption.list');
        Route::get('/my/adoptions/{id}/request', [AdoptionController::class, 'backend_adoption_request'])->name('adoption.request');
        Route::get('/my/adoptions/{id}/edit', [AdoptionController::class, 'edit'])->name('adoption.edit');
        Route::put('/my/adoptions/{id}', [AdoptionController::class, 'update'])->name('adoption.update');

        /**
         * Adoption Request Routes
         */
        Route::post('/my/adoptions/{id}/request', [AdoptionRequestController::class, 'store'])->name('adoptionrequest.store');
        Route::post('/my/adoptions/select', [AdoptionRequestController::class, 'backend_adoption_select'])->name('adoptionrequest.select');
        Route::post('/my/adoptionrequests/{id}/upload-payment', [AdoptionRequestController::class, 'upload_payment'])->name('adoptionrequest.upload-payment');
        Route::post('/my/adoptionrequests/{id}/confirm-free', [AdoptionRequestController::class, 'confirm_free'])->name('adoptionrequest.confirm-free');
        Route::post('/my/adoptionrequests/{id}/confirm-payment', [AdoptionRequestController::class, 'confirm_payment'])->name('adoptionrequest.confirm-payment');
        Route::post('/my/adoptions/{id}/shipping', [AdoptionRequestController::class, 'backend_adoption_shipping'])->name('adoptionrequest.shipping');
        Route::post('/my/adoptions/{id}/finalize', [AdoptionRequestController::class, 'backend_adoption_finalize'])->name('adoptionrequest.finalize');

        Route::get('/my/points', [PointController::class, 'index'])->name('points.index');
        Route::post('/my/points/redeem', [PointController::class, 'redeem'])->name('points.redeem');
        Route::get('/my/points/history', [PointController::class, 'history'])->name('points.history');

        Route::post('/my/testimonial', [TestimonialController::class, 'store'])->name('testimonial.store');
        Route::delete('/my/testimonial/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonial.destroy');

        Route::get('/admin/testimonials', [TestimonialController::class, 'adminIndex'])->name('testimonial.admin');
        Route::post('/admin/testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('testimonial.approve');
        Route::post('/admin/testimonials/{testimonial}/reject', [TestimonialController::class, 'reject'])->name('testimonial.reject');
    });
});


Route::get('/shelters', [ShelterController::class, 'index'])->name('shelters');
Route::get('/shelters/{id}', [ShelterController::class, 'show'])->name('shelter.show');

Route::get('/sugargliders/{id}', [SugargliderController::class, 'show'])->name('sugarglider.show');

Route::get('/collections', [CollectionController::class, 'index'])->name('collections');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::post('/contact', [ContactController::class, 'contactPost'])->name('contact.post');

Route::get('/pedigree', [PedigreeController::class, 'index'])->name('pedigree');
Route::get('/pedigree/{id}', [PedigreeController::class, 'show'])->name('pedigree.show');
