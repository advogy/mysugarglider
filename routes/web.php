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
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        /**
         * Profile Routes
         */
        Route::get('/dashboard/profile', [ProfileController::class, 'show'])->name('profile');
        Route::post('/dashboard/profile', [ProfileController::class, 'update_profile'])->name('profile.update');
        Route::post('/dashboard/profile/user', [ProfileController::class, 'update_user'])->name('profile.update.user');
        Route::post('/dashboard/password', [ProfileController::class, 'password_change'])->name('profile.password.change');
        Route::post('/dashboard/profile/avatar', [ProfileController::class, 'update_avatar'])->name('profile.update.avatar');

        /**
         * Shelter Routes
         */
        Route::get('/dashboard/shelters', [ShelterController::class, 'backend_shelters_index'])->name('shelter.index');
        Route::get('/dashboard/shelters/create', [ShelterController::class, 'create'])->name('shelter.create');
        Route::post('/dashboard/shelters', [ShelterController::class, 'store'])->name('shelter.store');
        Route::get('/dashboard/shelters/{id}/edit', [ShelterController::class, 'edit'])->name('shelter.edit');
        Route::put('/dashboard/shelters/{id}', [ShelterController::class, 'update'])->name('shelter.update');
        Route::delete('/dashboard/shelters/{id}', [ShelterController::class, 'destroy'])->name('shelter.destroy');

        /**
         * Sugar Glider Routes
         */
        Route::get('/dashboard/sugargliders', [SugargliderController::class, 'backend_sugarglider_index'])->name('sugarglider.index');
        Route::get('/dashboard/sugargliders/create', [SugargliderController::class, 'create'])->name('sugarglider.create');
        Route::post('/dashboard/sugargliders', [SugargliderController::class, 'store'])->name('sugarglider.store');
        Route::get('/dashboard/sugargliders/{id}/edit', [SugargliderController::class, 'edit'])->name('sugarglider.edit');
        Route::put('/dashboard/sugargliders/{id}', [SugargliderController::class, 'update'])->name('sugarglider.update');
        Route::delete('/dashboard/sugargliders/{id}', [SugargliderController::class, 'destroy'])->name('sugarglider.destroy');

        /**
         * Collections Routes
         */
        Route::get('/dashboard/collections', [CollectionController::class, 'backend_collection_index'])->name('collection.index');
        Route::get('/dashboard/collections/create', [CollectionController::class, 'create'])->name('collection.create');
        Route::post('/dashboard/collections', [CollectionController::class, 'store'])->name('collection.store');
        Route::get('/dashboard/collections/{id}/edit', [CollectionController::class, 'edit'])->name('collection.edit');
        Route::put('/dashboard/collections/{id}', [CollectionController::class, 'update'])->name('collection.update');
        Route::delete('/dashboard/collections/{id}', [CollectionController::class, 'destroy'])->name('collection.destroy');

        /**
         * Pedigree Sugar Glider Routes
         */
        Route::get('/dashboard/pedigree', [PedigreeController::class, 'backend_pedigree_index'])->name('pedigree.index');
        Route::get('/dashboard/pedigree/{id}', [PedigreeController::class, 'backend_show'])->name('pedigree.backend.show');

        /**
         * Adoption Sugar Glider Routes
         */
        Route::get('/dashboard/adoptions', [AdoptionController::class, 'backend_adoption_index'])->name('adoption.index');
        Route::get('/dashboard/adoptions/create', [AdoptionController::class, 'create'])->name('adoption.create');
        Route::post('/dashboard/adoptions', [AdoptionController::class, 'store'])->name('adoption.store');
        Route::get('/dashboard/adoptions/list', [AdoptionController::class, 'backend_adoption_list'])->name('adoption.list');
        Route::get('/dashboard/adoptions/{id}/request', [AdoptionController::class, 'backend_adoption_request'])->name('adoption.request');
        Route::get('/dashboard/adoptions/{id}/edit', [AdoptionController::class, 'edit'])->name('adoption.edit');
        Route::put('/dashboard/adoptions/{id}', [AdoptionController::class, 'update'])->name('adoption.update');

        /**
         * Adoption Request Routes
         */
        Route::post('/dashboard/adoptions/{id}/request', [AdoptionRequestController::class, 'store'])->name('adoptionrequest.store');
        Route::post('/dashboard/adoptions/select', [AdoptionRequestController::class, 'backend_adoption_select'])->name('adoptionrequest.select');
        Route::post('/dashboard/adoptionrequests/{id}/upload-payment', [AdoptionRequestController::class, 'upload_payment'])->name('adoptionrequest.upload-payment');
        Route::post('/dashboard/adoptionrequests/{id}/confirm-free', [AdoptionRequestController::class, 'confirm_free'])->name('adoptionrequest.confirm-free');
        Route::post('/dashboard/adoptionrequests/{id}/confirm-payment', [AdoptionRequestController::class, 'confirm_payment'])->name('adoptionrequest.confirm-payment');
        Route::post('/dashboard/adoptions/{id}/shipping', [AdoptionRequestController::class, 'backend_adoption_shipping'])->name('adoptionrequest.shipping');
        Route::post('/dashboard/adoptions/{id}/finalize', [AdoptionRequestController::class, 'backend_adoption_finalize'])->name('adoptionrequest.finalize');
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
