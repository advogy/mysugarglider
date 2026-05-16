<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/my';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $rememberme = $request->has('rememberme') ? true : false;

        if (Auth::attempt($credentials, $rememberme)) {
            $request->session()->regenerate();
            return redirect()->intended('/my');
        }

        return back()->withErrors([
            'email' => 'Data yang Anda masukkan tidak sesuai',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }


    /**
     * Password
     * Change, forget, reset
     *
     */
    public function password_forget()
    {
        return view('auth.passwords.v_forget');
    }

    public function password_link(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['pesan' => __('Link perbaharui kata sandi telah dikirim ke email Anda.')])
            : back()->withErrors(['email' => __($status)]);
    }

    public function password_reset_form(Request $request, $token = null)
    {
        $data = [
            'token' => $token,
            'email' => $request->email,
        ];
        return view('auth.passwords.v_reset', $data);
    }

    public function password_reset(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('pesan', __('Kata sandi Anda telah berhasil diperbaharui. Silakan masuk.'))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
