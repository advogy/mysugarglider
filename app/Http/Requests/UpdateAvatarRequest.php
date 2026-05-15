<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'avatar' => 'required|mimes:jpg,jpeg,bmp,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.required' => 'File avatar wajib dipilih.',
            'avatar.mimes'    => 'Avatar harus berformat JPG, JPEG, BMP, atau PNG.',
            'avatar.max'      => 'Ukuran avatar maksimal 2 MB.',
        ];
    }
}
