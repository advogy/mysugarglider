<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdoptionApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'adoption_id' => 'required|integer|exists:adoptions,id',
            'harga'       => 'required|numeric|min:0',
            'keterangan'  => 'nullable|string',
            'shelter_id'  => 'required|integer|exists:shelters,id',
        ];
    }

    public function messages(): array
    {
        return [
            'adoption_id.required' => 'Data adopsi tidak valid.',
            'adoption_id.exists'   => 'Data adopsi tidak ditemukan.',
            'harga.required'       => 'Harga penawaran wajib diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'harga.min'            => 'Harga tidak boleh negatif.',
            'shelter_id.required'  => 'Kandang tujuan wajib dipilih.',
            'shelter_id.exists'    => 'Kandang yang dipilih tidak ditemukan.',
        ];
    }
}
