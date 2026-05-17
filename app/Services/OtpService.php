<?php

namespace App\Services;

use App\Mail\EmailOtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    const EXPIRY_MINUTES = 10;

    public function generate(User $user): EmailOtp
    {
        // Hapus semua OTP lama milik user ini
        EmailOtp::where('user_id', $user->id)->delete();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $otp = EmailOtp::create([
            'user_id'    => $user->id,
            'otp'        => $code,
            'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
        ]);

        Mail::to($user->email)->send(new EmailOtpMail($user, $code));

        return $otp;
    }

    public function verify(User $user, string $code): bool
    {
        $otp = EmailOtp::where('user_id', $user->id)
            ->where('otp', $code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return false;
        }

        $otp->update(['used_at' => now()]);

        $user->forceFill(['email_verified_at' => now()])->save();

        event(new Verified($user));

        return true;
    }

    public function hasPendingOtp(User $user): bool
    {
        return EmailOtp::where('user_id', $user->id)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->exists();
    }
}
