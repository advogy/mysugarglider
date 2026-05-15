<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Laravel\Facades\Image;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateAvatarRequest;

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
            'user_id'   => Auth::id(),
            'alamat'    => $request->alamat,
            'kota'      => $request->kota,
            'provinsi'  => $request->provinsi,
            'telepon'   => $request->telepon,
            'bio'       => $request->bio,
            'instagram' => $request->instagram,
            'website'   => $request->website,
        ];

        if (is_null($profile)) {
            ProfileModel::create($fields);
            return redirect()->route('profile')->with('pesan', 'Data berhasil ditambahkan.');
        } else {
            $profile->fill($fields)->save();
            return redirect()->route('profile')->with('pesan', 'Data berhasil diperbaharui.');
        }
    }

    function update_user(UpdateUserRequest $request)
    {
        $user = User::find(Auth::id());

        $oldEmail = $user->email;

        $user->name     = $request->name;
        $user->email    = $request->email;

        if ($oldEmail != $user->email) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        $user->save();

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

    function update_avatar(UpdateAvatarRequest $request)
    {
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $imagename = 'avatar-' . Auth::id() . '.' . $image->extension();

            Image::read($image)->coverDown(150, 150)->save(public_path('upload/avatars/' . $imagename));

            $user = User::find(Auth::id());
            $user->avatar = $imagename;
            $user->save();
        }

        return redirect()->route('profile')->with('pesan', 'Avatar berhasil diperbaharui.');
    }
}
