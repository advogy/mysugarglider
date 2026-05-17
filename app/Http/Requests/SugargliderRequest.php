<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SugargliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode'           => 'nullable|string|max:50',
            'nama'           => 'required|string|max:255',
            'kelamin'        => 'required|in:0,1',
            'tgl_lahir'      => 'required|date',
            'warna'          => 'required|string|max:255',
            'jenis'          => 'required|string|max:255',
            'genetika'       => 'nullable|string',
            'fenotype'       => 'nullable|string',
            'indukan_betina' => 'nullable|integer|exists:sugargliders,id',
            'indukan_jantan' => 'nullable|integer|exists:sugargliders,id',
            'keterangan'     => 'nullable|string',
            'gambar'         => 'nullable|mimes:jpg,jpeg,bmp,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'    => 'Nama wajib diisi.',
            'kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'kelamin.in'       => 'Jenis kelamin tidak valid.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.date'     => 'Format tanggal lahir tidak valid.',
            'warna.required'   => 'Warna wajib diisi.',
            'jenis.required'   => 'Jenis wajib diisi.',
            'gambar.mimes'     => 'Gambar harus berformat JPG, JPEG, BMP, atau PNG.',
            'gambar.max'       => 'Ukuran gambar maksimal 2 MB.',
        ];
    }
}
