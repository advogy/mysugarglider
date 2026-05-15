<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShelterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'       => 'required|string|max:255',
            'kode'       => 'required|string|max:50',
            'alamat'     => 'required|string',
            'status'     => 'required|in:0,1',
            'keterangan' => 'nullable|string',
            'gmaps'      => 'nullable|string|max:500',
            'image'      => 'nullable|mimes:jpg,jpeg,bmp,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'   => 'Nama shelter wajib diisi.',
            'kode.required'   => 'Kode shelter wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status tidak valid.',
            'image.mimes'     => 'Foto harus berformat JPG, JPEG, BMP, atau PNG.',
            'image.max'       => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
