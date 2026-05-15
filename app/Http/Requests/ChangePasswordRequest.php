<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password'          => 'required|current_password',
            'password_new'              => 'required|string|min:8|confirmed',
            'password_new_confirmation' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required'          => 'Password saat ini wajib diisi.',
            'current_password.current_password'  => 'Password saat ini tidak sesuai.',
            'password_new.required'              => 'Password baru wajib diisi.',
            'password_new.min'                   => 'Password minimal 8 karakter.',
            'password_new.confirmed'             => 'Konfirmasi password tidak cocok.',
            'password_new_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ];
    }
}
