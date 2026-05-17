<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $profileId = Auth::user()->profile?->id;

        return [
            'alamat'      => 'required|string|max:500',
            'telepon'     => 'required|string|max:20',
            'kode_profil' => [
                'required',
                'string',
                'size:3',
                'regex:/^[A-Z]+$/',
                "unique:profiles,kode_profil,{$profileId}",
            ],
            'kota'      => 'nullable|string|max:100',
            'provinsi'  => 'nullable|string|max:100',
            'bio'       => 'nullable|string|max:500',
            'instagram' => 'nullable|string|max:100',
            'website'   => 'nullable|url|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'alamat.required'        => 'Alamat wajib diisi.',
            'telepon.required'       => 'Nomor telepon wajib diisi.',
            'kode_profil.required'   => 'Kode profil wajib diisi.',
            'kode_profil.size'       => 'Kode profil harus tepat 3 huruf.',
            'kode_profil.regex'      => 'Kode profil hanya boleh berisi huruf kapital (A-Z).',
            'kode_profil.unique'     => 'Kode profil ini sudah digunakan pengguna lain.',
        ];
    }
}
