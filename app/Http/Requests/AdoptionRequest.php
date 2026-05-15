<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdoptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'collection_id' => 'required|integer|exists:collections,id',
            'harga'         => 'required|numeric|min:0',
            'keterangan'    => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'collection_id.required' => 'Sugar glider wajib dipilih.',
            'collection_id.exists'   => 'Sugar glider yang dipilih tidak ditemukan.',
            'harga.required'         => 'Harga adopsi wajib diisi.',
            'harga.numeric'          => 'Harga harus berupa angka.',
            'harga.min'              => 'Harga tidak boleh negatif.',
        ];
    }
}
