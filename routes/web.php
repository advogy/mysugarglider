<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminPointController;
use App\Http\Controllers\Admin\AdminDataController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminConfigController;
use App\Http\Controllers\Admin\AdminAdoptionController;
use App\Http\Controllers\Auth\OtpController;
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
use App\Http\Controllers\BreedingController;
use App\Http\Controllers\NotificationController;

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
     * Email OTP Verification Routes
     */
    Route::get('/email/verify', [OtpController::class, 'show'])->name('verification.notice');
    Route::post('/email/verify', [OtpController::class, 'verify'])->name('verification.otp.verify')->middleware('throttle:5,1');
    Route::post('/email/resend', [OtpController::class, 'resend'])->name('verification.resend')->middleware('throttle:3,5');

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
        Route::post('/my/profile/bank', [ProfileController::class, 'update_bank'])->name('profile.update.bank');

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
        Route::delete('/my/adoptions/{id}', [AdoptionController::class, 'destroy'])->name('adoption.destroy');

        /**
         * Adoption Request Routes
         */
        Route::post('/my/adoptions/{id}/request', [AdoptionRequestController::class, 'store'])->name('adoptionrequest.store');
        Route::post('/my/adoptions/select', [AdoptionRequestController::class, 'backend_adoption_select'])->name('adoptionrequest.select');
        Route::post('/my/adoptionrequests/{id}/upload-payment', [AdoptionRequestController::class, 'upload_payment'])->name('adoptionrequest.upload-payment');
        Route::post('/my/adoptionrequests/{id}/cancel', [AdoptionRequestController::class, 'cancel_selection'])->name('adoptionrequest.cancel');
        Route::post('/my/adoptionrequests/{id}/confirm-free', [AdoptionRequestController::class, 'confirm_free'])->name('adoptionrequest.confirm-free');
        Route::post('/my/adoptions/{id}/shipping', [AdoptionRequestController::class, 'backend_adoption_shipping'])->name('adoptionrequest.shipping');
        Route::post('/my/adoptions/{id}/finalize', [AdoptionRequestController::class, 'backend_adoption_finalize'])->name('adoptionrequest.finalize');

        /**
         * Breeding Calculator Routes
         */
        Route::get('/my/breeding/inbreeding', [BreedingController::class, 'inbreeding'])->name('breeding.inbreeding');
        Route::post('/my/breeding/inbreeding', [BreedingController::class, 'calculateInbreeding'])->name('breeding.inbreeding.calculate');
        Route::get('/my/breeding/morph', [BreedingController::class, 'morph'])->name('breeding.morph');
        Route::post('/my/breeding/morph', [BreedingController::class, 'calculateMorph'])->name('breeding.morph.calculate');

        Route::get('/my/points', [PointController::class, 'index'])->name('points.index');
        Route::post('/my/points/redeem', [PointController::class, 'redeem'])->name('points.redeem');
        Route::get('/my/points/history', [PointController::class, 'history'])->name('points.history');

        Route::post('/my/testimonial', [TestimonialController::class, 'store'])->name('testimonial.store');
        Route::delete('/my/testimonial/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonial.destroy');

        /**
         * Notification Routes
         */
        Route::get('/my/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/my/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/my/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    });

    /**
     * Admin Routes — hanya bisa diakses oleh user dengan role 'admin'
     */
    Route::group(['middleware' => ['verified', 'admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {

        // Testimoni
        Route::get('/testimonials', [TestimonialController::class, 'adminIndex'])->name('testimonial.admin');
        Route::post('/testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('testimonial.approve');
        Route::post('/testimonials/{testimonial}/reject', [TestimonialController::class, 'reject'])->name('testimonial.reject');
        Route::put('/testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonial.update');

        // Manajemen Poin
        Route::get('/points/users', [AdminPointController::class, 'users'])->name('points.users');
        Route::get('/points/users/{user}', [AdminPointController::class, 'userDetail'])->name('points.user.detail');

        Route::get('/points/redemptions', [AdminPointController::class, 'redemptions'])->name('points.redemptions');
        Route::post('/points/redemptions/{redemption}/approve', [AdminPointController::class, 'approveRedemption'])->name('points.redemptions.approve');
        Route::post('/points/redemptions/{redemption}/cancel', [AdminPointController::class, 'cancelRedemption'])->name('points.redemptions.cancel');

        Route::get('/points/rewards', [AdminPointController::class, 'rewards'])->name('points.rewards');
        Route::get('/points/rewards/create', [AdminPointController::class, 'createReward'])->name('points.rewards.create');
        Route::post('/points/rewards', [AdminPointController::class, 'storeReward'])->name('points.rewards.store');
        Route::get('/points/rewards/{reward}/edit', [AdminPointController::class, 'editReward'])->name('points.rewards.edit');
        Route::put('/points/rewards/{reward}', [AdminPointController::class, 'updateReward'])->name('points.rewards.update');
        Route::delete('/points/rewards/{reward}', [AdminPointController::class, 'destroyReward'])->name('points.rewards.destroy');

        Route::get('/points/configs', [AdminPointController::class, 'configs'])->name('points.configs');
        Route::post('/points/configs', [AdminPointController::class, 'updateConfigs'])->name('points.configs.update');

        // Data Semua Pengguna
        Route::get('/data/shelters',                    [AdminDataController::class, 'shelters'])->name('data.shelters');
        Route::get('/data/shelters/{shelter}/edit',     [AdminDataController::class, 'editShelter'])->name('data.shelters.edit');
        Route::put('/data/shelters/{shelter}',          [AdminDataController::class, 'updateShelter'])->name('data.shelters.update');
        Route::delete('/data/shelters/{shelter}',       [AdminDataController::class, 'destroyShelter'])->name('data.shelters.destroy');

        Route::get('/data/sugargliders',                       [AdminDataController::class, 'sugargliders'])->name('data.sugargliders');
        Route::get('/data/sugargliders/{sugarglider}/edit',    [AdminDataController::class, 'editSugarglider'])->name('data.sugargliders.edit');
        Route::put('/data/sugargliders/{sugarglider}',         [AdminDataController::class, 'updateSugarglider'])->name('data.sugargliders.update');
        Route::delete('/data/sugargliders/{sugarglider}',      [AdminDataController::class, 'destroySugarglider'])->name('data.sugargliders.destroy');

        Route::get('/data/collections',                        [AdminDataController::class, 'collections'])->name('data.collections');
        Route::patch('/data/collections/{collection}/status',  [AdminDataController::class, 'updateCollectionStatus'])->name('data.collections.status');
        Route::delete('/data/collections/{collection}',        [AdminDataController::class, 'destroyCollection'])->name('data.collections.destroy');

        // Manajemen User
        Route::get('/users',                      [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/toggle-role',   [AdminUserController::class, 'toggleRole'])->name('users.toggle-role');
        Route::delete('/users/{user}',             [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Manajemen Adopsi (Escrow)
        Route::get('/adoptions',                              [AdminAdoptionController::class, 'index'])->name('adoptions.index');
        Route::get('/adoptions/{id}/requests',               [AdminAdoptionController::class, 'showRequests'])->name('adoptions.requests');
        Route::post('/adoptions/{id}/confirm-payment',       [AdminAdoptionController::class, 'confirmPayment'])->name('adoptions.confirm-payment');
        Route::post('/adoptions/{id}/disburse',              [AdminAdoptionController::class, 'disburse'])->name('adoptions.disburse');

        // Sistem Konfigurasi & Halaman Publik
        Route::get('/configs/site',          [AdminConfigController::class, 'site'])->name('configs.site');
        Route::post('/configs/site',         [AdminConfigController::class, 'updateSite'])->name('configs.site.update');
        Route::post('/configs/maintenance',  [AdminConfigController::class, 'updateMaintenance'])->name('configs.maintenance.update');
        Route::get('/configs/halaman',       [AdminConfigController::class, 'halaman'])->name('configs.halaman');
        Route::post('/configs/halaman',      [AdminConfigController::class, 'updateHalaman'])->name('configs.halaman.update');
    });
});


Route::get('/shelters', [ShelterController::class, 'index'])->name('shelters');
Route::get('/shelters/{id}', [ShelterController::class, 'show'])->name('shelter.show');

Route::get('/sugargliders/{id}', [SugargliderController::class, 'show'])->name('sugarglider.show');

Route::get('/collections', [CollectionController::class, 'index'])->name('collections');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/adopsi/panduan', [PageController::class, 'adoptionGuide'])->name('adoption.guide');
Route::post('/contact', [ContactController::class, 'contactPost'])->name('contact.post');

Route::get('/pedigree', [PedigreeController::class, 'index'])->name('pedigree');
Route::get('/pedigree/{id}', [PedigreeController::class, 'show'])->name('pedigree.show');
