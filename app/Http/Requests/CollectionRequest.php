<?php

namespace App\Http\Requests;

use App\Enums\CollectionStatus;
use App\Models\ShelterModel;
use App\Models\SugargliderModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shelter_id'     => 'required|integer|exists:shelters,id',
            'sugarglider_id' => 'required|integer|exists:sugargliders,id',
            'status'         => 'required|integer|in:' . implode(',', array_column(
                [CollectionStatus::PRIVAT, CollectionStatus::PUBLIK, CollectionStatus::ADOPSI, CollectionStatus::MATI],
                'value'
            )),
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->shelter_id && !ShelterModel::where('id', $this->shelter_id)->where('user_id', Auth::id())->exists()) {
                $validator->errors()->add('shelter_id', 'Kandang yang dipilih bukan milik Anda.');
            }
            if ($this->sugarglider_id && !SugargliderModel::where('id', $this->sugarglider_id)->where('user_id', Auth::id())->exists()) {
                $validator->errors()->add('sugarglider_id', 'Sugar glider yang dipilih bukan milik Anda.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'shelter_id.required'         => 'Kandang wajib dipilih.',
            'shelter_id.exists'           => 'Kandang yang dipilih tidak ditemukan.',
            'sugarglider_id.required'     => 'Sugar glider wajib dipilih.',
            'sugarglider_id.exists'       => 'Sugar glider yang dipilih tidak ditemukan.',
            'status.required'             => 'Status wajib dipilih.',
            'status.in'                   => 'Status tidak valid.',
        ];
    }
}
