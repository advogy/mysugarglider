<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateAvatarRequest;
use App\Enums\PointType;
use App\Services\OtpService;
use App\Services\PointService;

class ProfileController extends Controller
{
    function show()
    {
        $data = [
            'user' => User::where('id', Auth::id())->first(),
            'profile' => ProfileModel::where('user_id', Auth::id())->first(),
        ];

        return view('profiles.v_profile', $data);
    }

    function update_profile(UpdateProfileRequest $request)
    {
        $profile = ProfileModel::where('user_id', Auth::id())->first();

        $fields = [
            'user_id'     => Auth::id(),
            'kode_profil' => strtoupper($request->kode_profil),
            'alamat'      => $request->alamat,
            'kota'        => $request->kota,
            'provinsi'    => $request->provinsi,
            'telepon'     => $request->telepon,
            'bio'         => $request->bio,
            'instagram'   => $request->instagram,
            'website'     => $request->website,
        ];

        if (is_null($profile)) {
            $profile = ProfileModel::create($fields);
        } else {
            $profile->fill($fields)->save();
        }

        if (!empty($fields['telepon']) && !empty($fields['alamat'])) {
            app(PointService::class)->earn(Auth::user(), PointType::PROFILE_COMPLETE, $profile);
        }

        return redirect()->route('profile')->with('pesan', 'Data berhasil diperbaharui.');
    }

    function update_user(UpdateUserRequest $request)
    {
        $user         = User::find(Auth::id());
        $emailChanged = $user->email !== $request->email;

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($emailChanged) {
            app(OtpService::class)->generate($user);
            return redirect()->route('verification.notice')
                ->with('pesan', 'Email diubah. Masukkan kode verifikasi yang dikirim ke email baru Anda.');
        }

        return redirect()->route('profile')->with('pesan', 'Data berhasil diperbaharui.');
    }

    function password_change(ChangePasswordRequest $request)
    {
        $user = User::find(Auth::id());
        $user->password = $request->password_new;
        $user->save();
        Auth::logout();

        return redirect()->route('login')->with('pesan', 'Password berhasil diubah. Silakan masuk kembali.');
    }

    function update_bank(Request $request)
    {
        $request->validate([
            'bank_name'           => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name'   => 'required|string|max:100',
        ]);

        $profile = ProfileModel::where('user_id', Auth::id())->first();
        $fields  = [
            'user_id'             => Auth::id(),
            'bank_name'           => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name'   => $request->bank_account_name,
        ];

        if (is_null($profile)) {
            ProfileModel::create($fields);
        } else {
            $profile->fill($fields)->save();
        }

        return redirect()->route('profile', ['#tab-bank'])->with('pesan_bank', 'Rekening bank berhasil diperbaharui.');
    }

    function update_avatar(UpdateAvatarRequest $request)
    {
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $imagename = 'avatar-' . Auth::id() . '.' . $image->extension();

            ImageManager::gd()->read($image)->coverDown(150, 150)->save(public_path('upload/avatars/' . $imagename));

            $user = User::find(Auth::id());
            $user->avatar = $imagename;
            $user->save();
        }

        return redirect()->route('profile')->with('pesan', 'Avatar berhasil diperbaharui.');
    }
}
