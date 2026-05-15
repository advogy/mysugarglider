<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'alamat' => 'required|string|max:500',
            'telp'   => 'required|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'alamat.required' => 'Alamat wajib diisi.',
            'telp.required'   => 'Nomor telepon wajib diisi.',
            'telp.max'        => 'Nomor telepon maksimal 20 karakter.',
        ];
    }
}
