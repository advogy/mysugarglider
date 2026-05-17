<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OtpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request): View|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/my');
        }

        return view('auth.v_otp', [
            'email' => $request->user()->email,
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/'],
        ], [
            'otp.required' => 'Masukkan kode OTP.',
            'otp.size'     => 'Kode OTP harus 6 digit.',
            'otp.regex'    => 'Kode OTP harus berupa angka.',
        ]);

        if (app(OtpService::class)->verify($request->user(), $request->otp)) {
            return redirect('/my')->with('pesan', 'Email berhasil diverifikasi. Selamat datang!');
        }

        return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
    }

    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/my');
        }

        app(OtpService::class)->generate($request->user());

        return back()->with('resent', true);
    }
}
